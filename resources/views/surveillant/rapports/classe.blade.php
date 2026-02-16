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
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="classe_id" class="form-label">Classe</label>
                        <select class="form-select" id="classe_id" onchange="changeClasse(this.value)">
                            @foreach($classes ?? [] as $c)
                                <option value="{{ $c->id }}" {{ $classe->id == $c->id ? 'selected' : '' }}>
                                    {{ $c->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('surveillant.rapports.classe.export', $classe) }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-download me-2"></i> Exporter PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-2">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Total</h6>
                <h3>{{ $stats['total_eleves'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Avec photo</h6>
                <h3 class="text-success">{{ $stats['eleves_avec_photo'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Sans photo</h6>
                <h3 class="text-danger">{{ $stats['eleves_sans_photo'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Cartes</h6>
                <h3 class="text-info">{{ $stats['cartes_generees'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Imprimees</h6>
                <h3 class="text-warning">{{ $stats['cartes_imprimees'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="text-muted">Distribuees</h6>
                <h3 class="text-primary">{{ $stats['cartes_distribuees'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $classe->nom }} - Liste des eleves</h5>
        <span class="badge bg-primary">{{ $eleves->count() }} eleves</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prenoms</th>
                    <th>Matricule</th>
                    <th>Photo</th>
                    <th>Carte</th>
                    <th>Statut carte</th>
                </tr>
            </thead>
            <tbody>
                @forelse($eleves as $eleve)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $eleve->nom }}</td>
                    <td>{{ $eleve->prenoms }}</td>
                    <td><code>{{ $eleve->matricule ?? '-' }}</code></td>
                    <td>
                        @if($eleve->photos->where('statut', 'Approuvee')->count() > 0)
                            <span class="badge bg-success"><i class="fas fa-check"></i> Validee</span>
                        @elseif($eleve->photos->where('statut', 'En_attente')->count() > 0)
                            <span class="badge bg-warning"><i class="fas fa-hourglass-half"></i> En attente</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times"></i> Manquante</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $carteActive = $eleve->cartesScolaires->sortByDesc('created_at')->first();
                        @endphp
                        @if($carteActive)
                            @switch($carteActive->statut)
                                @case('Carte_generee')
                                    <span class="badge bg-info">Generee</span>
                                    @break
                                @case('Carte_imprimee')
                                    <span class="badge bg-success">Imprimee</span>
                                    @break
                                @case('Carte_distribuee')
                                    <span class="badge bg-primary">Distribuee</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $carteActive->statut }}</span>
                            @endswitch
                        @else
                            <span class="badge bg-secondary">Aucune</span>
                        @endif
                    </td>
                    <td>
                        @if($carteActive && $carteActive->statut === 'Carte_distribuee')
                            <span class="text-success"><i class="fas fa-check-circle"></i> Complete</span>
                        @else
                            <span class="text-warning"><i class="fas fa-clock"></i> En cours</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-3 text-muted">Aucun eleve dans cette classe</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    function changeClasse(classeId) {
        if (classeId) {
            window.location.href = '/surveillant/rapports/classe/' + classeId;
        }
    }
</script>
@endpush
@endsection