<div
    id="comment-{{ $commentId }}"
    @classes([
        'pr-8 py-4' => true,
        'pl-0' => $parentCommentId === null,
        'pl-16' => $parentCommentId !== null
    ])
>
    <div class="flex">
        <div class="w-12">
            <img
                class="h-12 w-12 rounded-full"
                src="{{ asset("images/users/$userImage") }}"
            />
        </div>

        <div class="flex-1 pl-4">
            <div class="font-medium text-gray-800">{{ ucwords($userName) }}</div>
            <div class="text-gray-600">{{ ucfirst($comment) }}</div>
            <div class="text-gray-400 text-sm mt-2">
                {{ str_replace('.', '', ucwords($date->translatedFormat('d M Y, H:i'))) }}

                @if (!$parentCommentId && $childCommentsCount > 0)
                    · {{ trans_choice(':replies reply|:replies replies', $childCommentsCount, [
                        'replies' => $childCommentsCount
                    ]) }}
                @endif

                @if (auth()->user()->isAdmin())
                    · <x-button.link
                        theme="error"
                        x-data=""
                        x-on:click="confirm('{!! __('Are you sure? This action can not be undone') !!}.') && $wire.delete()"
                    >
                        {{ __('Delete comment') }}
                    </x-button.link>
                @endif
            </div>
        </div>
    </div>

    @if (isset($replies) && $parentCommentId === null)
        <div class="mt-6 space-y-6">
            @foreach ($replies as $index => $reply)
                <livewire:clinical-case.comment
                    :key="$reply->id"
                    :clinical-case-id="$reply->clinical_case_id"
                    :comment-id="$reply->id"
                    :user-name="$reply->user->fullname"
                    :user-image="$reply->user->avatar_path"
                    :comment="$reply->comment"
                    :date="$reply->created_at"
                    :parent-comment-id="$reply->parent_comment_id"
                />
            @endforeach
        </div>
    @endif

    @if ($hasMorePages && $childCommentsCount > 0)
        <div class="text-primary-600 flex items-center justify-center my-8">
            <x-icon-cheveron-down class="h-5 w-5" />

            <x-button.link wire:click="loadReplies">
                @php($remaining = $childCommentsCount - ($replies ? $replies->count() : 0))
                @php($loadRemaining = $remaining > $repliesPerPage ? $repliesPerPage : $remaining)

                {{ trans_choice('Load :count reply more|Load :count replies more', $loadRemaining, [
                    'count' => $loadRemaining
                ]) }}
            </x-button.link>
        </div>
    @endif

    @if ($parentCommentId === null)
        <div
            @classes([
                'mt-4' => true,
                'pl-16' => $parentCommentId === null,
                'pl-24' => isset($replies) && $replies->count() > 0
            ])
        >
            <livewire:clinical-case.create-comment
                :key="$clinicalCaseId"
                :clinical-case-id="$clinicalCaseId"
                :parent-comment-id="$commentId"
            />
        </div>
    @endif
</div>