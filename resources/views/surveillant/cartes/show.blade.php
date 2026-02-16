@extends('layouts.app')

@section('title', 'Détails Carte')
@section('page-title', 'Détails de la carte')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> Aperçu de la carte</h5>
            </div>
            <div class="card-body text-center">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            border-radius: 8px; padding: 40px; height: 300px; display: flex;
                            flex-direction: column; align-items: center; justify-content: center;
                            color: white; position: relative; overflow: hidden;">
                    <div style="text-align: center;">
                        <div style="width: 80px; height: 80px; background: white;
                                   border-radius: 50%; margin: 0 auto 20px;
                                   display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="color: #667eea; font-size: 2rem;"></i>
                        </div>
                        <h6 class="mb-2" style="color: white;">Alice Dupont</h6>
                        <p class="mb-2" style="color: rgba(255,255,255,0.9);">6ème A</p>
                        <p style="font-size: 0.9rem; color: rgba(255,255,255,0.8);">
                            <strong>N°:</strong> CART001
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Historique</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item pb-3">
                        <div class="d-flex">
                            <div class="timeline-marker bg-success"></div>
                            <div class="ms-3">
                                <strong>Photo approuvée</strong>
                                <p class="text-muted mb-1">Validée par Surveillant</p>
                                <small>20/01/2026 15:30</small>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item pb-3">
                        <div class="d-flex">
                            <div class="timeline-marker bg-info"></div>
                            <div class="ms-3">
                                <strong>Carte générée</strong>
                                <p class="text-muted mb-1">Système</p>
                                <small>21/01/2026 10:15</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Élève:</strong> Alice Dupont
                </p>
                <p class="mb-2">
                    <strong>Classe:</strong> 6ème A
                </p>
                <p class="mb-2">
                    <strong>Numéro carte:</strong> <code>CART001</code>
                </p>
                <p class="mb-2">
                    <strong>Date génération:</strong> 21/01/2026
                </p>
                <p class="mb-0">
                    <strong>Statut:</strong> <span class="badge bg-success">Générée</span>
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body d-grid gap-2">
                <button class="btn btn-primary" onclick="printCarte()">
                    <i class="fas fa-print me-2"></i> Imprimer
                </button>
                <button class="btn btn-info" onclick="downloadCarte()">
                    <i class="fas fa-download me-2"></i> Télécharger
                </button>
                <a href="{{ route('surveillant.cartes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function printCarte() {
        window.print();
    }

    function downloadCarte() {
        Swal.fire('Téléchargement', 'Carte téléchargée avec succès.', 'success');
    }
</script>
@endpush
@endsection
