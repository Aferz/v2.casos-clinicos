@props([
    'theme' => 'primary',
    'disabled' => false
])

<button
    type="button"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' => resolve_classes([
            'text-sm font-medium focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed' => true,

            // Theme - Primary
            'text-primary-600' => $theme === 'primary',
            'hover:text-primary-500' => $theme === 'primary' && !$disabled,

            // Theme - Error
            'text-error-600' => $theme === 'error',
            'hover:text-error-500' => $theme === 'error' && !$disabled,
        ])
    ]) }}
>
    {{ $slot }}
</button>
