<!-- resources/views/profile/index.blade.php -->

@extends('layouts.app') <!-- Hérite d'un layout principal -->

@section('content')
<div class="container">
    <h1>Profil de {{ $user->name }}</h1>

    <p><strong>Nom :</strong> {{ $user->nom }}</p>
    <p><strong>Prénom :</strong> {{ $user->prenoms }}</p>
    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Téléphone :</strong> {{ $user->phone ?? 'Non renseigné' }}</p>

    <hr>

    <h2>Modifier le profil</h2>
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT') <!-- si la route attend PUT -->

        <div>
            <label>Nom :</label>
            <input type="text" name="nom" value="{{ old('nom', $user->nom) }}">
        </div>

        <div>
            <label>Prénoms :</label>
            <input type="text" name="prenoms" value="{{ old('prenoms', $user->prenoms) }}">
        </div>

        <div>
            <label>Email :</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}">
        </div>

        <div>
            <label>Téléphone :</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
</div>
@endsection
