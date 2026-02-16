<?php

namespace App\Http\Controllers;

use App\Models\CarteScolaire;
use App\Models\Eleve;
use App\Models\Classe;
use App\Services\CarteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CarteController extends Controller
{
    protected $carteService;

    public function __construct(CarteService $carteService)
    {
        $this->carteService = $carteService;
    }

    /**
     * Liste des cartes
     */
    public function index(Request $request)
    {
        $query = CarteScolaire::with(['eleve.classe', 'etablissement']);

        // Filtrer par établissement
        if (!auth()->user()->isAdmin()) {
            $etablissement = auth()->user()->etablissementPrincipal();
            if ($etablissement) {
                $query->where('etablissement_id', $etablissement->id);
            }
        }

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if ($request->filled('annee_scolaire')) {
            $query->where('annee_scolaire', $request->annee_scolaire);
        }

        $cartes = $query->orderBy('created_at', 'desc')->paginate(20);
        $classes = Classe::orderBy('nom')->get();

        return view('surveillant.cartes.index', compact('cartes', 'classes'));


    }

    /**
     * Détails d'une carte
     */
    public function show(CarteScolaire $carte)
    {
        $carte->load(['eleve.classe', 'photo', 'etablissement', 'generateur', 'imprimeur', 'distributeur']);

        return view('surveillant.cartes.show', compact('carte'));
    }

    /**
     * Générer une carte pour un élève
     */
    public function generate(Eleve $eleve)
    {
        try {
            // Vérifier si l'élève a une photo approuvée
            if (!$eleve->hasPhotoApprouvee()) {
                return redirect()->back()->with('error', "L'élève n'a pas de photo approuvée");
            }

            // Vérifier si une carte existe déjà pour cette année
            $carteExistante = $eleve->carteActive;
            if ($carteExistante) {
                return redirect()->back()->with('info', 'Une carte existe déjà pour cet élève');
            }

            // Générer la carte
            $carte = $this->carteService->genererCarte($eleve);

            return redirect()->route('surveillant.cartes.show', $carte)
                ->with('success', 'Carte générée avec succès');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Régénérer une carte
     */
    public function regenerate(CarteScolaire $carte)
    {
        try {
            $nouvelleCarte = $this->carteService->genererDuplicata($carte);

            return redirect()->route('surveillant.cartes.show', $nouvelleCarte)
                ->with('success', 'Duplicata généré avec succès');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Interface d'impression
     */
    public function impressionIndex()
    {
        $etablissement = auth()->user()->etablissementPrincipal();

        $cartesAPrinter = CarteScolaire::where('etablissement_id', $etablissement->id)
            ->where('statut', 'Carte_generee')
            ->with(['eleve.classe'])
            ->orderBy('classe_id')
            ->orderBy('created_at')
            ->get();

        $classes = Classe::where('etablissement_id', $etablissement->id)
            ->withCount([
                'cartesScolaires' => function($q) {
                    $q->where('statut', 'Carte_generee');
                }
            ])
            ->get();

        return view('surveillant.cartes.index');

    }

    /**
     * Traiter l'impression
     */
    public function processImpression(Request $request)
    {
        $request->validate([
            'carte_ids' => 'required|array',
            'carte_ids.*' => 'exists:cartes_scolaires,id'
        ]);

        $count = 0;
        foreach ($request->carte_ids as $carteId) {
            $carte = CarteScolaire::find($carteId);
            if ($carte && $carte->statut === 'Carte_generee') {
                $this->carteService->imprimerCarte($carte);
                $count++;
            }
        }

        return redirect()->back()->with('success', "{$count} cartes marquées comme imprimées");
    }

    /**
     * Impression en masse
     */
    public function bulkPrint(Request $request)
    {
        $request->validate([
            'carte_ids' => 'required|array',
            'carte_ids.*' => 'exists:cartes_scolaires,id'
        ]);

        try {
            $resultats = $this->carteService->imprimerEnMasse($request->carte_ids);

            $message = "{$resultats['succes']} cartes imprimées avec succès";
            if ($resultats['echecs'] > 0) {
                $message .= ", {$resultats['echecs']} échecs";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Marquer une carte comme distribuée
     */
    public function markAsDistributed(Request $request, CarteScolaire $carte)
    {
        $request->validate([
            'signature_parent' => 'nullable|string'
        ]);

        try {
            $this->carteService->marquerCommeDistribuee($carte);

            if ($request->filled('signature_parent')) {
                // Sauvegarder la signature (base64)
                $signaturePath = $this->saveSignature($request->signature_parent, $carte);
                $carte->update(['signature_parent' => $signaturePath]);
            }

            return redirect()->back()->with('success', 'Carte marquée comme distribuée');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Télécharger une carte
     */
    public function download(CarteScolaire $carte)
    {
        if (!$carte->chemin_pdf) {
            return redirect()->back()->with('error', 'Aucun PDF disponible');
        }

        $path = storage_path('app/public/' . $carte->chemin_pdf);
        
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'Fichier introuvable');
        }

        return response()->download($path, "carte_{$carte->numero_carte}.pdf");
    }

    /**
     * Sauvegarder une signature
     */
    private function saveSignature(string $signatureBase64, CarteScolaire $carte): string
    {
        $image_parts = explode(";base64,", $signatureBase64);
        $image_base64 = base64_decode($image_parts[1]);
        
        $nomFichier = "signature_{$carte->numero_carte}.png";
        $chemin = "signatures/{$carte->annee_scolaire}/{$nomFichier}";
        
        Storage::disk('public')->put($chemin, $image_base64);
        
        return $chemin;
    }

        public function impressionBatch()
    {
        return view('surveillant.impression.batch');
    }
}
