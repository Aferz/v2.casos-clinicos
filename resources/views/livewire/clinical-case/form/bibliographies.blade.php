<div class="col-span-1 sm:col-span-3 md:col-span-6">
    <x-util.label>
        {{ __('Bibliographies') }}
    </x-util.label>

    @foreach ($bibliographies as $index => $bibliography)
    <div class="mt-1 flex">
        <div class="text-gray-500">
            @if (
                count($bibliographies) > 1
                && count($bibliographies) > $bibliographiesMinimum
            )
            <div class="group hover:bg-red-100 rounded-full h-7 w-7 flex items-center justify-center mr-2">
                <button
                    tabindex="-1"
                    class="text-gray-500 group-hover:text-red-500 focus:outline-none"
                    type="button"
                    wire:click="removeBibliography({{ $index }})"
                >
                    <x-icon-trash class="h-6 w-6" />
                </button>
            </div>
            @endif
        </div>

        <div class="w-full">
            <x-input.textarea
                class="h-24 resize-none w-full"
                id="bibliography-{{ $index }}"
                :wire:model.defer="'bibliographies.'.$index"
                :theme="$errors->first('bibliographies.'.$index) ? 'error' : 'primary'"
            />

            @if($message = $errors->first('bibliographies.'.$index))
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>
    </div>
    @endforeach

    @if (count($bibliographies) < $bibliographiesMaximum)
    <div class="mt-2 flex justify-end">
        <x-button.link wire:click="addBibliography">
            {{ __('Add new bibliography') }}
        </x-button.link>
    </div>
    @endif
</div>