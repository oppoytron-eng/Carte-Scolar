@extends('layouts.app')

@section('title', 'Statistiques')
@section('page-title', 'Statistiques de l\'établissement')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Total Élèves</p>
                        <h3>{{ $totalEleves ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-user-graduate" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Classes</p>
                        <h3>{{ $totalClasses ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-chalkboard" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Taux Validation</p>
                        <h3>{{ $validationRate ?? 0 }}%</h3>
                    </div>
                    <i class="fas fa-chart-pie" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-2">Taux Distribution</p>
                        <h3>{{ $distributionRate ?? 0 }}%</h3>
                    </div>
                    <i class="fas fa-hand-holding" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Distribution par classe</h5>
            </div>
            <div class="card-body">
                <canvas id="chartClasses"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Progression</h5>
            </div>
            <div class="card-body">
                <canvas id="chartProgression"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Statistiques par classe</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Classe</th>
                            <th>Élèves</th>
                            <th>Photos</th>
                            <th>Cartes</th>
                            <th>Imprimées</th>
                            <th>Distribuées</th>
                            <th>Progression</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes ?? [] as $classe)
                        <tr>
                            <td><strong>{{ $classe->nom }}</strong></td>
                            <td>{{ $classe->eleves_count ?? 0 }}</td>
                            <td>
                                <span class="badge bg-info">{{ $classe->photos_count ?? 0 }}</span>
                                <small>{{ round(($classe->photos_count ?? 0) / ($classe->eleves_count ?? 1) * 100) }}%</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $classe->cartes_count ?? 0 }}</span>
                                <small>{{ round(($classe->cartes_count ?? 0) / ($classe->eleves_count ?? 1) * 100) }}%</small>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $classe->cartes_imprimees ?? 0 }}</span>
                                <small>{{ round(($classe->cartes_imprimees ?? 0) / ($classe->eleves_count ?? 1) * 100) }}%</small>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $classe->cartes_distribuees ?? 0 }}</span>
                                <small>{{ round(($classe->cartes_distribuees ?? 0) / ($classe->eleves_count ?? 1) * 100) }}%</small>
                            </td>
                            <td>
                                <div class="progress" style="width: 100px;">
                                    <div class="progress-bar" style="width: {{ round(($classe->cartes_distribuees ?? 0) / ($classe->eleves_count ?? 1) * 100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctxClasses = document.getElementById('chartClasses').getContext('2d');
    new Chart(ctxClasses, {
        type: 'bar',
        data: {
            labels: @json(($classes ?? collect())->pluck('nom')),
            datasets: [{
                label: 'Nombre d\'élèves',
                data: @json(($classes ?? collect())->pluck('eleves_count')),
                backgroundColor: '#4F46E5'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    const ctxProgression = document.getElementById('chartProgression').getContext('2d');
    new Chart(ctxProgression, {
        type: 'line',
        data: {
            labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'],
            datasets: [
                {
                    label: 'Photos validées',
                    data: [25, 55, 75, 95],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Cartes générées',
                    data: [10, 35, 60, 90],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true, max: 100 } }
        }
    });
</script>
@endpush
@endsection
