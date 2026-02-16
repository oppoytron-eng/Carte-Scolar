@extends('layouts.app')

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

    .btn-capture:hover {
        transform: scale(1.1);
        color: white;
    }

    .student-select {
        border: 2px solid #F3F4F6;
        border-radius: 12px;
        padding: 12px;
    }

    .student-select:focus {
        border-color: var(--primary-violet);
        box-shadow: none;
    }
</style>

<div class="container py-4">
    <div class="row">
        <div class="col-md-7">
            <div class="capture-container mb-4 text-center">
                <h4 class="fw-bold mb-3"><i class="fas fa-video me-2 text-primary"></i> Station de Capture</h4>
                <div class="camera-box mb-4" id="webcam-container">
                    <i class="fas fa-user-circle fa-5x opacity-20"></i>
                    </div>
                <button class="btn btn-capture" onclick="takeSnapshot()">
                    <i class="fas fa-camera"></i>
                </button>
                <p class="mt-3 text-muted small">Cliquez pour capturer la photo de l'élève</p>
            </div>
        </div>

        <div class="col-md-5">
            <div class="capture-container">
                <h4 class="fw-bold mb-4">Informations</h4>
                
                <form action="{{ route('operateur.photos.store') }}" method="POST" id="captureForm">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Sélectionner l'élève</label>
                        <select name="eleve_id" class="form-select student-select" required>
                            <option value="">-- Choisir un élève --</option>
                            @foreach($eleves as $eleve)
                                <option value="{{ $eleve->id }}">
                                    {{ $eleve->nom }} {{ $eleve->prenom }} ({{ $eleve->classe->nom ?? 'Sans classe' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert alert-primary border-0" style="background: #EEF2FF; color: #4338CA;">
                        <i class="fas fa-info-circle me-2"></i>
                        Établissement : <strong>{{ $etablissement->nom }}</strong>
                    </div>

                    <input type="hidden" name="image_data" id="image_data">
                    
                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 12px; background: var(--primary-violet);">
                        <i class="fas fa-save me-2"></i> Enregistrer la photo
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection