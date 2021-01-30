@component('mail::message')
# You have been succesfully registered in {{ config('app.name') }}

You can access clicking on the link:

@component('mail::button', ['url' => route('directory')])
Access to platform
@endcomponent

Thank you,<br>
Technical secretariat
@endcomponent
