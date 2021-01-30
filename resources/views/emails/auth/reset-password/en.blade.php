@component('mail::message')
# Hi!
If you have requested the password reset, please, access through the button below and follor the instructions.

If you have not requested the password reset, please ignore this message.

@component('mail::button', compact('url'))
Reset password
@endcomponent

If you cannot see the button, please copy and paste the next address:
{{ $url }}

Thank you,<br>
Technical secretariat
@endcomponent
