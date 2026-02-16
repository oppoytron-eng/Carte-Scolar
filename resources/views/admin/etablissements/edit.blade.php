@extends('layouts.app')

@section('title', 'Éditer Établissement')
@section('page-title', 'Éditer établissement')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i> Éditer {{ $etablissement->nom ?? 'établissement' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.etablissements.update', $etablissement->id ?? 0) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom de l'établissement</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror"
                               id="nom" name="nom" value="{{ old('nom', $etablissement->nom ?? '') }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type d'établissement</label>
                            <select class="form-select @error('type') is-invalid @enderror"
                                    id="type" name="type" required>
                                <option value="">-- Sélectionnez un type --</option>
                                <option value="lycee" {{ old('type', $etablissement->type ?? '') == 'lycee' ? 'selected' : '' }}>Lycée</option>
                                <option value="college" {{ old('type', $etablissement->type ?? '') == 'college' ? 'selected' : '' }}>Collège</option>
                                <option value="ecole" {{ old('type', $etablissement->type ?? '') == 'ecole' ? 'selected' : '' }}>École Primaire</option>
                                <option value="autre" {{ old('type', $etablissement->type ?? '') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="code" class="form-label">Code établissement</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror"
                                   id="code" name="code" value="{{ old('code', $etablissement->code ?? '') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                               id="adresse" name="adresse" value="{{ old('adresse', $etablissement->adresse ?? '') }}" required>
                        @error('adresse')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control @error('ville') is-invalid @enderror"
                                   id="ville" name="ville" value="{{ old('ville', $etablissement->ville ?? '') }}" required>
                            @error('ville')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="code_postal" class="form-label">Code postal</label>
                            <input type="text" class="form-control @error('code_postal') is-invalid @enderror"
                                   id="code_postal" name="code_postal" value="{{ old('code_postal', $etablissement->code_postal ?? '') }}">
                            @error('code_postal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="directeur" class="form-label">Directeur/Proviseur</label>
                            <input type="text" class="form-control @error('directeur') is-invalid @enderror"
                                   id="directeur" name="directeur" value="{{ old('directeur', $etablissement->directeur ?? '') }}">
                            @error('directeur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('telephone') is-invalid @enderror"
                                   id="telephone" name="telephone" value="{{ old('telephone', $etablissement->telephone ?? '') }}">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email', $etablissement->email ?? '') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $etablissement->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.etablissements.index') }}" class="btn btn-secondary">
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
