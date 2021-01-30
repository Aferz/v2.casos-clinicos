<div class="flex">
    <x-button.button
        theme="warning"
        :outlined="!$highlighted"
        wire:click="toggle"
    >
        @if ($highlighted)
            <x-icon-star-solid class="h-5 w-5" />
        @else
            <x-icon-star class="h-5 w-5" />
        @endif

        <span class="ml-2">{{ $highlighted ? __('Highlighted') : __('Not highlighted') }}</span>
    </x-button.button>
</div>
