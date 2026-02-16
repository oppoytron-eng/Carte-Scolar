@props(['id' => 'modal', 'title' => null])

<div id="{{ $id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" onclick="if(event.target.id === '{{ $id }}') this.classList.add('hidden')">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" onclick="event.stopPropagation()">
        @if($title)
            <div class="bg-gradient-to-r from-primary to-secondary text-white px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">{{ $title }}</h3>
                    <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="text-white hover:text-gray-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
