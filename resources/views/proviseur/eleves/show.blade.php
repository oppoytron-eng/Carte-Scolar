@extends('layouts.app')

@section('title', 'Détails Élève')
@section('page-title', 'Détails de l\'élève')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $eleve->photo_url ?? 'https://via.placeholder.com/150' }}"
                     alt="{{ $eleve->full_name }}" class="rounded-circle mb-3" width="120" height="120">
                <h4 class="mb-2">{{ $eleve->full_name ?? 'N/A' }}</h4>
                <p class="text-muted mb-3">
                    <span class="badge bg-primary">{{ $eleve->classe->nom ?? 'N/A' }}</span>
                </p>
                <p class="mb-0">
                    @if($eleve->is_active)
                        <span class="badge bg-success">Actif</span>
                    @else
                        <span class="badge bg-danger">Inactif</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('proviseur.eleves.edit', $eleve->id ?? 0) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i> Éditer
                </a>
                <a href="{{ route('proviseur.eleves.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Informations personnelles</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Nom</strong></p>
                        <p>{{ $eleve->nom ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Prénom</strong></p>
                        <p>{{ $eleve->prenom ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Date de naissance</strong></p>
                        <p>{{ $eleve->date_naissance ? $eleve->date_naissance->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Lieu de naissance</strong></p>
                        <p>{{ $eleve->lieu_naissance ?? '-' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Numéro d'inscription</strong></p>
                        <p>{{ $eleve->numero_inscription ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Adresse</strong></p>
                        <p>{{ $eleve->adresse ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Informations scolaires</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Classe</strong></p>
                        <p>{{ $eleve->classe->nom ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Niveau</strong></p>
                        <p>{{ $eleve->classe->niveau ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Professeur</strong></p>
                        <p>{{ $eleve->classe->professeur ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Téléphone parent</strong></p>
                        <p>{{ $eleve->telephone_parent ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Statut document</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h6 class="text-muted">Photo</h6>
                        @if($eleve->photo_valid ?? false)
                            <h3><i class="fas fa-check-circle text-success"></i></h3>
                            <p class="small">Validée le {{ $eleve->photo_validated_at ? $eleve->photo_validated_at->format('d/m/Y') : '-' }}</p>
                        @else
                            <h3><i class="fas fa-times-circle text-danger"></i></h3>
                            <p class="small">Manquante</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Carte</h6>
                        @if($eleve->carte_generee ?? false)
                            <h3><i class="fas fa-check-circle text-info"></i></h3>
                            <p class="small">Générée le {{ $eleve->carte_generated_at ? $eleve->carte_generated_at->format('d/m/Y') : '-' }}</p>
                        @else
                            <h3><i class="fas fa-circle text-secondary"></i></h3>
                            <p class="small">Non générée</p>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Distribution</h6>
                        @if($eleve->carte_received ?? false)
                            <h3><i class="fas fa-check-circle text-warning"></i></h3>
                            <p class="small">Reçue le {{ $eleve->carte_received_at ? $eleve->carte_received_at->format('d/m/Y') : '-' }}</p>
                        @else
                            <h3><i class="fas fa-circle text-secondary"></i></h3>
                            <p class="small">Pas de réception</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
