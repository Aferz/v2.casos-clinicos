@component('mail::message')
# ¡Hola!
Si ha solicitado el recordatorio de contraseña, por favor, acceda mediante
el botón inferior y siga las instrucciones.

Si no ha solicitado el recordatorio, por favor, ignore este mensaje.

@component('mail::button', ['url' => $url])
Restablecer contraseña
@endcomponent

Si no puede ver el botón de acceso, por favor, copie y pegue la siguiente dirección:
{{ $url }}

Muchas gracias,<br>
SECRETARÍA TÉCNICA
@endcomponent
