<div
    class="relative"
    x-data="{
        on: false,
        toggle () { this.on = !this.on }
    }"
    x-on:click.away="on = false"
>
    <div>
        {{ $trigger }}
    </div>

    <div
        style="display: none;"
        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-10"
        x-show="on"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
    >
        {{ $slot }}
    </div>
</div>