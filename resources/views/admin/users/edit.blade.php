@extends('layouts.app')

@section('title', 'Éditer Utilisateur')
@section('page-title', 'Éditer utilisateur')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i> Éditer {{ $user->full_name ?? 'utilisateur' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                   id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="prenoms" class="form-label">Prénoms <span class="text-danger">*</span></label>
                            <input type="text" name="prenoms" class="form-control @error('prenoms') is-invalid @enderror"
                                   value="{{ old('prenoms', $user->prenoms) }}" required>
                            @error('prenoms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="">-- Sélectionnez un rôle --</option>
                                <option value="Administrateur" {{ old('role', $user->role) == 'Administrateur' ? 'selected' : '' }}>Administrateur</option>
                                <option value="Proviseur" {{ old('role', $user->role) == 'Proviseur' ? 'selected' : '' }}>Proviseur</option>
                                <option value="Surveillant General" {{ old('role', $user->role) == 'Surveillant General' ? 'selected' : '' }}>Surveillant Général</option>
                                <option value="Operateur Photo" {{ old('role', $user->role) == 'Operateur Photo' ? 'selected' : '' }}>Opérateur Photo</option>
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="etablissement_id" class="form-label">Établissement</label>
                            <select class="form-select @error('etablissement_id') is-invalid @enderror"
                                    id="etablissement_id" name="etablissement_id">
                                <option value="">-- Aucun établissement --</option>
                                @foreach($etablissements ?? [] as $etablissement)
                                    <option value="{{ $etablissement->id }}"
                                        {{ in_array($etablissement->id, $userEtablissements ?? []) ? 'selected' : '' }}>
                                        {{ $etablissement->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('etablissement_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted">L'établissement principal de l'utilisateur</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Statut</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="actif" name="is_active" value="1"
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="actif">Actif</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="inactif" name="is_active" value="0"
                                           {{ !old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inactif">Inactif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Laissez vide si vous ne souhaitez pas modifier le mot de passe
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection