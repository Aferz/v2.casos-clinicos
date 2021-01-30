@props([
    'href',
    'value',
    'theme' => 'primary',
    'showExternalIcon' => false,
    'size' => 'sm'
])
<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => resolve_classes([
            'font-bold focus:outline-none flex items-center' => true,
            'text-sm' => $size === 'sm',
            'text-base' => $size === 'md',

            // Theme - Primary
            'text-primary-600 hover:text-primary-500' => $theme === 'primary',

            // Theme - Error
            'text-error-600 hover:text-error-500' => $theme === 'error'
        ])
    ]) }}
>
    {{ $value ?? $slot }}

    @if ($showExternalIcon)
    <x-icon-external-link class="ml-1 h-4 w-4" />
    @endif
</a>
