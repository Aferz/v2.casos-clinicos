<x-layout.main>
    <div class="h-screen flex flex-col">
        <main class="flex-1 flex">
            <div class="hidden lg:block relative w-0 flex-1">
                <img
                    class="absolute inset-0 w-full h-full object-cover"
                    src="{{ asset('images/login.jpg') }}"
                >
            </div>

            <div class="flex-1 flex flex-col justify-center py-12 px-4 bg-gray-100 sm:px-6 lg:flex-none lg:px-20 xl:px-24 lg:bg-white">
                <div class="mx-auto w-full max-w-md lg:w-96 border rounded-md lg:border-0 p-8 lg:p-0 bg-white">
                    <div class="flex flex-col justify-center">
                        <x-util.logo-app class="h-12 w-auto" />

                        <h2 class="mt-6 text-3xl font-extrabold text-gray-900 text-center">
                            Casos Clínicos
                        </h2>
                    </div>

                    <div class="mt-8">
                        @error('email')
                            <div class="mb-4 text-center text-error-600 bg-error-50 px-4 py-2 rounded border border-error-300">
                                {{ $message }}
                            </div>
                        @endif

                        @if(session('passwordReset'))
                            <div class="mb-4 text-center text-success-600 bg-success-50 px-4 py-2 rounded border border-success-300">
                                {{ __('Password updated successfuly') }}.
                            </div>
                        @endif

                        @if(session('resetLinkSent'))
                            <div class="mb-4 text-center text-success-600 bg-success-50 px-4 py-2 rounded border border-success-300">
                                {{ __('We\'ve sent you an email to recover your password') }}.
                            </div>
                        @endif

                        @if(session('prevent'))
                            <div class="b-4 text-center text-error-600 bg-error-50 px-4 py-2 rounded border border-error-300">
                                {{ __('It\'s not allowed to access the application in this moments') }}.
                            </div>
                        @endif

                        <div>
                            <form
                                action="{{ url('/login') }}"
                                method="POST"
                                class="space-y-6"
                            >
                                @csrf

                                <div>
                                    <x-input.text-label
                                        label="{{ __('Email') }}"
                                        tabindex="1"
                                        name="email"
                                        type="email"
                                        autocomplete="email"
                                        required
                                        value="{{ app()->environment('local') ? 'doctor@casosclinicos.com' : '' }}"
                                    />
                                </div>

                                <div class="space-y-1">
                                    <x-input.text-label
                                        label="{{ __('Password') }}"
                                        tabindex="2"
                                        name="password"
                                        type="password"
                                        autocomplete="current-password"
                                        required
                                        value="{{ app()->environment('local') ? 'Password1%' : '' }}"
                                    />
                                </div>

                                <div class="flex items-center justify-end">
                                    <x-util.link href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </x-util.link>
                                </div>

                                <div>
                                    <x-button.button tabindex="3" class="w-full">
                                        {{ __('Sign in') }}
                                    </x-button.button>
                                </div>

                                <div>
                                    <div class="-mt-2 flex justify-center">
                                        <x-util.link href="{{ route('register') }}">
                                            {{ __('Register as new user') }}
                                        </x-util.link>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('auth.footer')
    </div>
</x-layout.main>