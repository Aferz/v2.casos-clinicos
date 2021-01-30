@component('mail::message')
# Clinical case sent:

> {{ $clinicalCase->title }}

@component('mail::button', ['url' => route('directory')])
    Access to platform
@endcomponent

Thank you,<br>
Technical secretariat
@endcomponent
