@props([
    'label',
    'id' => '',

    // Remove when Laravel 9.x
    'theme' => 'primary',
    'options' => [],
    'value' => null,
    'valueKey' => 'value',
    'textKey' => 'text',
    'disabled' => false
])

<x-util.label for="{{ $id }}">
    {{ $label }}
</x-util.label>

<div class="mt-1">
    <x-input.select
        id="{{ $id }}"
        :theme="$theme"
        :options="$options"
        :value="$value"
        :valueKey="$valueKey"
        :textKey="$textKey"
        :disabled="$disabled"
        {{ $attributes }}
    />
</div>