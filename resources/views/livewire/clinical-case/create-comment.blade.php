<div>
    <x-input.textarea
        style="resize: none;"
        class="w-full rounded-md border border-gray-300 h-32"
        wire:model.lazy="comment"
    ></x-input.textarea>

    <div class="flex mt-2">
        @if($message = $errors->first('comment'))
            <span class="text-red-500 flex-1">{{ $message }}</span>
        @elseif ($message = $errors->first('limited'))
            <span class="text-red-500 flex-1">{{ $message }}</span>
        @else
            <div class="flex-1"></div>
        @endif

        <x-button.button wire:click="createComment">
            {{ __('Comment') }}
        </x-button.button>
    </div>
</div>