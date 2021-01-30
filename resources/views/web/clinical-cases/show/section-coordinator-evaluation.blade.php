@php
    $evaluations = $clinicalCase->evaluations()
        ->where('user_id', auth()->user()->id)
        ->get();
@endphp

<div class="col-span-1">
    <div class="p-8 bg-white rounded-md border">
        <h3 class="text-2xl leading-6 font-medium text-gray-700 text-center">
            {{ __('Your Evaluation') }}
        </h3>

        <div class="mt-16 space-y-8">
            @foreach ($evaluations as $evaluation)
                {!! criteria($evaluation->criterion)->render($evaluation) !!}
            @endforeach
        </div>
    </div>
</div>

{{-- Helper --}}
<div class="col-span-1 hidden lg:block"></div>