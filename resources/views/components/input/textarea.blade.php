@props([
    'theme' => 'primary',
    'disabled' => false
])

<textarea
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'type' => 'text',
        'class' => resolve_classes([
            'appearance-none block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed' => true,

            // Theme - Primary
            'border-gray-300 focus:ring-primary-500 focus:border-primary-500' => $theme === 'primary',

            // Theme - Error
            'border-gray-300' => $theme !== 'error',
            'border-error-300 text-error-700 focus:ring-error-500 focus:border-error-500' => $theme === 'error'
        ])
    ]) }}
></textarea>