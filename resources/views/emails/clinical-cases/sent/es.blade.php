@component('mail::message')
# Caso clínico enviado

> {{ $clinicalCase->title }}

@component('mail::button', ['url' => route('directory')])
    Acceder a la plataforma
@endcomponent

Muchas gracias,<br>
SECRETARÍA TÉCNICA
@endcomponent
