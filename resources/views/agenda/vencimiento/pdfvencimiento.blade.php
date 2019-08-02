<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>PDF</title>
		 <link rel="stylesheet" href="assets/css/pdf.css" media="all" />
	</head>
	<body>
		<header>
				<figure>
				<img class="logo" src="assets/img/logo.png" alt="">
			</figure>
		</header>
    <main>
			<h2  class="clearfix"><small><span>{{$date}}</span></small> CREDITOS QUE TERMINAN SU PLAZO</h2>
			<div class="details">
				@if (isset($vendedor))<h3  class="clearfix"><small><span>=>
					{{$vendedor->sucursal}}</span></small>  {{$vendedor->idPerfil}}  =>  {{$vendedor->nombre}}  </h3>
					@endif
			</div>
      <table>
				<caption class="text-center">Créditos que termina su plazo en el Mes</caption>
			<thead>
          <tr>
          <th>Crédito</th>
          <th>Nombre del Cliente</th>
          <th>Fecha Fin</th>
          <th>Max Atraso</th>
          <th>Monto credito</th>
          <th>Dom. Colonia</th>
          <th>Celular</th>
          <th>¿Renueva?</th>
          <th>$ que renovará</th>
				</tr>
			</thead>
			<tbody>
        @foreach ($vencimientos as $vencimiento)
        <tr>
          <td class="text-center">{{$vencimiento->idCredito}}</td>
          <td>{{$vencimiento->nomCliente}}</td>
          <td class="text-center">{{date_format(date_create($vencimiento->fechaFin),'d/m/Y')}}</td>
          <td class="text-center">{{$vencimiento->maxDiasAtraso}}</td>
          <td class="text-rigth">{{'$ '.number_format($vencimiento->montoInicial,2)}}</td>
          <td>{{$vencimiento->colonia}}</td>
          <td>{{$vencimiento->telefonoCelular}}</td>
          <td class="text-center">{{$vencimiento->renueva}}</td>
          @if ($vencimiento->montoRenovacion<>0)
						<td class="text-rigth">{{'$ '.number_format($vencimiento->montoRenovacion,2)}}</td>
						@else
							<td class="text-rigth"></td>
					@endif
        </tr>
        @endforeach
			</tbody>
      </table>
    </main>
    <footer>
      ALFIN-Servicios Financieros
    </footer>
	</body>
</html>
