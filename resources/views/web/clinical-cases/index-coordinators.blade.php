@php
$tabs = [
    'all' => [
        'text' => __('All'),
        'value' => 'all',
        'href' => route('clinical-cases.index')
    ],
    'evaluated' => [
        'text' => __('Evaluated'),
        'value' => 'evaluated',
        'href' => route('clinical-cases.index', ['status' => 'evaluated'])
    ],
    'not-evaluated' => [
        'text' => __('Not Evaluated'),
        'value' => 'not-evaluated',
        'href' => route('clinical-cases.index', ['status' => 'not-evaluated'])
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

                <div class="flex-1 flex md:justify-center md:pr-8">
                    <x-tabs.container
                        class="w-full md:w-auto"
                        :tabs="$tabs"
                        :value="$status"
                    />
                </div>

                <div class="flex justify-end space-x-4 md:w-48">
                    <!-- -->
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

                    <x-table.th orderable="evaluated_at">
                        {{ __('Evaluated at') }}
                    </x-table.th>
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
                        @if ($clinicalCase->status === 'evaluated')
                            <x-util.pill theme="success">{{ __('Evaluated') }}</x-util.pill>
                        @elseif ($clinicalCase->status === 'not-evaluated')
                            <x-util.pill theme="warning">{{ __('Not Evaluated') }}</x-util.pill>
                        @endif
                    </x-table.td>

                    <x-table.td size="sm">
                        {{ str_replace('.', '', ucwords($clinicalCase->sent_at->translatedFormat('H:i, d M Y'))) }}
                    </x-table.td>

                    <x-table.td size="sm">
                        @if ($clinicalCase->evaluated_at)
                            {{ str_replace('.', '',
                                ucwords((new Carbon\Carbon($clinicalCase->evaluated_at))->translatedFormat('H:i, d M Y'))
                            ) }}
                        @else
                            —
                        @endif
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