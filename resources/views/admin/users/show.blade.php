@extends('layouts.app')

@section('title', 'Détails Utilisateur')
@section('page-title', 'Détails utilisateur')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $user->profile_photo_url ?? 'https://via.placeholder.com/150' }}"
                     alt="{{ $user->full_name }}" class="rounded-circle mb-3" width="120" height="120">
                <h4 class="mb-2">{{ $user->full_name ?? 'N/A' }}</h4>
                <p class="text-muted mb-3">
                    <span class="badge bg-info">{{ $user->role ?? 'N/A' }}</span>
                </p>
                <p class="mb-0">
                    @if($user->is_active ?? false)
                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Actif</span>
                    @else
                        <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Inactif</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i> Actions
                </h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.users.edit', $user->id ?? 0) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i> Éditer
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i> Informations personnelles
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Nom complet</strong></p>
                        <p>{{ $user->full_name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Email</strong></p>
                        <p>
                            <a href="mailto:{{ $user->email ?? '' }}">
                                {{ $user->email ?? 'N/A' }}
                            </a>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Téléphone</strong></p>
                        <p>
                            @if($user->telephone ?? false)
                                <a href="tel:{{ $user->telephone }}">{{ $user->telephone }}</a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Rôle</strong></p>
                        <p><span class="badge bg-info">{{ $user->role ?? 'N/A' }}</span></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Établissement</strong></p>
                        <p>{{ $user->etablissement->nom ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted mb-1"><strong>Statut</strong></p>
                        <p>
                            @if($user->is_active ?? false)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i> Historique
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Date d'inscription</th>
                            <th>Dernière connexion</th>
                            <th>Nombre de connexions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                            <td>{{ $user->created_at?->format('d/m/Y') ?? 'Date inconnue' }}</td>
                            <td>{{ $user->login_count ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @if($user->role === 'proviseur' || $user->role === 'surveillant' || $user->role === 'operateur')
        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i> Statistiques
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h6 class="text-muted">Actions effectuées</h6>
                        <h3>{{ $user->actions_count ?? 0 }}</h3>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Documents créés</h6>
                        <h3>{{ $user->documents_count ?? 0 }}</h3>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted">Temps d'utilisation</h6>
                        <h3>{{ $user->usage_hours ?? 0 }}h</h3>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
