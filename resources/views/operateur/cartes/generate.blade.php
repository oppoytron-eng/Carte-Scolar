@extends('layouts.app')

@section('title', 'Générer Carte')
@section('page-title', 'Générer une carte scolaire')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-magic me-2"></i> Générer une carte</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('operateur.cartes.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="eleve_id" class="form-label">Sélectionner un élève</label>
                        <select class="form-select @error('eleve_id') is-invalid @enderror"
                                id="eleve_id" name="eleve_id" required>
                            <option value="">-- Sélectionnez un élève --</option>
                            @foreach($eleves ?? [] as $eleve)
                                <option value="{{ $eleve->id }}" {{ old('eleve_id') == $eleve->id ? 'selected' : '' }}>
                                    {{ $eleve->full_name }} ({{ $eleve->classe->nom ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('eleve_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Vérification avant génération:</strong>
                        <ul class="mb-0 mt-2">
                            <li id="photoCheck" class="text-warning">Photo validée: <i class="fas fa-hourglass-end"></i> En attente</li>
                            <li id="infoCheck" class="text-warning">Informations complètes: <i class="fas fa-hourglass-end"></i> En attente</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <label for="modele" class="form-label">Modèle de carte</label>
                        <select class="form-select" id="modele" name="modele" required>
                            <option value="standard" selected>Standard</option>
                            <option value="premium">Premium</option>
                            <option value="simple">Simple</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="couleur" class="form-label">Couleur</label>
                        <select class="form-select" id="couleur" name="couleur" required>
                            <option value="couleur" selected>Couleur</option>
                            <option value="nb">Noir & Blanc</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('operateur.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary" id="generateBtn">
                            <i class="fas fa-magic me-2"></i> Générer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('eleve_id').addEventListener('change', function() {
        if (this.value) {
            // Simuler la vérification
            document.getElementById('photoCheck').innerHTML = '<i class="fas fa-check-circle text-success me-2"></i>Photo validée: <i class="fas fa-check text-success"></i> OK';
            document.getElementById('infoCheck').innerHTML = '<i class="fas fa-check-circle text-success me-2"></i>Informations complètes: <i class="fas fa-check text-success"></i> OK';
            document.getElementById('generateBtn').disabled = false;
        } else {
            document.getElementById('photoCheck').innerHTML = '<i class="fas fa-hourglass-end text-warning me-2"></i>Photo validée: <i class="fas fa-hourglass-end text-warning"></i> En attente';
            document.getElementById('infoCheck').innerHTML = '<i class="fas fa-hourglass-end text-warning me-2"></i>Informations complètes: <i class="fas fa-hourglass-end text-warning"></i> En attente';
            document.getElementById('generateBtn').disabled = true;
        }
    });
</script>
@endpush
@endsection
