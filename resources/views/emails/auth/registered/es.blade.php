@component('mail::message')
# Se ha registrado en la plataforma {{ config('app.name') }}

Puede acceder pulsando el siguiente botón:

@component('mail::button', ['url' => url(config('app.url'))])
Acceder a la plataforma
@endcomponent

Muchas gracias,<br>
SECRETARÍA TÉCNICA
@endcomponent
