@props([
    'id' => null
])

<x-input.checkbox
    :id="$id"
    {{ $attributes }}
>
    <x-util.label :for="$id">
        <x-localization.json key="I accept the :sanedMailingRules">
            <x-slot name="sanedMailingRules">
                <x-clinical-case.modal-rules-saned-mailing>
                    <x-slot name="trigger">
                        <x-button.link
                            x-on:click="toggle"
                            class="text-left"
                        >{{ __('Saned\'s mailing rules') }}</x-button.link>
                    </x-slot>
                </x-clinical-case.modal-rules-saned-mailing>
            </x-slot>
        </x-localization.json>
    </x-util.label>
</x-input.checkbox>