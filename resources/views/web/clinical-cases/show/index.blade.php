<x-layout.home>
    @if (auth()->user()->isAdmin())
        @include('web.clinical-cases.show.section-admin-toolbar', [
            'clinicalCase' => $clinicalCase
        ])
    @endif

    <div class="w-full min-h-full py-4 grid grid-cols-1 gap-4 lg:grid-cols-clinical-case lg:gap-8 lg:py-8">
        @include('web.clinical-cases.show.section-detail', [
            'clinicalCase' => $clinicalCase,
            'titleFieldName' => $titleFieldName,
            'authorFieldName' => $authorFieldName,
        ])

        @include('web.clinical-cases.show.section-author', [
            'clinicalCase' => $clinicalCase,
        ])

        @if (
            auth()->user()->isCoordinator()
            && !$clinicalCase->isEvaluatedBy(auth()->user())
            && !site()->clinicalCaseEvaluationIsRestricted()
        )
            @include('web.clinical-cases.show.section-coordinator-evaluate', [
                'clinicalCase' => $clinicalCase
            ])
        @endif

        @if (
            auth()->user()->isCoordinator()
            && $clinicalCase->isEvaluatedBy(auth()->user())
        )
            @include('web.clinical-cases.show.section-coordinator-evaluation', [
                'clinicalCase' => $clinicalCase
            ])
        @endif

        @if (
            auth()->user()->isAdmin()
            && $clinicalCase->isEvaluable()
        )
            @include('web.clinical-cases.show.section-admin-evaluations', [
                'clinicalCase' => $clinicalCase
            ])
        @endif

        @if ($clinicalCase->isPublished())
            @feature('comments')
                @include('web.clinical-cases.show.section-comments', [
                    'clinicalCase' => $clinicalCase
                ])
            @endfeature
        @endif
    </div>
</x-layout.home>
