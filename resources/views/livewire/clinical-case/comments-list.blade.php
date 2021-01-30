<div class="p-8 bg-white rounded-md border">
    <h3 class="text-2xl leading-6 font-medium text-gray-700 text-center">
        {{ trans_choice(':count Comment|:count Comments', $commentsCount, [
            'count' => $commentsCount
        ]) }}
    </h3>

    <div class="mt-12 text-xl text-gray-700">
        {{ __('Leave your comment') }}
    </div>

    <div
        @classes([
            'pt-2' => true,
            'pb-8' => $comments->count()
        ])
    >
        <livewire:clinical-case.create-comment
            :clinical-case-id="$clinicalCaseId"
        />
    </div>

    @if ($comments->count())
        <div class="pb-6">
            @foreach ($comments as $comment)
                <livewire:clinical-case.comment
                    :key="$comment->id"
                    :clinical-case-id="$comment->clinical_case_id"
                    :comment-id="$comment->id"
                    :user-name="$comment->user->fullname"
                    :user-image="$comment->user->avatar_path"
                    :comment="$comment->comment"
                    :date="$comment->created_at"
                    :child-comments-count="$comment->child_comments_count"
                />
            @endforeach
        </div>
    @endif

    @if ($hasMorePages)
        <div class="flex justify-center pt-6 pb-12">
            <x-button.button wire:click="loadComments">
                @php($remaining = $total - $comments->count())
                @php($loadRemaining = $remaining > $perPage ? $perPage : $remaining)

                {{ trans_choice('Load :count comment more|Load :count comments more', $loadRemaining, [
                    'count' => $loadRemaining
                ]) }}
            </x-button.button>
        </div>
    @endif

    <script type="text/javascript">
        window.addEventListener('scrollIntoView', event => {
            const element = document.querySelector(`#${event.detail.elementId}`)

            element.classList.add('bg-green-100')

            setTimeout(() => {
                element.classList.add('transition-colors', 'duration-1000', 'bg-white')
                element.classList.remove('bg-green-100')
            }, 1500)
        })
    </script>
</div>