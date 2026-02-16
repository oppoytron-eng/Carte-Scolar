@props(['title' => null, 'class' => ''])

<div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden {{ $class }}">
    @if($title)
        <div class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
