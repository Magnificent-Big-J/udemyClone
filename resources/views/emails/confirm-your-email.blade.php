@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => route('confirm-email'). '?token=' . $user->confirm_token])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
