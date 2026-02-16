@extends('layouts.app')

@section('title', 'Capture Photo')
@section('page-title', 'Station de Capture Photo')

@section('content')
<style>
    :root {
        --primary-violet: #4F46E5;
        --secondary-purple: #7C3AED;
    }
    .capture-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        padding: 2rem;
    }
    .camera-box {
        background: #1F2937;
        border-radius: 15px;
        aspect-ratio: 4/3;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        overflow: hidden;
        border: 4px solid #E5E7EB;
        position: relative;
    }
    .camera-box video, .camera-box canvas {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
    }
    .btn-capture {
        background: linear-gradient(135deg, var(--primary-violet) 0%, var(--secondary-purple) 100%);
        border: none;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);
        transition: transform 0.2s;
    }
    .btn-capture:hover { transform: scale(1.1); color: white; }
    .student-select {
        border: 2px solid #F3F4F6;
        border-radius: 12px;
        padding: 12px;
    }
    .student-select:focus { border-color: var(--primary-violet); box-shadow: none; }
    .preview-zone { display: none; }
    .preview-zone.active { display: block; }
    .camera-zone.hidden { display: none; }
    .upload-zone {
        border: 2px dashed #ddd;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.3s;
    }
    .upload-zone:hover { border-color: var(--primary-violet); }
</style>

<div class="container py-4">
    <div class="row">
        <!-- Zone caméra -->
        <div class="col-md-7">
            <div class="capture-container mb-4 text-center">
                <h4 class="fw-bold mb-3"><i class="fas fa-video me-2 text-primary"></i>Station de Capture</h4>

                <!-- Caméra en direct -->
                <div class="camera-box mb-4" id="camera-zone">
                    <video id="webcamVideo" autoplay playsinline muted></video>
                    <div id="cameraPlaceholder" class="position-absolute">
                        <i class="fas fa-user-circle fa-5x opacity-25"></i>
                        <p class="mt-2 opacity-50">Cliquez "Démarrer" pour activer la caméra</p>
                    </div>
                </div>

                <!-- Preview photo capturée -->
                <div class="camera-box mb-4 preview-zone" id="preview-zone">
                    <canvas id="photoCanvas"></canvas>
                </div>

                <!-- Boutons de contrôle -->
                <div class="d-flex justify-content-center gap-3">
                    <button class="btn btn-primary btn-lg" id="btnStart" onclick="startCamera()">
                        <i class="fas fa-video me-1"></i>Démarrer
                    </button>
                    <button class="btn btn-capture d-none" id="btnCapture" onclick="takeSnapshot()">
                        <i class="fas fa-camera"></i>
                    </button>
                    <button class="btn btn-warning btn-lg d-none" id="btnRetake" onclick="retakePhoto()">
                        <i class="fas fa-redo me-1"></i>Reprendre
                    </button>
                </div>

                <!-- Tab upload alternatif -->
                <hr class="my-4">
                <h6 class="text-muted mb-3">Ou télécharger un fichier</h6>
                <div class="upload-zone" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Cliquez pour sélectionner un fichier (JPEG, PNG, max 5 Mo)</p>
                    <input type="file" id="fileInput" class="d-none" accept="image/jpeg,image/png" onchange="handleFileUpload(event)">
                </div>
            </div>
        </div>

        <!-- Infos élève -->
        <div class="col-md-5">
            <div class="capture-container mb-4">
                <h4 class="fw-bold mb-4"><i class="fas fa-user-graduate me-2"></i>Informations</h4>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Sélectionner l'élève</label>
                    <select id="eleveSelect" class="form-select student-select" required>
                        <option value="">-- Choisir un élève --</option>
                        @foreach($eleves as $eleve)
                            <option value="{{ $eleve->id }}" data-nom="{{ $eleve->nom }}" data-prenoms="{{ $eleve->prenoms }}" data-classe="{{ $eleve->classe->nom ?? 'Sans classe' }}">
                                {{ $eleve->nom }} {{ $eleve->prenoms }} ({{ $eleve->classe->nom ?? 'Sans classe' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="alert alert-primary border-0" style="background: #EEF2FF; color: #4338CA;">
                    <i class="fas fa-info-circle me-2"></i>
                    Établissement : <strong>{{ $etablissement->nom }}</strong>
                </div>

                <div id="eleveInfo" class="d-none mb-4">
                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <h6 class="fw-bold" id="eleveNom"></h6>
                            <p class="text-muted mb-0" id="eleveClasse"></p>
                        </div>
                    </div>
                </div>

                <button id="btnSave" class="btn btn-primary w-100 py-3 fw-bold d-none" style="border-radius: 12px;" onclick="savePhoto()">
                    <i class="fas fa-save me-2"></i>Enregistrer la photo
                </button>

                <div id="savingSpinner" class="text-center d-none py-3">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-2">Enregistrement en cours...</p>
                </div>
            </div>

            <!-- Stats rapides -->
            <div class="capture-container">
                <h6 class="fw-bold mb-3"><i class="fas fa-chart-bar me-2"></i>Élèves sans photo</h6>
                <p class="display-6 fw-bold text-primary mb-0">{{ $eleves->count() }}</p>
                <p class="text-muted">élèves en attente de photo</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let stream = null;
let capturedImageData = null;

// Afficher info élève sélectionné
document.getElementById('eleveSelect').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const infoDiv = document.getElementById('eleveInfo');
    if (this.value) {
        document.getElementById('eleveNom').textContent = selected.dataset.nom + ' ' + selected.dataset.prenoms;
        document.getElementById('eleveClasse').textContent = 'Classe : ' + selected.dataset.classe;
        infoDiv.classList.remove('d-none');
    } else {
        infoDiv.classList.add('d-none');
    }
});

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }
        });
        const video = document.getElementById('webcamVideo');
        video.srcObject = stream;
        document.getElementById('cameraPlaceholder').style.display = 'none';
        document.getElementById('btnStart').classList.add('d-none');
        document.getElementById('btnCapture').classList.remove('d-none');
    } catch (err) {
        Swal.fire('Erreur caméra', 'Impossible d\'accéder à la caméra. Vérifiez les permissions de votre navigateur.\n\n' + err.message, 'error');
    }
}

function takeSnapshot() {
    const eleveId = document.getElementById('eleveSelect').value;
    if (!eleveId) {
        Swal.fire('Attention', 'Veuillez d\'abord sélectionner un élève.', 'warning');
        return;
    }

    const video = document.getElementById('webcamVideo');
    const canvas = document.getElementById('photoCanvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0);

    capturedImageData = canvas.toDataURL('image/jpeg', 0.9);

    // Afficher preview, masquer caméra
    document.getElementById('camera-zone').style.display = 'none';
    document.getElementById('preview-zone').classList.add('active');

    document.getElementById('btnCapture').classList.add('d-none');
    document.getElementById('btnRetake').classList.remove('d-none');
    document.getElementById('btnSave').classList.remove('d-none');

    // Arrêter la caméra
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
}

function retakePhoto() {
    capturedImageData = null;
    document.getElementById('camera-zone').style.display = '';
    document.getElementById('preview-zone').classList.remove('active');
    document.getElementById('btnRetake').classList.add('d-none');
    document.getElementById('btnSave').classList.add('d-none');
    document.getElementById('btnStart').classList.remove('d-none');
    document.getElementById('cameraPlaceholder').style.display = '';
}

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const eleveId = document.getElementById('eleveSelect').value;
    if (!eleveId) {
        Swal.fire('Attention', 'Veuillez d\'abord sélectionner un élève.', 'warning');
        event.target.value = '';
        return;
    }

    // Upload via FormData
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('eleve_id', eleveId);
    formData.append('methode_capture', 'Upload');

    document.getElementById('savingSpinner').classList.remove('d-none');

    fetch('{{ route("operateur.photo.upload") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => {
        if (!res.ok) return res.text().then(t => { throw new Error(t.substring(0, 200)); });
        return res.json();
    })
    .then(data => {
        document.getElementById('savingSpinner').classList.add('d-none');
        if (data.success) {
            Swal.fire('Succès', data.message, 'success').then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire('Erreur', data.message, 'error');
        }
    })
    .catch(err => {
        document.getElementById('savingSpinner').classList.add('d-none');
        Swal.fire('Erreur', 'Erreur réseau : ' + err.message, 'error');
    });
}

function savePhoto() {
    if (!capturedImageData) return;

    const eleveId = document.getElementById('eleveSelect').value;
    if (!eleveId) {
        Swal.fire('Attention', 'Veuillez sélectionner un élève.', 'warning');
        return;
    }

    document.getElementById('btnSave').classList.add('d-none');
    document.getElementById('savingSpinner').classList.remove('d-none');

    fetch('{{ route("operateur.photo.save") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            image: capturedImageData,
            eleve_id: eleveId
        })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('savingSpinner').classList.add('d-none');
        if (data.success) {
            Swal.fire('Succès', data.message, 'success').then(() => {
                window.location.reload();
            });
        } else {
            document.getElementById('btnSave').classList.remove('d-none');
            Swal.fire('Erreur', data.message, 'error');
        }
    })
    .catch(err => {
        document.getElementById('savingSpinner').classList.add('d-none');
        document.getElementById('btnSave').classList.remove('d-none');
        Swal.fire('Erreur', 'Erreur réseau : ' + err.message, 'error');
    });
}
</script>
@endpush
@endsection