<form
    class="bg-white border rounded-md p-8"
    wire:submit.prevent="save"
>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="col-span-2">
            <x-input.text-label
                min="{{ date('Y-m-d') }}"
                type="date"
                label="{{ __('Restrict access to doctors after date') }}"
                theme="{{ $errors->has('name') ? 'error' : 'primary' }}"
                wire:model.defer="restrictDoctorAccessAt"
            />

            @error('restrictDoctorAccessAt')
                <p class="text-error-500 mt-2 text-sm">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
        <div class="col-span-2">
            <x-input.text-label
                type="date"
                min="{{ date('Y-m-d') }}"
                label="{{ __('Restrict access to coordinators after date') }}"
                theme="{{ $errors->has('name') ? 'error' : 'primary' }}"
                wire:model.defer="restrictCoordinatorAccessAt"
            />

            @error('restrictCoordinatorAccessAt')
                <p class="text-error-500 mt-2 text-sm">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
        <div class="col-span-2">
            <x-input.text-label
                type="date"
                min="{{ date('Y-m-d') }}"
                label="{{ __('Restrict clinical cases creation after date') }}"
                theme="{{ $errors->has('name') ? 'error' : 'primary' }}"
                wire:model.defer="restrictClinicalCaseCreationAt"
            />

            @error('restrictClinicalCaseCreationAt')
                <p class="text-error-500 mt-2 text-sm">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
        <div class="col-span-2">
            <x-input.text-label
                type="date"
                min="{{ date('Y-m-d') }}"
                label="{{ __('Restrict clinical cases evaluation after date') }}"
                theme="{{ $errors->has('name') ? 'error' : 'primary' }}"
                wire:model.defer="restrictClinicalCaseEvaluationAt"
            />

            @error('restrictClinicalCaseEvaluationAt')
                <p class="text-error-500 mt-2 text-sm">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="flex justify-between items-center mt-16">
        <x-util.link
            href="{{ route('directory') }}"
            tabindex="-1"
        >
            {{ '<- ' . __('Cancel') }}
        </x-util.link>

        <div class="flex space-x-4">
            <x-button.button>
                {{ __('Save') }}
            </x-button.button>
        </div>
    </div>
</form>