<div class="col-span-1">
    <div class="p-8 bg-white rounded-md border">
        <div class="font-medium text-primary-600 text-3xl">
            {{ ucfirst($clinicalCase->{$titleFieldName}) }}
        </div>

        <div class="text-sm text-gray-500 mt-0.5">
            {{ __('Published at') }} {{ str_replace(
                '.', '', ucwords($clinicalCase->created_at->translatedFormat('d M, Y'))
            ) }}
        </div>

        @php
            $except = array_filter([
                'title',
                auth()->user()->isCoordinator() ? $authorFieldName : null
            ]);
        @endphp

        @foreach (fields()->exceptName($except) as $field)
            <div class="mt-10 font-medium text-primary-600 text-lg">
                {{ ucfirst(__(str_replace('_', ' ', $field->name()))) }}
            </div>

            <div class="mt-1 space-y-8 text-gray-700">
                @if ($field->name() === 'speciality')
                    {{ __(\App\Models\ClinicalCaseSpeciality::find($clinicalCase->{$field->name()})->name) }}
                @elseif ($field instanceof \App\Services\Fields\BooleanField)
                    {{ $clinicalCase->{$field->name()} === 1 ? __('Yes') : __('No') }}
                @else
                    {{ $clinicalCase->{$field->name()} }}
                @endif
            </div>
        @endforeach

        @feature('bibliographies')
            @if ($clinicalCase->bibliographies->count() > 0)
                <div class="mt-10 font-medium text-primary-600 text-lg">
                    {{ __('Bibliographies') }}
                </div>

                <div class="mt-1 space-y-2 text-gray-700">
                    @foreach ($clinicalCase->bibliographies as $bibliography)
                        <p>{{ $bibliography->bibliography }}</p>
                    @endforeach
                </div>
            @endif
        @endfeature

        @feature('images')
            @if ($clinicalCase->images->count() > 0)
                <div class="mt-10 font-medium text-primary-600 text-lg">
                    {{ __('Images') }}
                </div>

                <div class="mt-1 space-y-2 text-gray-700">
                    <div class="grid grid-cols-1 gap-4 w-full">
                        @foreach ($clinicalCase->images as $image)
                        <x-modal.clinical-case-gallery-image
                            :src="asset($image->path)"
                            :description="$image->description"
                        />
                        @endforeach
                    </div>
                </div>
            @endif
        @endfeature

        @if ($clinicalCase->isPublished())
            @feature(['likes', 'share'])
                <hr class="my-8 mx-auto w-full" />

                <div class="flex justify-between">
                    @feature('likes')
                        <div>
                            <livewire:clinical-case.button-like
                                :clinical-case-id="$clinicalCase->id"
                                :liked="$clinicalCase->user_liked"
                                :count="$clinicalCase->likes_count"
                            />
                        </div>
                    @endfeature

                    @feature('share')
                        <div class="flex items-end">
                            <x-icon-share
                                data-test-name="share-icon"
                                class="h-7 w-7 text-gray-500 cursor-pointer"
                                style="padding-top: 0.1rem;"
                            />
                        </div>
                    @endfeature
                </div>
            @endfeature
        @endif
    </div>
</div>