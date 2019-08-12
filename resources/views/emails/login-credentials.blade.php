@component('mail::message')
# Introduction

The body of your message.

@component('mail::table')
    |Usuario|Password|
    |-------|--------|
    |{{ $usuario->name }}|{{ $password }}|
@endcomponent

@component('mail::button', ['url' => 'login'])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
