@props([
    'align' => 'left',
    'orderable' => null
])


@php
$currentOrder = request()->get('order');

if ($orderable) {
    $query = request()->query->all();

    if (
        !isset($query['order'])
        || !$query['order']
        || \Illuminate\Support\Str::after($query['order'], '-') !== $orderable
    ) {
        $query['order'] = $orderable;
    } else if ($query['order'] === $orderable) {
        $query['order'] = "-$orderable";
    } else {
        $query['order'] = null;
    }

    $nextOrderUrl = request()->url() . '?' . http_build_query($query);
}
@endphp

<th
    @classes([
        'px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider relative group whitespace-nowrap' => true,
        'hover:bg-primary-100 hover:text-primary-600 cursor-pointer' => $orderable !== null,
        'text-left' => $align === 'left',
        'text-center' => $align === 'center',
        'text-right' => $align === 'right',

    ])
    @if ($orderable) onclick="window.location.href = '{{ $nextOrderUrl }}'" @endif
    {{ $attributes }}
>
    <span @classes([
        'pr-6' => $currentOrder === $orderable || $currentOrder === "-$orderable"
    ])>{{ $slot }}</span>

    <div class="absolute right-0 top-0 bottom-0 flex items-center pt-1 mr-4 group-hover:text-primary-600 text-gray-500">
        @if ($orderable)
            @if ($currentOrder === $orderable)
            <x-icon-sort-ascending class="h-4 w-4" />
            @elseif ($currentOrder === "-$orderable")
            <x-icon-sort-descending class="h-4 w-4" />
            @endif
        @endif
    </div>
</th>