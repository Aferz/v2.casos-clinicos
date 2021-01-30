<x-modal.simple>
    <x-slot name="trigger">
        {{ $trigger }}
    </x-slot>

    <div class="bg-white rounded-lg overflow-hidden shadow-xl p-8 sm:max-w-lg sm:w-full">
        {{ __(config('cc.app.lab_name')) }} Privacy Policy
    </div>
</x-modal.modal>