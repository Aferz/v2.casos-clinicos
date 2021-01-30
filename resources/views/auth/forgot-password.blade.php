<x-layout.auth>
    <div class="max-w-lg w-full justify-center px-4 bg-gray-100 mx-auto my-8">
        <form
            class="bg-white border rounded-md p-8"
            action="{{ route('password.email') }}"
            method="POST"
            x-data="{ email: '' }"
        >
            @csrf

            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __('Reset email') }}
                </h3>

                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ __('Enter your email and we\'ll send you an email with a link to reset your password') }}.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 mt-8">
                <div class="col-span-1">
                    <x-input.text-label
                        tabindex="1"
                        label="{{ __('Email') }}"
                        id="email"
                        name="email"
                        theme="{{ $errors->has('email') ? 'error' : 'primary' }}"
                        :value="old('email')"
                        x-model="email"
                    />

                    @error('email')
                    <p class="text-error-500 mt-2 text-sm">
                        {{ $message }}
                    </p>
                    @enderror
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
                    tabindex="2"
                    disabled
                    x-bind:disabled="!email"
                >
                    {{ __('Send email') }}
                </x-button.button>
            </div>
        </form>
    </div>
</x-layout.auth>