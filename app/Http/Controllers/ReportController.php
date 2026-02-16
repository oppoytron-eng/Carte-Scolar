<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Photo;
use App\Models\CarteScolaire;
use App\Models\Action;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.rapports.index');
    }

    public function global()
    {
        $stats = [
            'total_eleves' => Eleve::count(),
            'eleves_actifs' => Eleve::where('statut', 'Actif')->count(),
            'eleves_inactifs' => Eleve::where('statut', 'Inactif')->count(),
            'total_photos' => Photo::count(),
            'photos_approuvees' => Photo::where('statut', 'Approuvee')->count(),
            'photos_attente' => Photo::where('statut', 'En_attente')->count(),
            'photos_rejetees' => Photo::where('statut', 'Rejetee')->count(),
            'total_cartes' => CarteScolaire::count(),
            'cartes_generees' => CarteScolaire::where('statut', 'Carte_generee')->count(),
            'cartes_imprimees' => CarteScolaire::where('statut', 'Carte_imprimee')->count(),
            'cartes_distribuees' => CarteScolaire::where('statut', 'Carte_distribuee')->count(),
        ];

        return view('admin.rapports.global', compact('stats'));
    }

    public function classe(Classe $classe)
    {
        $classe->load('eleves.photos', 'eleves.cartesScolaires', 'etablissement');

        $etablissement = $classe->etablissement;
        $classes = $etablissement ? Classe::where('etablissement_id', $etablissement->id)->orderBy('nom')->get() : collect();

        $stats = [
            'total_eleves' => $classe->eleves->count(),
            'eleves_actifs' => $classe->eleves->where('statut', 'Actif')->count(),
            'eleves_avec_photo' => $classe->eleves->filter(fn($e) => $e->photos->where('statut', 'Approuvee')->count() > 0)->count(),
            'eleves_sans_photo' => $classe->eleves->filter(fn($e) => $e->photos->where('statut', 'Approuvee')->count() === 0)->count(),
            'cartes_generees' => $classe->eleves->flatMap->cartesScolaires->where('statut', 'Carte_generee')->count(),
            'cartes_imprimees' => $classe->eleves->flatMap->cartesScolaires->where('statut', 'Carte_imprimee')->count(),
            'cartes_distribuees' => $classe->eleves->flatMap->cartesScolaires->where('statut', 'Carte_distribuee')->count(),
        ];

        $eleves = $classe->eleves;

        return view('surveillant.rapports.classe', compact('classe', 'classes', 'stats', 'eleves'));
    }

    public function exportExcel()
    {
        // Utiliser Maatwebsite\Excel pour l'export
        // À implémenter selon les besoins
        return response()->json(['message' => 'Export Excel en développement']);
    }

    public function exportPdf()
    {
        $stats = [
            'total_eleves' => Eleve::count(),
            'eleves_actifs' => Eleve::where('statut', 'Actif')->count(),
            'total_photos' => Photo::count(),
            'photos_approuvees' => Photo::where('statut', 'Approuvee')->count(),
            'total_cartes' => CarteScolaire::count(),
            'cartes_distribuees' => CarteScolaire::where('statut', 'Carte_distribuee')->count(),
        ];

        $pdf = Pdf::loadView('reports.global-pdf', compact('stats'));
        return $pdf->download('rapport-global-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportClasseExcel(Classe $classe)
    {
        // À implémenter
        return response()->json(['message' => 'Export Excel classe en développement']);
    }

    public function exportClassePdf(Classe $classe)
    {
        $classe->load('eleves.photos', 'eleves.cartesScolaires');

        $stats = [
            'total_eleves' => $classe->eleves()->count(),
            'eleves_actifs' => $classe->eleves()->where('statut', 'Actif')->count(),
            'cartes_distribuees' => CarteScolaire::whereHas('eleve', function ($q) use ($classe) {
                $q->where('classe_id', $classe->id);
            })->where('statut', 'Carte_distribuee')->count(),
        ];

        $pdf = Pdf::loadView('reports.classe-pdf', compact('classe', 'stats'));
        return $pdf->download('rapport-' . $classe->nom . '-' . now()->format('Y-m-d') . '.pdf');
    }

    public function proviseur()
    {
        $etablissement = auth()->user()->etablissementPrincipal();

        if (!$etablissement) {
            return redirect()->route('profile')->with('error', 'Aucun établissement assigné.');
        }

        $stats = [
            'total_eleves' => Eleve::where('etablissement_id', $etablissement->id)->count(),
            'eleves_actifs' => Eleve::where('etablissement_id', $etablissement->id)->where('statut', 'Actif')->count(),
            'total_photos' => Photo::whereHas('eleve', fn($q) => $q->where('etablissement_id', $etablissement->id))->count(),
            'photos_approuvees' => Photo::whereHas('eleve', fn($q) => $q->where('etablissement_id', $etablissement->id))->where('statut', 'Approuvee')->count(),
            'total_cartes' => CarteScolaire::where('etablissement_id', $etablissement->id)->count(),
            'cartes_distribuees' => CarteScolaire::where('etablissement_id', $etablissement->id)->where('statut', 'Carte_distribuee')->count(),
        ];

        $classes = Classe::where('etablissement_id', $etablissement->id)
            ->withCount('eleves')
            ->get();

        return view('proviseur.statistiques', compact('stats', 'classes', 'etablissement'));
    }

    public function audit()
    {
        $actions = Action::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.audit.index', compact('actions'));
    }
}
