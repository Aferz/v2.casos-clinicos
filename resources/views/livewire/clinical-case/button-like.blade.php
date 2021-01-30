<div class="flex items-end mr-4">
    <button
        tabindex="-1"
        class="h-7 w-7 flex items-center justify-center rounded-full transition duration-300 focus:outline-none focus:bg-red-100 hover:bg-red-100 group"
        wire:click="toggle"
    >
        @if (!$liked)
        <x-icon-heart class="h-7 w-7 text-gray-500 group-focus:text-red-500 hover:text-red-500 transition duration-300" />
        @else
        <x-icon-heart-solid class="h-7 w-7 text-red-500 transition duration-300" />
        @endif
    </button>

    <span class="text-lg text-gray-600 font-medium ml-1 leading-none tabular-nums tracking-tighter">{{ $count }}</span>
</div>