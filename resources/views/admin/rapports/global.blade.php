@extends('layouts.app')

@section('title', 'Rapport Global')
@section('page-title', 'Rapport Global du Système')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h5>Rapport Global - Période: 01/01/2026 au 21/01/2026</h5>
            <div>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i> Imprimer
                </button>
                <button class="btn btn-outline-success">
                    <i class="fas fa-download me-2"></i> Télécharger PDF
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Total Utilisateurs</h6>
                <h3>{{ $totalUsers ?? 150 }}</h3>
                <small class="text-success"><i class="fas fa-arrow-up"></i> +12 ce mois</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Total Établissements</h6>
                <h3>{{ $totalEtablissements ?? 25 }}</h3>
                <small class="text-success"><i class="fas fa-arrow-up"></i> +3 ce mois</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Total Élèves</h6>
                <h3>{{ $totalEleves ?? 2500 }}</h3>
                <small class="text-success"><i class="fas fa-arrow-up"></i> +450 ce mois</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Cartes Générées</h6>
                <h3>{{ $cartesGenerees ?? 1850 }}</h3>
                <small class="text-success"><i class="fas fa-arrow-up"></i> +320 ce mois</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Évolution des utilisateurs</h5>
            </div>
            <div class="card-body">
                <canvas id="chartUsers"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Distribution par rôle</h5>
            </div>
            <div class="card-body">
                <canvas id="chartRoles"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Détails par établissement</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Établissement</th>
                            <th>Utilisateurs</th>
                            <th>Élèves</th>
                            <th>Classes</th>
                            <th>Cartes Générées</th>
                            <th>Taux de complétude</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Lycée Central</strong></td>
                            <td>5</td>
                            <td>450</td>
                            <td>15</td>
                            <td>450</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: 100%">100%</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Collège Dupont</strong></td>
                            <td>4</td>
                            <td>380</td>
                            <td>12</td>
                            <td>320</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-warning" style="width: 84%">84%</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Lycée Pasteur</strong></td>
                            <td>6</td>
                            <td>520</td>
                            <td>18</td>
                            <td>480</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-info" style="width: 92%">92%</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>École Primaire A</strong></td>
                            <td>3</td>
                            <td>240</td>
                            <td>8</td>
                            <td>200</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-danger" style="width: 83%">83%</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Statistiques d'activité</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Connexions totales</span>
                        <strong>3,450</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Documents créés</span>
                        <strong>1,850</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Documents modifiés</span>
                        <strong>2,340</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Documents supprimés</span>
                        <strong>125</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Performance du système</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Disponibilité</span>
                        <strong>99.9%</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: 99.9%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Temps de réponse moyen</span>
                        <strong>245ms</strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Espace utilisé</span>
                        <strong>2.3 GB / 5 GB</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: 46%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Utilisateurs actifs</span>
                        <strong>42</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctxUsers = document.getElementById('chartUsers').getContext('2d');
    const chartUsers = new Chart(ctxUsers, {
        type: 'line',
        data: {
            labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6'],
            datasets: [{
                label: 'Utilisateurs actifs',
                data: [45, 52, 60, 68, 72, 85],
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const ctxRoles = document.getElementById('chartRoles').getContext('2d');
    const chartRoles = new Chart(ctxRoles, {
        type: 'doughnut',
        data: {
            labels: ['Administrateurs', 'Proviseurs', 'Surveillants', 'Opérateurs'],
            datasets: [{
                data: [8, 25, 45, 72],
                backgroundColor: ['#4F46E5', '#7C3AED', '#3B82F6', '#10B981']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endpush
@endsection
