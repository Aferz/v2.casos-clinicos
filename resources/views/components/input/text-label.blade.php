@props([
    'label',
    'id' => null,
    'theme' => 'primary',

    // Remove when Laravel 9.x
    'disabled' => false,
])

<x-util.label for="{{ $id }}">
    {{ $label }}
</x-util.label>

<div class="mt-1">
    <x-input.text
        :theme="$theme"
        :disabled="$disabled"
        {{ $attributes->merge(compact('id')) }}
    />
</div>