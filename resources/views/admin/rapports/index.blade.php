@extends('layouts.app')

@section('title', 'Rapports')
@section('page-title', 'Centre de Rapports')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Bienvenue au centre de rapports</strong> - Générez et consultez les rapports détaillés du système.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-file-pdf" style="font-size: 2.5rem; color: #EF4444;"></i>
                <h6 class="mt-3 mb-2">Rapport Global</h6>
                <p class="text-muted mb-3">Vue d'ensemble du système</p>
                <a href="{{ route('admin.rapports.global') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-arrow-right me-1"></i> Accéder
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-users" style="font-size: 2.5rem; color: #3B82F6;"></i>
                <h6 class="mt-3 mb-2">Rapport Utilisateurs</h6>
                <p class="text-muted mb-3">Gestion des utilisateurs</p>
                <button class="btn btn-sm btn-primary" onclick="generateReport('users')">
                    <i class="fas fa-arrow-right me-1"></i> Accéder
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-school" style="font-size: 2.5rem; color: #10B981;"></i>
                <h6 class="mt-3 mb-2">Rapport Établissements</h6>
                <p class="text-muted mb-3">Données d'établissements</p>
                <button class="btn btn-sm btn-primary" onclick="generateReport('etablissements')">
                    <i class="fas fa-arrow-right me-1"></i> Accéder
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-id-card" style="font-size: 2.5rem; color: #F59E0B;"></i>
                <h6 class="mt-3 mb-2">Rapport Cartes</h6>
                <p class="text-muted mb-3">Gestion des cartes</p>
                <button class="btn btn-sm btn-primary" onclick="generateReport('cartes')">
                    <i class="fas fa-arrow-right me-1"></i> Accéder
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i> Rapports générés récemment
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type de rapport</th>
                            <th>Généré par</th>
                            <th>Format</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>21/01/2026 10:30</td>
                            <td><i class="fas fa-file-pdf text-danger me-2"></i> Rapport Global</td>
                            <td>Admin</td>
                            <td><span class="badge bg-danger">PDF</span></td>
                            <td><span class="badge bg-success">Complété</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Télécharger
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>20/01/2026 15:45</td>
                            <td><i class="fas fa-file-excel text-success me-2"></i> Utilisateurs</td>
                            <td>Admin</td>
                            <td><span class="badge bg-success">Excel</span></td>
                            <td><span class="badge bg-success">Complété</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Télécharger
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>19/01/2026 09:20</td>
                            <td><i class="fas fa-file-csv text-info me-2"></i> Établissements</td>
                            <td>Admin</td>
                            <td><span class="badge bg-info">CSV</span></td>
                            <td><span class="badge bg-success">Complété</span></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-1"></i> Télécharger
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-cog me-2"></i> Paramètres de rapport
                </h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label for="dateDebut" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="dateDebut" name="date_debut">
                    </div>
                    <div class="mb-3">
                        <label for="dateFin" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="dateFin" name="date_fin">
                    </div>
                    <div class="mb-3">
                        <label for="format" class="form-label">Format de sortie</label>
                        <select class="form-select" id="format" name="format">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-download me-2"></i> Générer rapport
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i> Audit et sécurité
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">Consultez les logs d'audit pour un suivi complet des actions.</p>
                <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-primary w-100">
                    <i class="fas fa-history me-2"></i> Voir l'historique d'audit
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function generateReport(type) {
        Swal.fire({
            title: 'Génération en cours...',
            html: '<div class="spinner-border" role="status"><span class="visually-hidden">Chargement...</span></div>',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            Swal.fire({
                title: 'Succès!',
                text: 'Le rapport ' + type + ' a été généré avec succès.',
                icon: 'success'
            });
        }, 2000);
    }
</script>
@endpush
@endsection
