@extends('layouts.app')

@section('title', 'Éditer Élève')
@section('page-title', 'Éditer élève')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Éditer {{ $eleve->full_name ?? 'élève' }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('proviseur.eleves.update', $eleve->id ?? 0) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                   id="nom" name="nom" value="{{ old('nom', $eleve->nom ?? '') }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                   id="prenom" name="prenom" value="{{ old('prenom', $eleve->prenom ?? '') }}" required>
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control @error('date_naissance') is-invalid @enderror"
                                   id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $eleve->date_naissance ?? '') }}">
                            @error('date_naissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                            <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror"
                                   id="lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance', $eleve->lieu_naissance ?? '') }}">
                            @error('lieu_naissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_inscription" class="form-label">Numéro d'inscription</label>
                            <input type="text" class="form-control @error('numero_inscription') is-invalid @enderror"
                                   id="numero_inscription" name="numero_inscription" value="{{ old('numero_inscription', $eleve->numero_inscription ?? '') }}" required>
                            @error('numero_inscription')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="classe_id" class="form-label">Classe</label>
                            <select class="form-select @error('classe_id') is-invalid @enderror"
                                    id="classe_id" name="classe_id" required>
                                <option value="">-- Sélectionnez une classe --</option>
                                @foreach($classes ?? [] as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id', $eleve->classe_id ?? '') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                   id="adresse" name="adresse" value="{{ old('adresse', $eleve->adresse ?? '') }}">
                            @error('adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telephone_parent" class="form-label">Téléphone parent</label>
                            <input type="tel" class="form-control @error('telephone_parent') is-invalid @enderror"
                                   id="telephone_parent" name="telephone_parent" value="{{ old('telephone_parent', $eleve->telephone_parent ?? '') }}">
                            @error('telephone_parent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="actif" name="is_active" value="1" {{ old('is_active', $eleve->is_active ?? 1) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="actif">Actif</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="inactif" name="is_active" value="0" {{ old('is_active', $eleve->is_active ?? 1) == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="inactif">Inactif</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('proviseur.eleves.index') }}" class="btn btn-secondary">
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
