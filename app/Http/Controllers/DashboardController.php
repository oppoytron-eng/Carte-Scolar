<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Eleve;
use App\Models\Photo;
use App\Models\CarteScolaire;
use App\Models\Classe;
use App\Models\Etablissement;
use App\Models\User;
use App\Models\Notification;

class DashboardController extends Controller
{
    /**
     * Dashboard principal (redirection selon le rôle)
     */
    public function index()
    {
        $user = Auth::user();

        return match($user->role) {
            'Administrateur' => $this->adminDashboard(),
            'Proviseur' => $this->proviseurDashboard(),
            'Surveillant General' => $this->surveillantDashboard(),
            'Operateur Photo' => $this->operateurDashboard(),
            default => abort(403, 'Rôle non reconnu')
        };
    }

    /**
     * Dashboard Administrateur
     */
    private function adminDashboard()
    {
        $stats = [
            'total_etablissements' => Etablissement::count(),
            'total_etablissements_actifs' => Etablissement::where('is_active', true)->count(),
            'total_eleves' => Eleve::count(),
            'total_eleves_actifs' => Eleve::where('statut', 'Actif')->count(),
            'total_cartes_generees' => CarteScolaire::whereIn('statut', ['Carte_generee', 'Carte_imprimee', 'Carte_distribuee'])->count(),
            'total_cartes_distribuees' => CarteScolaire::where('statut', 'Carte_distribuee')->count(),
            'total_photos_attente' => Photo::where('statut', 'En_attente')->count(),
            'total_users' => User::count(),
        ];

        // Statistiques par établissement
        $etablissements = Etablissement::with('eleves', 'cartesScolaires')
            ->withCount(['eleves', 'cartesScolaires'])
            ->orderBy('nom')
            ->limit(10)
            ->get();

        // Activités récentes
        $activitesRecentes = \App\Models\Action::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        // Graphiques
        $cartesParMois = $this->getCartesParMois();
        $elevesParEtablissement = $this->getElevesParEtablissement();

        return view('admin.dashboard', compact(
            'stats', 
            'etablissements', 
            'activitesRecentes',
            'cartesParMois',
            'elevesParEtablissement'
        ));
    }

    /**
     * Dashboard Proviseur
     */
    private function proviseurDashboard()
    {
        $user = Auth::user();
        $etablissement = $user->etablissementPrincipal();

        if (!$etablissement) {
            return redirect()->route('profile')->with('error', 'Aucun établissement assigné. Veuillez contacter l\'administrateur.');
        }

        $stats = [
            'total_eleves' => $etablissement->eleves()->count(),
            'total_eleves_actifs' => $etablissement->eleves()->where('statut', 'Actif')->count(),
            'total_classes' => $etablissement->classes()->count(),
            'total_cartes_generees' => $etablissement->cartesScolaires()
                ->whereIn('statut', ['Carte_generee', 'Carte_imprimee', 'Carte_distribuee'])
                ->count(),
            'total_cartes_distribuees' => $etablissement->cartesScolaires()
                ->where('statut', 'Carte_distribuee')
                ->count(),
            'taux_completion' => $this->calculerTauxCompletion($etablissement->id),
        ];

        // Classes avec effectifs
        $classes = $etablissement->classes()
            ->with('eleves')
            ->withCount('eleves')
            ->orderBy('niveau')
            ->get();

        // Cartes en attente
        $cartesEnAttente = CarteScolaire::where('etablissement_id', $etablissement->id)
            ->whereIn('statut', ['Photo_prise', 'Informations_validees'])
            ->with('eleve', 'classe')
            ->limit(10)
            ->get();

        // Notifications
        $notifications = $user->notificationsNonLues()->limit(5)->get();

        return view('proviseur.dashboard', compact(
            'stats',
            'etablissement',
            'classes',
            'cartesEnAttente',
            'notifications'
        ));
    }

    /**
     * Dashboard Surveillant Général
     */
    private function surveillantDashboard()
    {
        $user = Auth::user();
        $etablissement = $user->etablissementPrincipal();

        if (!$etablissement) {
            return redirect()->route('profile')->with('error', 'Aucun établissement assigné.');
        }

        $stats = [
            'photos_a_valider' => Photo::whereHas('eleve', function($q) use ($etablissement) {
                    $q->where('etablissement_id', $etablissement->id);
                })
                ->where('statut', 'En_attente')
                ->count(),
            'photos_validees_aujourd_hui' => Photo::whereHas('eleve', function($q) use ($etablissement) {
                    $q->where('etablissement_id', $etablissement->id);
                })
                ->where('statut', 'Approuvee')
                ->whereDate('date_validation', today())
                ->count(),
            'eleves_sans_photo' => Eleve::where('etablissement_id', $etablissement->id)
                ->where('statut', 'Actif')
                ->doesntHave('photoApprouvee')
                ->count(),
            'cartes_a_imprimer' => CarteScolaire::where('etablissement_id', $etablissement->id)
                ->where('statut', 'Carte_generee')
                ->count(),
        ];

        // Photos en attente de validation
        $photosEnAttente = Photo::whereHas('eleve', function($q) use ($etablissement) {
                $q->where('etablissement_id', $etablissement->id);
            })
            ->where('statut', 'En_attente')
            ->with(['eleve.classe', 'operateur'])
            ->orderBy('date_capture', 'desc')
            ->limit(20)
            ->get();

        // Statistiques par classe
        $statsParClasse = $this->getStatsParClasse($etablissement->id);

        // Classes pour le dropdown rapport
        $classes = $etablissement->classes()->orderBy('niveau')->get();

        return view('surveillant.dashboard', compact(
            'stats',
            'etablissement',
            'photosEnAttente',
            'statsParClasse',
            'classes'
        ));
    }

    /**
     * Dashboard Opérateur Photo
     */
    private function operateurDashboard()
    {
        $user = Auth::user();
        $etablissement = $user->etablissementPrincipal();

        $stats = [
            'photos_prises_aujourd_hui' => Photo::where('operateur_id', $user->id)
                ->whereDate('date_capture', today())
                ->count(),
            'photos_prises_semaine' => Photo::where('operateur_id', $user->id)
                ->whereBetween('date_capture', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'photos_approuvees' => Photo::where('operateur_id', $user->id)
                ->where('statut', 'Approuvee')
                ->count(),
            'photos_en_attente' => Photo::where('operateur_id', $user->id)
                ->where('statut', 'En_attente')
                ->count(),
            'taux_approbation' => $this->calculerTauxApprobation($user->id),
        ];

        // Élèves sans photo dans l'établissement
        $elevesSansPhoto = [];
        if ($etablissement) {
            $elevesSansPhoto = Eleve::where('etablissement_id', $etablissement->id)
                ->where('statut', 'Actif')
                ->whereDoesntHave('photos', function($q) {
                    $q->where('statut', 'Approuvee');
                })
                ->with('classe')
                ->limit(30)
                ->get();
        }

        // Photos récentes de l'opérateur
        $mesPhotosRecentes = Photo::where('operateur_id', $user->id)
            ->with(['eleve.classe', 'validateur'])
            ->orderBy('date_capture', 'desc')
            ->limit(15)
            ->get();

        // Graphique de productivité
        $productiviteJour = $this->getProductiviteParJour($user->id);

        return view('operateur.dashboard', compact(
            'stats',
            'elevesSansPhoto',
            'mesPhotosRecentes',
            'productiviteJour',
            'etablissement'
        ));
    }

    /**
     * Calculer le taux de completion des cartes
     */
    private function calculerTauxCompletion(int $etablissementId): float
    {
        $totalEleves = Eleve::where('etablissement_id', $etablissementId)
            ->where('statut', 'Actif')
            ->count();

        if ($totalEleves === 0) {
            return 0;
        }

        $cartesCompletes = CarteScolaire::where('etablissement_id', $etablissementId)
            ->whereIn('statut', ['Carte_imprimee', 'Carte_distribuee'])
            ->count();

        return round(($cartesCompletes / $totalEleves) * 100, 2);
    }

    /**
     * Calculer le taux d'approbation des photos d'un opérateur
     */
    private function calculerTauxApprobation(int $operateurId): float
    {
        $totalPhotos = Photo::where('operateur_id', $operateurId)->count();

        if ($totalPhotos === 0) {
            return 0;
        }

        $photosApprouvees = Photo::where('operateur_id', $operateurId)
            ->where('statut', 'Approuvee')
            ->count();

        return round(($photosApprouvees / $totalPhotos) * 100, 2);
    }

    /**
     * Obtenir les statistiques de cartes par mois
     */
    private function getCartesParMois(): array
    {
        $mois = [];
        $donnees = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $mois[] = $date->format('M Y');
            
            $count = CarteScolaire::whereYear('date_generation', $date->year)
                ->whereMonth('date_generation', $date->month)
                ->count();
            
            $donnees[] = $count;
        }

        return [
            'labels' => $mois,
            'data' => $donnees
        ];
    }

    /**
     * Obtenir le nombre d'élèves par établissement
     */
    private function getElevesParEtablissement(): array
    {
        $etablissements = Etablissement::withCount('eleves')
            ->orderBy('eleves_count', 'desc')
            ->limit(10)
            ->get();

        return [
            'labels' => $etablissements->pluck('nom')->toArray(),
            'data' => $etablissements->pluck('eleves_count')->toArray()
        ];
    }

    /**
     * Obtenir les stats par classe pour un établissement
     */
    private function getStatsParClasse(int $etablissementId): array
    {
        return Classe::where('etablissement_id', $etablissementId)
            ->with('eleves')
            ->get()
            ->map(function($classe) {
                $totalEleves = $classe->eleves()->where('statut', 'Actif')->count();
                $elevesAvecPhoto = $classe->eleves()
                    ->where('statut', 'Actif')
                    ->whereHas('photoApprouvee')
                    ->count();
                
                return [
                    'nom' => $classe->nom_complet,
                    'total' => $totalEleves,
                    'avec_photo' => $elevesAvecPhoto,
                    'sans_photo' => $totalEleves - $elevesAvecPhoto,
                    'pourcentage' => $totalEleves > 0 ? round(($elevesAvecPhoto / $totalEleves) * 100, 2) : 0
                ];
            })
            ->toArray();
    }

    /**
     * Obtenir les stats du dashboard (API)
     */
    public function getStats()
    {
        $user = Auth::user();
        $etablissement = $user->etablissementPrincipal();

        return response()->json([
            'total_eleves' => $etablissement ? $etablissement->eleves()->count() : Eleve::count(),
            'total_photos' => Photo::count(),
            'total_cartes' => CarteScolaire::count(),
        ]);
    }

    /**
     * Obtenir la productivité par jour pour un opérateur
     */
    private function getProductiviteParJour(int $operateurId): array
    {
        $jours = [];
        $donnees = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $jours[] = $date->format('D');
            
            $count = Photo::where('operateur_id', $operateurId)
                ->whereDate('date_capture', $date)
                ->count();
            
            $donnees[] = $count;
        }

        return [
            'labels' => $jours,
            'data' => $donnees
        ];
    }
}
