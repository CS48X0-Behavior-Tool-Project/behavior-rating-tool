@component('mail::message')
# Introduction

Hello, {{$mail_data['name']}}!

Welcome to the Behavioural Rating Tool!
Please follow the link to complete your registration:

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
