@props([
    'src',
    'description'
])

<x-modal.simple>
    <x-slot name="trigger">
        <div class="col-span-1 flex flex-col sm:flex-row">
            <img
                class="w-auto h-56 md:h-40 object-cover rounded-md shadow cursor-pointer"
                src="{{ $src }}"
                x-on:click="toggle"
            />

            <div class="mt-2 sm:mt-0 sm:ml-4 text-gray-700">{{ $description }}</div>
        </div>
    </x-slot>

    <img
        class="max-w-3xl w-full h-auto rounded-md shadow-lg border border-white mx-auto"
        src="{{ $src }}"
    />
</x-modal.modal>