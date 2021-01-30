@props([
    'min',
    'max',
    'disabled' => false,
    'value' => null
])

<span
    class="relative z-0 inline-flex shadow-sm rounded-md"
    @if (!$disabled)
        x-data="{
            value: {{ $value ?? 'null' }}
        }"
    @endif
>
    @foreach (range($min, $max) as $index => $score)
        <button
            type="button"
            @classes([
                '-ml-px' => $index != 0,
                'rounded-l-md' => $index == 0,
                'rounded-r-md' => $index == $loop->count - 1,
                'bg-primary-600 text-primary-50 border-primary-600' => $value == $score,
                'bg-white text-gray-700 hover:bg-gray-50 border-gray-300' => $value != $score && !$disabled,
                'bg-gray-50 opacity-75' => $value != $score && $disabled,
                'focus:outline-none cursor-default' => $disabled,
                'focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500' => !$disabled,
                'relative inline-flex items-center py-2 border font-medium w-8 text-xs sm:text-base sm:w-12 justify-center' => true
            ])
            @if (!$disabled)
                x-on:click="
                    $refs.radio{{ $index }}.click();
                    value = $refs.radio{{ $index }}.value;
                "
                x-bind:class="{
                    'bg-primary-600 text-primary-50 border-primary-600': value == {{ $score }},
                    'bg-white text-gray-700 hover:bg-gray-50 border-gray-300': value != {{ $score }}
                }"
            @endif
        >
            {{ $score }}

            @if (!$disabled)
                <input
                    type="radio"
                    class="hidden"
                    value="{{ $score }}"
                    x-ref="radio{{ $index }}"
                    {{ $attributes }}
                />
            @endif
        </button>
    @endforeach
</span>
