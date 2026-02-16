@extends('layouts.app')

@section('title', 'Prise de Photo')
@section('page-title', 'Prise de photo')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-camera me-2"></i> Appareil photo</h5>
            </div>
            <div class="card-body">
                <div id="camera" style="background: #000; border-radius: 8px; height: 500px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-video" style="font-size: 4rem; color: #666;"></i>
                </div>
                <div class="mt-4 text-center">
                    <button class="btn btn-lg btn-success" onclick="capturePhoto()">
                        <i class="fas fa-circle me-2"></i> Capturer
                    </button>
                    <button class="btn btn-lg btn-secondary ms-2" onclick="resetCamera()">
                        <i class="fas fa-redo me-2"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-user-check me-2"></i> Élève</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="eleve_id" class="form-label">Sélectionner un élève</label>
                    <input type="text" class="form-control" id="elevSearch" placeholder="Rechercher...">
                </div>

                <div id="eleveInfo" style="display: none;">
                    <div class="mb-3 p-3 bg-light rounded">
                        <p class="mb-1"><strong>Nom:</strong> <span id="eleveName"></span></p>
                        <p class="mb-1"><strong>Classe:</strong> <span id="eleveClasse"></span></p>
                        <p class="mb-0"><strong>N°:</strong> <span id="eleveNum"></span></p>
                    </div>
                </div>

                <div class="list-group list-group-flush">
                    @for($i = 1; $i <= 5; $i++)
                    <a href="#" class="list-group-item list-group-item-action" onclick="selectEleve({{ $i }})">
                        <div class="d-flex">
                            <div style="width: 40px; height: 40px; background: #e9ecef; border-radius: 4px; margin-right: 10px;"></div>
                            <div>
                                <p class="mb-1">Élève {{ $i }}</p>
                                <small class="text-muted">6ème A</small>
                            </div>
                        </div>
                    </a>
                    @endfor
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-cog me-2"></i> Paramètres</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="resolution" class="form-label">Résolution</label>
                    <select class="form-select" id="resolution">
                        <option>1920x1080</option>
                        <option selected>1280x720</option>
                        <option>640x480</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="luminosite" class="form-label">Luminosité</label>
                    <input type="range" class="form-range" id="luminosite" min="0" max="100" value="50">
                </div>
                <div class="mb-0">
                    <label for="contraste" class="form-label">Contraste</label>
                    <input type="range" class="form-range" id="contraste" min="0" max="100" value="50">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectEleve(id) {
        document.getElementById('eleveInfo').style.display = 'block';
        document.getElementById('eleveName').textContent = 'Élève ' + id;
        document.getElementById('eleveClasse').textContent = '6ème A';
        document.getElementById('eleveNum').textContent = 'ELE' + String(id).padStart(3, '0');
    }

    function capturePhoto() {
        Swal.fire({
            title: 'Photo capturée',
            text: 'La photo a été capturée avec succès.',
            icon: 'success'
        }).then(() => {
            // Recharger
        });
    }

    function resetCamera() {
        document.getElementById('eleveInfo').style.display = 'none';
    }

    document.getElementById('elevSearch').addEventListener('keyup', function() {
        // Implémenter la recherche
    });
</script>
@endpush
@endsection
