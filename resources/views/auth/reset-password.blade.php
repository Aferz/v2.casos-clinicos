<x-layout.auth>
    <div class="max-w-lg w-full justify-center px-4 bg-gray-100 mx-auto my-8">
        <form
            class="bg-white border rounded-md p-8 mt-8"
            action="{{ route('password.update') }}"
            method="POST"
            x-data="{ password: '', passwordConfirmation: '' }"
        >
            @csrf

            <input
                type="hidden"
                name="email"
                value="{{ $email }}"
            />

            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            />

            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Reset password') }}
                </h3>

                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ __('You can change your password to a fresh new one') }}.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-8">
                <div class="col-span-1">
                    <x-input.password
                        tabindex="1"
                        label="{{ __('Password') }}"
                        id="password"
                        name="password"
                        autocomplete="off"
                        :value="old('password')"
                        x-model="password"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-8">
                <div class="col-span-1">
                    <x-input.text-label
                        tabindex="2"
                        label="{{ __('Password Confirmation') }}"
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        theme="{{ $errors->has('password') ? 'error' : 'primary' }}"
                        x-model="passwordConfirmation"
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

                <x-button.button
                    tabindex="3"
                    disabled
                    x-bind:disabled="!password || !passwordConfirmation"
                >
                    {{ __('Reset password') }}
                </x-button.button>
            </div>
        </form>
    </div>
</x-layout.auth>