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
                                <td>${{$otrosIngresos->otro_negocio}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>EMPLEO</td>
                                <td>${{$otrosIngresos->empleo}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>CÓNYUGE</td>
                                <td>${{$otrosIngresos->conyuge}}</td>
                            </tr>
                            <tr>
                                <td>TOTAL</td>
                                <td>${{$otrosIngresos->conyuge + $otrosIngresos->empleo + $otrosIngresos->otro_negocio}}</td>
                            </tr>
                            <tr>
                                <td>DISPONIBLE DE OTROS INGRESOS (30%)</td>
                                <td>${{$totalOtrosIngresos*0.3}}</td>
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
                                <td>${{$activos->maquinaria}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>LOCAL</td>
                                <td>${{$activos->local}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>AUTO</td>
                                <td>${{$activos->auto}}</td>
                            </tr>
                            <tr>
                                <td>TOTAL ACTIVOS FIJOS</td>
                                <td>${{$totalActivoFijo}}</td>
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
                                <td>${{$inventario}}</td>
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
                                <td>${{$totalActivoFijo}}</td>
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
                                <td>${{$ventasMensuales}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>(-) COSTO DE VENTAS (B)</td>
                                <td>${{$compraMensuales}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>(=) UTILIDAD BRUTA</td>
                                <td>${{$utilidadBruta}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>(-) GASTOS DE OPERACIÓN ( C )</td>
                                <td>${{$operacion}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>(=) UTILIDAD NETA</td>
                                <td>${{$utilidadNeta}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>(+) OTROS INGRESOS (D)</td>
                                <td>${{$porcentajeOtrosIngresos}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>(-) GASTOS FAMILIARES ( E )</td>
                                <td>${{$familiares}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>(=) DISPONIBLE</td>
                                <td>${{$disponible}}</td>
                            </tr>
                            <tr class="form-group">
                                <td>($) CAPACIDAD MÁXIMA DE PAGO</td>
                                <td>${{$capacidadPago}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>		
    </div> 
</div>
@endsection