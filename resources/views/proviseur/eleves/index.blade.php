@extends('layouts.app')

@section('title', 'Élèves')
@section('page-title', 'Gestion des Élèves')

@section('content')
<div class="row mb-4">
    <div class="col-md-4">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" id="searchEleves" placeholder="Rechercher un élève...">
        </div>
    </div>
    <div class="col-md-4">
        <select class="form-select" id="filterClasse">
            <option value="">Toutes les classes</option>
            @foreach($classes ?? [] as $classe)
                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 text-end">
        <div class="btn-group">
            <a href="{{ route('proviseur.eleves.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Ajouter
            </a>
            <a href="{{ route('proviseur.eleves.import') }}" class="btn btn-info">
                <i class="fas fa-upload me-2"></i> Importer
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-bottom">
        <h5 class="mb-0">
            <i class="fas fa-user-graduate me-2"></i> Liste des élèves ({{ $total ?? 0 }})
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Classe</th>
                    <th>Photo</th>
                    <th>Carte</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($eleves ?? [] as $eleve)
                <tr>
                    <td>{{ $eleve->id ?? '#' }}</td>
                    <td>{{ $eleve->nom ?? 'N/A' }}</td>
                    <td>{{ $eleve->prenom ?? 'N/A' }}</td>
                    <td><span class="badge bg-primary">{{ $eleve->classe->nom ?? 'N/A' }}</span></td>
                    <td>
                        @if($eleve->photo_valid)
                            <span class="badge bg-success"><i class="fas fa-check"></i> Validée</span>
                        @elseif($eleve->photo_uploaded)
                            <span class="badge bg-warning"><i class="fas fa-hourglass-end"></i> En attente</span>
                        @else
                            <span class="badge bg-secondary"><i class="fas fa-times"></i> Manquante</span>
                        @endif
                    </td>
                    <td>
                        @if($eleve->carte_generee)
                            <span class="badge bg-info"><i class="fas fa-id-card"></i> Générée</span>
                        @else
                            <span class="badge bg-light"><i class="fas fa-times"></i> Non</span>
                        @endif
                    </td>
                    <td>
                        @if($eleve->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-danger">Inactif</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('proviseur.eleves.show', $eleve->id ?? 0) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('proviseur.eleves.edit', $eleve->id ?? 0) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-outline-danger" onclick="deleteEleve({{ $eleve->id ?? 0 }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-3 mb-0">Aucun élève trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($eleves) && $eleves->hasPages())
    <div class="card-footer">
        {{ $eleves->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.getElementById('searchEleves').addEventListener('keyup', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    });

    function deleteEleve(id) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Vous ne pourrez pas récupérer cet élève!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/proviseur/eleves/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                }).then(response => {
                    if (response.ok) {
                        Swal.fire('Supprimé!', 'Élève supprimé avec succès.', 'success');
                        location.reload();
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection
