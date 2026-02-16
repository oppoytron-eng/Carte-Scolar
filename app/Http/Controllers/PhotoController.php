<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    /**
     * Afficher la liste des photos
     */
    public function index(Request $request)
    {
        $query = Photo::with(['eleve.classe', 'operateur', 'validateur']);

        // Filtrer par opérateur pour les opérateurs photo
        if (auth()->user()->isOperateur()) {
            $query->where('operateur_id', auth()->id());
        }

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $query->whereHas('eleve', function($q) use ($request) {
                $q->where('nom', 'LIKE', "%{$request->search}%")
                  ->orWhere('prenoms', 'LIKE', "%{$request->search}%")
                  ->orWhere('matricule', 'LIKE', "%{$request->search}%");
            });
        }

        $photos = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('photos.index', compact('photos'));
    }

    /**
     * Afficher l'interface de capture de photo
     */
    public function capture()
{
    $user = auth()->user();
    $etablissement = $user->etablissementPrincipal();

    // Si pas d'établissement principal, on prend le premier lié ou on redirige
    if (!$etablissement) {
        $etablissement = $user->etablissements()->first();
    }

    if (!$etablissement) {
        return redirect()->back()->with('error', "Vous n'êtes lié à aucun établissement.");
    }

    $eleves = Eleve::where('etablissement_id', $etablissement->id)
        ->where('statut', 'Actif')
        ->whereDoesntHave('photos', function($q) {
            $q->where('statut', 'Approuvee');
        })
        ->with('classe')
        ->orderBy('nom')
        ->get();

    // Note : Vérifiez que votre vue s'appelle bien 'operateur.photo-capture'
    return view('operateur.photo-capture', compact('eleves', 'etablissement'));
}

    /**
     * Interface de capture pour un élève spécifique
     */
    public function captureForEleve(Eleve $eleve)
    {
        return view('operateur.photo-capture-eleve', compact('eleve'));
    }

    /**
     * Upload d'une photo
     */
    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'eleve_id' => 'required|exists:eleves,id',
            'methode_capture' => 'required|in:Webcam,Upload,Smartphone,Tablette',
        ]);

        $eleve = Eleve::findOrFail($request->eleve_id);
        
        try {
            $photo = $this->traiterPhoto($request->file('photo'), $eleve, $request->methode_capture);

            return response()->json([
                'success' => true,
                'message' => 'Photo capturée avec succès',
                'photo' => $photo,
                'redirect' => route('operateur.photos.show', $photo)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sauvegarder une photo depuis la webcam (base64)
     */
    public function save(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'eleve_id' => 'required|exists:eleves,id',
        ]);

        $eleve = Eleve::findOrFail($request->eleve_id);

        try {
            // Décoder l'image base64
            $image_parts = explode(";base64,", $request->image);
            $image_base64 = base64_decode($image_parts[1]);
            
            // Créer un fichier temporaire
            $tempFile = tempnam(sys_get_temp_dir(), 'photo_');
            file_put_contents($tempFile, $image_base64);

            // Traiter la photo
            $photo = $this->traiterPhoto($tempFile, $eleve, 'Webcam', true);

            // Supprimer le fichier temporaire
            unlink($tempFile);

            return response()->json([
                'success' => true,
                'message' => 'Photo capturée avec succès',
                'photo_id' => $photo->id,
                'photo_url' => $photo->photo_redimensionnee_url
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher les détails d'une photo
     */
    public function show(Photo $photo)
    {
        $photo->load(['eleve.classe', 'operateur', 'validateur']);

        return view('photos.show', compact('photo'));
    }

    /**
     * Supprimer une photo
     */
    public function destroy(Photo $photo)
    {
        // Vérifier les permissions
        if (!auth()->user()->isAdmin() && $photo->operateur_id != auth()->id()) {
            abort(403, 'Vous ne pouvez supprimer que vos propres photos');
        }

        // Supprimer les fichiers
        Storage::disk('public')->delete($photo->chemin_original);
        if ($photo->chemin_redimensionne) {
            Storage::disk('public')->delete($photo->chemin_redimensionne);
        }
        if ($photo->chemin_miniature) {
            Storage::disk('public')->delete($photo->chemin_miniature);
        }

        $photo->delete();

        return redirect()->route('operateur.photos.index')
            ->with('success', 'Photo supprimée avec succès');
    }

    /**
     * Interface de validation des photos
     */
    public function validationIndex()
    {
        $etablissement = auth()->user()->etablissementPrincipal();

        $photosEnAttente = Photo::whereHas('eleve', function($q) use ($etablissement) {
                $q->where('etablissement_id', $etablissement->id);
            })
            ->where('statut', 'En_attente')
            ->with(['eleve.classe', 'operateur'])
            ->orderBy('date_capture', 'asc')
            ->paginate(20);

        return view('surveillant.photos.validation', compact('photosEnAttente'));

    }

    /**
     * Approuver une photo
     */
    public function approve(Request $request, Photo $photo)
    {
        $request->validate([
            'commentaire' => 'nullable|string|max:500'
        ]);

        $photo->update([
            'statut' => 'Approuvee',
            'validateur_id' => auth()->id(),
            'date_validation' => now(),
        ]);

        // Désactiver les anciennes photos de cet élève
        Photo::where('eleve_id', $photo->eleve_id)
            ->where('id', '!=', $photo->id)
            ->where('statut', 'Approuvee')
            ->update(['is_active' => false]);

        // Créer une notification pour l'opérateur
        \App\Models\Notification::create([
            'user_id' => $photo->operateur_id,
            'type' => 'photo_approuvee',
            'titre' => 'Photo approuvée',
            'message' => "Votre photo de {$photo->eleve->nom_complet} a été approuvée",
            'date_notification' => now(),
            'priorite' => 'Normale',
        ]);

        $this->logAction('approve', $photo, "Approbation de la photo de {$photo->eleve->nom_complet}");

        return redirect()->back()->with('success', 'Photo approuvée avec succès');
    }

    /**
     * Rejeter une photo
     */
    public function reject(Request $request, Photo $photo)
    {
        $request->validate([
            'motif_rejet' => 'required|string|max:500'
        ]);

        $photo->update([
            'statut' => 'Rejetee',
            'validateur_id' => auth()->id(),
            'date_validation' => now(),
            'motif_rejet' => $request->motif_rejet,
        ]);

        // Notification pour l'opérateur
        \App\Models\Notification::create([
            'user_id' => $photo->operateur_id,
            'type' => 'photo_rejetee',
            'titre' => 'Photo rejetée',
            'message' => "Votre photo de {$photo->eleve->nom_complet} a été rejetée. Motif : {$request->motif_rejet}",
            'date_notification' => now(),
            'priorite' => 'Haute',
        ]);

        $this->logAction('reject', $photo, "Rejet de la photo de {$photo->eleve->nom_complet}");

        return redirect()->back()->with('success', 'Photo rejetée');
    }

    /**
     * Approuver plusieurs photos en masse
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'photo_ids' => 'required|array',
            'photo_ids.*' => 'exists:photos,id'
        ]);

        $count = 0;
        foreach ($request->photo_ids as $photoId) {
            $photo = Photo::find($photoId);
            if ($photo && $photo->statut === 'En_attente') {
                $photo->update([
                    'statut' => 'Approuvee',
                    'validateur_id' => auth()->id(),
                    'date_validation' => now(),
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "{$count} photos approuvées avec succès");
    }

    /**
     * Reprendre une photo
     */
    public function retake(Photo $photo)
    {
        // Marquer l'ancienne photo comme "À refaire"
        $photo->update([
            'statut' => 'A_refaire',
            'is_active' => false
        ]);

        return redirect()->route('operateur.photo.eleve', $photo->eleve_id)
            ->with('info', 'Vous pouvez maintenant reprendre la photo');
    }

    /**
     * Télécharger une photo
     */
    public function download(Photo $photo)
    {
        $path = storage_path('app/public/' . $photo->chemin_original);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, "photo_{$photo->eleve->matricule}.jpg");
    }

    /**
     * Obtenir la photo d'un élève (API)
     */
    public function getElevePhoto(Eleve $eleve)
    {
        $photo = $eleve->photoApprouvee;

        if (!$photo) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune photo approuvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'photo' => [
                'id' => $photo->id,
                'url' => $photo->photo_redimensionnee_url,
                'miniature' => $photo->photo_miniature_url,
                'statut' => $photo->statut,
            ]
        ]);
    }

    /**
     * Traiter et enregistrer une photo
     */
    private function traiterPhoto($file, Eleve $eleve, string $methode, bool $isTemp = false)
    {
        // Générer un ID unique pour la photo
        $photoId = 'PHO_' . strtoupper(Str::random(10));
        $anneeScolaire = $eleve->annee_scolaire;

        // Chemins de stockage
        $cheminBase = "photos/{$anneeScolaire}/{$eleve->etablissement_id}";
        
        // Charger l'image
        if ($isTemp) {
            $image = Image::read($file);
        } else {
            $image = Image::read($file->getRealPath());
        }

        // Obtenir les dimensions originales
        $largeur = $image->width();
        $hauteur = $image->height();

        // Sauvegarder l'original
        $nomOriginal = "{$photoId}_original.jpg";
        $cheminOriginal = "{$cheminBase}/{$nomOriginal}";
        Storage::disk('public')->put($cheminOriginal, $image->encodeByMediaType('image/jpeg', quality: 90));

        // Créer une version redimensionnée (pour les cartes)
        $imageRedim = clone $image;
        $imageRedim->scale(width: 400);
        $nomRedim = "{$photoId}_redim.jpg";
        $cheminRedim = "{$cheminBase}/{$nomRedim}";
        Storage::disk('public')->put($cheminRedim, $imageRedim->encodeByMediaType('image/jpeg', quality: 85));

        // Créer une miniature
        $imageMini = clone $image;
        $imageMini->scale(width: 150);
        $nomMini = "{$photoId}_mini.jpg";
        $cheminMini = "{$cheminBase}/{$nomMini}";
        Storage::disk('public')->put($cheminMini, $imageMini->encodeByMediaType('image/jpeg', quality: 80));

        // Calculer la taille du fichier
        $tailleFichier = Storage::disk('public')->size($cheminOriginal) / 1024; // En KB

        // Analyse de qualité (simplifié)
        $scoreQualite = $this->analyserQualite($image, $largeur, $hauteur);

        // Créer l'enregistrement
        $photo = Photo::create([
            'photo_id' => $photoId,
            'eleve_id' => $eleve->id,
            'operateur_id' => auth()->id(),
            'chemin_original' => $cheminOriginal,
            'chemin_redimensionne' => $cheminRedim,
            'chemin_miniature' => $cheminMini,
            'format' => 'jpg',
            'largeur' => $largeur,
            'hauteur' => $hauteur,
            'taille_fichier' => round($tailleFichier),
            'methode_capture' => $methode,
            'date_capture' => now(),
            'statut' => 'En_attente',
            'score_qualite' => $scoreQualite,
            'is_active' => true,
            'version' => 1,
        ]);

        // Logger
        $this->logAction('create', $photo, "Capture de photo pour {$eleve->nom_complet}");

        return $photo;
    }

    /**
     * Analyser la qualité d'une photo (simplifié)
     */
    private function analyserQualite($image, int $largeur, int $hauteur): int
    {
        $score = 100;

        // Pénalité pour résolution trop faible
        if ($largeur < 300 || $hauteur < 400) {
            $score -= 30;
        }

        // Pénalité pour dimensions non proportionnelles (photo portrait attendue)
        $ratio = $hauteur / $largeur;
        if ($ratio < 1.2 || $ratio > 1.5) {
            $score -= 20;
        }

        return max(0, $score);
    }

    /**
     * Logger une action
     */
    private function logAction(string $type, Photo $photo, string $description)
    {
        \App\Models\Action::create([
            'user_id' => auth()->id(),
            'type_action' => $type,
            'module' => 'Photo',
            'description' => $description,
            'entite_type' => 'Photo',
            'entite_id' => $photo->id,
            'adresse_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'statut' => 'Succes',
        ]);
    }
}
