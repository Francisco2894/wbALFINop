<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>PDF</title>
		<link rel="stylesheet" href="assets/css/pdf.css" media="all" />
		<style>
			.text-justify{
				text-align: justify;
			 }
			 .text-right{
				 text-align: right;
			 }
		</style>
	</head>
	<body>
		<header>
				<figure>
				<img class="logo" src="assets/img/logo.png" alt="">
			</figure>
		</header>
    <main>
		<h2 class="clearfix">An&aacute;lisis de Solvencia Crediticia</h2>
		<div class="details">
			SUCURSAL: {{ $sucursal->sucursal }}
		</div>		
		<table style="width: 98%;">
			<tbody>
				<tr>
					<td style="vertical-align:top;">
						<table>
							<thead>
								<tr><th>ANTECEDENTES</th></tr>
							</thead>
							<tbody>
								<tr>
									<td class="text-justify">
										<strong>GIRO:</strong><br><br>
										{{ $actividad->giro }}
									</td>
				
								</tr>
								<tr>
									<td class="text-justify">
										<strong>OBJETIVO DEL PR&Eacute;STAMO:</strong><br><br>
										{{ $actividad->destinoprestamo }}
									</td>
								</tr>
								<tr>
									<td class="text-justify">
										<strong>¿C&oacute;mo inici&oacute;con su negocio?</strong><br><br>
										{{ $actividad->comoinicio }}
									</td>
								</tr>
								<tr>
									<td class="text-justify">
										<strong>Describe brevemente el proceso de producci&oacute;n, venta o servicio:</strong><br><br>
										{{ $actividad->desc_negocio }}
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="vertical-align:top;">
						<table>
							<head>
								<tr><th colspan="6">Inventario</th></tr>
								<tr>
									<th>Producto</th>
									<th>Cantidad</th>
									<th>Precio Compra (PC) $</th>
									<th>Precio Venta (PV) $</th>
									<th>Total (C X PC) $</th>
									<th>Margen de Ganancias <br> (MG)=(PV-PC)/PV</th>
								</tr>
							</head>
							<tbody>
								@foreach ($productos as $producto)
								<tr>
									<td class="text-right">{{ $producto->producto }}</td>
									<td class="text-right">{{ $producto->cantidad }}</td>
									<td class="text-right">{{ number_format($producto->precio_compra,2) }}</td>
									<td class="text-right">{{ number_format($producto->precio_venta,2) }}</td>
									<td class="text-right">{{ number_format($producto->precio_compra * $producto->cantidad,2) }}</td>
									<td class="text-right">{{ round((($producto->precio_venta-$producto->precio_compra)/$producto->precio_compra)*100) }}%</td>
								</tr>
								@endforeach
								<tr>
									<td class="text-right"><strong>Total</strong></td>
									<td class="text-right" colspan="4"><strong>${{ number_format($totalp,2) }}</strong></td>
									<td class="text-right"><strong>{{ $totalpv }}%</strong></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table style="width: 98%;">
			<tbody>
				<td style="vertical-align:top;">
					<table>
						<thead>
							<tr><th colspan="4">Transacciones</th></tr>
							<tr>
								<th colspan="2">CÁLCULO COMPRAS</th>
								<th colspan="2">CÁLCULO VENTAS</th>
							</tr>
						</thead>
						<tbody>
							@for ($i = 0; $i < 7; $i++)
								<tr>
									<td class="text-center" class="text-right">{{ $transacionesCompra[$i]->lugar_compra }}</td>
									<td class="text-right" class="text-right">${{ number_format($transacionesCompra[$i]->monto,2) }}</td>
									<td class="text-center" class="text-right">{{ $transacionesVenta[$i]->lugar_compra }}</td>
									<td class="text-right" class="text-right">${{ number_format($transacionesVenta[$i]->monto,2) }}</td>
								</tr>
							@endfor
							<tr style="font-weight: 900;" class="text-right">
								<td>Total Semanal</td>
								<td colspan="" class="text-right">${{ number_format($totalc,2) }}</td>
								<td colspan="2" class="text-right">${{ number_format($totalv,2) }}</td>
							</tr>
							<tr style="font-weight: 900;" class="text-right">
								<td>Total Mensual</td>
								<td colspan="" class="text-right">${{ number_format($totalc*4,2) }}</td>
								<td colspan="2" class="text-right">${{ number_format($totalv*4,2) }}</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td>
					<table >
						<thead>
							<tr><th colspan="4">Gastos</th></tr>
							<tr>
								<th colspan="2">GASTOS DE OPERACIÓN (mensual)</th>
								<th colspan="2">GASTOS FAMILIARES (mensual)</th>
							</tr>
						</thead>
						<tbody>
							@for ($i = 0; $i < 11; $i++)
								<tr>
									<td class="text-right" class="text-right">{{ $gastosOperacion[$i]->catgasto->descripcion }}</td>
									<td class="text-right" class="text-right">${{ number_format($gastosOperacion[$i]->monto,2) }}</td>
									<td class="text-right" class="text-right">{{ $gastosFamiliares[$i]->catgasto->descripcion }}</td>
									<td class="text-right" class="text-right">${{ number_format($gastosFamiliares[$i]->monto,2) }}</td>
								</tr>
							@endfor
							<tr style="font-weight: 900;" class="text-right">
								<td>Total</td>
								<td colspan="" class="text-right">${{ number_format($totalo,2) }}</td>
								<td colspan="2" class="text-right">${{ number_format($totalf,2) }}</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tbody>
		</table>
		<table>
			<thead>
				<tr><th colspan="4">Otros Ingresos</th></tr>
				<tr>
					<th colspan="2">OTROS INGRESOS</th>
					<th colspan="2">ACTIVOS FIJOS DEL NEGOCIO</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-right" class="text-right">OTRO NEGOCIO</td>
					<td class="text-right" class="text-right">${{ number_format($otrosIngresos->otro_negocio,2) }}</td>
					<td class="text-right" class="text-right">MAQUINARIA, EQUIPO, HERRAMIENTAS</td>
					<td class="text-right" class="text-right">${{ number_format($activos->maquinaria,2) }}</td>
				</tr>
				<tr>
					<td class="text-right" class="text-right">EMPLEO</td>
					<td class="text-right" class="text-right">${{ number_format($otrosIngresos->empleo,2) }}</td>
					<td class="text-right" class="text-right">LOCAL</td>
					<td class="text-right" class="text-right">${{ number_format($activos->local,2) }}</td>
				</tr>
				<tr>
					<td class="text-right" class="text-right">CÓNYUGE</td>
					<td class="text-right" class="text-right">${{ number_format($otrosIngresos->conyuge,2) }}</td>
					<td class="text-right" class="text-right">AUTO</td>
					<td class="text-right" class="text-right">${{ number_format($activos->auto,2) }}</td>
				</tr>
				<tr style="font-weight: 900;" class="text-right">
					<td>Total</td>
					<td colspan="" class="text-right">${{ number_format($totaloi,2) }}</td>
					<td colspan="2" class="text-right">${{ number_format($totala,2) }}</td>
				</tr>
				<tr style="font-weight: 900;" class="text-right">
					<td>DISPONIBLE DE OTROS INGRESOS (30%)</td>
					<td colspan="" class="text-right">${{ number_format(round($totaloi*0.3),2) }}</td>
				</tr>
			</tbody>
		</table>

		<table style="position: absolute; bottom: 10%;" class="text-center">
			<tbody>
				<tr>
					<td class="text-center">
						_____________________________________<br>
						{{ $cliente->nombre }}
					</td>
				</tr>
			</tbody>
		</table>
			{{-- <h2  class="clearfix"><small><span>{{$date}}</span></small> AGENDA DE ACTIVIDADES DEL GESTOR</h2>
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
			</table> --}}

    </main>
    <footer>
      ALFIN-Servicios Financieros
    </footer>
	</body>
</html>
