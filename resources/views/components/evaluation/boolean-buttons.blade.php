@props([
    'labelTrue',
    'labelFalse',
    'value' => null,
    'disabled' => false,
])

<span
    class="relative z-0 inline-flex shadow-sm rounded-md"
    @if (!$disabled)
        x-data="{
            value: {{ $value ?? 'null' }}
        }"
    @endif
>
    <button
        type="button"
        @classes([
            'rounded-l-md relative inline-flex items-center py-2 border font-medium w-16 justify-center' => true,
            'bg-error-600 text-error-50 border-error-600' => $value == 0,
            'focus:z-10 focus:outline-none focus:ring-1 focus:ring-error-500 focus:border-error-500' => !$disabled,
            'focus:outline-none cursor-default' => $disabled,
            'bg-gray-50 opacity-75' => $value != 0 && $disabled
        ])
        @if (!$disabled)
            x-on:click="
                $refs.radio0.click();
                value = $refs.radio0.value;
            "
            x-bind:class="{
                'bg-error-600 text-error-50 border-error-600': value == 0,
                'bg-white text-gray-700 hover:bg-gray-50 border-gray-300': value != 0
            }"
        @endif
    >
        {{ $labelFalse }}

        @if (!$disabled)
            <input
                type="radio"
                class="hidden"
                value="0"
                x-ref="radio0"
                {{ $attributes }}
            />
        @endif
    </button>

    <button
        type="button"
        @classes([
            '-ml-px rounded-r-md relative inline-flex items-center py-2 border font-medium w-16 justify-center' => true,
            'bg-success-600 text-success-50 border-success-600' => $value == 1,
            'focus:z-10 focus:outline-none focus:ring-1 focus:ring-success-500 focus:border-success-500' => !$disabled,
            'focus:outline-none cursor-default' => $disabled,
            'bg-gray-50 opacity-75' => $value != 1 && $disabled
        ])
        @if (!$disabled)
            x-on:click="
                $refs.radio1.click();
                value = $refs.radio1.value;
            "
            x-bind:class="{
                'bg-success-600 text-success-50 border-success-600': value == 1,
                'bg-white text-gray-700 hover:bg-gray-50 border-gray-300': value != 1
            }"
        @endif
    >
        {{ $labelTrue }}

        @if (!$disabled)
            <input
                type="radio"
                class="hidden"
                value="1"
                x-ref="radio1"
                {{ $attributes }}
            />
        @endif
    </button>
</span>