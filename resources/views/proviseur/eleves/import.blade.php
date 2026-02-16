@extends('layouts.app')

@section('title', 'Importer Élèves')
@section('page-title', 'Importer des élèves')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-upload me-2"></i> Importer des élèves</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Format accepté:</strong> CSV ou Excel (XLSX). La première ligne doit contenir les en-têtes.
                </div>

                <form action="{{ route('proviseur.eleves.import.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="mb-4">
                        <label for="classe_id" class="form-label">Classe (optionnel)</label>
                        <select class="form-select" id="classe_id" name="classe_id">
                            <option value="">-- Sélectionnez une classe --</option>
                            @foreach($classes ?? [] as $classe)
                                <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Si vous sélectionnez une classe, tous les élèves seront assignés à cette classe</small>
                    </div>

                    <div class="mb-4">
                        <label for="fichier" class="form-label">Fichier</label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('fichier') is-invalid @enderror"
                                   id="fichier" name="fichier" accept=".csv,.xlsx,.xls" required>
                            <label class="input-group-text" for="fichier">
                                <i class="fas fa-paperclip"></i>
                            </label>
                        </div>
                        @error('fichier')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="doublons" name="skip_doublons" value="1" checked>
                            <label class="form-check-label" for="doublons">
                                Ignorer les doublons (basé sur le numéro d'inscription)
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('proviseur.eleves.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i> Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-file-csv me-2"></i> Format du fichier</h5>
            </div>
            <div class="card-body">
                <p><strong>Les colonnes requises:</strong></p>
                <ul>
                    <li><code>nom</code> - Nom de l'élève</li>
                    <li><code>prenom</code> - Prénom de l'élève</li>
                    <li><code>numero_inscription</code> - Numéro d'inscription unique</li>
                    <li><code>classe</code> - Nom de la classe (ex: 6ème A)</li>
                </ul>

                <p class="mt-3"><strong>Colonnes optionnelles:</strong></p>
                <ul>
                    <li><code>date_naissance</code> - Format: JJ/MM/YYYY</li>
                    <li><code>lieu_naissance</code> - Lieu de naissance</li>
                    <li><code>adresse</code> - Adresse</li>
                    <li><code>telephone_parent</code> - Téléphone parent</li>
                </ul>

                <p class="mt-3"><strong>Exemple CSV:</strong></p>
                <pre><code>nom,prenom,numero_inscription,classe,date_naissance
Dupont,Alice,ALI001,6ème A,15/08/2012
Martin,Bob,BOB002,6ème A,22/11/2012
Bernard,Claire,CLA003,5ème B,05/03/2013</code></pre>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-download me-2"></i> Modèles</h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('proviseur.eleves.import.template.csv') }}" class="btn btn-outline-primary" download>
                    <i class="fas fa-file-csv me-2"></i> Télécharger CSV
                </a>
                <a href="{{ route('proviseur.eleves.import.template.excel') }}" class="btn btn-outline-success" download>
                    <i class="fas fa-file-excel me-2"></i> Télécharger Excel
                </a>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i> Historique</h5>
            </div>
            <div class="card-body">
                @forelse($imports ?? [] as $import)
                <div class="mb-3 pb-3 border-bottom">
                    <p class="mb-1">
                        <strong>{{ $import->created_at?->format('d/m/Y H:i') ?? '-' }}</strong>
                    </p>
                    <p class="mb-1 text-muted">
                        <i class="fas fa-check text-success"></i> {{ $import->success_count }} élèves importés
                    </p>
                    @if($import->error_count > 0)
                    <p class="mb-0 text-danger">
                        <i class="fas fa-exclamation"></i> {{ $import->error_count }} erreurs
                    </p>
                    @endif
                </div>
                @empty
                <p class="text-muted text-center">Aucun import</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
