@props([
    'id' => null
])

<x-input.checkbox
    :id="$id"
    {{ $attributes }}
>
    <x-util.label :for="$id">
        <x-localization.json key="I've read and accept the :labRules">
            <x-slot name="labRules">
                <x-clinical-case.modal-rules-lab>
                    <x-slot name="trigger">
                        <x-button.link
                            x-on:click="toggle"
                            class="text-left"
                        >{{ __(':name\'s usage conditions and privacy policy', [
                            'name' => __(config('cc.app.lab_name'))
                        ]) }}</x-button.link>
                    </x-slot>
                </x-clinical-case.modal-rules-lab>*
            </x-slot>
        </x-localization.json>
    </x-util.label>
</x-input.checkbox>