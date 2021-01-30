<x-slot name="subToolbar">
    <div class="relative w-full bg-white border-b py-2">
        <x-navigation.toolbar class="flex items-center justify-between">
            <div class="space-x-4">
                <form>
                    <livewire:clinical-case.publish-toggle
                        theme="success"
                        :remaining-evaluations="$clinicalCase->remainingEvaluationsCount()"
                        :clinical-case-id="$clinicalCase->id"
                        :published="$clinicalCase->published_at !== null"
                    />
                </form>
            </div>

            <div class="flex space-x-4">
                @feature('highlight')
                    <livewire:clinical-case.button-highlight
                        :clinical-case-id="$clinicalCase->id"
                        :highlighted="$clinicalCase->highlighted"
                    />
                @endfeature

                <x-menu.dropdown>
                    <x-slot name="trigger">
                        <x-button.button x-on:click="toggle">
                            {{ __('Export') }}
                        </x-button.button>
                    </x-slot>

                    <a
                        href="{{ $clinicalCase->exportUrl(['as' => 'pdf']) }}"
                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none"
                        x-on:click="toggle"
                    >
                        {{ __('As PDF') }}
                    </a>

                    <a
                        href="{{ $clinicalCase->exportUrl(['as' => 'zip']) }}"
                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none"
                        x-on:click="toggle"
                    >
                        {{ __('As ZIP') }}
                    </a>
                </x-menu.dropdown>
            </div>
        </x-navigation.toolbar>
    </div>
</x-slot>