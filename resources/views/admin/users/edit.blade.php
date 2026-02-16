@extends('layouts.app')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('title', 'Éditer Utilisateur')
@section('page-title', 'Éditer utilisateur')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i> Éditer {{ $user->full_name ?? 'utilisateur' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id ?? 0) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                   id="nom" name="nom" value="{{ old('nom', $user->full_name ?? '') }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                                <label class="form-label">Prénoms</label>
                                <input type="text" name="prenoms" class="form-control @error('prenoms') is-invalid @enderror" value="{{ old('prenoms') }}" required>
                                @error('prenoms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                                   id="telephone" name="telephone" value="{{ old('telephone', $user->telephone ?? '') }}">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    id="role" name="role" required>
                                <option value="">-- Sélectionnez un rôle --</option>
                                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                <option value="proviseur" {{ old('role', $user->role ?? '') == 'proviseur' ? 'selected' : '' }}>Proviseur</option>
                                <option value="surveillant" {{ old('role', $user->role ?? '') == 'surveillant' ? 'selected' : '' }}>Surveillant</option>
                                <option value="operateur" {{ old('role', $user->role ?? '') == 'operateur' ? 'selected' : '' }}>Opérateur</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="etablissement_id" class="form-label">Établissement</label>
                            <select class="form-select @error('etablissement_id') is-invalid @enderror"
                                    id="etablissement_id" name="etablissement_id">
                                <option value="">-- Sélectionnez un établissement --</option>
                                @foreach($etablissements ?? [] as $etablissement)
                                    <option value="{{ $etablissement->id }}" {{ old('etablissement_id', $user->etablissement_id ?? '') == $etablissement->id ? 'selected' : '' }}>
                                        {{ $etablissement->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('etablissement_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Statut</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="actif" name="is_active" value="1" {{ old('is_active', $user->is_active ?? 1) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="actif">
                                        Actif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="inactif" name="is_active" value="0" {{ old('is_active', $user->is_active ?? 1) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inactif">
                                        Inactif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Laissez vide si vous ne souhaitez pas modifier le mot de passe
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe (optionnel)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer mot de passe</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation" name="password_confirmation">
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
