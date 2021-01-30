<div>
    <x-util.label>
        {{ __('Bibliography') }}
    </x-util.label>

    @if (count($bibliographies) > 0)
    <div class="mt-4 mb-6 w-full space-y-4">
        @foreach ($bibliographies as $index => $bibliography)
        <div class="flex">
            <div class="w-10">
                <button
                    tabindex="-1"
                    type="button"
                    class="p-1 rounded-full text-gray-500 focus:outline-none hover:bg-red-100 hover:text-red-500 transition duration-150"
                    wire:click="delete({{ $index }})"
                >
                    <x-icon-trash class="h-6 w-6" />
                </button>
            </div>

            <div class="flex-1">
                <p>{{ $bibliography['title'] }}</p>
                <span class="flex items-center text-primary-600">
                    <x-util.link
                        target="_blank"
                        show-external-icon
                        href="{{ $bibliography['url'] }}"
                    >{{ $bibliography['url'] }}</x-util.link>
                </span>
            </div>
        </div>

        <input
            type="hidden"
            name="bibliographies[{{ $index }}]['title']"
            value="{{ $bibliography['title'] }}"
        />

        <input
            type="hidden"
            name="bibliographies[{{ $index }}]['url']"
            value="{{ $bibliography['url'] }}"
        />
        @endforeach
    </div>
    @endif

    <div class="mt-2 w-full">
        <div>
            <x-input.text
                placeholder="{{ __('Title') }}"
                wire:model.debounce.300ms="title"
                wire:keydown.enter.prevent="add"
                theme="{{ $errors->has('title') ? 'error' : 'primary' }}"
            />

            @error('title')
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="mt-2">
            <x-input.text
                placeholder="{{ __('Url') }}"
                wire:model.debounce.300ms="url"
                wire:keydown.enter.prevent="add"
                theme="{{ $errors->has('url') ? 'error' : 'primary' }}"
            />

            @error('url')
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>
    </div>

    <div class="w-full flex justify-end mt-4">
        <x-button.link wire:click="add">
            {{ __('Add new reference') }}
        </x-button.link>
    </div>
</div>