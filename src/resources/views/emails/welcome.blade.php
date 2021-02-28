@component('mail::message')
Hello, {{$mail_data['name']}}!

Welcome to the Behavioural Rating Tool!
An account in your name has been created by the administrator.
Please follow the link to complete your registration:
<a href="{{$link}}"> {{$link}} </a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
