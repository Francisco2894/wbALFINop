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
                                <th class="text-center table-success" colspan="2">OTROS INGRESOS (mensual)</th>
                            </tr>
                        </thead>
                        <tbody >
                            <tr class="form-group">
                                <td>OTRO NEGOCIO</td>
                                <td><strong>${{$otrosIngresos->otro_negocio}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>EMPLEO</td>
                                <td><strong>${{$otrosIngresos->empleo}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>CÓNYUGE</td>
                                <td><strong>${{$otrosIngresos->conyuge}}</strong></td>
                            </tr>
                            <tr>
                                <td>TOTAL</td>
                                <td><strong>${{$otrosIngresos->conyuge + $otrosIngresos->empleo + $otrosIngresos->otro_negocio}}</strong></td>
                            </tr>
                            <tr>
                                <td>DISPONIBLE DE OTROS INGRESOS (30%)</td>
                                <td><strong>${{$totalOtrosIngresos*0.3}}</strong></td>
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
                            <tr class="form-group">
                                <td>MAQUINARIA, EQUIPO, HERRAMIENTAS</td>
                                <td><strong>${{$activos->maquinaria}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>LOCAL</td>
                                <td><strong>${{$activos->local}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>AUTO</td>
                                <td><strong>${{$activos->auto}}</strong></td>
                            </tr>
                            <tr>
                                <td>TOTAL ACTIVOS FIJOS</td>
                                <td><strong>${{$totalActivoFijo}}</strong></td>
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
                                <td><strong>${{$inventario}}</strong></td>
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
                                <td><strong>${{$totalActivoFijo}}</strong></td>
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
                                <td><strong>${{$ventasMensuales}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>(-) COSTO DE VENTAS (B)</td>
                                <td><strong>${{$compraMensuales}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>(=) UTILIDAD BRUTA</td>
                                <td><strong>${{$utilidadBruta}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>(-) GASTOS DE OPERACIÓN ( C )</td>
                                <td><strong>${{$operacion}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>(=) UTILIDAD NETA</td>
                                <td><strong>${{$utilidadNeta}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>(+) OTROS INGRESOS (D)</td>
                                <td><strong>${{$porcentajeOtrosIngresos}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>(-) GASTOS FAMILIARES ( E )</td>
                                <td><strong>${{$familiares}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>(=) DISPONIBLE</td>
                                <td><strong>${{$disponible}}</strong></td>
                            </tr>
                            <tr class="form-group">
                                <td>($) CAPACIDAD MÁXIMA DE PAGO</td>
                                <td><strong>${{$capacidadPago}}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>		
    </div> 
</div>

<div class="col-md-4 col-md-offset-4">
    <a href="{{ url("$urlanterior") }}" class="btn btn-block btn-primary text-center">Regresar</a>
</div>
@endsection