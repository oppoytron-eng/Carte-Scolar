@extends('layouts.app')

@section('title', 'Audit')
@section('page-title', 'Logs d\'Audit')

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="input-group">
            <span class="input-group-text">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="form-control" id="searchAudit" placeholder="Rechercher...">
        </div>
    </div>
    <div class="col-md-3">
        <select class="form-select" id="filterAction">
            <option value="">Toutes les actions</option>
            <option value="create">Créer</option>
            <option value="update">Modifier</option>
            <option value="delete">Supprimer</option>
            <option value="login">Connexion</option>
        </select>
    </div>
    <div class="col-md-3">
        <input type="date" class="form-control" id="filterDate">
    </div>
    <div class="col-md-3">
        <button class="btn btn-outline-primary w-100" onclick="exportAudit()">
            <i class="fas fa-download me-2"></i> Exporter
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header border-bottom">
        <h5 class="mb-0">
            <i class="fas fa-history me-2"></i> Historique d'audit ({{ $total ?? 0 }} entrées)
        </h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 table-sm">
            <thead>
                <tr>
                    <th style="width: 12%">Date/Heure</th>
                    <th style="width: 15%">Utilisateur</th>
                    <th style="width: 12%">Action</th>
                    <th style="width: 15%">Entité</th>
                    <th style="width: 20%">Description</th>
                    <th style="width: 10%">Adresse IP</th>
                    <th style="width: 8%">Statut</th>
                    <th style="width: 8%">Détails</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audits ?? [] as $audit)
                <tr>
                    <td>{{ $audit->created_at?->format('d/m/Y H:i:s') ?? 'N/A' }}</td>
                    <td>
                        <i class="fas fa-user me-2"></i>
                        {{ $audit->user->full_name ?? 'Système' }}
                    </td>
                    <td>
                        @switch($audit->action ?? '')
                            @case('create')
                                <span class="badge bg-success"><i class="fas fa-plus"></i> Créer</span>
                                @break
                            @case('update')
                                <span class="badge bg-info"><i class="fas fa-edit"></i> Modifier</span>
                                @break
                            @case('delete')
                                <span class="badge bg-danger"><i class="fas fa-trash"></i> Supprimer</span>
                                @break
                            @case('login')
                                <span class="badge bg-warning"><i class="fas fa-sign-in-alt"></i> Connexion</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $audit->action ?? 'N/A' }}</span>
                        @endswitch
                    </td>
                    <td>{{ $audit->entity ?? 'N/A' }}</td>
                    <td>
                        <small>{{ substr($audit->description ?? 'N/A', 0, 30) }}...</small>
                    </td>
                    <td>
                        <small class="font-monospace">{{ $audit->ip_address ?? 'N/A' }}</small>
                    </td>
                    <td>
                        @if($audit->status === 'success' || $audit->status === 'ok')
                            <span class="badge bg-success">✓ Réussi</span>
                        @elseif($audit->status === 'failed' || $audit->status === 'error')
                            <span class="badge bg-danger">✗ Erreur</span>
                        @else
                            <span class="badge bg-warning">Neutre</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="showAuditDetails({{ $audit->id ?? 0 }})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-3">Aucune entrée d'audit trouvée</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($audits) && $audits->hasPages())
    <div class="card-footer">
        <nav aria-label="Pagination">
            <ul class="pagination mb-0 justify-content-center">
                {{ $audits->links() }}
            </ul>
        </nav>
    </div>
    @endif
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i> Actions par type
                </h5>
            </div>
            <div class="card-body">
                <canvas id="chartActions"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i> Distribution des statuts
                </h5>
            </div>
            <div class="card-body">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('searchAudit').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    document.getElementById('filterAction').addEventListener('change', function() {
        // Implémenter le filtrage par action
        console.log('Filtre action: ' + this.value);
    });

    function showAuditDetails(id) {
        Swal.fire({
            title: 'Détails d\'audit',
            html: `
                <div class="text-start">
                    <p><strong>ID:</strong> ${id}</p>
                    <p><strong>Utilisateur:</strong> Jean Dupont</p>
                    <p><strong>Action:</strong> Modification</p>
                    <p><strong>Entité:</strong> Utilisateur</p>
                    <p><strong>Date:</strong> 21/01/2026 10:30:45</p>
                    <p><strong>IP:</strong> 192.168.1.100</p>
                    <hr>
                    <p><strong>Changements:</strong></p>
                    <pre>- Ancien rôle: Surveillant
+ Nouveau rôle: Proviseur</pre>
                </div>
            `,
            icon: 'info'
        });
    }

    function exportAudit() {
        Swal.fire({
            title: 'Sélectionnez le format',
            html: `
                <div class="btn-group w-100" role="group">
                    <button type="button" class="btn btn-outline-primary" onclick="doExport('csv')">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="doExport('excel')">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="doExport('pdf')">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: true
        });
    }

    function doExport(format) {
        Swal.fire('Succès', `Audit exporté en ${format.toUpperCase()}`, 'success');
    }

    // Charts
    const ctxActions = document.getElementById('chartActions').getContext('2d');
    new Chart(ctxActions, {
        type: 'bar',
        data: {
            labels: ['Créer', 'Modifier', 'Supprimer', 'Connexion'],
            datasets: [{
                label: 'Nombre d\'actions',
                data: [120, 250, 45, 380],
                backgroundColor: ['#10B981', '#3B82F6', '#EF4444', '#F59E0B']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Réussi', 'Erreur'],
            datasets: [{
                data: [950, 45],
                backgroundColor: ['#10B981', '#EF4444']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
@endsection
