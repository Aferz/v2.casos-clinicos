@props([
    'id' => null
])

<x-input.checkbox
    :id="$id"
    {{ $attributes }}
>
    <x-util.label :for="$id">
        <x-localization.json key="I've read and accept the :sanedRules">
            <x-slot name="sanedRules">
                <x-clinical-case.modal-rules-saned>
                    <x-slot name="trigger">
                        <x-button.link
                            x-on:click="toggle"
                            class="text-left"
                        >{{ __('Saned\'s usage conditions and privacy policy') }}</x-button.link>
                    </x-slot>
                </x-clinical-case.modal-rules-saned>
            </x-slot>
        </x-localization.json>*
    </x-util.label>
</x-input.checkbox>