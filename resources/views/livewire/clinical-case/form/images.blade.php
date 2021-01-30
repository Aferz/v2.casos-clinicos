@php
$errorShown = false

@endphp


<div class="col-span-1 sm:col-span-3 md:col-span-6">
    <x-util.label>
        {{ __('Images') }}
    </x-util.label>

    <div class="mt-1 space-y-4">
    @foreach ($images as $index => $image)
        <div class="flex">
            <div class="group hover:bg-red-100 rounded-full h-7 w-7 flex items-center justify-center mr-2">
                <button
                    tabindex="-1"
                    class="text-gray-500 group-hover:text-red-500 focus:outline-none"
                    type="button"
                    wire:click="removeImage({{ $index }})"
                >
                    <x-icon-trash class="h-6 w-6" />
                </button>
            </div>

            <div
                class="relative h-24 w-24 sm:h-32 sm:w-32 bg-cover bg-center rounded-md shadow border border-white"
                style="background-image: url({{ asset($image['src']) }});"
            ></div>

            <div class="ml-4 flex-1">
                <x-input.textarea
                    :placeholder="__('Description...')"
                    class="resize-none h-24 sm:h-32"
                    :wire:model.defer="'images.'.$index.'.description'"
                />

                @if($message = $errors->first("images.$index.description"))
                <p class="text-error-500 mt-2 text-sm">
                    {{ $message }}
                </p>
                @enderror
            </div>
        </div>
        @endforeach

        @if (count($images) < $imagesMaximum)
            @if (count($images) === 0)
            <label
                class="relative h-24 sm:h-32 w-full flex flex-col items-center justify-center border rounded-md bg-gray-50 border-dashed cursor-pointer group"
                onclick="document.querySelector('#{{ $randomInputId }}').click()"
            >
                <x-icon-photograph class="h-8 w-8 text-gray-600 group-hover:text-gray-500" />
                <span class="text-gray-600 group-hover:text-gray-500 font-medium mt-1">{{ __('Add image') }}</span>
            </label>
            @else
            <div class="w-full flex justify-end">
                <label class="-mt-2">
                    <x-button.link onclick="document.querySelector('#{{ $randomInputId }}').click()">
                        {{ __('Add image') }}
                    </x-button.link>
                </label>
            </div>
            @endif

            <input
                id="{{ $randomInputId }}"
                name="{{ $randomInputId }}"
                style="width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; position: absolute; z-index: -1;"
                class="absolute"
                type="file"
                wire:model="imageModel"
                multiple
                accept="{{ $imagesAccept }}"
            />
        @endif
    </div>

    @if($message = $errors->first('images.*.file'))
    <p class="text-error-500 mt-2 text-sm">
        {{ $message }}
    </p>
    @enderror
</div>

