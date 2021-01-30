@props([
    'theme' => 'primary',
    'disabled' => false,
    'outlined' => false
])

<button
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => resolve_classes([
            'flex justify-center items-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed' => true,

            // Theme - Primary
            'text-white bg-primary-600 focus:ring-primary-500' => $theme === 'primary',
            'hover:bg-primary-700' => $theme === 'primary' && !$disabled,

            // Theme - Success
            'text-white bg-success-600 focus:ring-success-500' => $theme === 'success',
            'hover:bg-success-700' => $theme === 'success' && !$disabled,

            // Theme - Error
            'text-white bg-error-600 focus:ring-error-500' => $theme === 'error',
            'hover:bg-error-700' => $theme === 'error' && !$disabled,

            // Theme - Warning
            'text-white bg-warning-600 focus:ring-warning-500' => $theme === 'warning' && !$outlined,
            'hover:bg-warning-700' => $theme === 'warning' && !$outlined && !$disabled,
            'border-warning-600 text-warning-600 focus:ring-warning-500' => $theme === 'warning' && $outlined,
            'hover:bg-warning-50' => $theme === 'warning' && $outlined && !$disabled,

            // Theme - White
            'text-gray-800 bg-white border-gray-400 focus:ring-gray-300' => $theme === 'white',
            'hover:bg-gray-100' => $theme === 'white' && !$disabled,
        ])
    ]) }}
>
    {{ $slot }}
</button>
