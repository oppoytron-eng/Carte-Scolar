@extends('layouts.app')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')
<style>
    :root {
        --primary-violet: #4F46E5;
    }
    .card { border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .card-header { background: white; border-bottom: 1px solid #f0f0f0; padding: 1.5rem; }
    .form-label { font-weight: 600; color: #374151; }
    .btn-primary { background: var(--primary-violet); border: none; padding: 0.6rem 1.5rem; border-radius: 10px; }
    .btn-primary:hover { background: #4338CA; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0 fw-bold" style="color: var(--primary-violet);">
                        <i class="fas fa-user-plus me-2"></i> Nouvel Utilisateur
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom complet</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                       name="nom" value="{{ old('nom') }}" required>
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prénoms</label>
                                <input type="text" name="prenoms" class="form-control @error('prenoms') is-invalid @enderror" value="{{ old('prenoms') }}" required>
                                @error('prenoms') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Rôle</label>
                                <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Administrateur" {{ old('role') == 'Administrateur' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="Proviseur" {{ old('role') == 'Proviseur' ? 'selected' : '' }}>Proviseur</option>
                                    <option value="Surveillant General" {{ old('role') == 'Surveillant General' ? 'selected' : '' }}>Surveillant</option>
                                    <option value="Operateur Photo" {{ old('role') == 'Operateur Photo' ? 'selected' : '' }}>Opérateur</option>
                                </select>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Établissement</label>
                                <select class="form-select @error('etablissement_id') is-invalid @enderror" name="etablissement_id">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($etablissements ?? [] as $et)
                                        <option value="{{ $et->id }}" {{ old('etablissement_id') == $et->id ? 'selected' : '' }}>{{ $et->nom }}</option>
                                    @endforeach
                                </select>
                                @error('etablissement_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="active" checked>
                                <label class="form-check-label" for="active">Compte actif</label>
                            </div>
                            
                            <div>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light px-4 me-2">Annuler</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-check-circle me-1"></i> Créer l'utilisateur
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection