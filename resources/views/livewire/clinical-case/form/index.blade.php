<form class="max-w-3xl mx-auto p-8 bg-white rounded-md shadow-md">
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ $clinicalCaseId ? __('Edit clinical case') : __('Create clinical case') }}
        </h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-6 gap-x-8 gap-y-12 mt-8">
        @include('livewire.clinical-case.form.fields')

        @if ($bibliographiesAreEnabled)
            @include('livewire.clinical-case.form.bibliographies')
        @endif

        @if ($imagesAreEnabled)
            @include('livewire.clinical-case.form.images')
        @endif
    </div>

    <div class="flex justify-between items-center mt-16">
        <div>
            <x-util.link
                href="{{ route('clinical-cases.index') }}"
                tabindex="-1"
            >
                {{ '<- ' . __('Cancel') }}
            </x-util.link>
        </div>

        <div class="flex space-x-4">
            @if (! Auth::user()->isAdmin())
                <x-button.button
                    :tabindex="fields()->count() + 1"
                    type="button"
                    theme="white"
                    wire:click="save"
                >
                    {{ __('Save Draft') }}
                </x-button.button>

                <x-button.button
                    :tabindex="fields()->count() + 2"
                    type="button"
                    onclick="send()"
                >
                    {{ __('Send Clinical Case') }}
                </x-button.button>
            @else
                <x-button.button
                    :tabindex="fields()->count() + 1"
                    type="button"
                    wire:click="save"
                >
                    {{ __('Save') }}
                </x-button.button>
            @endif
        </div>
    </div>

    <script type="text/javascript">
        function send () {
            if (confirm("{!! __('Are you sure? This action can\'t be undone.') !!}")) {
                @this.call('send')
            }
        }
    </script>
</form>