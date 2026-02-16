@extends('layouts.app')

@section('title', 'Éditer Classe')
@section('page-title', 'Éditer classe')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i> Éditer {{ $classe->nom ?? 'classe' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('proviseur.classes.update', $classe->id ?? 0) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom de la classe</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                   id="nom" name="nom" value="{{ old('nom', $classe->nom ?? '') }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="niveau" class="form-label">Niveau</label>
                            <select class="form-select @error('niveau') is-invalid @enderror"
                                    id="niveau" name="niveau" required>
                                <option value="">-- Sélectionnez un niveau --</option>
                                <option value="6eme" {{ old('niveau', $classe->niveau ?? '') == '6eme' ? 'selected' : '' }}>6ème</option>
                                <option value="5eme" {{ old('niveau', $classe->niveau ?? '') == '5eme' ? 'selected' : '' }}>5ème</option>
                                <option value="4eme" {{ old('niveau', $classe->niveau ?? '') == '4eme' ? 'selected' : '' }}>4ème</option>
                                <option value="3eme" {{ old('niveau', $classe->niveau ?? '') == '3eme' ? 'selected' : '' }}>3ème</option>
                                <option value="2nde" {{ old('niveau', $classe->niveau ?? '') == '2nde' ? 'selected' : '' }}>2nde</option>
                                <option value="1ere" {{ old('niveau', $classe->niveau ?? '') == '1ere' ? 'selected' : '' }}>1ère</option>
                                <option value="terminale" {{ old('niveau', $classe->niveau ?? '') == 'terminale' ? 'selected' : '' }}>Terminale</option>
                            </select>
                            @error('niveau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="salle" class="form-label">Salle/Local</label>
                            <input type="text" class="form-control @error('salle') is-invalid @enderror"
                                   id="salle" name="salle" value="{{ old('salle', $classe->salle ?? '') }}">
                            @error('salle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="professeur" class="form-label">Professeur principal</label>
                            <input type="text" class="form-control @error('professeur') is-invalid @enderror"
                                   id="professeur" name="professeur" value="{{ old('professeur', $classe->professeur ?? '') }}">
                            @error('professeur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="effectif" class="form-label">Effectif</label>
                            <input type="number" class="form-control @error('effectif') is-invalid @enderror"
                                   id="effectif" name="effectif" value="{{ old('effectif', $classe->effectif ?? '') }}" min="1" max="50">
                            @error('effectif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="specialite" class="form-label">Spécialité</label>
                            <select class="form-select" id="specialite" name="specialite">
                                <option value="">-- Aucune spécialité --</option>
                                <option value="general" {{ old('specialite', $classe->specialite ?? '') == 'general' ? 'selected' : '' }}>Générale</option>
                                <option value="technologique" {{ old('specialite', $classe->specialite ?? '') == 'technologique' ? 'selected' : '' }}>Technologique</option>
                                <option value="professionnelle" {{ old('specialite', $classe->specialite ?? '') == 'professionnelle' ? 'selected' : '' }}>Professionnelle</option>
                                <option value="bilingue" {{ old('specialite', $classe->specialite ?? '') == 'bilingue' ? 'selected' : '' }}>Bilingue</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3">{{ old('description', $classe->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('proviseur.classes.index') }}" class="btn btn-secondary">
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
