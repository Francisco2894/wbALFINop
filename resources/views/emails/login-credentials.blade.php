@component('mail::message')
Contraseña Restablecida

Para acceder a tu cuenta ingresa con tu nueva contraseña:

@component('mail::table')
    |Usuario|Correo|Contraseña|
    |-------|------|----------|
    |{{ $usuario->name }}|{{ $usuario->email }}|{{ $password }}|
@endcomponent

No compartas esta información con nadie


Gracias,<br>
{{ config('app.name') }}
@endcomponent
