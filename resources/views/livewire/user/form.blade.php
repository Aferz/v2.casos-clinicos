<form
    class="max-w-3xl mx-auto p-8 bg-white rounded-md shadow-md"
    wire:submit.prevent="save"
>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="col-span-1 md:col-span-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $userId ? __(':role personal information', [
                    'role' => ucfirst(__($role))
                ]) : __(':role personal information', [
                    'role' => ucfirst(__($role))
                ]) }}
            </h3>

            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                {{ __('Information about the user') }}. {{ __('Fields marked with an asterisk are required') }}.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
        <div class="col-span-2">
            <x-input.text-label
                tabindex="1"
                label="{{ __('Name') }} *"
                id="name"
                theme="{{ $errors->has('name') ? 'error' : 'primary' }}"
                wire:model.lazy="name"
            />

            @error('name')
                <p class="text-error-500 mt-2 text-sm">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
        <div class="col-span-2">
            <x-input.text-label
                tabindex="2"
                label="{{ __('Last Name 1') }} *"
                id="lastname1"
                theme="{{ $errors->has('lastname1') ? 'error' : 'primary' }}"
                wire:model.lazy="lastname1"
            />

            @error('lastname1')
                <p class="text-error-500 mt-2 text-sm">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
        <div class="col-span-2">
            <x-input.text-label
                tabindex="3"
                label="{{ __('Last Name 2') }}"
                id="lastname2"
                theme="{{ $errors->has('lastname2') ? 'error' : 'primary' }}"
                wire:model.lazy="lastname2"
            />

            @error('lastname2')
                <p class="text-error-500 mt-2 text-sm">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mt-16">
        <div class="col-span-1 md:col-span-3">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ __('Credentials') }}
            </h3>

            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                {{ __('This information will be used for login into the platform') }}. @if (!$userId) {{ __('Fields marked with an asterisk are required') }}. @endif
            </p>
        </div>
    </div>

    @if ($userId)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
            <div class="col-span-2">
                <x-input.text-label
                    tabindex="4"
                    label="{{ __('Email') }}"
                    :disabled="true"
                    :value="$email"
                />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
            <div class="col-span-2">
                <x-input.password
                    tabindex="5"
                    label="{{ __('New password') }}"
                    id="password"
                    autocomplete="off"
                    wire:model.defer="password"
                />
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
            <div class="col-span-2">
                <x-input.text-label
                    tabindex="4"
                    label="{{ __('Email') }} *"
                    id="email"
                    wire:model.lazy="email"
                    theme="{{ $errors->has('email') ? 'error' : 'primary' }}"
                />

                @error ('email')
                    <p class="text-error-500 mt-2 text-sm">
                        {{ $message }}
                    </p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
            <div class="col-span-2">
                <x-input.password
                    tabindex="5"
                    label="{{ __('Password') }} *"
                    id="password"
                    autocomplete="off"
                    wire:model.defer="password"
                />
            </div>
        </div>
    @endif

    <div class="flex justify-between items-center mt-16">
        <div>
            <x-util.link
                href="{{ route('users.index', ['role' => $role]) }}"
                tabindex="-1"
            >
                {{ '<- ' . __('Cancel') }}
            </x-util.link>
        </div>

        <div class="flex space-x-4">
            @if ($userId)
                <x-button.button tabindex="6">
                    {{ __('Save') }}
                </x-button.button>
            @else
                <x-button.button tabindex="7">
                    {{ __('Create') }}
                </x-button.button>
            @endif
        </div>
    </div>
</form>