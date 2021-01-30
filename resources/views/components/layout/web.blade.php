@php
$user = auth()->user();

$routes = [
    [
        'url' => route('directory'),
        'name' => __('Directory'),
        'access' => true
    ],
    [
        'url' => route('clinical-cases.index'),
        'name' => __('Clinical Cases'),
        'access' => true
    ],
    [
        'url' => route('users.index'),
        'name' => __('Users'),
        'access' => Auth::user()->isAdmin()
    ],
    [
        'url' => route('config'),
        'name' => __('Configuration'),
        'access' => Auth::user()->isAdmin()
    ]
];
@endphp

<x-layout.main>
    <div class="h-full min-h-screen flex flex-col">
        <div
            class="relative w-full bg-white flex-shrink-0 border-b"
            x-data="{ opened: false }"
        >
            <x-navigation.toolbar>
                <div class="h-full flex items-center">
                    <div class="h-full flex items-center mr-8">
                        <a
                            tabindex="-1"
                            href="{{ route('directory') }}"
                            class="h-full flex items-center focus:outline-none"
                        >
                            <x-util.logo-app
                                class="h-full hidden sm:block"
                                style="max-height: 27px;"
                            />
                        </a>

                        <button
                            tabindex="-1"
                            class="p-2 rounded focus:outline-none focus:bg-primary-100 sm:hidden"
                            x-on:click="opened = !opened"
                        >
                            <x-icon-menu class="block h-6 w-6 text-primary-500" />
                        </button>
                    </div>

                    <div class="flex-1"></div>

                    <div class="h-full hidden sm:block">
                        <div class="h-full flex space-x-4">
                            @foreach ($routes as $route)
                                @if($route['access'])
                                    @if (request()->url() === $route['url'])
                                    <span class="h-full border-b-4 flex items-center border-primary-500 font-medium px-3 text-gray-900 select-none">
                                        {{ $route['name'] }}
                                    </span>
                                    @else
                                    <a
                                        tabindex="0"
                                        href="{{ $route['url'] }}"
                                        class="h-full border-b-4 flex items-center border-transparent px-3 text-gray-700 hover:text-gray-900 hover:bg-gray-50 focus:bg-gray-50 focus:text-gray-900 focus:outline-none"
                                    >{{ $route['name'] }}</a>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="h-full flex items-center ml-8">
                        <x-menu.dropdown>
                            <x-slot name="trigger">
                                <button
                                    class="bg-primary-700 flex text-sm rounded-full text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-700 focus:ring-white"
                                    x-on:click="toggle"
                                >
                                    <img
                                        class="h-10 w-10 rounded-full"
                                        src="{{ asset("images/users/" . auth()->user()->avatar_path) }}"
                                    >
                                </button>
                            </x-slot>

                            <button
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none"
                                onclick="document.forms.logout.submit()"
                            >
                                {{ __('Logout') }}
                            </button>
                        </x-menu.dropdown>
                    </div>
                </div>
            </x-navigation.toolbar>

            <!-- Overlay -->
            <div
                style="display: none"
                x-show="opened"
                class="absolute h-screen w-screen bg-gray-900 opacity-25"
            ></div>

            <div
                style="display: none"
                class="relative block sm:hidden w-full bg-white border-t z-10"
                x-show="opened"
                x-on:click.away="opened = false"
            >
                <div class="max-w-7xl mx-auto divide-y">
                    @foreach ($routes as $route)
                        @if($route['access'])
                            @if (request()->url() === $route['url'])
                            <span class="h-16 px-4 flex items-center font-medium text-gray-900 select-none">
                                {{ $route['name'] }}
                            </span>
                            @else
                            <a
                                href="{{ $route['url'] }}"
                                class="h-16 px-4 flex items-center text-gray-900 select-none"
                            >{{ $route['name'] }}</a>
                            @endif
                        @endif
                    @endforeach

                    <button
                        class="w-full h-16 px-4 flex items-center text-gray-900 select-none focus:outline-none focus:bg-gray-100"
                        onclick="document.forms.logout.submit()"
                    >
                        {{ __('Logout') }}
                    </button>

                    <div class="h-16 w-full flex justify-center items-center">
                        <x-util.logo-app
                            class="h-full"
                            style="max-height: 27px;"
                        />
                    </div>
                </div>
            </div>

            <form
                id="logout"
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf
            </form>
        </div>

        @if (isset($subToolbar))
            {{ $subToolbar }}
        @endif

        <main class="flex-1 h-full w-full overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</x-layout.main>