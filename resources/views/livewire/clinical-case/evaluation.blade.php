<div class="p-8 bg-white rounded-md border">
    <h3 class="text-2xl leading-6 font-medium text-gray-700 text-center">
        {{ __('Your Evaluation') }}
    </h3>

    <form
        class="mt-16"
        wire:submit.prevent="evaluate"
    >
        <div class="space-y-8">
            @foreach (criteria() as $criterion)
                {!! $criterion->renderForm([
                    'valueModel' => 'models.' . $criterion->name() . '.value',
                    'commentModel' => 'models.' . $criterion->name() . '.comment',
                    'error' => $errors->first($criterion->name() . '.*'),
                ]) !!}
            @endforeach
        </div>

        <div class="flex justify-end mt-12">
            <x-button.button>
                {{ __('Send evaluation') }}
            </x-button.button>
        </div>
    </form>
</div>