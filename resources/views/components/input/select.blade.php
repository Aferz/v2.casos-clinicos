@props([
    'theme' => 'primary',
    'options' => [],
    'value' => null,
    'valueKey' => 'value',
    'textKey' => 'text',
    'disabled' => false
])

<select
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' => resolve_classes([
            'block w-full pl-3 pr-10 py-2 text-base focus:outline-none sm:text-sm rounded-md' => true,
            'cursor-not-allowed opacity-50' => $disabled === true,

            // Theme - Primary
            'focus:ring-indigo-500 focus:border-indigo-500' => $theme === 'primary',

            // Theme - Error
            'border-gray-300' => $theme !== 'error',
            'border-error-300 text-error-700 focus:ring-error-500 focus:border-error-500' => $theme === 'error'
        ])
    ]) }}
>
    @php
        $options = is_callable($options) ? $options() : $options;
    @endphp

    @foreach ($options as $option)
        @if (isset($option[$valueKey]))
            <option
                {{ $value === $option[$valueKey] ? 'selected' : '' }}
                value="{{ $option[$valueKey] }}"
            >
                {{ $option[$textKey] }}
            </option>
        @else
            <option
                {{ $value === $option ? 'selected' : '' }}
                value="{{ $option }}"
            >
                {{ $option }}
            </option>
        @endif
    @endforeach
</select>