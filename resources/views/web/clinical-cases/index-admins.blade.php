@php
    $tabs = [
        'all' => [
            'text' => __('All'),
            'value' => 'all',
            'href' => route('clinical-cases.index')
        ],
        'needs-evaluation' => [
            'text' => __('Needs evaluation'),
            'value' => 'needs-evaluation',
            'href' => route('clinical-cases.index', ['status' => 'needs-evaluation'])
        ],
        'evaluated' => [
            'text' => __('Evaluated'),
            'value' => 'evaluated',
            'href' => route('clinical-cases.index', ['status' => 'evaluated'])
        ],
        'published' => [
            'text' => __('Published'),
            'value' => 'published',
            'href' => route('clinical-cases.index', ['status' => 'published'])
        ]
    ];
@endphp

<x-layout.home>
    <x-slot name="subToolbar">
        <div class="relative w-full bg-white border-b py-2">
            <x-navigation.toolbar class="flex items-center">
                <div class="text-2xl font-medium text-gray-700 w-48 hidden md:block">
                    {{ __('Clinical Cases') }}
                </div>

                <div class="flex-1 flex md:justify-center pr-8">
                    <x-tabs.container
                        class="w-full md:w-auto"
                        :tabs="$tabs"
                        :value="$status"
                    />
                </div>

                <div class="flex justify-end space-x-4 md:w-48">
                    <x-menu.dropdown>
                        <x-slot name="trigger">
                            <x-button.button
                                :disabled="$clinicalCases->count() === 0"
                                x-on:click="toggle"
                            >
                                {{ __('Export clinical cases') }}
                            </x-button.button>
                        </x-slot>

                        @if ($clinicalCases->count())
                        <a
                            href="{{ route('clinical-cases.export-list', ['status' => $status, 'as' => 'xlsx']) }}"
                            class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none"
                            x-on:click="toggle"
                        >
                            {{ __('As Excel') }}
                        </a>

                        <a
                            href="{{ route('clinical-cases.export-list', ['status' => $status, 'as' => 'csv']) }}"
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
                    <x-table.th :orderable="$titleFieldName">
                        {{ __('Title') }}
                    </x-table.th>

                    <x-table.th orderable="user_name">
                        {{ __('Author') }}
                    </x-table.th>

                    <x-table.th orderable="status">
                        {{ __('Status') }}
                    </x-table.th>

                    <x-table.th orderable="sent_at">
                        {{ __('Sent at') }}
                    </x-table.th>

                    <x-table.th></x-table.th>
                </x-table.tr>
            </x-table.thead>

            <x-table.tbody>
                @foreach ($clinicalCases as $clinicalCase)
                <x-table.tr>
                    <x-table.td highlighted>
                        <x-util.link
                            size="md"
                            href="{{ $clinicalCase->showUrl() }}"
                        >
                            {{ ucfirst($clinicalCase->{$titleFieldName}) }}
                        </x-util.link>
                    </x-table.td>

                    <x-table.td highlighted>
                        {{ ucwords($clinicalCase->user_name) }}
                    </x-table.td>

                    <x-table.td>
                        @if ($clinicalCase->status === "needs-evaluation")
                            <x-util.pill theme="warning">{{ __('Needs Evaluation') }}</x-util.pill>
                        @elseif ($clinicalCase->status === "evaluated")
                            <x-util.pill theme="primary">{{ __('Evaluated') }}</x-util.pill>
                        @elseif ($clinicalCase->status === "published")
                            <x-util.pill theme="success">{{ __('Published') }}</x-util.pill>
                        @endif
                    </x-table.td>

                    <x-table.td size="sm">
                        {{ str_replace('.', '', ucwords($clinicalCase->sent_at->translatedFormat('H:i, d M Y'))) }}
                    </x-table.td>

                    <x-table.td>
                        <div class="flex justify-center w-full">
                            <x-util.link href="{{ $clinicalCase->editUrl() }}">
                                <x-icon-pencil-alt class="h-6 w-6" />
                            </x-util.link>
                        </div>
                    </x-table.td>
                </x-table.tr>
                @endforeach

                @if ($clinicalCases->count() === 0)
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
            {{ $clinicalCases->links() }}
        </div>
    </div>
</x-layout.home>