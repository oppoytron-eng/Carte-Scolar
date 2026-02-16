@extends('layouts.app')

@section('title', 'Cartes')
@section('page-title', 'Gestion des cartes')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Rechercher une carte...">
        </div>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" onclick="generateCartes()">
            <i class="fas fa-magic me-2"></i> Générer cartes
        </button>
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
                        @foreach(range(1, 5) as $i)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>Élève {{ $i }}</td>
                            <td><span class="badge bg-primary">6ème A</span></td>
                            <td>21/01/2026 10:30</td>
                            <td><code>CART00{{ $i }}</code></td>
                            <td>
                                @if($i % 2 == 0)
                                    <span class="badge bg-success">Imprimée</span>
                                @else
                                    <span class="badge bg-info">Générée</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ url('/surveillant/cartes/'.$i) }}" class="btn btn-outline-violet">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-outline-success" onclick="printCarte({{ $i }})">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function generateCartes() {
        Swal.fire({
            title: 'Générer les cartes',
            html: `
                <div class="mb-3">
                    <label class="form-label">Classe</label>
                    <select class="form-select" id="classeSelect">
                        <option value="">Toutes les classes</option>
                        <option value="6eme-a">6ème A</option>
                        <option value="5eme-b">5ème B</option>
                    </select>
                </div>
            `,
            confirmButtonText: 'Générer',
            showCancelButton: true,
            preConfirm: () => {
                const classe = document.getElementById('classeSelect').value;
                return { classe };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Génération en cours...', '', 'info');
                setTimeout(() => {
                    Swal.fire('Cartes générées', 'Les cartes ont été générées avec succès.', 'success');
                }, 2000);
            }
        });
    }

    function printCarte(id) {
        window.open(`/cartes/${id}/print`, '_blank');
    }
</script>
@endpush
@endsection
