@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Tableau de bord Administrateur')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Total Utilisateurs</p>
                        <h3 class="mb-0">{{ $totalUsers ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-users text-primary" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>12% cette semaine</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Établissements</p>
                        <h3 class="mb-0">{{ $totalEtablissements ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-school text-success" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>5% ce mois</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Cartes Générées</p>
                        <h3 class="mb-0">{{ $cartesGenerees ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-id-card text-info" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>24% cette année</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Rapports Générés</p>
                        <h3 class="mb-0">{{ $rapportsGeneres ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-chart-bar text-warning" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>8% cette semaine</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i> Activité système
                </h5>
            </div>
            <div class="card-body">
                <canvas id="chartActivite"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-tasks me-2"></i> Tâches récentes
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between">
                            <span>Synchronisation BD</span>
                            <span class="badge bg-success">✓</span>
                        </div>
                        <small class="text-muted">Il y a 2 heures</small>
                    </div>
                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between">
                            <span>Backup données</span>
                            <span class="badge bg-success">✓</span>
                        </div>
                        <small class="text-muted">Il y a 4 heures</small>
                    </div>
                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between">
                            <span>Génération rapports</span>
                            <span class="badge bg-warning">En cours</span>
                        </div>
                        <small class="text-muted">Depuis 15 minutes</small>
                    </div>
                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex justify-content-between">
                            <span>Import données</span>
                            <span class="badge bg-info">Planifié</span>
                        </div>
                        <small class="text-muted">Demain à 02:00</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i> Utilisateurs récents
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Rôle</th>
                            <th>Date d'inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                Jean Dupont
                            </td>
                            <td><span class="badge bg-primary">Proviseur</span></td>
                            <td>20/01/2026</td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-user-circle me-2 text-info"></i>
                                Marie Martin
                            </td>
                            <td><span class="badge bg-info">Surveillant</span></td>
                            <td>19/01/2026</td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-user-circle me-2 text-success"></i>
                                Pierre Bernard
                            </td>
                            <td><span class="badge bg-success">Opérateur</span></td>
                            <td>18/01/2026</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i> Alertes système
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Mise à jour disponible</strong> - Version 2.5.1 est disponible
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Maintenance prévue</strong> - Jeudi 25/01 de 02:00 à 04:00
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Tous les systèmes OK</strong> - Aucune anomalie détectée
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i> Historique des actions
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date/Heure</th>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Détails</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>21/01/2026 10:30</td>
                            <td>Admin</td>
                            <td><i class="fas fa-user-plus text-success me-1"></i> Création utilisateur</td>
                            <td>Jean Dupont - Proviseur</td>
                            <td><span class="badge bg-success">Réussi</span></td>
                        </tr>
                        <tr>
                            <td>21/01/2026 09:45</td>
                            <td>Admin</td>
                            <td><i class="fas fa-edit text-info me-1"></i> Édition établissement</td>
                            <td>Lycée Central</td>
                            <td><span class="badge bg-success">Réussi</span></td>
                        </tr>
                        <tr>
                            <td>20/01/2026 16:20</td>
                            <td>Admin</td>
                            <td><i class="fas fa-download text-primary me-1"></i> Export données</td>
                            <td>Rapport mensuel</td>
                            <td><span class="badge bg-success">Réussi</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('chartActivite').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Utilisateurs actifs',
                data: [65, 78, 90, 81, 56, 55, 40],
                borderColor: '#4be546ff',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection
