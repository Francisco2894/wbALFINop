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
			},
			 .page-break {
				page-break-after: always;
				border: 1px solid white;
			},
			.bg-danger{
				background-color: #d9534f;
				color: white;
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
		<h2 class="clearfix">An&aacute;lisis de Solvencia Econ&oacute;mica</h2>
		<div class="details" style="margin: 0px;">
			<table class="details" style="width: 100%; margin: 0px;">
				<tbody>
					<tr class="fondo">
						<td style="border-bottom: 0.5px solid #009688;" class="fondo" colspan="3">Nombre del Cliente: {{ $cliente->nomCliente }}</td>
						<td style="border-bottom: 0.7px solid wheat;" class="fondo">ID Soliciud:</td>
						<td style="border-bottom: 0.5px solid #009688; width: 17%" class="fondo">Fecha: {{ date('d/m/Y') }}</td>
					</tr>
					<tr class="fondo">
						<td style="border-bottom: 0.7px solid wheat; padding: 0%;" class="fondo" colspan="2">Nombre del Asesor:</td>
						<td style="border-bottom: 0.7px solid wheat;" class="fondo">Zona:</td>
						<td style="border-bottom: 0.5px solid #009688;" class="fondo">Sucursal: {{ $sucursal->sucursal }}</td>
						<td style="border-bottom: 0.5px solid #009688;" class="fondo">Cr&eacute;dito     N ( &nbsp;&nbsp;)      R ( &nbsp;&nbsp; )</td>
					</tr>
					<tr class="fondo">
						<td style="border-bottom: 0.5px solid #009688;" class="fondo">Monto Sol: ${{ number_format($oferta->monto,2) }}</td>
						<td style="border-bottom: 1px solid wheat;" class="fondo">Plazo Sol:</td>
						<td style="border-bottom: 1px solid wheat;" class="fondo">Pago Sol:</td>
						<td style="border-bottom: 1px solid wheat;" class="fondo">Frecuencia:</td>
						<td style="border-bottom: 0.5px solid #009688;" class="fondo">Pago Mensual: ${{ number_format($pagoMensual,2) }}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br>
		<table style="width: 98%; margin: 0px;">
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
									<td class="text-right">${{ number_format($producto->precio_compra,2) }}</td>
									<td class="text-right">${{ number_format($producto->precio_venta,2) }}</td>
									<td class="text-right">${{ number_format($producto->precio_compra * $producto->cantidad,2) }}</td>
									<td class="text-right">{{ round((($producto->precio_venta-$producto->precio_compra)/$producto->precio_venta)*100) }}%</td>
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
		<table style="width: 98%; margin: 0px;">
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
		<table style="margin: 0px; width: 98%;">
			<tbody>
				<td style="vertical-align:top;">
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
								<td class="text-justify">
									OTRO NEGOCIO <br>
									<strong>{{ $otrosIngresos[0]->descripcion }}</strong>
								</td>
								<td class="text-right">${{ number_format($otrosIngresos[0]->monto,2) }}</td>
								<td class="text-justify" style="padding-left: 5%;">
									MAQUINARIA, EQUIPO, HERRAMIENTAS <br>
									<strong>{{ $activos[0]->descripcion }}</strong>
								</td>
								<td class="text-right">${{ number_format($activos[0]->monto,2) }}</td>
							</tr>
							<tr>
								<td class="text-justify">
									EMPLEO <br>
									<strong>{{ $otrosIngresos[1]->descripcion }}</strong>
								</td>
								<td class="text-right">${{ number_format($otrosIngresos[1]->monto,2) }}</td>
								<td class="text-justify" style="padding-left: 5%;">
									LOCAL <br>
									<strong>{{ $activos[1]->descripcion }}</strong>
								</td>
								<td class="text-right">${{ number_format($activos[1]->monto,2) }}</td>
							</tr>
							<tr>
								<td class="text-justify">
									CÓNYUGE <br>
									<strong>{{ $otrosIngresos[2]->descripcion }}</strong>
								</td>
								<td class="text-right">${{ number_format($otrosIngresos[2]->monto,2) }}</td>
								<td class="text-justify" style="padding-left: 5%;">
									VEHICULO <br>
									<strong>{{ $activos[2]->descripcion }}</strong>
								</td>
								<td class="text-right">${{ number_format($activos[2]->monto,2) }}</td>
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
				</td>
				<td style="vertical-align:top;">
					<table>
						<thead>
							<tr><th colspan="4">Garantias</th></tr>
							<tr><th colspan="4">GARANTÍA PRENDARIA</th></tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-justify" colspan="4">
									GARANTIA <br>
									<Strong>{!! $garantia->garantia !!}</Strong>
								</td>
							</tr>
							<tr>
								<td>VALOR ESTIMADO DE LA GARANTÍA:</td>
								<td><strong>${{ number_format($garantia->valorEstimado,2) }}</strong></td>
								<td>COBERTURA DE LA GARANTÍA <br> (EN REFERENCIA AL MONTO DE CRÉDITO OTORGADO):</td>
								<td><strong>{{ round($coberturaGarantia )}}%</strong></td>
							</tr>
						</tbody>
					</table>
				</td>

			</tbody>
		</table>
		<div class="page-break"></div>
		<table>
			<thead></thead>
			<tbody>
				<tr>
					<td style="vertical-align:top;">
						<table>
							<thead>
								<tr>
									<th class="text-center" colspan="4">BALANCE GENERAL</th>
								</tr>
							</thead>
							<tbody >
								<tr class="form-group">
									<td class="bg-danger">CAJA/EFECTIVO/BANCOS</td>
									<td class="bg-danger"></td>
									<td class="bg-danger">PASIVO CORTO PLAZO</td>
									<td class="bg-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								</tr>
								<tr class="form-group">
									<td class="bg-danger">CUENTAS POR COBRAR</td>
									<td class="bg-danger"></td>
									<td class="bg-danger">PASIVO LARGO PLAZO</td>
									<td class="bg-danger">&nbsp;</td>
								</tr>
								<tr class="form-group">
									<td>INVENTARIO</td>
									<td><strong>${{ number_format($totalp,2) }}</strong></td>
									<td class="bg-danger">TOTAL PASIVO</td>
									<td class="bg-danger">&nbsp;</td>
								</tr>
								<tr class="form-group">
									<td class="bg-danger">TOTAL ACTIVO CIRCULANTE</td>
									<td class="bg-danger"></td>
									<td></td>
									<td></td>
								</tr>
								<tr class="form-group">
									<td>TOTAL ACTIVO FIJO</td>
									<td><strong>${{ number_format($totala,2) }}</strong></td>
									<td></td>
									<td>&nbsp;</td>
								</tr>
								<tr class="form-group">
									<td class="bg-danger">TOTAL ACTIVOS</td>
									<td class="bg-danger"></td>
									<td></td>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="vertical-align:top; width: 50%;">
						<table>
							<thead>
								<tr>
									<th colspan="2">ESTADO DE RESULTADOS</th>
								</tr>
							</thead>
							<tbody>
								<tr class="form-group">
									<td>(+) VENTAS (A)</td>
									<td><strong>${{ number_format($ventasMensuales,2) }}</strong></td>
								</tr>
								<tr class="form-group">
									<td>(-) COSTO DE VENTAS (B)</td>
									<td><strong>${{ number_format($compraMensuales,2) }}</strong></td>
								</tr>
								<tr class="form-group">
									<td>(=) UTILIDAD BRUTA</td>
									<td><strong>${{ number_format($utilidadBruta,2) }}</strong></td>
								</tr>
								<tr class="form-group">
									<td>(-) GASTOS DE OPERACIÓN ( C )</td>
									<td><strong>${{ number_format($totalo,2) }}</strong></td>
								</tr>
								<tr class="form-group">
									<td>(=) UTILIDAD NETA</td>
									<td><strong>${{ number_format($utilidadNeta,2) }}</strong></td>
								</tr>
								<tr class="form-group">
									<td>(+) OTROS INGRESOS (D)</td>
									<td><strong>${{ number_format($porcentajeOtrosIngresos,2) }}</strong></td>
								</tr>
								<tr class="form-group">
									<td>(-) GASTOS FAMILIARES ( E )</td>
									<td><strong>${{ number_format($totalf,2) }}</strong></td>
								</tr>
								<tr class="form-group">
									<td>(=) DISPONIBLE</td>
									<td><strong>${{ number_format($disponible,2) }}</strong></td>
								</tr>
								<tr class="form-group">
									<td>($) CAPACIDAD MÁXIMA DE PAGO 30%</td>
									<td><strong>${{ number_format($capacidadPago,2) }}</strong></td>
								</tr>
								<tr class="form-group">
									<td>($) CAPACIDAD MÁXIMA DE PAGO 50%</td>
									<td><strong>${{ number_format($capacidadPago50,2) }}</strong></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		<table style="position: absolute; bottom: 7%; margin: 0px;" class="text-center">
			<tbody>
				<tr>
					<td class="text-center">
						_____________________________________<br>
						{{ $cliente->nomCliente }}
					</td>
				</tr>
			</tbody>
		</table>
    </main>
    <footer>
      ALFIN-Servicios Financieros
    </footer>
	</body>
</html>
