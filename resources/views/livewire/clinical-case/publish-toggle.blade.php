<div class="flex items-center">
    <button
        @classes([
            'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2' => true,
            'cursor-not-allowed opacity-50 bg-gray-200' => $this->disabled === true,
            'bg-gray-200' => $this->disabled === false,

            // Theme - Primary
            'focus:ring-indigo-500' => $theme === 'primary',
            'bg-indigo-600' => $theme === 'primary' && $published === true,

            // Theme - Success
            'focus:ring-success-500' => $theme === 'success',
            'bg-success-600' => $theme === 'success' && $published === true,
        ])
        type="button"
        wire:click="toggle"
        {{ $this->disabled ? 'disabled' : '' }}
    >
        <span
            @classes([
                'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200' => true,
                'translate-x-0' => $published === false,
                'translate-x-5' => $published === true
            ])
        ></span>
    </button>

    <span class="ml-3">
        @if (!$this->disabled)
            <span class="font-medium text-lg text-gray-700">
                {{ $published === true ? __('Published') : __('Not Published') }}
            </span>
        @else
            <span class="italic text-sm md:text-base text-gray-500">
                {{ __(':count :evaluation required before publication', [
                    'evaluation' => trans_choice('evaluation|evaluations', $remainingEvaluations),
                    'count' => $remainingEvaluations
                ]) }}
            </span>
        @endif
    </span>

    <input
        class="hidden"
        type="checkbox"
        wire:model="published"
        {{ $published === true ? 'checked' : '' }}
    />
</div>