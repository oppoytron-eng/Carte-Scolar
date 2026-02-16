<?php

namespace App\Http\Controllers;

use App\Models\Etablissement;
use Illuminate\Http\Request;

class EtablissementController extends Controller
{
    public function index()
    {
        $etablissements = Etablissement::with('classes', 'eleves')
            ->paginate(15);
        return view('admin.etablissements.index', compact('etablissements'));
    }

    public function create()
    {
        $types = ['Primaire', 'Collège', 'Lycée', 'Université'];
        $grades = ['Public', 'Privé'];
        return view('admin.etablissements.create', compact('types', 'grades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:etablissements,nom',
            'type' => 'required|in:Primaire,Collège,Lycée,Université',
            'grade' => 'required|in:Public,Privé',
            'ville' => 'required|string|max:100',
            'commune' => 'required|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $validated['is_active'] = true;

        Etablissement::create($validated);

        return redirect()->route('admin.etablissements.index')->with('success', 'Établissement créé avec succès');
    }

    public function show(Etablissement $etablissement)
    {
        $etablissement->load('classes', 'eleves', 'users');
        $stats = [
            'total_eleves' => $etablissement->eleves()->count(),
            'total_classes' => $etablissement->classes()->count(),
            'total_photos' => 0, // À implémenter selon le modèle
            'total_cartes' => 0, // À implémenter selon le modèle
        ];
        return view('admin.etablissements.show', compact('etablissement', 'stats'));
    }

    public function edit(Etablissement $etablissement)
    {
        $types = ['Primaire', 'Collège', 'Lycée', 'Université'];
        $grades = ['Public', 'Privé'];
        return view('admin.etablissements.edit', compact('etablissement', 'types', 'grades'));
    }

    public function update(Request $request, Etablissement $etablissement)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:etablissements,nom,' . $etablissement->id,
            'type' => 'required|in:Primaire,Collège,Lycée,Université',
            'grade' => 'required|in:Public,Privé',
            'ville' => 'required|string|max:100',
            'commune' => 'required|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'adresse' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $etablissement->update($validated);

        return redirect()->route('admin.etablissements.show', $etablissement)->with('success', 'Établissement mis à jour avec succès');
    }

    public function destroy(Etablissement $etablissement)
    {
        $etablissement->delete();
        return redirect()->route('admin.etablissements.index')->with('success', 'Établissement supprimé avec succès');
    }

    public function toggleStatus(Etablissement $etablissement)
    {
        $etablissement->update(['is_active' => !$etablissement->is_active]);
        $status = $etablissement->is_active ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "Établissement $status avec succès");
    }
}
