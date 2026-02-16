@extends('layouts.app')

@section('title', 'Établissements')
@section('page-title', 'Gestion des Établissements')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" id="searchEtablissements" placeholder="Rechercher un établissement...">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.etablissements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Ajouter établissement
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header border-bottom">
        <h5 class="mb-0">
            <i class="fas fa-school me-2"></i> Liste des établissements ({{ $total ?? 0 }})
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 25%">Nom</th>
                    <th style="width: 20%">Type</th>
                    <th style="width: 20%">Adresse</th>
                    <th style="width: 15%">Directeur</th>
                    <th style="width: 10%">Utilisateurs</th>
                    <th style="width: 5%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($etablissements ?? [] as $etablissement)
                <tr>
                    <td>{{ $etablissement->id ?? '#' }}</td>
                    <td>
                        <i class="fas fa-building me-2"></i>
                        <strong>{{ $etablissement->nom ?? 'N/A' }}</strong>
                    </td>
                    <td>{{ $etablissement->type ?? 'N/A' }}</td>
                    <td>
                        <small>{{ $etablissement->adresse ?? 'N/A' }}</small>
                    </td>
                    <td>{{ $etablissement->directeur ?? '-' }}</td>
                    <td>
                        <span class="badge bg-info">{{ $etablissement->users_count ?? 0 }}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.etablissements.show', $etablissement->id ?? 0) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.etablissements.edit', $etablissement->id ?? 0) }}" class="btn btn-sm btn-outline-primary" title="Éditer">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteEtablissement({{ $etablissement->id ?? 0 }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-3">Aucun établissement trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($etablissements) && $etablissements->hasPages())
    <div class="card-footer">
        <nav aria-label="Pagination">
            <ul class="pagination mb-0">
                {{ $etablissements->links() }}
            </ul>
        </nav>
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.getElementById('searchEtablissements').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    function deleteEtablissement(id) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Vous ne pourrez pas récupérer cet établissement!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/etablissements/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        Swal.fire('Supprimé!', 'Établissement supprimé avec succès.', 'success');
                        location.reload();
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection
