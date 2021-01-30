@props([
    'tabs' => [],
    'value' => null,
])

<div {{ $attributes }}>
    <script type="text/javascript">
        var tabs = @json($tabs)
    </script>

    <div class="flex md:hidden items-center space-x-4">
        <x-icon-filter-solid class="h-7 w-7 text-gray-600" />

        <x-input.select
            class="h-10"
            :options="$tabs"
            :value="$value"
            onchange="window.location.href = tabs[this.value].href"
        />
    </div>

    <div class="hidden md:block">
        <nav class="flex space-x-4">
            @foreach ($tabs as $tab)
                <x-tabs.tab
                    :text="$tab['text']"
                    :href="$tab['href']"
                    :selected="$tab['value'] === $value"
                />
            @endforeach
        </nav>
    </div>
</div>