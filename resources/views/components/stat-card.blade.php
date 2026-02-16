@props(['icon', 'value', 'label', 'trend' => null, 'color' => 'primary'])

@php
    $colorClasses = [
        'primary' => 'border-l-primary bg-blue-50',
        'success' => 'border-l-success bg-green-50',
        'danger' => 'border-l-danger bg-red-50',
        'warning' => 'border-l-warning bg-yellow-50',
    ];
@endphp

<div class="bg-white rounded-lg shadow-md border-l-4 {{ $colorClasses[$color] ?? $colorClasses['primary'] }} p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600 text-sm font-medium">{{ $label }}</p>
            <h4 class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</h4>
            @if($trend)
                <p class="text-xs {{ str_contains($trend, '+') ? 'text-green-600' : 'text-red-600' }} mt-2">
                    {{ $trend }}
                </p>
            @endif
        </div>
        <div class="text-3xl text-gray-400">
            <i class="fas {{ $icon }}"></i>
        </div>
    </div>
</div>
