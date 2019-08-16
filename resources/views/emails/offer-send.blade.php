@component('mail::message')
Oferta Aceptada


@component('mail::table')
    |Cliente|Credito|Plazo|Monto|Cuota|
    |-------|-------|-----|-----|-----|
    |{{ $oferta->cliente->nombre }}|{{ $oferta->idcredito }}|{{ $oferta->plazo }}|{{ $oferta->monto }}|{{ $oferta->cuota }}|
@endcomponent

No compartas esta información con nadie.


Gracias,<br>
{{ config('app.name') }}
@endcomponent
