@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" id="searchUsers" placeholder="Rechercher un utilisateur...">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Ajouter utilisateur
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header border-bottom">
        <h5 class="mb-0">
            <i class="fas fa-users me-2"></i> Liste des utilisateurs ({{ $total ?? 0 }})
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 25%">Nom complet</th>
                    <th style="width: 25%">Email</th>
                    <th style="width: 15%">Rôle</th>
                    <th style="width: 15%">Établissement</th>
                    <th style="width: 10%">Statut</th>
                    <th style="width: 5%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $user)
                <tr>
                    <td>{{ $user->id ?? '#' }}</td>
                    <td>
                        <i class="fas fa-user-circle me-2"></i>
                        {{ $user->full_name ?? 'N/A' }}
                    </td>
                    <td>
                        <a href="mailto:{{ $user->email ?? '' }}">
                            {{ $user->email ?? 'N/A' }}
                        </a>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $user->role ?? 'N/A' }}</span>
                    </td>
                    <td>{{ $user->etablissement->nom ?? '-' }}</td>
                    <td>
                        @if($user->is_active ?? false)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-danger">Inactif</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.users.show', $user->id ?? 0) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id ?? 0) }}" class="btn btn-sm btn-outline-primary" title="Éditer">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $user->id ?? 0 }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-3">Aucun utilisateur trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($users) && $users->hasPages())
    <div class="card-footer">
        <nav aria-label="Pagination">
            <ul class="pagination mb-0">
                {{ $users->links() }}
            </ul>
        </nav>
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.getElementById('searchUsers').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    function deleteUser(id) {
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Vous ne pourrez pas récupérer cet utilisateur!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implement delete via AJAX
                fetch(`/admin/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        Swal.fire('Supprimé!', 'Utilisateur supprimé avec succès.', 'success');
                        location.reload();
                    }
                });
            }
        });
    }
</script>
@endpush
@endsection
