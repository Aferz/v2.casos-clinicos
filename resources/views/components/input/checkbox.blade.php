@props([
    'theme' => 'primary',
    'disabled' => false,
    'label',
    'id' => null
])

<div class="relative flex items-start">
    <div class="flex items-center h-5">
        <input
            type="checkbox"
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge([
                'class' => resolve_classes([
                    'h-4 w-4 border-gray-300 rounded' => true,
                    'cursor-not-allowed opacity-50' => $disabled === true,

                    // Theme - Primary
                    'text-indigo-600 focus:ring-primary-500' => $theme === 'primary'
                ])
            ]) }}
        >
    </div>

    <div class="ml-3 text-sm">
        @if (isset($slot) && !$slot->isEmpty())
            {{ $slot }}
        @else
            <x-util.label for="{{ $id }}">
                {{ $label }}
            </x-util.label>
        @endif
    </div>
</div>