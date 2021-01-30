@props([
    'value' => null,

    // Remove when Laravel 9.x
    'id' => null,
    'label' => null,
    'disabled' => false,
])

<x-input.text-label
    :id="$id"
    :label="$label"
    :disabled="$disabled"
    theme="{{ $errors->has('password') ? 'error' : 'primary' }}"
    {{ $attributes->merge([
        'type' => 'password'
    ]) }}
/>

@if ($errors->first('password') && $value !== '')
<p class="text-error-500 mt-2 text-sm">
    {{ $errors->first('password') }}
</p>
@elseif ($errors->first('password') && $value === '')
<p class="text-error-500 mt-2 text-sm">
    {{ $errors->first('password') }} {{ __('It must have at least 8 characters, one capital letter, one small letter, one number and one symbol (%, $, &, !)') }}.
</p>
@else
<p class="text-gray-500 mt-2 text-sm">
    {{ __('It must have at least 8 characters, one capital letter, one small letter, one number and one symbol (%, $, &, !)') }}.
</p>
@endif