@extends('layouts.app')

@section('title', 'Dashboard Surveillant')
@section('page-title', 'Tableau de bord Surveillant')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Photos en attente</p>
                        <h3 class="mb-0">{{ $photosEnAttente ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-hourglass-end text-warning" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-danger"><i class="fas fa-arrow-up me-1"></i>3 nouvelles</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Photos Approuvées</p>
                        <h3 class="mb-0">{{ $photosApprovees ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-check-circle text-success" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>92% du total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Cartes à distribuer</p>
                        <h3 class="mb-0">{{ $cartesADistribuer ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-boxes text-info" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-info"><i class="fas fa-info-circle me-1"></i>Prêt à imprimer</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Cartes Distribuées</p>
                        <h3 class="mb-0">{{ $cartesDistribuees ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-hand-holding text-primary" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>85% complètes</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i> Validation photos par classe
                </h5>
            </div>
            <div class="card-body">
                <canvas id="chartValidation"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-tasks me-2"></i> Actions rapides
                </h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('surveillant.photos.validation') }}" class="btn btn-primary">
                    <i class="fas fa-camera me-2"></i> Valider photos
                </a>
                <a href="{{ route('surveillant.cartes.index') }}" class="btn btn-info">
                    <i class="fas fa-id-card me-2"></i> Gérer cartes
                </a>
                <a href="{{ route('surveillant.impression.batch') }}" class="btn btn-success">
                    <i class="fas fa-print me-2"></i> Impression en masse
                </a>
                <a href="{{ route('surveillant.rapports.classe') }}" class="btn btn-outline-primary">
                    <i class="fas fa-file-pdf me-2"></i> Rapport de classe
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list-check me-2"></i> Photos en attente de validation
                </h5>
                <a href="{{ route('surveillant.photos.validation') }}" class="btn btn-sm btn-link">Voir tout</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-sm">
                    <thead>
                        <tr>
                            <th>Élève</th>
                            <th>Classe</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <i class="fas fa-user me-2"></i>
                                Alice Dupont
                            </td>
                            <td>6ème A</td>
                            <td>21/01/2026</td>
                            <td><span class="badge bg-warning">En attente</span></td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-user me-2"></i>
                                Bob Martin
                            </td>
                            <td>5ème B</td>
                            <td>20/01/2026</td>
                            <td><span class="badge bg-warning">En attente</span></td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-user me-2"></i>
                                Claire Bernard
                            </td>
                            <td>4ème C</td>
                            <td>19/01/2026</td>
                            <td><span class="badge bg-danger">Rejetée</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-print me-2"></i> Impression récentes
                </h5>
                <a href="{{ route('surveillant.impression.index') }}" class="btn btn-sm btn-link">Voir tout</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-sm">
                    <thead>
                        <tr>
                            <th>Classe</th>
                            <th>Qty</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <i class="fas fa-chalkboard me-2"></i>
                                6ème A
                            </td>
                            <td>42</td>
                            <td>21/01/2026</td>
                            <td><span class="badge bg-success">Complètes</span></td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-chalkboard me-2"></i>
                                5ème B
                            </td>
                            <td>38</td>
                            <td>20/01/2026</td>
                            <td><span class="badge bg-success">Complètes</span></td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-chalkboard me-2"></i>
                                4ème C
                            </td>
                            <td>40</td>
                            <td>20/01/2026</td>
                            <td><span class="badge bg-info">En cours</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctxValidation = document.getElementById('chartValidation').getContext('2d');
    const chartValidation = new Chart(ctxValidation, {
        type: 'bar',
        data: {
            labels: ['6ème A', '6ème B', '5ème A', '5ème B', '4ème A', '4ème B'],
            datasets: [
                {
                    label: 'Approuvées',
                    data: [45, 42, 48, 43, 46, 44],
                    backgroundColor: '#10B981'
                },
                {
                    label: 'En attente',
                    data: [2, 3, 1, 2, 1, 2],
                    backgroundColor: '#F59E0B'
                },
                {
                    label: 'Rejetées',
                    data: [1, 0, 1, 0, 1, 0],
                    backgroundColor: '#EF4444'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: false
                },
                y: {
                    stacked: false
                }
            }
        }
    });
</script>
@endpush
@endsection
