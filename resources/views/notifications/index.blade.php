@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Mes Notifications</h3>
        <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-primary">Tout marquer comme lu</button>
        </form>
    </div>

    <div class="card">
        <ul class="list-group list-group-flush">
            @forelse($notifications as $notification)
                <li class="list-group-item {{ $notification->est_lu ? '' : 'bg-light' }}">
                    <strong>{{ $notification->titre }}</strong><br>
                    <small>{{ $notification->message }}</small><br>
                    <span class="text-muted small">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                </li>
            @empty
                <li class="list-group-item text-center">Aucune notification</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection