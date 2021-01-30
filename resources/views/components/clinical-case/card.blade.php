@props([
    'id',
    'image' => null,
    'title',
    'body',
    'date',
    'showUrl',
    'likesCount' => null,
    'commentsCount' => null,
    'userFullname',
    'userLiked' => false,
    'highlighted' => false
])

<div
    tabindex="0"
    @classes([
        'rounded-md focus:outline-none overflow-hidden flex flex-col' => true,
        'border' => !$highlighted,
        'flex-col md:flex-row md:h-96 border-2 border-warning-400' => $highlighted
    ])
>
    <a
        href="{{ $showUrl }}"
        tabindex="-1"
        class="focus:outline-none"
    >
        @feature('images')
            <div
                @classes([
                    'relative' => true,
                    'h-48' => !$highlighted,
                    'w-full h-48 md:h-full md:w-56 lg:w-72' => $highlighted
                ])
            >
                @if ($highlighted)
                    <div class="absolute w-32 top-0 left-0 h-48 transform rotate-45 bg-yellow-400"
                        style="top: -95px; left: -95px;"
                    ></div>

                    <x-icon-star-solid class="h-7 w-7 text-white absolute top-0 left-0 ml-1 mt-1" />
                @endif

                <img
                    class="w-full h-full object-cover"
                    src="{{ asset($image) }}"
                >
            </div>
        @endfeature
    </a>

    <div class="h-full flex flex-col bg-white">
        <a
            href="{{ $showUrl }}"
            tabindex="-1"
            class="focus:outline-none flex-1"
        >
            <div class="h-48 bg-white pt-4">
                <div
                    @classes([
                        'text-lg px-4 font-medium text-primary-600 leading-6 line-clamp-2' => true,
                        'md:text-3xl' => $highlighted
                    ])
                >
                    {{ ucfirst($title) }}
                </div>

                <div
                    @classes([
                        'text-sm px-4 font-medium text-gray-600 mt-1' => true,
                        'md:text-lg' => $highlighted
                    ])
                >
                    {{ ucwords($userFullname) }} · <span class="font-normal text-gray-500">
                        {{ str_replace('.', '', ucwords($date->translatedFormat('d M, Y'))) }}
                    </span>
                </div>

                <div
                    @classes([
                        'line-clamp-3 px-4 text-gray-600 overflow-hidden mt-4' => true,
                        'md:text-lg md:line-clamp-5' => $highlighted
                    ])
                >
                    {{ ucfirst($body) }}
                </div>
            </div>
        </a>

        <div class="px-4 pb-4 bg-white flex justify-between">
            <div class="flex">
                @feature('likes')
                    <livewire:clinical-case.button-like
                        :clinical-case-id="$id"
                        :liked="$userLiked"
                        :count="$likesCount"
                    />
                @endfeature

                @feature('comments')
                    <div class="flex items-end">
                        <x-icon-annotation
                            data-test-name="comments-icon"
                            class="h-7 w-7 text-gray-500"
                            style="padding-top: 0.1rem;"
                        />
                        <span class="text-lg text-gray-600 font-medium ml-1 leading-none tabular-nums tracking-tighter">{{ $commentsCount }}</span>
                    </div>
                @endfeature
            </div>

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
    </div>
</div>