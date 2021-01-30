@props([
    'criterion',
    'render',
    'commentModel',
    'error',
])

<div class="flex flex-col sm:flex-row">
    <div class="w-full sm:w-48 font-medium text-lg text-gray-600">
        {{ ucwords(str_replace('-', ' ', __($criterion))) }}
    </div>

    <div class="flex-1 mt-2 sm:mt-0">
        <div>
            {!! $render !!}
        </div>

        @feature('evaluation_comments')
            <x-input.textarea
                class="resize-none mt-3"
                rows="3"
                placeholder="{{ __('Leave a comment ...') }}"
                wire:model.defer="{{ $commentModel }}"
            />
        @endfeature

        @if($error)
            <p class="text-error-500 mt-2 text-sm">
                {{ $error }}
            </p>
        @enderror
    </div>
</div>