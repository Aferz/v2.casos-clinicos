<form
    class="bg-white border rounded-md p-8"
    wire:submit.prevent="register"
>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ __('Credentials') }}
        </h3>

        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ __('This information will be used for login into the platform') }}.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
        <div class="col-span-2">
            <x-input.text-label
                tabindex="1"
                label="{{ __('Email') }}*"
                id="email"
                wire:model.defer="email"
                theme="{{ $errors->has('email') ? 'error' : 'primary' }}"
            />

            @error ('email')
                <p class="text-error-500 mt-2 text-sm">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
        <div class="col-span-2">
            <x-input.password
                tabindex="2"
                label="{{ __('Password') }}*"
                id="password"
                autocomplete="off"
                wire:model.defer="password"
                :value="$password"
            />
        </div>
    </div>

    <div class="mt-16">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ __('Personal information') }}
        </h3>

        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ __('Information about you and your work') }}.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
        <div class="col-span-2">
            <x-input.text-label
                tabindex="4"
                label="{{ __('Name') }}*"
                id="name"
                theme="{{ $errors->has('name') ? 'error' : 'primary' }}"
                wire:model.defer="name"
            />

            @error('name')
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        <div class="col-span-1">
            <x-input.text-label
                tabindex="5"
                label="{{ __('Last name 1') }}*"
                id="lastname1"
                theme="{{ $errors->has('lastname1') ? 'error' : 'primary' }}"
                wire:model.defer="lastname1"
            />

            @error('lastname1')
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="col-span-1">
            <x-input.text-label
                tabindex="6"
                label="{{ __('Last Name 2') }}"
                id="lastname2"
                theme="{{ $errors->has('lastname2') ? 'error' : 'primary' }}"
                wire:model.defer="lastname2"
            />

            @error('lastname2')
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        <div class="col-span-1">
            <x-input.select-label
                tabindex="7"
                label="{{ __('Country') }}*"
                id="countryId"
                :options="$countries"
                theme="{{ $errors->has('countryId') ? 'error' : 'primary' }}"
                wire:model.defer="countryId"
            />

            @error('countryId')
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="col-span-1">
            <x-input.text-label
                tabindex="8"
                label="{{ __('City') }}*"
                id="city"
                theme="{{ $errors->has('city') ? 'error' : 'primary' }}"
                wire:model.defer="city"
            />

            @error('city')
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
        <div class="col-span-1">
            <x-input.text-label
                tabindex="9"
                label="{{ __('Work center') }}*"
                id="workcenter"
                theme="{{ $errors->has('workcenter') ? 'error' : 'primary' }}"
                wire:model.defer="workcenter"
            />

            @error('workcenter')
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>

        <div class="col-span-1">
            <x-input.select-label
                tabindex="10"
                label="{{ __('Speciality') }}*"
                id="specialityId"
                :options="$specialities"
                theme="{{ $errors->has('specialityId') ? 'error' : 'primary' }}"
                wire:model.defer="specialityId"
            />

            @error('specialityId')
            <p class="text-error-500 mt-2 text-sm">
                {{ $message }}
            </p>
            @enderror
        </div>
    </div>

    <div class="mt-16">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ __('Terms & Conditions') }}
        </h3>

        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ __('Conditions about the use of the service') }}.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-8 mt-8">
        <div class="col-span-1">
            <x-clinical-case.checkbox-rules-saned
                required
                tabindex="11"
                id="sanedRules"
                wire:model.defer="sanedRules"
            />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 mt-8">
        <div class="col-span-1">
            <x-clinical-case.checkbox-rules-lab
                required
                tabindex="12"
                id="labRules"
                wire:model.defer="labRules"
            />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 mt-8">
        <div class="col-span-1">
            <x-clinical-case.checkbox-rules-saned-mailing
                tabindex="13"
                id="sanedRulesMailing"
                wire:model.defer="sanedRulesMailing"
            />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 mt-8">
        <div class="col-span-1">
            <x-clinical-case.checkbox-rules-lab-mailing
                tabindex="14"
                id="labRulesMailing"
                wire:model.defer="labRulesMailing"
            />
        </div>
    </div>

    <div class="flex justify-between items-center mt-16">
        <x-util.link
            href="{{ route('login') }}"
            tabindex="-1"
        >
            {{ '<- ' . __('Cancel') }}
        </x-util.link>

        <x-button.button tabindex="15">
            {{ __('Register') }}
        </x-button.button>
    </div>
</form>