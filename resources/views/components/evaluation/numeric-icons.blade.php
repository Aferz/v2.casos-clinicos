@props([
    'min',
    'max',
    'disabled' => false,
    'value' => null,
    'icon',
    'iconSolid',
    'iconClass',
    'iconSolidClass'
])

<span
    class="inline-flex space-x-1"
    x-data="{
        value: {{ $value ?? 'null' }},
        hoverValue: {{ $value ?? 'null' }}
    }"
>
    @foreach (range($min, $max) as $index => $score)
        <button
            type="button"
            @classes([
                'focus:outline-none' => true,
                'focus:outline-none cursor-default' => $disabled,
            ])
            @if (!$disabled)
            x-on:click="
                $refs.radio{{ $index }}.click();
                value = $refs.radio{{ $index }}.value;
            "
            @endif
        >
            <div
                class="w-8 h-8 sm:h-9 sm:w-9 -ml-1 flex justify-center"
                @if (!$disabled)
                x-on:mouseenter="hoverValue = {{ $score }}"
                x-on:mouseleave="hoverValue = null"
                @endif
            >
                {{ svg($icon, [
                    'class' => $iconClass,
                    'x-show' => "
                        (hoverValue !== null && hoverValue < $score)
                        || (hoverValue === null && (value < $score || value === null))
                    "
                ]) }}

                {{ svg($iconSolid, [
                    'class' => $iconSolidClass,
                    'x-show' => "
                        (hoverValue !== null && hoverValue >= $score)
                        || (hoverValue === null && value !== null && value >= $score)
                    "
                ]) }}
            </div>

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
