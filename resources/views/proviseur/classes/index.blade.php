@extends('layouts.app')

@section('title', 'Classes')
@section('page-title', 'Gestion des Classes')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" id="searchClasses" placeholder="Rechercher une classe...">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('proviseur.classes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Créer classe
        </a>
    </div>
</div>

<div class="row">
    @forelse($classes ?? [] as $classe)
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-header border-bottom bg-light">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-chalkboard me-2"></i>{{ $classe->nom ?? 'N/A' }}
                        </h5>
                        <small class="text-muted">{{ $classe->niveau ?? 'N/A' }}</small>
                    </div>
                    <div>
                        <span class="badge bg-info">{{ $classe->eleves_count ?? 0 }} élèves</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center mb-3">
                    <div class="col-6">
                        <h6 class="text-muted">Professeur</h6>
                        <p>{{ $classe->professeur ?? '-' }}</p>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted">Salle</h6>
                        <p>{{ $classe->salle ?? '-' }}</p>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-muted">Photos</h6>
                        <p>
                            <span class="badge bg-success">{{ $classe->photos_count ?? 0 }}/{{ $classe->eleves_count ?? 0 }}</span>
                        </p>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted">Cartes</h6>
                        <p>
                            <span class="badge bg-info">{{ $classe->cartes_count ?? 0 }}/{{ $classe->eleves_count ?? 0 }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top bg-light">
                <div class="btn-group w-100">
                    <a href="{{ route('proviseur.classes.show', $classe->id ?? 0) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i> Voir
                    </a>
                    <a href="{{ route('proviseur.classes.edit', $classe->id ?? 0) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit me-1"></i> Éditer
                    </a>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteClasse({{ $classe->id ?? 0 }})">
                        <i class="fas fa-trash me-1"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-inbox" style="font-size: 2.5rem; opacity: 0.5;"></i>
            <p class="mt-3 mb-0">Aucune classe trouvée. <a href="{{ route('proviseur.classes.create') }}">Créer une classe</a></p>
        </div>
    </div>
    @endforelse
</div>

@push('scripts')
<script>
    document.getElementById('searchClasses').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.parentElement.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    function deleteClasse(id) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Vous ne pourrez pas récupérer cette classe!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/proviseur/classes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        Swal.fire('Supprimée!', 'Classe supprimée avec succès.', 'success');
                        location.reload();
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection
