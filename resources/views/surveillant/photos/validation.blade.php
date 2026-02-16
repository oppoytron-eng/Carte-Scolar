@extends('layouts.app')

@section('title', 'Validation Photos')
@section('page-title', 'Validation des photos')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-image me-2"></i>
                        Photo en validation
                    </h5>
                    <small class="text-muted">Photo 1 / 125</small>
                </div>
            </div>
            <div class="card-body text-center">
                <div style="background: #f0f0f0; height: 400px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-image" style="font-size: 4rem; color: #ccc;"></i>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted">Informations élève</h6>
                    <p class="mb-2">
                        <strong>Nom:</strong> Alice Dupont<br>
                        <strong>Classe:</strong> 6ème A<br>
                        <strong>Numéro:</strong> ALI001
                    </p>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Critères de validation:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Photo claire et bien éclairée</li>
                        <li>Visage correctement cadré</li>
                        <li>Pas de lunettes de soleil</li>
                        <li>Fond uni blanc ou gris</li>
                    </ul>
                </div>

                <div class="btn-group w-100" role="group">
                    <button class="btn btn-lg btn-success" onclick="approvePhoto()">
                        <i class="fas fa-check-circle me-2"></i> Approuver
                    </button>
                    <button class="btn btn-lg btn-warning" onclick="showRejectForm()">
                        <i class="fas fa-times-circle me-2"></i> Rejeter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Files à traiter</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @for($i = 1; $i <= 5; $i++)
                    <div class="list-group-item px-0 py-2">
                        <div class="d-flex">
                            <div style="width: 40px; height: 40px; background: #e9ecef; border-radius: 4px; margin-right: 10px;"></div>
                            <div>
                                <p class="mb-1"><strong>Élève {{ $i }}</strong></p>
                                <small class="text-muted">6ème A</small>
                            </div>
                            <span class="badge bg-warning ms-auto">En attente</span>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Statistiques</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Approuvées:</strong> <span class="badge bg-success">42</span>
                </p>
                <p class="mb-2">
                    <strong>Rejetées:</strong> <span class="badge bg-danger">5</span>
                </p>
                <p class="mb-2">
                    <strong>En attente:</strong> <span class="badge bg-warning">78</span>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function approvePhoto() {
        Swal.fire({
            title: 'Photo approuvée',
            text: 'La photo a été validée avec succès.',
            icon: 'success'
        }).then(() => {
            location.reload();
        });
    }

    function showRejectForm() {
        Swal.fire({
            title: 'Rejeter la photo',
            html: `
                <div class="mb-3">
                    <label class="form-label">Raison du rejet</label>
                    <select class="form-select" id="rejectReason">
                        <option value="">-- Sélectionnez une raison --</option>
                        <option value="mauvaise_qualite">Mauvaise qualité</option>
                        <option value="mal_cadre">Mal cadrée</option>
                        <option value="mauvais_eclairage">Mauvais éclairage</option>
                        <option value="lunettes_soleil">Lunettes de soleil</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Commentaire</label>
                    <textarea class="form-control" id="rejectComment" rows="3"></textarea>
                </div>
            `,
            confirmButtonText: 'Rejeter',
            showCancelButton: true,
            preConfirm: () => {
                const reason = document.getElementById('rejectReason').value;
                const comment = document.getElementById('rejectComment').value;
                if (!reason) {
                    Swal.showValidationMessage('Veuillez sélectionner une raison');
                }
                return { reason, comment };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Photo rejetée', 'La photo a été rejetée avec succès.', 'success');
            }
        });
    }
</script>
@endpush
@endsection
