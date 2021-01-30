@php
    $usersEvaluations = $clinicalCase->evaluations->groupBy('user_id');
@endphp

<div class="col-span-1">
    <div class="p-8 bg-white rounded-md border">
        <h3 class="text-2xl leading-6 font-medium text-gray-700 text-center">
            {{ __('Evaluations') }}
        </h3>

        @if ($usersEvaluations->count() === 0)
            <div class="mt-8 text-center italic text-lg text-gray-600 w-96 mx-auto">
                {{ __('None yet') }}.
            </div>
        @endif

        @if ($usersEvaluations->count() > 0)
            <div class="mt-16 space-y-8">
                @foreach ($usersEvaluations as $evaluations)
                    <div class="space-y-8">
                        <div>
                            <p class="text-xl font-medium text-primary-500">
                                {{ __('Evaluation done by :name', ['name' => $evaluations[0]->user->fullname]) }}
                            </p>
                            <p class="text-gray-500 italic">
                                {{ $evaluations[0]->created_at->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>

                        <div class="space-y-12">
                            @foreach ($evaluations as $evaluation)
                                {!! criteria($evaluation->criterion)->render($evaluation) !!}
                            @endforeach
                        </div>
                    </div>

                    @if ($loop->iteration !== $usersEvaluations->count())
                        <hr class="my-8" />
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Helper --}}
<div class="col-span-1 hidden lg:block"></div>