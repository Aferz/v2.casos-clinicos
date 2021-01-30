@props([
    'key'
])

{!! __($key, collect($__laravel_slots)->filter(fn ($slot, $key) => $key !== '__default')->toArray()) !!}