<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('etablissements')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $etablissements = Etablissement::where('is_active', true)->get();
        $roles = ['Administrateur', 'Proviseur', 'Surveillant Général', 'Opérateur Photo'];
        return view('admin.users.create', compact('etablissements', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenoms' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:Administrateur,Proviseur,Surveillant Général,Opérateur Photo',
            'phone' => 'nullable|string',
            'etablissements' => 'nullable|array',
            'etablissements.*' => 'exists:etablissements,id',
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'prenoms' => $validated['prenoms'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => true,
        ]);

        if ($request->has('etablissements') && !empty($validated['etablissements'])) {
            $user->etablissements()->sync($validated['etablissements']);
        }

        return redirect()->route('admin.users.show', $user)->with('success', 'Utilisateur créé avec succès');
    }

    public function show(User $user)
    {
        $user->load('etablissements', 'photosRealisees', 'photosValidees', 'cartesGenerees');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $etablissements = Etablissement::where('is_active', true)->get();
        $roles = ['Administrateur', 'Proviseur', 'Surveillant Général', 'Opérateur Photo'];
        $userEtablissements = $user->etablissements->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'etablissements', 'roles', 'userEtablissements'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenoms' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:Administrateur,Proviseur,Surveillant Général,Opérateur Photo',
            'phone' => 'nullable|string',
            'is_active' => 'boolean',
            'etablissements' => 'nullable|array',
            'etablissements.*' => 'exists:etablissements,id',
        ]);

        $user->update([
            'nom' => $validated['nom'],
            'prenoms' => $validated['prenoms'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if ($request->has('etablissements')) {
            $user->etablissements()->sync($validated['etablissements'] ?? []);
        }

        return redirect()->route('admin.users.show', $user)->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenoms' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $validated['profile_photo'] = $path;
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil mis à jour avec succès');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed|min:8|different:current_password',
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Mot de passe changé avec succès');
    }
}
