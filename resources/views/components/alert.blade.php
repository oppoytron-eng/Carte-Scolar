@props(['type' => 'info', 'dismissible' => true])

@php
    $colors = [
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
    ];

    $icons = [
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle',
    ];
@endphp

<div class="border rounded-lg p-4 {{ $colors[$type] ?? $colors['info'] }}" role="alert">
    <div class="flex items-start">
        <i class="fas {{ $icons[$type] ?? $icons['info'] }} mr-3 mt-0.5"></i>
        <div class="flex-1">
            {{ $slot }}
        </div>
        @if($dismissible)
            <button type="button" class="ml-3 text-lg leading-none" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        @endif
    </div>
</div>
