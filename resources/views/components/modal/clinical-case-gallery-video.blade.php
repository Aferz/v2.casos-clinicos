@props([
    'src'
])

<x-modal.simple
    x-init="() => {
        $watch('on', (on) => {
            if (on) {
                $refs.video.pause()
                $refs.video.currentTime = 0
            }
        })
    }"
>
    <x-slot name="trigger">
        <div
            class="relative col-span-1 h-56 md:h-40 rounded-md shadow overflow-hidden flex items-center justify-center bg-gray-100 cursor-pointer"
            x-on:click="toggle"
        >
            <video src="{{ $src }}"></video>

            <div class="absolute inset-0"></div>
            <x-icon-play class="absolute text-white h-12 w-12" />
        </div>
    </x-slot>

    <video
        x-ref="video"
        class="max-w-3xl w-full h-auto rounded-md shadow-lg border border-white mx-auto focus:outline-none"
        src="{{ $src }}"
        controls
    ></video>
</x-modal.modal>