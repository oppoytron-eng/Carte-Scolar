@extends('layouts.app')

@section('title', 'Rapport Classe')
@section('page-title', 'Rapport de classe')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Sélectionner une classe</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('surveillant.rapports.classe') }}">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="classe_id" class="form-label">Classe</label>
                            <select class="form-select" id="classe_id" name="classe_id" onchange="this.form.submit()">
                                <option value="">-- Sélectionnez une classe --</option>
                                @foreach($classes ?? [] as $classe)
                                    <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-primary w-100" onclick="exportRapport()">
                                <i class="fas fa-download me-2"></i> Exporter PDF
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if($classe ?? false)
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Total élèves</h6>
                <h3>{{ $classe->eleves_count ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Photos</h6>
                <h3>{{ $classe->photos_count ?? 0 }}/{{ $classe->eleves_count ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Cartes</h6>
                <h3>{{ $classe->cartes_count ?? 0 }}/{{ $classe->eleves_count ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Distribuées</h6>
                <h3>{{ $classe->cartes_distribuees ?? 0 }}/{{ $classe->eleves_count ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-bottom">
        <h5 class="mb-0">Détail des élèves</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 table-sm">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Photo</th>
                    <th>Carte</th>
                    <th>Imprimée</th>
                    <th>Distribuée</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classe->eleves ?? [] as $eleve)
                <tr>
                    <td>{{ $eleve->full_name ?? 'N/A' }}</td>
                    <td>
                        @if($eleve->photo_valid)
                            <span class="badge bg-success"><i class="fas fa-check"></i></span>
                        @elseif($eleve->photo_uploaded)
                            <span class="badge bg-warning"><i class="fas fa-hourglass"></i></span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times"></i></span>
                        @endif
                    </td>
                    <td>
                        @if($eleve->carte_generee)
                            <span class="badge bg-info"><i class="fas fa-check"></i></span>
                        @else
                            <span class="badge bg-secondary"><i class="fas fa-minus"></i></span>
                        @endif
                    </td>
                    <td>
                        @if($eleve->carte_imprimee)
                            <span class="badge bg-info"><i class="fas fa-check"></i></span>
                        @else
                            <span class="badge bg-secondary"><i class="fas fa-minus"></i></span>
                        @endif
                    </td>
                    <td>
                        @if($eleve->carte_received)
                            <span class="badge bg-success"><i class="fas fa-check"></i></span>
                        @else
                            <span class="badge bg-secondary"><i class="fas fa-minus"></i></span>
                        @endif
                    </td>
                    <td>
                        @if($eleve->carte_received)
                            <span class="badge bg-success">Complète</span>
                        @else
                            <span class="badge bg-warning">En cours</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-3 text-muted">Aucun élève</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-info-circle" style="font-size: 2rem; color: #999;"></i>
        <p class="text-muted mt-3">Sélectionnez une classe pour voir le rapport</p>
    </div>
</div>
@endif

@push('scripts')
<script>
    function exportRapport() {
        const classeId = document.getElementById('classe_id').value;
        if (!classeId) {
            Swal.fire('Erreur', 'Veuillez sélectionner une classe', 'error');
            return;
        }
        window.location.href = `/surveillant/rapports/classe/${classeId}/export`;
    }
</script>
@endpush
@endsection
