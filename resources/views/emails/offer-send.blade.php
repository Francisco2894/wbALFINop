<style>
    table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    margin-top: 20px;
    }

    table th,
    table td {
    text-align: center;
    }

    table th {
    color: #263238;
    border-bottom: 1px solid #009688;
    border-top: 1px solid #009688;
    }
    table th.resum {
    color: #263238;
    border-top: 1px solid #009688;
    border-bottom: 0px solid #009688;
    }
    table caption {
    padding: 4px 4px 4px 4px;
    color: #263238;
    }

    table .service,
    table .desc {
    text-align: left;
    }

    table td {
    text-align: center;
    border-bottom: 0.5px solid #ECEFF1;
    font-size: 0.9em;
    color: #5D6975;
    }

    table td.service,
    table td.desc {
    vertical-align: top;
    }

    table td.unit,
    table td.qty,
    table td.total {
    font-size: 1.2em;
    }

    table td.text-center {
    text-align: center;
    }
    table td.text-rigth {
    text-align: right;
    }

    table td.sub {
    border-top: 1px solid #C1CED9;
    }

    table td.grand {
    border-top: 1px solid #5D6975;
    }

    table tr:nth-child(2n-1) td {
    background: #FAFAFA;
    }

    table tr:last-child td {
    background: #FFFFFF;
    }
    table tr.danger td{
    background: #FFEBEE;
    }
    table tr.resum td{
    border-bottom: 0.5px solid #009688;
    border-right: 0.5px solid #009688;
    border-left: 0.5px solid #009688;
    }
</style>

@component('mail::message')
<h2 style="text-align: center; background-color: #428bca;">Precalificación de refinanciamiento</h2>

<table>
    <thead>
        <tr><th colspan="6" style="background-color: #5bc0de;"><h3 style="text-align: center;">Condiciones de Credito Anterior</h3></th></tr>
        <tr>
            <th>Producto</th>
            <th>Plazo</th>
            <th>Frecuencia</th>
            <th>Monto</th>
            <th>Amortizaci&oacute;n</th>
            <th>Saldo Capital</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $credito->producto }}</td>
            <td>{{ $credito->plazo }}</td>
            <td>{{ $credito->frecuenciaPago }}</td>
            <td>${{ number_format($credito->montoInicial,2) }}</td>
            <td>${{ number_format($credito->devengo->cuota,2) }}</td>
            <td>${{ number_format($credito->devengo->saldo,2) }}</td>
        </tr>
    </tbody>
</table>
<br>
<br>
<table>
    <thead>
        <tr>
            <th colspan="6" style="background-color: #5bc0de;"><h3 style="text-align: center;">Oferta Aceptada (Producto oferta)</h3></th>
        </tr>
        <tr>
            <th>Incremento</th>
            <th>Plazo</th>
            <th>Frecuencia</th>
            <th>Monto</th>
            <th>Amortizaci&oacute;n</th>
            <th>Saldo Capital</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $oferta->monto==$credito->montoInicial?'0%':'30%' }}</td>
            <td>{{ $oferta->plazo }}</td>
            <td>{{ $oferta->frecuencia==1?'Mensual':'' }}</td>
            <td>${{ number_format($oferta->monto,2) }}</td>
            <td>${{ number_format($oferta->cuota,2) }}</td>
            <td>{{ date('d-m-Y', strtotime($oferta->fechai)) }} - {{ date('d-m-Y', strtotime($oferta->fechaf)) }}</td>
        </tr>
    </tbody>
</table>
<br>
No compartas esta información con nadie.


Gracias,<br>
{{ config('app.name') }}
@endcomponent
