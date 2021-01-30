@props([
    'theme' => 'primary',
    'value' => null
])

<span
    {{ $attributes->merge([
        'class' => resolve_classes([
            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full' => true,

            // Theme - Primary
            'bg-primary-100 text-primary-800' => $theme === 'primary',

            // Theme - Error
            'bg-error-100 text-error-800' => $theme === 'error',

            // Theme - Success
            'bg-success-100 text-success-800' => $theme === 'success',

            // Theme - Warning
            'bg-warning-100 text-warning-800' => $theme === 'warning',

            // Theme - Gray
            'bg-gray-100 text-gray-800' => $theme === 'gray'
        ])
    ]) }}
>
    {{ $value ?? $slot }}
</span>