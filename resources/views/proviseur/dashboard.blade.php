@extends('layouts.app')

@section('title', 'Dashboard Proviseur')
@section('page-title', 'Tableau de bord Proviseur')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Total Élèves</p>
                        <h3 class="mb-0">{{ $totalEleves ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-user-graduate text-primary" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>8% ce mois</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Classes</p>
                        <h3 class="mb-0">{{ $totalClasses ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-chalkboard text-success" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>2 nouvelles</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Photos Validées</p>
                        <h3 class="mb-0">{{ $photosValidees ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-camera text-info" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>95% complètes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Cartes Imprimées</p>
                        <h3 class="mb-0">{{ $cartesImprimees ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-print text-warning" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>72% distribuées</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i> Distribution par classe
                </h5>
            </div>
            <div class="card-body">
                <canvas id="chartDistribution"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i> Actions rapides
                </h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('proviseur.eleves.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Ajouter un élève
                </a>
                <a href="{{ route('proviseur.eleves.import') }}" class="btn btn-info">
                    <i class="fas fa-upload me-2"></i> Importer élèves
                </a>
                <a href="{{ route('proviseur.classes.create') }}" class="btn btn-success">
                    <i class="fas fa-folder-plus me-2"></i> Créer une classe
                </a>
                <a href="{{ route('proviseur.statistiques') }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-bar me-2"></i> Statistiques
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i> Activités récentes
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item pb-3">
                        <div class="d-flex">
                            <div class="timeline-marker bg-success"></div>
                            <div class="ms-3">
                                <strong>10 élèves ajoutés</strong>
                                <p class="text-muted mb-1">Classe 6ème A</p>
                                <small class="text-muted">Il y a 2 heures</small>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item pb-3">
                        <div class="d-flex">
                            <div class="timeline-marker bg-info"></div>
                            <div class="ms-3">
                                <strong>Photos validées</strong>
                                <p class="text-muted mb-1">25 photos de la classe 5ème B</p>
                                <small class="text-muted">Il y a 5 heures</small>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item pb-3">
                        <div class="d-flex">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="ms-3">
                                <strong>Cartes générées</strong>
                                <p class="text-muted mb-1">50 cartes prêtes à imprimer</p>
                                <small class="text-muted">Il y a 1 jour</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-circle me-2"></i> À faire
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 py-2 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-3">
                        <div class="flex-grow-1">
                            <p class="mb-1">Valider photos de la classe 4ème C</p>
                            <small class="text-muted">18/25 complètes</small>
                        </div>
                        <span class="badge bg-warning">Urgent</span>
                    </div>
                    <div class="list-group-item px-0 py-2 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-3">
                        <div class="flex-grow-1">
                            <p class="mb-1">Vérifier les informations des élèves</p>
                            <small class="text-muted">5 anomalies détectées</small>
                        </div>
                        <span class="badge bg-info">Important</span>
                    </div>
                    <div class="list-group-item px-0 py-2 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input me-3">
                        <div class="flex-grow-1">
                            <p class="mb-1">Imprimer les cartes de classe 3ème A</p>
                            <small class="text-muted">Prêt à imprimer</small>
                        </div>
                        <span class="badge bg-success">Normal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctxPie = document.getElementById('chartDistribution').getContext('2d');
    const chartPie = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['6ème A', '6ème B', '5ème A', '5ème B', '4ème A', '4ème B'],
            datasets: [{
                data: [45, 42, 48, 43, 46, 44],
                backgroundColor: [
                    '#4F46E5',
                    '#7C3AED',
                    '#10B981',
                    '#3B82F6',
                    '#F59E0B',
                    '#EF4444'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection
