<footer class="h-32 bg-gray-900">
    <div class="h-full max-w-7xl mx-auto py-8 px-4 overflow-hidden sm:px-6 lg:px-8 flex flex-col">
        <div class="flex space-x-8 justify-center">
            <x-util.logo-saned style="max-height: 27px;" />
            <x-util.logo-saned style="max-height: 27px;" />
        </div>

        <nav class="flex flex-wrap justify-center items-center flex-1 pt-6">
            <div class="px-5">
                <x-modal.cookies-policy-site>
                    <x-slot name="trigger">
                        <span
                            href="javascript:void"
                            class="cursor-pointer antialiased font-bold text-gray-400 hover:text-gray-200"
                            x-on:click="toggle"
                        >
                            {{ __('Cookies Policy') }}
                        </span>
                    </x-slot>
                </x-modal.cookies-policy-site>
            </div>

            <div class="px-5">
                <x-modal.privacy-policy-site>
                    <x-slot name="trigger">
                        <span
                            href="javascript:void"
                            class="cursor-pointer antialiased font-bold text-gray-400 hover:text-gray-200"
                            x-on:click="toggle"
                        >
                            {{ __('Privacy Policy') }}
                        </span>
                    </x-slot>
                </x-modal.privacy-policy-site>
            </div>

            <div class="px-5">
                <a
                    href="mailto:dnl.latam@gruposaned.com"
                    class="antialiased font-bold text-gray-400 hover:text-gray-200"
                >
                    {{ __('Help') }}
                </a>
            </div>

            <div class="px-5">
                <span class="antialiased font-bold text-gray-400">
                    {{ __('Web code: :code', ['code' => 'Web: M-N/A-MX-12-20-0018'])}}
                </span>
            </div>
        </nav>
    </div>
</footer>