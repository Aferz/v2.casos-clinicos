<x-layout.main>
    <div class="h-full min-h-screen flex flex-col">
        <div class="relative w-full bg-white flex-shrink-0 border-b">
            <x-navigation.toolbar>
                <div class="h-full flex items-center justify-center">
                    <x-util.logo-app class="h-10 w-auto" />
                </div>
            </x-navigation.toolbar>
        </div>

        <main class="flex-1 h-full w-full overflow-y-auto">
            <div style="min-height: calc(100% - 10rem)">
                {{ $slot }}
            </div>

            @include('auth.footer')
        </main>
    </div>
</x-layout.main>