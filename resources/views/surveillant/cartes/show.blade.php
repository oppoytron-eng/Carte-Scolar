@extends('layouts.app')

@section('title', 'Détails Carte')
@section('page-title', 'Détails de la carte')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> Aperçu de la carte</h5>
            </div>
            <div class="card-body text-center">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                            border-radius: 8px; padding: 40px; max-width: 500px; margin: 0 auto;
                            display: flex; flex-direction: column; align-items: center; justify-content: center;
                            color: white; position: relative; overflow: hidden;">
                    <div style="text-align: center;">
                        @if($carte->photo && $carte->photo->chemin)
                            <img src="{{ asset('storage/' . $carte->photo->chemin) }}"
                                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid white; margin-bottom: 15px;">
                        @else
                            <div style="width: 100px; height: 100px; background: white;
                                       border-radius: 50%; margin: 0 auto 15px;
                                       display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user" style="color: #667eea; font-size: 2.5rem;"></i>
                            </div>
                        @endif
                        <h5 class="mb-1" style="color: white;">{{ $carte->eleve->nom ?? '' }} {{ $carte->eleve->prenoms ?? '' }}</h5>
                        <p class="mb-1" style="color: rgba(255,255,255,0.9);">{{ $carte->eleve->classe->nom ?? '-' }}</p>
                        <p class="mb-1" style="color: rgba(255,255,255,0.9); font-size: 0.85rem;">
                            {{ $carte->etablissement->nom ?? '' }}
                        </p>
                        <p class="mb-0" style="font-size: 0.9rem; color: rgba(255,255,255,0.8);">
                            <strong>N°:</strong> {{ $carte->numero_carte }}
                        </p>
                        <p class="mb-0" style="font-size: 0.8rem; color: rgba(255,255,255,0.7);">
                            Année: {{ $carte->annee_scolaire }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Historique</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($carte->date_generation)
                    <div class="timeline-item pb-3">
                        <div class="d-flex">
                            <div class="timeline-marker bg-info rounded-circle" style="width:12px;height:12px;min-width:12px;margin-top:5px;"></div>
                            <div class="ms-3">
                                <strong>Carte générée</strong>
                                <p class="text-muted mb-1">Par {{ $carte->generateur->nom ?? 'Système' }} {{ $carte->generateur->prenoms ?? '' }}</p>
                                <small>{{ \Carbon\Carbon::parse($carte->date_generation)->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($carte->date_impression)
                    <div class="timeline-item pb-3">
                        <div class="d-flex">
                            <div class="timeline-marker bg-success rounded-circle" style="width:12px;height:12px;min-width:12px;margin-top:5px;"></div>
                            <div class="ms-3">
                                <strong>Carte imprimée</strong>
                                <p class="text-muted mb-1">Par {{ $carte->imprimeur->nom ?? '-' }} {{ $carte->imprimeur->prenoms ?? '' }}</p>
                                <small>{{ \Carbon\Carbon::parse($carte->date_impression)->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($carte->date_distribution)
                    <div class="timeline-item pb-3">
                        <div class="d-flex">
                            <div class="timeline-marker bg-primary rounded-circle" style="width:12px;height:12px;min-width:12px;margin-top:5px;"></div>
                            <div class="ms-3">
                                <strong>Carte distribuée</strong>
                                <p class="text-muted mb-1">Par {{ $carte->distributeur->nom ?? '-' }} {{ $carte->distributeur->prenoms ?? '' }}</p>
                                <small>{{ \Carbon\Carbon::parse($carte->date_distribution)->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Informations</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Élève:</strong> {{ $carte->eleve->nom ?? '' }} {{ $carte->eleve->prenoms ?? '' }}
                </p>
                <p class="mb-2">
                    <strong>Matricule:</strong> {{ $carte->eleve->matricule ?? '-' }}
                </p>
                <p class="mb-2">
                    <strong>Classe:</strong> {{ $carte->eleve->classe->nom ?? '-' }}
                </p>
                <p class="mb-2">
                    <strong>Établissement:</strong> {{ $carte->etablissement->nom ?? '-' }}
                </p>
                <p class="mb-2">
                    <strong>Numéro carte:</strong> <code>{{ $carte->numero_carte }}</code>
                </p>
                <p class="mb-2">
                    <strong>Année scolaire:</strong> {{ $carte->annee_scolaire }}
                </p>
                <p class="mb-2">
                    <strong>Date génération:</strong> {{ $carte->date_generation ? \Carbon\Carbon::parse($carte->date_generation)->format('d/m/Y H:i') : '-' }}
                </p>
                <p class="mb-0">
                    <strong>Statut:</strong>
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
                </p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body d-grid gap-2">
                @if($carte->statut === 'Carte_generee')
                <form action="{{ route('surveillant.impression.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="carte_ids[]" value="{{ $carte->id }}">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-print me-2"></i> Marquer comme imprimée
                    </button>
                </form>
                @endif

                @if($carte->statut === 'Carte_imprimee')
                <form action="{{ route('surveillant.cartes.distribute', $carte) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-hand-holding me-2"></i> Marquer comme distribuée
                    </button>
                </form>
                @endif

                @if($carte->chemin_pdf)
                <a href="{{ route('cartes.download', $carte) }}" class="btn btn-info">
                    <i class="fas fa-download me-2"></i> Télécharger PDF
                </a>
                @endif

                <a href="{{ route('surveillant.cartes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection