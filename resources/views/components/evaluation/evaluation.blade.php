@props([
    'criterion',
    'comment',
    'render'
])

<div class="flex flex-col sm:flex-row">
    <div class="w-full sm:w-48 font-medium text-lg text-gray-600">
        {{ ucwords(str_replace('-', ' ', __($criterion))) }}
    </div>

    <div class="flex-1 mt-2 sm:mt-0">
        <div>
            {!! $render !!}
        </div>

        @feature('evaluation_comments')
            <p class="mt-4 text-gray-700 italic">{{ $comment }}</p>
        @endfeature
    </div>
</div>