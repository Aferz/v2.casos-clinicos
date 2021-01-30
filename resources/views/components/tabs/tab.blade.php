@props([
    'text',
    'href',
    'selected' => false,
])

@if ($selected)
<span class="bg-primary-100 text-primary-700 px-3 py-2 font-medium text-sm rounded-md">
    {{ $text }}
</span>
@else
<a
    href="{{ $href }}"
    class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md"
>
    {{ $text }}
</a>
@endif