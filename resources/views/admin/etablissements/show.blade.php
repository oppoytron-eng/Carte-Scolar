@extends('layouts.app')

@section('title', 'Détails Établissement')
@section('page-title', 'Détails établissement')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-school" style="font-size: 3rem; color: #4F46E5; margin-bottom: 1rem;"></i>
                <h4 class="mb-2">{{ $etablissement->nom ?? 'N/A' }}</h4>
                <p class="text-muted mb-2">
                    <span class="badge bg-info">{{ $etablissement->type ?? 'N/A' }}</span>
                </p>
                <p class="mb-1"><strong>Code:</strong> {{ $etablissement->code ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i> Actions
                </h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.etablissements.edit', $etablissement->id ?? 0) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i> Éditer
                </a>
                <a href="{{ route('admin.etablissements.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i> Informations générales
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Nom</strong></p>
                        <p>{{ $etablissement->nom ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Type</strong></p>
                        <p>{{ $etablissement->type ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Code établissement</strong></p>
                        <p>{{ $etablissement->code ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Ville</strong></p>
                        <p>{{ $etablissement->ville ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Adresse</strong></p>
                        <p>{{ $etablissement->adresse ?? 'N/A' }}<br>
                           <span class="text-muted">{{ $etablissement->code_postal ?? '' }}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Email</strong></p>
                        <p>
                            @if($etablissement->email ?? false)
                                <a href="mailto:{{ $etablissement->email }}">{{ $etablissement->email }}</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Directeur/Proviseur</strong></p>
                        <p>{{ $etablissement->directeur ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Téléphone</strong></p>
                        <p>
                            @if($etablissement->telephone ?? false)
                                <a href="tel:{{ $etablissement->telephone }}">{{ $etablissement->telephone }}</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($etablissement->description ?? false)
        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i> Description
                </h5>
            </div>
            <div class="card-body">
                {{ $etablissement->description }}
            </div>
        </div>
        @endif

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i> Statistiques
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h6 class="text-muted">Utilisateurs</h6>
                        <h3>{{ $etablissement->users_count ?? 0 }}</h3>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Classes</h6>
                        <h3>{{ $etablissement->classes_count ?? 0 }}</h3>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Élèves</h6>
                        <h3>{{ $etablissement->eleves_count ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i> Utilisateurs de cet établissement
                </h5>
                <span class="badge bg-primary">{{ $etablissement->users->count() ?? 0 }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($etablissement->users ?? [] as $user)
                        <tr>
                            <td>{{ $user->full_name ?? 'N/A' }}</td>
                            <td><a href="mailto:{{ $user->email ?? '' }}">{{ $user->email ?? 'N/A' }}</a></td>
                            <td><span class="badge bg-info">{{ $user->role ?? 'N/A' }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-3 text-muted">Aucun utilisateur</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
