@php
$tabs = [
    'all' => [
        'text' => __('All'),
        'value' => 'all',
        'href' => route('clinical-cases.index')
    ],
    'published' => [
        'text' => __('Published'),
        'value' => 'published',
        'href' => route('clinical-cases.index', ['status' => 'published'])
    ],
    'sent' => [
        'text' => __('Sent'),
        'value' => 'sent',
        'href' => route('clinical-cases.index', ['status' => 'sent'])
    ],
    'draft' => [
        'text' => __('Drafts'),
        'value' => 'draft',
        'href' => route('clinical-cases.index', ['status' => 'draft'])
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
                    @if (! site()->clinicalCaseCreationIsRestricted())
                        <a
                            href="{{ route('clinical-cases.create') }}"
                            tabindex="-1"
                        >
                            <x-button.button>
                                {{ __('Create clinical case') }}
                            </x-button.button>
                        </a>
                    @endif
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

                    <x-table.th>
                        {{ __('Status') }}
                    </x-table.th>

                    <x-table.th orderable="updated_at">
                        {{ __('Last update') }}
                    </x-table.th>

                    <x-table.th></x-table.th>
                </x-table.tr>
            </x-table.thead>

            <x-table.tbody>
                @foreach ($clinicalCases as $clinicalCase)
                <x-table.tr>
                    <x-table.td highlighted>
                        @if ($clinicalCase->status === 'draft')
                            {{ ucfirst($clinicalCase->{$titleFieldName}) }}
                        @else
                            <x-util.link
                                size="md"
                                href="{{ $clinicalCase->showUrl() }}"
                            >
                                {{ ucfirst($clinicalCase->{$titleFieldName}) }}
                            </x-util.link>
                        @endif
                    </x-table.td>

                    <x-table.td>
                        @if ($clinicalCase->status === 'draft')
                            <x-util.pill theme="gray">{{ __('Draft') }}</x-util.pill>
                        @elseif ($clinicalCase->status === 'sent')
                            <x-util.pill theme="primary">{{ __('Sent') }}</x-util.pill>
                        @elseif ($clinicalCase->status === 'published')
                            <x-util.pill theme="success">{{ __('Published') }}</x-util.pill>
                        @endif
                    </x-table.td>

                    <x-table.td size="sm">
                        {{ str_replace('.', '', ucwords($clinicalCase->updated_at->translatedFormat('H:i, d M Y'))) }}
                    </x-table.td>

                    <x-table.td>
                        <div class="flex justify-center w-full">
                            <x-button.link
                                :disabled="!$clinicalCase->isDraft() || site()->clinicalCaseCreationIsRestricted()"
                                onclick="window.location.href = '{{ $clinicalCase->editUrl() }}'"
                            >
                                <x-icon-pencil-alt class="h-6 w-6" />
                            </x-button.link>
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