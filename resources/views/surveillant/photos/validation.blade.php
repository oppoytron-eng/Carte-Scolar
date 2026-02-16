@extends('layouts.app')

@section('title', 'Validation Photos')
@section('page-title', 'Validation des photos')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        @if(isset($photosEnAttente) && $photosEnAttente->count() > 0)
            <form id="bulkForm" action="{{ route('surveillant.photos.bulk-approve') }}" method="POST">
                @csrf
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-image me-2"></i>Photos en attente de validation</h5>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleSelectAll()">
                                <i class="fas fa-check-double me-1"></i>Tout sélectionner
                            </button>
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-check-circle me-1"></i>Approuver la sélection
                            </button>
                        </div>
                    </div>
                </div>

                @foreach($photosEnAttente as $photo)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <input type="checkbox" name="photo_ids[]" value="{{ $photo->id }}" class="form-check-input photo-checkbox" style="transform: scale(1.3);">
                            </div>
                            <div class="col-auto">
                                <img src="{{ $photo->photo_miniature_url }}" alt="Photo" class="rounded" width="80" height="100" style="object-fit: cover; cursor: pointer;" onclick="showPhotoModal('{{ $photo->photo_redimensionnee_url }}')">
                            </div>
                            <div class="col">
                                <h6 class="mb-1">{{ $photo->eleve->nom_complet ?? 'N/A' }}</h6>
                                <small class="text-muted">
                                    {{ $photo->eleve->classe->nom_complet ?? '' }} |
                                    Matricule : {{ $photo->eleve->matricule ?? '' }}
                                </small><br>
                                <small class="text-muted">
                                    <i class="fas fa-camera me-1"></i>{{ $photo->methode_capture }} |
                                    <i class="fas fa-clock me-1"></i>{{ $photo->date_capture?->format('d/m/Y H:i') }} |
                                    Qualité : {{ $photo->score_qualite_format }}
                                </small><br>
                                <small class="text-muted">
                                    Opérateur : {{ $photo->operateur->full_name ?? 'N/A' }}
                                </small>
                            </div>
                            <div class="col-auto">
                                <div class="d-flex gap-2">
                                    <form action="{{ route('surveillant.photos.approve', $photo) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check"></i></button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="showRejectForm({{ $photo->id }})"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </form>

            <div class="d-flex justify-content-center">
                {{ $photosEnAttente->links() }}
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5>Aucune photo en attente</h5>
                    <p class="text-muted">Toutes les photos ont été validées.</p>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Critères de validation</h6></div>
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <ul class="mb-0 small">
                        <li>Photo claire et bien éclairée</li>
                        <li>Visage correctement cadré</li>
                        <li>Pas de lunettes de soleil</li>
                        <li>Fond uni blanc ou gris</li>
                        <li>Expression neutre</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h6 class="mb-0">Raccourcis</h6></div>
            <div class="card-body">
                <p class="small text-muted mb-0">Utilisez les boutons <span class="badge bg-success"><i class="fas fa-check"></i></span> pour approuver et <span class="badge bg-danger"><i class="fas fa-times"></i></span> pour rejeter individuellement, ou cochez plusieurs photos puis cliquez "Approuver la sélection".</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Photo -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-0">
                <img id="modalPhoto" class="img-fluid" alt="Photo">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.photo-checkbox');
    const allChecked = [...checkboxes].every(cb => cb.checked);
    checkboxes.forEach(cb => cb.checked = !allChecked);
}

function showPhotoModal(url) {
    document.getElementById('modalPhoto').src = url;
    new bootstrap.Modal(document.getElementById('photoModal')).show();
}

function showRejectForm(photoId) {
    Swal.fire({
        title: 'Rejeter la photo',
        html: `
            <form id="rejectForm" action="/surveillant/photos/${photoId}/reject" method="POST">
                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Motif du rejet</label>
                    <textarea class="form-control" name="motif_rejet" rows="3" required placeholder="Ex: Photo floue, mauvais éclairage..."></textarea>
                </div>
            </form>
        `,
        confirmButtonText: 'Rejeter',
        confirmButtonColor: '#EF4444',
        showCancelButton: true,
        cancelButtonText: 'Annuler',
        preConfirm: () => {
            const motif = document.querySelector('#rejectForm textarea').value;
            if (!motif.trim()) {
                Swal.showValidationMessage('Veuillez indiquer un motif');
                return false;
            }
            document.getElementById('rejectForm').submit();
            return false;
        }
    });
}
</script>
@endpush
@endsection