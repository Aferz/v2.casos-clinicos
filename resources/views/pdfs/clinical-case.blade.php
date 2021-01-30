<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="{{ public_path('css/app.css') }}">
        <title>{{ $clinicalCase->title }}</title>

        <style>
            .page-break {
                page-break-after: always;
            }
        </style>
    </head>
    <body>
        <div class="font-bold text-3xl">
            {{ ucfirst($clinicalCase->{$titleFieldName}) }}
        </div>

        <div class="text-sm text-gray-500 mt-0.5">
            {{ __('Published at') }} {{ str_replace(
                '.', '', ucwords($clinicalCase->created_at->translatedFormat('d M, Y'))
            ) }}
        </div>

        @foreach (fields()->exceptName($titleFieldName) as $field)
            <div class="font-bold mt-10 text-primary-700 text-lg">
                {{ ucfirst(__(str_replace('_', ' ', $field->name()))) }}
            </div>

            <div class="mt-1 space-y-8 text-gray-500">
                {{ $clinicalCase->{$field->name()} }}
            </div>
        @endforeach

        @feature('bibliographies')
            @if ($clinicalCase->bibliographies->count() > 0)
                <div class="mt-10 font-bold text-primary-700 text-lg">
                    {{ __('Bibliographies') }}
                </div>

                <div class="mt-1 space-y-2 text-gray-500">
                    @foreach ($clinicalCase->bibliographies as $bibliography)
                        <p>{{ $bibliography->bibliography }}</p>
                    @endforeach
                </div>
            @endif
        @endfeature

        <div class="page-break"></div>

        @feature('images')
            @if ($clinicalCase->images->count() > 0)
                <div class="mt-10 font-bold text-primary-600 text-lg">
                    {{ __('Images') }}
                </div>

                <div class="mt-2">
                    <div class="w-full">
                        @foreach ($clinicalCase->images as $image)
                            <div class="w-96 mt-8">
                                <img class="w-full" src="{{ storage_path("app/public/" . $image->path) }}">
                            </div>

                            <div class="text-gray-500">{{ $image->description }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endfeature
    </body>
</html>