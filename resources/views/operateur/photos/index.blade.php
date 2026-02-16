@extends('layouts.app')

@section('title', 'Mes Photos')
@section('page-title', 'Galerie de mes photos')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Rechercher...">
        </div>
    </div>
    <div class="col-md-6">
        <select class="form-select">
            <option>Tous les statuts</option>
            <option>Validées</option>
            <option>En attente</option>
            <option>Rejetées</option>
        </select>
    </div>
</div>

<div class="row">
    @for($i = 1; $i <= 12; $i++)
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div style="background: #e9ecef; height: 200px; border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-image" style="font-size: 2rem; color: #999;"></i>
            </div>
            <div class="card-body">
                <p class="card-title mb-2">Élève {{ $i }}</p>
                <p class="text-muted small mb-2">6ème A</p>
                <div class="d-flex justify-content-between">
                    @if($i % 3 == 0)
                        <span class="badge bg-success">Validée</span>
                    @elseif($i % 3 == 1)
                        <span class="badge bg-warning">En attente</span>
                    @else
                        <span class="badge bg-danger">Rejetée</span>
                    @endif
                    <small class="text-muted">20/01/2026</small>
                </div>
            </div>
            <div class="card-footer bg-light border-top">
                <a href="#" class="btn btn-sm btn-outline-primary w-100">
                    <i class="fas fa-eye me-1"></i> Voir
                </a>
            </div>
        </div>
    </div>
    @endfor
</div>

<div class="row">
    <div class="col-md-12">
        <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">Précédent</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">Suivant</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
