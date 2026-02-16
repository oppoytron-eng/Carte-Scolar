@extends('layouts.app')

@section('title', 'Gestion Impression')
@section('page-title', 'Gestion de l\'impression')


@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Prêtes à imprimer</h6>
                <h3>{{ $cartesEnAttente ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Imprimées ce mois</h6>
                <h3>{{ $cartesImprimeesMois ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-print me-2"></i> Travaux d'impression</h5>
            <a  class="btn btn-sm btn-primary">
                <i class="fas fa-plus me-1"></i> Nouvelle impression
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Classe</th>
                    <th>Cartes</th>
                    <th>Date création</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(range(1, 5) as $i)
                <tr>
                    <td>IMP00{{ $i }}</td>
                    <td>{{ ['6ème A', '5ème B', '4ème C', '3ème A', '2nde B'][$i-1] }}</td>
                    <td>{{ 40 + ($i * 5) }}</td>
                    <td>{{ now()->subDays($i)->format('d/m/Y') }}</td>
                    <td>
                        @if($i == 1)
                            <span class="badge bg-warning">En cours</span>
                        @elseif($i == 2)
                            <span class="badge bg-success">Complétée</span>
                        @else
                            <span class="badge bg-secondary">Archivée</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="viewImpression({{ $i }})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    function viewImpression(id) {
        Swal.fire({
            title: 'Travail d\'impression #IMP00' + id,
            html: `
                <div class="text-start">
                    <p><strong>Classe:</strong> 6ème A</p>
                    <p><strong>Cartes:</strong> 45/45</p>
                    <p><strong>Date création:</strong> 20/01/2026</p>
                    <p><strong>Statut:</strong> <span class="badge bg-warning">En cours</span></p>
                    <p><strong>Progression:</strong></p>
                    <div class="progress">
                        <div class="progress-bar" style="width: 65%">65%</div>
                    </div>
                </div>
            `,
            confirmButtonText: 'Fermer'
        });
    }
</script>
@endpush
@endsection
