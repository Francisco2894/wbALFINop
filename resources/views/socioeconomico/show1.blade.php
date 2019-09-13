@extends('layouts.admin')
@section('contenido')
<div class="panel panel-default" >
    <div class="panel-heading">
        Datos <button class="btn btn-primary btn-simple" type="button" name="btnRenovacion" rel="tooltip" title="Información" ><i class="material-icons">info</i></button>
    </div>
    <div class="panel-body" id="seccionCuatro">
        <div class="row">
            <div class="col-sm-6" style="padding: 0;" id="tabla">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center table-success" colspan="2">OTROS INGRESOS</th>
                            </tr>
                        </thead>
                        <tbody >
                            <tr>
                                <td>
                                        <label style="margin: 0" class="control-label">OTRO NEGOCIO</label> <br>
                                        <strong>{{ $otrosIngresos[0]->descripcion }}</strong>
                                </td>
                                <td class="text-right">
                                        <label style="margin: 0" class="control-label">Catidad</label> <br>
                                        <strong>${{ number_format($otrosIngresos[0]->monto,2) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                        <label style="margin: 0" class="control-label">EMPLEO</label> <br>
                                        <strong>{{ $otrosIngresos[1]->descripcion }}</strong>
                                </td>
                                <td class="text-right">
                                        <label style="margin: 0" class="control-label">Catidad</label> <br>
                                        <strong>${{ number_format( $otrosIngresos[1]->monto,2)}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                        <label style="margin: 0" class="control-label">CÓNYUGE</label> <br>
                                        <strong>{{ $otrosIngresos[2]->descripcion }}</strong>
                                </td>
                                <td class="text-right">
                                        <label style="margin: 0" class="control-label">Catidad</label> <br>
                                        <strong>${{ number_format($otrosIngresos[2]->monto,2) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>TOTAL</td>
                                <td class="text-right" style="font-weight: 900;" id="totalIngresos">${{ number_format($totalOtrosIngresos,2) }}</td>
                            </tr>
                            <tr>
                                <td>DISPONIBLE DE OTROS INGRESOS (30%)</td>
                                <td class="text-right" style="font-weight: 900;" id="totalIngresosPorcentaje">${{ number_format($totalOtrosIngresos*0.3,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center table-success" colspan="2">ACTIVOS FIJOS DEL NEGOCIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                        <label style="margin: 0" class="control-label">MAQUINARIA, EQUIPO, HERRAMIENTAS</label> <br>
                                        <strong>{{ $activos[0]->descripcion }}</strong>
                                </td>
                                <td class="text-right">
                                        <label style="margin: 0" class="control-label">Catidad</label> <br>
                                        <strong>${{ number_format($activos[0]->monto,2) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                        <label style="margin: 0" class="control-label">LOCAL</label> <br>
                                        <strong>{{ $activos[1]->descripcion }}</strong>
                                </td>
                                <td class="text-right">
                                        <label style="margin: 0" class="control-label">Catidad</label> <br>
                                        <strong>${{ number_format($activos[1]->monto,2) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                        <label style="margin: 0" class="control-label">VEHICULO</label> <br>
                                        <strong>{{ $activos[2]->descripcion }}</strong>
                                </td>
                                <td class="text-right">
                                        <label style="margin: 0" class="control-label">Catidad</label> <br>
                                        <strong>${{ number_format($activos[2]->monto,2) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right" style="font-weight: 900;" id="totalFijos">${{ number_format($totalActivoFijo,2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-6" style="padding: 0;" id="tabla">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center table-success" colspan="4">BALANCE GENERAL</th>
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
                                <td><strong>${{ number_format($inventario,2) }}</strong></td>
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
                                <td><strong>${{ number_format($totalActivoFijo,2) }}</strong></td>
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
                </div>
            </div>
            <div class="col-sm-6" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center table-success" colspan="2">ESTADO DE RESULTADOS</th>
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
                                <td><strong>${{ number_format($operacion,2) }}</strong></td>
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
                                <td><strong>${{ number_format($familiares,2) }}</strong></td>
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
                </div>
            </div>
        </div>
        <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr><th colspan="4" class="text-center">GARANTÍA PRENDARIA</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">{{ $garantia->garantia }}</td>
                        </tr>
                        <tr>
                            <td>VALOR ESTIMADO DE LA GARANTÍA:</td>
                            <td style="width: 200px; font-weight: 900;">${{ number_format($garantia->valorEstimado,2) }}</td>
                            <td>COBERTURA DE LA GARANTÍA <br> (EN REFERENCIA AL MONTO DE <strong>CRÉDITO ANTERIOR</strong> OTORGADO):</td>
                            <td style="width: 200px; font-weight: 900;" id="cobertura">{{ round($totalco*100) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<div class="col-md-4 col-md-offset-4">
    <a href="{{ url("$urlanterior") }}" class="btn btn-block btn-primary text-center">Regresar</a>
</div>
@endsection
