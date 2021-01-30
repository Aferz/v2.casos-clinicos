<x-layout.web>
    @if (isset($subToolbar))
        <x-slot name="subToolbar">
            {{ $subToolbar }}
        </x-slot>
    @endif

    <div class="relative max-w-7xl mx-auto h-full px-4">
        {{ $slot }}
    </div>
</x-layout.web>