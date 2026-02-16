@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0"><i class="fas fa-question-circle me-2"></i>Aide et Documentation</h1>
        </div>
        <div class="card-body">
            <h5>Bienvenue dans le centre d'assistance</h5>
            <p>Voici les modules disponibles pour vous aider :</p>
            <ul>
                <li><strong>Gestion des élèves :</strong> Importation et modification des données.</li>
                <li><strong>Opérations Photo :</strong> Capture et validation.</li>
                <li><strong>Cartes Scolaires :</strong> Génération et impression.</li>
            </ul>
            <hr>
            <p>Si vous rencontrez un problème technique, veuillez contacter l'administrateur.</p>
        </div>
    </div>
</div>
@endsection