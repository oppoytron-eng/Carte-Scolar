@extends('layouts.app')

@section('title', 'Cartes')
@section('page-title', 'Gestion des cartes')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <form method="GET" action="{{ route('surveillant.cartes.index') }}" class="d-flex gap-2">
            <select name="statut" class="form-select" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="Carte_generee" {{ request('statut') == 'Carte_generee' ? 'selected' : '' }}>Générée</option>
                <option value="Carte_imprimee" {{ request('statut') == 'Carte_imprimee' ? 'selected' : '' }}>Imprimée</option>
                <option value="Carte_distribuee" {{ request('statut') == 'Carte_distribuee' ? 'selected' : '' }}>Distribuée</option>
            </select>
            <select name="classe_id" class="form-select" onchange="this.form.submit()">
                <option value="">Toutes les classes</option>
                @foreach($classes ?? [] as $classe)
                    <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>{{ $classe->nom }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> Cartes générées</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Élève</th>
                            <th>Classe</th>
                            <th>Date génération</th>
                            <th>Numéro carte</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cartes as $carte)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $carte->eleve->nom ?? '' }} {{ $carte->eleve->prenoms ?? '' }}</td>
                            <td><span class="badge bg-primary">{{ $carte->eleve->classe->nom ?? '-' }}</span></td>
                            <td>{{ $carte->date_generation ? \Carbon\Carbon::parse($carte->date_generation)->format('d/m/Y H:i') : '-' }}</td>
                            <td><code>{{ $carte->numero_carte }}</code></td>
                            <td>
                                @switch($carte->statut)
                                    @case('Carte_generee')
                                        <span class="badge bg-info">Générée</span>
                                        @break
                                    @case('Carte_imprimee')
                                        <span class="badge bg-success">Imprimée</span>
                                        @break
                                    @case('Carte_distribuee')
                                        <span class="badge bg-primary">Distribuée</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $carte->statut }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('surveillant.cartes.show', $carte) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($carte->statut === 'Carte_generee')
                                    <form action="{{ route('surveillant.impression.process') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="carte_ids[]" value="{{ $carte->id }}">
                                        <button type="submit" class="btn btn-outline-success">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Aucune carte trouvée</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($cartes->hasPages())
            <div class="card-footer">
                {{ $cartes->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection