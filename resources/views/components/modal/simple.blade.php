<span
    class="w-full"
    x-data="{
        on: false,
        toggle () { this.on = !this.on }
    }"
    x-on:keydown.escape.prevent="toggle"
    {{ $attributes }}
>
    {{ $trigger }}

    <div
        style="display: none"
        class="z-10 fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
        x-show="on"
    >
        <div
            class="fixed inset-0 transition-opacity"
            x-show="on"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div
                class="absolute inset-0 bg-gray-500 opacity-75"
                x-on:click="on = false"
            ></div>
        </div>

        <div
            class="transform transition-all"
            x-show="on"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <div>
                <div class="flex justify-end mb-2">
                    <button
                        type="button"
                        class="rounded-full focus:bg-gray-700 focus:outline-none"
                        x-on:click="on = false"
                    >
                        <x-icon-close class="h-8 w-8 text-white" />
                    </button>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</span>