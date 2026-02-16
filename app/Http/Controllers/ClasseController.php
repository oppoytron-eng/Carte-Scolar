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
    
    // ... Gardez le reste du fichier identique
}