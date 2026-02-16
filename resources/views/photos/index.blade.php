@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-violet: #4F46E5;
        --secondary-purple: #7C3AED;
        --light-bg: #F3F4F6;
    }

    body {
        background-color: var(--light-bg);
        color: #1F2937;
        font-family: 'Inter', sans-serif;
    }

    .header-section {
        background: white;
        padding: 2rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid #E5E7EB;
    }

    .table-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
    }

    .btn-violet {
        background: linear-gradient(135deg, var(--primary-violet) 0%, var(--secondary-purple) 100%);
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        transition: opacity 0.3s;
    }

    .btn-violet:hover {
        color: white;
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .img-preview {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #F3F4F6;
    }

    .status-badge {
        background-color: #DEF7EC;
        color: #03543F;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .table thead th {
        background-color: #F9FAFB;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #6B7280;
        border-bottom: 1px solid #E5E7EB;
    }
</style>

<div class="header-section">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-0">Gestion des <span style="color: var(--primary-violet);">Photos</span></h2>
            <p class="text-muted small mb-0">Visualisez et gérez les captures d'identité</p>
        </div>
        <a href="{{ url('/operateur/photos/capture') }}" class="btn btn-violet px-4 py-2">
            <i class="fas fa-plus-circle me-2"></i> Nouvelle Capture
        </a>
    </div>
</div>

<div class="container">
    <div class="table-container">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Aperçu</th>
                        <th>Élève</th>
                        <th>Matricule</th>
                        <th>Date de capture</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($photos as $photo)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/'.$photo->path) }}" class="img-preview" alt="Photo">
                        </td>
                        <td>
                            <div class="fw-bold">{{ $photo->eleve->nom ?? 'N/A' }}</div>
                        </td>
                        <td><code class="text-primary">{{ $photo->eleve->matricule ?? '---' }}</code></td>
                        <td class="text-muted">{{ $photo->created_at->format('d M Y, H:i') }}</td>
                        <td><span class="status-badge">Approuvé</span></td>
                        <td class="text-end">
                            <button class="btn btn-light btn-sm text-primary"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-light btn-sm text-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-image fa-3x mb-3 opacity-20"></i>
                            <p>Aucune photo trouvée dans la base de données.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $photos->links() }}
        </div>
    </div>
</div>
@endsection