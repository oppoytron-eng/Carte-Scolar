@extends('layouts.app')

@section('title', 'Détails Photo')
@section('page-title', 'Détails de la photo')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center">
                <div style="background: #f0f0f0; border-radius: 8px; height: 400px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-image" style="font-size: 4rem; color: #ccc;"></i>
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i> Télécharger
                    </button>
                    <button class="btn btn-outline-info">
                        <i class="fas fa-print me-2"></i> Imprimer
                    </button>
                    <button class="btn btn-outline-danger" onclick="deletePhoto()">
                        <i class="fas fa-trash me-2"></i> Supprimer
                    </button>
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
                    <strong>Date capture:</strong> 21/01/2026 10:30
                </p>
                <p class="mb-2">
                    <strong>Résolution:</strong> 1920x1080
                </p>
                <p class="mb-0">
                    <strong>Taille:</strong> 2.4 MB
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Statut</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Validée</span>
                </p>
                <small class="text-muted">Approuvée par Surveillant le 21/01/2026</small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function deletePhoto() {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Vous ne pourrez pas récupérer cette photo!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Supprimée!', 'Photo supprimée avec succès.', 'success');
            }
        });
    }
</script>
@endpush
@endsection
