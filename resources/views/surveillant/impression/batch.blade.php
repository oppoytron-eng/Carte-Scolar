@extends('layouts.app')

@section('title', 'Impression en Masse')
@section('page-title', 'Impression en masse')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i> Imprimer en masse</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('surveillant.impression.batch.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="selection" class="form-label">Sélectionner les classes</label>
                        <div class="card bg-light">
                            <div class="card-body">
                                @foreach(['6ème A', '6ème B', '5ème A', '5ème B', '4ème A', '4ème B', '4ème C'] as $classe)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="classe_{{ $loop->index }}" name="classes[]" value="{{ $loop->index }}">
                                    <label class="form-check-label" for="classe_{{ $loop->index }}">
                                        {{ $classe }} ({{ 40 + rand(0, 10) }} élèves)
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="copies" class="form-label">Nombre de copies par élève</label>
                            <input type="number" class="form-control" id="copies" name="copies" value="1" min="1" max="5">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="qualite" class="form-label">Qualité d'impression</label>
                            <select class="form-select" id="qualite" name="qualite">
                                <option value="draft">Brouillon</option>
                                <option value="normal" selected>Normal</option>
                                <option value="haute">Haute qualité</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="couleur" class="form-label">Couleur</label>
                            <select class="form-select" id="couleur" name="couleur">
                                <option value="couleur" selected>Couleur</option>
                                <option value="nb">Noir & Blanc</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="imprimante" class="form-label">Imprimante</label>
                            <select class="form-select" id="imprimante" name="imprimante">
                                <option value="">-- Sélectionnez une imprimante --</option>
                                <option value="imp1" selected>Imprimante 1</option>
                                <option value="imp2">Imprimante 2</option>
                                <option value="imp3">Imprimante 3</option>
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Résumé:</strong> Vous allez imprimer <strong id="totalCartes">0 cartes</strong>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('surveillant.impression.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-print me-2"></i> Lancer impression
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Calculer le total de cartes
    function calculateTotal() {
        const checkboxes = document.querySelectorAll('input[name="classes[]"]:checked');
        const copies = parseInt(document.getElementById('copies').value) || 1;
        const total = checkboxes.length * 45 * copies; // 45 élèves par classe en moyenne
        document.getElementById('totalCartes').textContent = total + ' cartes';
    }

    document.querySelectorAll('input[name="classes[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotal);
    });

    document.getElementById('copies').addEventListener('change', calculateTotal);
</script>
@endpush
@endsection
