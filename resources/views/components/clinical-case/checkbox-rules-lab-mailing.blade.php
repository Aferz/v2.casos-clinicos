@props([
    'id' => null
])

<x-input.checkbox
    :id="$id"
    {{ $attributes }}
>
    <x-util.label :for="$id">
        <x-localization.json key="I accept the :labMailingRules">
            <x-slot name="labMailingRules">
                <x-clinical-case.modal-rules-lab-mailing>
                    <x-slot name="trigger">
                        <x-button.link
                            x-on:click="toggle"
                            class="text-left"
                        >{{ __(':name\'s mailing rules', [
                            'name' => __(config('cc.app.lab_name'))
                        ]) }}</x-button.link>
                    </x-slot>
                </x-clinical-case.modal-rules-lab-mailing>
            </x-slot>
        </x-localization.json>
    </x-util.label>
</x-input.checkbox>