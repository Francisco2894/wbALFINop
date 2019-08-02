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
			<h2  class="clearfix"><small><span>{{$date}}</span></small> AGENDA DE ACTIVIDADES DEL GESTOR</h2>
			<div class="details">
			@if (isset($vendedor))<h3  class="clearfix"><small><span>=>
				{{$vendedor->sucursal}}</span></small>  {{$vendedor->idPerfil}}  =>  {{$vendedor->nombre}}  </h3>
				@endif
			</div>
			<!--Solo se imprime una vez -->
			<table>
		 <thead>
			 <tr>
			 <th class="resum">Total Clientes</th>
			 <th class="resum">Saldo Cartera</th>
			 <th class="resum">Al corriente</th>
			 <th class="resum">Saldo al Corriente</th>
			 <th class="resum">En atraso</th>
			 <th class="resum">saldo en Riesgo</th>
			 <th class="resum">Capital Vigente</th>
			 <th class="resum">Capital Vencido</th>
			 <th class="resum">Normalidad</th>
			 </tr>
		 </thead>
		 <tbody>
			 @foreach ($resumen as $res)
					 <tr class="resum">
						 <td class="text-center">{{$res->cuenta}}</td>
						 <td class="text-center">{{'$ '.number_format($res->saldo,2)}}</td>
						 <td class="text-center">{{$res->corriente}}</td>
						 <td class="text-center">{{'$ '.number_format($res->saldocorriente,2)}}</td>
						 <td class="text-center">{{$res->riesgo}}</td>
						 <td class="text-center">{{'$ '.number_format($res->saldoriesgo,2)}}</td>
						 <td class="text-center">{{'$ '.number_format($res->capitalVigente,2)}}</td>
						 <td class="text-center">{{'$ '.number_format($res->capitalVencido,2)}}</td>
						 <td class="text-center">{{number_format($res->normalidad,2).' %'}}</td>
					 </tr>
			 @endforeach
		 </tbody>
			</table>
      <table>
				<caption class="text-center">Créditos que pagan en los sig. 3 días</caption>
			<thead>
				<tr>
				<th>Crédito</th>
				<th>Nombre del Cliente</th>
				<th>ProxDevengo</th>
				<th>Cuota</th>
				<th>Saldo a pagar</th>
				<th>Dom. Colonia</th>
				<th>Celular</th>
				<th>F Acuerdo</th>
				<th>Acuerdo</th>
				<th>Sucursal</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($devengos as $devengo)
					@if ($devengo->estatus > 0)
						<tr>
							<td class="text-center">{{$devengo->idCredito}}</td>
							<td>{{$devengo->nomCliente}}</td>
							<td class="text-center">{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
							<td class="text-rigth">{{'$ '.number_format($devengo->cuota,2)}}</td>
							<td class="text-rigth">{{'$ '.number_format($devengo->saldo,2)}}</td>
							<td>{{$devengo->colonia}}</td>
							<td>{{$devengo->telefonoCelular}}</td>
							@if (is_null($devengo->fechaAcuerdo))
		            <td></td>
		          @else
		            <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
		          @endif
							@if ($devengo->montoAcuerdo<>0)
								<td class="text-rigth">{{'$ '.number_format($devengo->montoAcuerdo,2)}}</td>
								@else
									<td class="text-rigth"></td>
							@endif
							 <td>{{$devengo->sucursal}}</td>
						</tr>
					@endif
				@endforeach
				@foreach ($devengosV as $devengo)
					@if ($devengo->estatus > 0)
						<tr class="danger">
							<td class="text-center">{{$devengo->idCredito}}</td>
							<td>{{$devengo->nomCliente}}</td>
							<td class="text-center">{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
							<td class="text-rigth">{{'$ '.number_format($devengo->cuota,2)}}</td>
							<td class="text-rigth">{{'$ '.number_format($devengo->saldo,2)}}</td>
							<td>{{$devengo->colonia}}</td>
							<td>{{$devengo->telefonoCelular}}</td>
							@if (is_null($devengo->fechaAcuerdo))
		            <td></td>
		          @else
		            <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
		          @endif
							@if ($devengo->montoAcuerdo<>0)
								<td class="text-rigth">{{'$ '.number_format($devengo->montoAcuerdo,2)}}</td>
								@else
									<td class="text-rigth"></td>
							@endif
							 <td>{{$devengo->sucursal}}</td>
						</tr>
					@endif
				@endforeach
			</tbody>
      </table>
			<table>
			 <caption class="text-center">Créditos de 1 a 90 días de atraso</caption>
		 <thead>
			 <tr>
			 <th>Crédito</th>
			 <th>Nombre del Cliente</th>
			 <th>FechaDevengo</th>
			 <th>Atraso</th>
			 <th>MontoRiesgo</th>
			 <th>Cuota</th>
			 <th>MontoExigible</th>
			 <th>Dom. Colonia</th>
			 <th>Celular</th>
			 <th>F Acuerdo</th>
			 <th>AcuerdoPago</th>
			 <th>Sucursal</th>
			 </tr>
		 </thead>
		 <tbody>
			 @foreach ($devengos1_90 as $devengo)
				 @if ($devengo->estatus > 0)
					 <tr>
						 <td class="text-center">{{$devengo->idCredito}}</td>
						 <td>{{$devengo->nomCliente}}</td>
						 <td class="text-center">{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
						 <td class="text-center">{{$devengo->diasAtraso}}</td>
						 <td class="text-rigth">{{'$ '.number_format($devengo->montoRiesgo,2)}}</td>
						 <td class="text-rigth">{{'$ '.number_format($devengo->cuota,2)}}</td>
						 <td class="text-rigth">{{'$ '.number_format($devengo->montoExigible,2)}}</td>
						 <td>{{$devengo->colonia}}</td>
						 <td>{{$devengo->telefonoCelular}}</td>
						 @if (is_null($devengo->fechaAcuerdo))
							 <td></td>
						 @else
							 <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
						 @endif
						 @if ($devengo->montoAcuerdo<>0)
							 <td class="text-rigth">{{'$ '.number_format($devengo->montoAcuerdo,2)}}</td>
							 @else
								 <td class="text-rigth"></td>
						 @endif
						  <td>{{$devengo->sucursal}}</td>
					 </tr>
				 @endif
			 @endforeach
			 @foreach ($devengosV1_90 as $devengo)
				 @if ($devengo->estatus > 0)
					 <tr class="danger">
 					<td class="text-center">{{$devengo->idCredito}}</td>
 					<td>{{$devengo->nomCliente}}</td>
 					<td class="text-center">{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
 					<td class="text-center">{{$devengo->diasAtraso}}</td>
 					<td class="text-rigth">{{'$ '.number_format($devengo->montoRiesgo,2)}}</td>
 					<td class="text-rigth">{{'$ '.number_format($devengo->cuota,2)}}</td>
 					<td class="text-rigth">{{'$ '.number_format($devengo->saldoExigible,2)}}</td>
 					<td>{{$devengo->colonia}}</td>
 					<td>{{$devengo->telefonoCelular}}</td>
					@if (is_null($devengo->fechaAcuerdo))
						<td></td>
					@else
						<td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
					@endif
 					@if ($devengo->montoAcuerdo<>0)
 						<td class="text-rigth">{{'$ '.number_format($devengo->montoAcuerdo,2)}}</td>
 						@else
 							<td class="text-rigth"></td>
 					@endif
					 <td>{{$devengo->sucursal}}</td>
 				</tr>
				 @endif
			@endforeach
		 </tbody>
			</table>
			<table>
			 <caption class="text-center">Créditos de más de 90 días de atraso</caption>
		 <thead>
			 <tr>
				 <th>Crédito</th>
  			 <th>Nombre del Cliente</th>
				 <th>Fecha Devengo</th>
				 <th>Atraso</th>
				<th>MontoRiesgo</th>
  			 <th>Cuota</th>
  			 <th>MontoExigible</th>
  			 <th>Dom. Colonia</th>
  			 <th>Celular</th>
				 <th>F Acuerdo</th>
  			 <th>AcuerdoPago</th>
				 <th>Sucursal</th>
			 </tr>
		 </thead>
		 <tbody>
			 @foreach ($devengos_mas90 as $devengo)
				 @if ($devengo->estatus > 0)
					 <tr>
						 <td class="text-center">{{$devengo->idCredito}}</td>
	  				 <td>{{$devengo->nomCliente}}</td>
						 <td class="text-center">{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
	  				 <td class="text-center">{{$devengo->diasAtraso}}</td>
	  				 <td class="text-rigth">{{'$ '.number_format($devengo->montoRiesgo,2)}}</td>
	  				 <td class="text-rigth">{{'$ '.number_format($devengo->cuota,2)}}</td>
	  				 <td class="text-rigth">{{'$ '.number_format($devengo->montoExigible,2)}}</td>
						 <td>{{$devengo->colonia}}</td>
						 <td>{{$devengo->telefonoCelular}}</td>
						 @if (is_null($devengo->fechaAcuerdo))
							 <td></td>
						 @else
							 <td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
						 @endif
						 @if ($devengo->montoAcuerdo<>0)
							 <td class="text-rigth">{{'$ '.number_format($devengo->montoAcuerdo,2)}}</td>
							 @else
								 <td class="text-rigth"></td>
						 @endif
						  <td>{{$devengo->sucursal}}</td>
					 </tr>
				 @endif
			 @endforeach
			 @foreach ($devengosV_mas90 as $devengo)
				 @if ($devengo->estatus > 0)
					 <tr class="danger">
 					<td class="text-center">{{$devengo->idCredito}}</td>
 	 				 <td>{{$devengo->nomCliente}}</td>
 					 <td class="text-center">{{date_format(date_create($devengo->fechaDevengo),'d/m/Y')}}</td>
 	 				 <td class="text-center">{{$devengo->diasAtraso}}</td>
 	 				 <td class="text-rigth">{{'$ '.number_format($devengo->montoRiesgo,2)}}</td>
 	 				 <td class="text-rigth">{{'$ '.number_format($devengo->cuota,2)}}</td>
 	 				 <td class="text-rigth">{{'$ '.number_format($devengo->saldoExigible,2)}}</td>
 						<td>{{$devengo->colonia}}</td>
 						<td>{{$devengo->telefonoCelular}}</td>
						@if (is_null($devengo->fechaAcuerdo))
							<td></td>
						@else
							<td>{{date_format(date_create($devengo->fechaAcuerdo),'d/m/Y')}}</td>
						@endif
 						@if ($devengo->montoAcuerdo<>0)
 							<td class="text-rigth">{{'$ '.number_format($devengo->montoAcuerdo,2)}}</td>
 							@else
 								<td class="text-rigth"></td>
 						@endif
						 <td>{{$devengo->sucursal}}</td>
 					</tr>
				 @endif
			@endforeach
		 </tbody>
			</table>

    </main>
    <footer>
      ALFIN-Servicios Financieros
    </footer>
	</body>
</html>
