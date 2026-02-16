@extends('layouts.app')

@section('title', 'Détails Classe')
@section('page-title', 'Détails de la classe')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-chalkboard" style="font-size: 3rem; color: #4F46E5; margin-bottom: 1rem;"></i>
                <h4 class="mb-2">{{ $classe->nom ?? 'N/A' }}</h4>
                <p class="text-muted mb-3">{{ $classe->niveau ?? 'N/A' }}</p>
                <p class="mb-2"><span class="badge bg-primary">{{ $classe->eleves_count ?? 0 }} élèves</span></p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('proviseur.classes.edit', $classe->id ?? 0) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i> Éditer
                </a>
                <a href="{{ route('proviseur.classes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Niveau</strong></p>
                        <p>{{ $classe->niveau ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Salle</strong></p>
                        <p>{{ $classe->salle ?? '-' }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Professeur</strong></p>
                        <p>{{ $classe->professeur ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Effectif</strong></p>
                        <p>{{ $classe->effectif ?? 'N/A' }} élèves</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Spécialité</strong></p>
                        <p>{{ $classe->specialite ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Créée le</strong></p>
                        <p>{{ $classe->created_at->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Statistiques</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h6 class="text-muted">Photos</h6>
                        <h3>{{ $classe->photos_count ?? 0 }}/{{ $classe->eleves_count ?? 0 }}</h3>
                        <small>{{ round(($classe->photos_count ?? 0) / ($classe->eleves_count ?? 1) * 100) }}%</small>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Cartes</h6>
                        <h3>{{ $classe->cartes_count ?? 0 }}/{{ $classe->eleves_count ?? 0 }}</h3>
                        <small>{{ round(($classe->cartes_count ?? 0) / ($classe->eleves_count ?? 1) * 100) }}%</small>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Imprimées</h6>
                        <h3>{{ $classe->cartes_imprimees ?? 0 }}/{{ $classe->eleves_count ?? 0 }}</h3>
                        <small>{{ round(($classe->cartes_imprimees ?? 0) / ($classe->eleves_count ?? 1) * 100) }}%</small>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Distribuées</h6>
                        <h3>{{ $classe->cartes_distribuees ?? 0 }}/{{ $classe->eleves_count ?? 0 }}</h3>
                        <small>{{ round(($classe->cartes_distribuees ?? 0) / ($classe->eleves_count ?? 1) * 100) }}%</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Élèves de cette classe</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-sm">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Photo</th>
                            <th>Carte</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classe->eleves ?? [] as $eleve)
                        <tr>
                            <td>{{ $eleve->full_name ?? 'N/A' }}</td>
                            <td>
                                @if($eleve->photo_valid)
                                    <span class="badge bg-success">✓</span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif
                            </td>
                            <td>
                                @if($eleve->carte_generee)
                                    <span class="badge bg-info">✓</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>{{ $eleve->statut ?? 'Actif' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">Aucun élève</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
