@props([
    'align' => 'left',
    'highlighted' => false,
    'truncate' => true,
    'size' => 'base'
])

<td
    @classes([
        'px-6 py-4 whitespace-nowrap' => true,
        'text-gray-800' => $highlighted === true,
        'text-gray-500' => $highlighted === false,
        'text-left' => $align === 'left',
        'text-center' => $align === 'center',
        'text-right' => $align === 'right',
        'text-sm' => $size === 'sm'
    ])
    {{ $attributes }}
>
    <div class="{{ $truncate ? 'truncate' : '' }}">{{ $slot }}</div>
</td>