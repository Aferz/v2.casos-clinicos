@props([
    'value',
    'theme' => null
])

<label
    {{ $attributes->merge([
        'class' => resolve_classes([
            'block font-medium text-sm' => true,

            // Theme - Empty
            'text-gray-700' => $theme === null,

            // Theme - Error
            'text-error-700' => $theme === 'error'
        ])
    ]) }}
>
    {{ $value ?? $slot }}
</label>
