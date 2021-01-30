@php
$tabs = [
    'doctor' => [
        'text' => __('Profesionals'),
        'value' => 'doctor',
        'href' => route('users.index')
    ],
    'coordinator' => [
        'text' => __('Coordinators'),
        'value' => 'coordinator',
        'href' => route('users.index', ['role' => 'coordinator'])
    ],
    'admin' => [
        'text' => __('Admins'),
        'value' => 'admin',
        'href' => route('users.index', ['role' => 'admin'])
    ],
];
@endphp

<x-layout.home>
    <x-slot name="subToolbar">
        <div class="relative w-full bg-white border-b py-2">
            <x-navigation.toolbar class="flex items-center">
                <div class="text-2xl font-medium text-gray-700 w-96 hidden md:block">
                    {{ __('Users') }}
                </div>

                <div class="flex-1 flex md:justify-center pr-8">
                    <x-tabs.container
                        class="w-full md:w-auto"
                        :tabs="$tabs"
                        :value="$role"
                    />
                </div>

                <div class="flex justify-end space-x-4 md:w-96">
                    @if ($role === 'coordinator')
                        <a
                            href="{{ route('users.create', ['role' => 'coordinator']) }}"
                            class="focus:outline-none"
                            tabindex="-1"
                        >
                            <x-button.button theme="white">
                                {{ __('Create coordinator') }}
                            </x-button.button>
                        </a>
                    @elseif ($role === 'admin')
                        <a
                            href="{{ route('users.create', ['role' => 'admin']) }}"
                            class="focus:outline-none"
                            tabindex="-1"
                        >
                            <x-button.button theme="white">
                                {{ __('Create admin') }}
                            </x-button.button>
                        </a>
                    @endif

                    <x-menu.dropdown>
                        <x-slot name="trigger">
                            <x-button.button
                                :disabled="$users->count() === 0"
                                x-on:click="toggle"
                            >
                                {{ __('Export users') }}
                            </x-button.button>
                        </x-slot>

                        @if ($users->count())
                            <a
                                href="{{ route('users.export-list', ['role' => $role, 'as' => 'xlsx']) }}"
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none"
                                x-on:click="toggle"
                            >
                                {{ __('As Excel') }}
                            </a>

                            <a
                                href="{{ route('users.export-list', ['role' => $role, 'as' => 'csv']) }}"
                                class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none"
                                x-on:click="toggle"
                            >
                                {{ __('As CSV') }}
                            </a>
                        @endif
                    </x-menu.dropdown>
                </div>
            </x-navigation.toolbar>
        </div>
    </x-slot>

    <div class="flex flex-col py-4 lg:py-8">
        <x-table.table>
            <x-table.thead>
                <x-table.tr>
                    <x-table.th orderable="name">
                        {{ __('Name') }}
                    </x-table.th>

                    @if ($role === 'doctor')
                        <x-table.th orderable="speciality">
                            {{ __('Speciality') }}
                        </x-table.th>
                    @endif

                    <x-table.th orderable="email">
                        {{ __('Email') }}
                    </x-table.th>

                    <x-table.th></x-table.th>
                </x-table.tr>
            </x-table.thead>

            <x-table.tbody>
                @foreach ($users as $user)
                    <x-table.tr>
                        <x-table.td highlighted>
                            {{ ucwords($user->fullname) }}
                        </x-table.td>

                        @if ($role === 'doctor')
                            <x-table.td>
                                {{ $user->speciality_name }}
                            </x-table.td>
                        @endif

                        <x-table.td>
                            {{ $user->email }}
                        </x-table.td>

                        <x-table.td>
                            <div class="flex items-center justify-end w-full space-x-4">
                                @if ($role !== 'doctor')
                                    <x-util.link href="{{ $user->editUrl() }}">
                                        <x-icon-pencil-alt class="h-6 w-6" />
                                    </x-util.link>

                                    @if (auth()->user()->isAdmin())
                                        <x-button.link
                                            theme="error"
                                            :disabled="$user->id === auth()->user()->id"
                                            href="{{ $user->editUrl() }}"
                                            onclick="confirmDelete() && document.forms.deleteUser{{ $user->id }}.submit()"
                                        >
                                            <x-icon-trash class="h-6 w-6" />
                                        </x-button.link>
                                    @endif
                                @endif
                            </div>

                            <form
                                action="{{ $user->deleteUrl() }}"
                                id="deleteUser{{ $user->id }}"
                                method="POST"
                            >
                                @csrf()
                                @method('DELETE')
                            </form>
                        </x-table.td>
                    </x-table.tr>
                @endforeach

                @if ($users->count() === 0)
                    <x-table.tr>
                        <x-table.td align="center" colspan="999">
                            <div class="w-full py-3">
                                {{ __('No results for your search') }}.
                            </div>
                        </x-table.td>
                    </x-table.tr>
                @endif
            </x-table.tbody>
        </x-table.table>

        <div class="mt-4 lg:mt-8">
            {{ $users->links() }}
        </div>

        <script>
            function confirmDelete() {
                return confirm("{!! __('Are you sure? This action can\'t be undone.') !!}");
            }
        </script>
    </div>
</x-layout.home>