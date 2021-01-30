<x-layout.home>
    <div class="w-full min-h-full py-4 lg:py-8">
        @feature('highlight')
            @if ($clinicalCaseHighlighted)
                <div class="grid gap-4 grid-cols-1 md:grid-cols-9 lg:grid-cols-6 lg:pb-6 pb-4">
                    <div class="md:col-start-2 md:col-span-7 lg:col-start-2 lg:col-span-4">
                        <x-clinical-case.card
                            :id="$clinicalCaseHighlighted->id"
                            :show-url="$clinicalCaseHighlighted->showUrl()"
                            :image="$clinicalCaseHighlighted->image"
                            :title="$clinicalCaseHighlighted->title"
                            :body="$clinicalCaseHighlighted->body"
                            :date="$clinicalCaseHighlighted->created_at"
                            :likes-count="$clinicalCaseHighlighted->likes_count"
                            :comments-count="$clinicalCaseHighlighted->comments_count"
                            :user-fullname="$clinicalCaseHighlighted->user->fullname"
                            :user-liked="$clinicalCaseHighlighted->user_liked"
                            :highlighted="true"
                        />
                    </div>
                </div>
            @endif
        @endfeature

        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 lg:gap-6">
            @foreach ($clinicalCases as $clinicalCase)
                <div class="col-span-1">
                    <x-clinical-case.card
                        :id="$clinicalCase->id"
                        :show-url="$clinicalCase->showUrl()"
                        :image="$clinicalCase->image"
                        :title="$clinicalCase->title"
                        :body="$clinicalCase->body"
                        :date="$clinicalCase->created_at"
                        :likes-count="$clinicalCase->likes_count"
                        :comments-count="$clinicalCase->comments_count"
                        :user-fullname="$clinicalCase->user->fullname"
                        :user-liked="$clinicalCase->user_liked"
                    />
                </div>
            @endforeach
        </div>

        <div class="mt-4 lg:mt-8">
            {{ $clinicalCases->links() }}
        </div>
    </div>
</x-layout.home>