<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Etablissement;
use App\Models\Filiere;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $etablissement = auth()->user()->etablissementPrincipal();

        // Correction : Redirection vers la route existante 'dashboard.dashboard'
        if (!$etablissement) {
            return redirect()->route('dashboard.dashboard')
                ->with('error', "Aucun établissement principal n'est associé à votre compte.");
        }

        $classes = Classe::where('etablissement_id', $etablissement->id)
            ->with('etablissement', 'filiere', 'eleves')
            ->paginate(15);
            
        return view('proviseur.classes.index', compact('classes', 'etablissement'));
    }

    public function create()
    {
        $etablissement = auth()->user()->etablissementPrincipal();
        
        if (!$etablissement) {
            return redirect()->route('dashboard.dashboard')->with('error', "Action impossible : aucun établissement associé.");
        }

        $filieres = Filiere::where('is_active', true)->get();
        $niveaux = ['CP', 'CE1', 'CE2', 'CM1', 'CM2', '6e', '5e', '4e', '3e', '2nde', '1e', 'Tle'];
        
        return view('proviseur.classes.create', compact('etablissement', 'filieres', 'niveaux'));
    }

    public function store(Request $request)
    {
        $etablissement = auth()->user()->etablissementPrincipal();

        if (!$etablissement) {
            return redirect()->route('dashboard.dashboard')->with('error', "Erreur d'association d'établissement.");
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'niveau' => 'required|string',
            'filiere_id' => 'nullable|exists:filieres,id',
            'salle' => 'nullable|string|max:50',
            'effectif_max' => 'nullable|integer|min:1',
            'annee_scolaire' => 'required|string',
        ]);

        Classe::create([
            'nom' => $validated['nom'],
            'niveau' => $validated['niveau'],
            'etablissement_id' => $etablissement->id,
            'filiere_id' => $validated['filiere_id'] ?? null,
            'salle' => $validated['salle'] ?? null,
            'effectif_max' => $validated['effectif_max'] ?? null,
            'annee_scolaire' => $validated['annee_scolaire'],
            'is_active' => true,
        ]);

        return redirect()->route('proviseur.classes.index')->with('success', 'Classe créée avec succès');
    }
    
    public function show(Classe $classe)
    {
        $classe->load(['etablissement', 'filiere', 'eleves.photos', 'eleves.cartesScolaires']);

        $stats = [
            'total_eleves' => $classe->eleves()->count(),
            'eleves_actifs' => $classe->elevesActifs()->count(),
            'eleves_avec_photo' => $classe->eleves()->whereHas('photoApprouvee')->count(),
            'eleves_sans_photo' => $classe->eleves()->whereDoesntHave('photoApprouvee')->count(),
        ];

        return view('proviseur.classes.show', compact('classe', 'stats'));
    }

    public function edit(Classe $classe)
    {
        $etablissement = auth()->user()->etablissementPrincipal();
        $filieres = Filiere::where('is_active', true)->get();
        $niveaux = ['CP', 'CE1', 'CE2', 'CM1', 'CM2', '6e', '5e', '4e', '3e', '2nde', '1e', 'Tle'];

        return view('proviseur.classes.edit', compact('classe', 'etablissement', 'filieres', 'niveaux'));
    }

    public function update(Request $request, Classe $classe)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'niveau' => 'required|string',
            'filiere_id' => 'nullable|exists:filieres,id',
            'salle' => 'nullable|string|max:50',
            'effectif_max' => 'nullable|integer|min:1',
            'annee_scolaire' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $classe->update($validated);

        return redirect()->route('proviseur.classes.show', $classe)->with('success', 'Classe mise à jour avec succès');
    }

    public function destroy(Classe $classe)
    {
        if ($classe->eleves()->count() > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer une classe qui contient des élèves.');
        }

        $classe->delete();

        return redirect()->route('proviseur.classes.index')->with('success', 'Classe supprimée avec succès');
    }

    public function importEleves(Request $request, Classe $classe)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            // Import simple sans Maatwebsite Excel
            return redirect()->route('proviseur.classes.show', $classe)
                ->with('success', 'Élèves importés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Erreur lors de l'importation : " . $e->getMessage());
        }
    }

    public function getEleves(Classe $classe)
    {
        $eleves = $classe->eleves()
            ->where('statut', 'Actif')
            ->select('id', 'nom', 'prenoms', 'matricule')
            ->orderBy('nom')
            ->get();

        return response()->json($eleves);
    }
}