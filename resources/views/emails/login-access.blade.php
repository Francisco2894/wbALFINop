@component('mail::message')
Credenciales de Acceso

Para acceder a tu cuenta ingresa con tus credenciales de Acceso:

@component('mail::table')
    |Usuario|Correo|Contraseña|
    |-------|------|----------|
    |{{ $usuario->name }}|{{ $usuario->email }}|{{ $password }}|
@endcomponent

No compartas esta información con nadie.


Gracias,<br>
{{ config('app.name') }}
@endcomponent 
