@extends('layouts.app')

@section('title', 'Dashboard Opérateur')
@section('page-title', 'Tableau de bord Opérateur')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Photos du jour</p>
                        <h3 class="mb-0">{{ $photosAujourd ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-camera text-primary" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>12 ce matin</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Total Photos</p>
                        <h3 class="mb-0">{{ $totalPhotos ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-images text-success" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>85% validées</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Élèves en attente</p>
                        <h3 class="mb-0">{{ $elevesEnAttente ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-hourglass-end text-warning" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-info"><i class="fas fa-info-circle me-1"></i>À photographier</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-2">Cartes générées</p>
                        <h3 class="mb-0">{{ $cartesGenerees ?? 0 }}</h3>
                    </div>
                    <i class="fas fa-id-card text-info" style="font-size: 2rem; opacity: 0.3;"></i>
                </div>
                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>25 ce mois</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i> Photos capturées par jour
                </h5>
            </div>
            <div class="card-body">
                <canvas id="chartPhotos"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-lightning-bolt me-2"></i> Actions rapides
                </h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('operateur.photo.capture') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-camera me-2"></i> Prise de photo
                </a>
                <a href="{{ route('operateur.photos.index') }}" class="btn btn-info">
                    <i class="fas fa-gallery me-2"></i> Voir mes photos
                </a>
                <a href="{{ route('operateur.eleves.index') }}" class="btn btn-success">
                    <i class="fas fa-id-card me-2"></i> Générer carte
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
                    <i class="fas fa-list me-2"></i> Élèves à photographier
                </h5>
                <a href="{{ route('operateur.photo.capture') }}" class="btn btn-sm btn-link">Voir tout</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 table-sm">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Classe</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <i class="fas fa-user me-2"></i>
                                Sophie Lemoine
                            </td>
                            <td>6ème A</td>
                            <td><span class="badge bg-warning">Non photographié</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-camera"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-user me-2"></i>
                                Thomas Leclerc
                            </td>
                            <td>5ème A</td>
                            <td><span class="badge bg-warning">Non photographié</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-camera"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-user me-2"></i>
                                Marine Fournier
                            </td>
                            <td>4ème C</td>
                            <td><span class="badge bg-warning">Non photographié</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-camera"></i>
                                </a>
                            </td>
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
                    <i class="fas fa-history me-2"></i> Photos récentes
                </h5>
                <a href="{{ route('operateur.photos.index') }}" class="btn btn-sm btn-link">Voir tout</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="photo-thumbnail" style="background: #e9ecef; border-radius: 8px; height: 120px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image" style="font-size: 2rem; color: #999;"></i>
                        </div>
                        <small class="d-block mt-2">Alice Dupont - 21/01</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="photo-thumbnail" style="background: #e9ecef; border-radius: 8px; height: 120px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image" style="font-size: 2rem; color: #999;"></i>
                        </div>
                        <small class="d-block mt-2">Bob Martin - 21/01</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="photo-thumbnail" style="background: #e9ecef; border-radius: 8px; height: 120px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image" style="font-size: 2rem; color: #999;"></i>
                        </div>
                        <small class="d-block mt-2">Claire Bernard - 20/01</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="photo-thumbnail" style="background: #e9ecef; border-radius: 8px; height: 120px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image" style="font-size: 2rem; color: #999;"></i>
                        </div>
                        <small class="d-block mt-2">David Petit - 20/01</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctxPhotos = document.getElementById('chartPhotos').getContext('2d');
    const chartPhotos = new Chart(ctxPhotos, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
            datasets: [{
                label: 'Photos capturées',
                data: [25, 30, 28, 35, 40, 15],
                borderColor: '#4F46E5',
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
