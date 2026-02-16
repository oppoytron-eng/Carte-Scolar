@extends('layouts.app')

@section('title', 'Photo Élève')
@section('page-title', 'Capture photo élève')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-camera me-2"></i>
                    Capturer photo - {{ $eleve->full_name ?? 'N/A' }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="photoPreview" class="form-label">Aperçu</label>
                            <div id="photoPreview" style="background: #f0f0f0; border-radius: 8px; height: 300px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                                <i class="fas fa-image" style="font-size: 3rem; color: #ccc;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="fas fa-info-circle"></i> Informations élève</h6>
                                <p class="mb-2">
                                    <strong>Nom:</strong> {{ $eleve->full_name ?? 'N/A' }}
                                </p>
                                <p class="mb-2">
                                    <strong>Classe:</strong> {{ $eleve->classe->nom ?? 'N/A' }}
                                </p>
                                <p class="mb-2">
                                    <strong>N° inscription:</strong> {{ $eleve->numero_inscription ?? 'N/A' }}
                                </p>
                                <p class="mb-0">
                                    <strong>Date naissance:</strong> {{ $eleve->date_naissance ? $eleve->date_naissance->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-lightbulb"></i>
                            <strong>Conseils:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li>Éclairage frontal</li>
                                <li>Fond uni</li>
                                <li>Visage centré</li>
                                <li>Pas d'accessoires</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <button class="btn btn-lg btn-success" onclick="capturePhoto()">
                            <i class="fas fa-camera me-2"></i> Capturer
                        </button>
                        <button class="btn btn-lg btn-warning ms-2" onclick="retakePhoto()">
                            <i class="fas fa-redo me-2"></i> Reprendre
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <div class="btn-group w-100">
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-check me-2"></i> Valider et suivant
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Précédent
                    </button>
                    <a href="{{ route('operateur.photo.capture') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times me-2"></i> Quitter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function capturePhoto() {
        Swal.fire({
            title: 'Photo capturée',
            text: 'La photo de ' + '{{ $eleve->full_name ?? "" }}' + ' a été capturée.',
            icon: 'success'
        });
    }

    function retakePhoto() {
        Swal.fire({
            title: 'Reprendre',
            text: 'Cliquez sur Capturer pour reprendre la photo.',
            icon: 'info'
        });
    }
</script>
@endpush
@endsection
