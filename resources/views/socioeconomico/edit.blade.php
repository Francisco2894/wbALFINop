@extends('layouts.admin')
@section('contenido')
    
    {!! Form::model($socioeconomico, ['route'=>['socioeconomico.update',$socioeconomico->idCliente],'method'=>'PATCH','onsubmit'=>'return checkSubmit();']) !!}
    {{--,'autocomplete'=>'off','role'=>'search'--}}
    <div class="panel panel-default">
        <div class="panel-heading">
                ACTIVIDAD <button class="btn btn-primary btn-simple" type="button" name="btnRenovacion" rel="tooltip" title="Desplegar" id="btns1"><i class="material-icons">format_line_spacing</i></button>
        </div>
        <div class="panel-body" id="seccionUno">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                      <label for="">GIRO DEL NEGOCIO:</label>
                      <input type="text" name="giro" class="form-control" placeholder="" required value="{{$socioeconomico->actividades[0]->giro}}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label for="">OBJETIVO DEL PR&Eacute;STAMO:</label>
                        <input type="text" name="destinoprestamo" class="form-control" placeholder="" required value="{{$socioeconomico->actividades[0]->destinoprestamo}}">
                    </div>
                </div>
            </div>
            <h5 class="text-center" style="margin: 0px;">ANTECEDENTES</h5>
            <div class="row">
                <div class="col-xs-12 col-sm-2">
                    <div class="form-group">
                        <label for="">Antiguedad del Negocio</label>
                        <input type="number" step="any" name="antiguedad_negocio" class="form-control" placeholder="" required value="{{$socioeconomico->actividades[0]->antiguedad_negocio}}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-5">
                    <div class="form-group">
                        <label for="">&iquest;C&oacute;mo inici&oacute; con su negocio?</label>
                        <input type="text" name="comoinicio" class="form-control" placeholder="" required value="{{$socioeconomico->actividades[0]->comoinicio}}">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-5">
                    <div class="form-group">
                        <label for="">Describe brevemente el proceso de producci&oacute;n, venta o servicio:</label >
                        <input type="text" name="desc_negocio" class="form-control" placeholder="" required value="{{$socioeconomico->actividades[0]->desc_negocio}}">
                    </div>
                </div>
            </div>
            <h5 class="text-center" style="margin: 0px;">INVENTARIO</h5>
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="row form-group">
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group label-floating">
                                <label class="control-label">Producto</label>
                                <input class="form-control"  type="text" name="" id="producto">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="form-group label-floating">
                                <label class="control-label">Cantidad</label>
                                <input class="form-control"  type="number" name="" id="cantidad">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group label-floating">
                                <label class="control-label">Precio Compra</label>
                                <input class="form-control"  type="number" name="" id="precio_compra">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group label-floating">
                                <label class="control-label">Precio Venta</label>
                                <input class="form-control"  type="number" name="" id="precio_venta">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-1">
                            <button type="button" class="btn btn-primary btn-simple btn-xs" onclick="agregar()" name="btnRenovacion" rel="tooltip" title="¿Agregar?"><i class="material-icons">add_circle</i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 11%"></th>
                                <th class="text-center">Productos</th>
                                <th class="text-center">Cantidad (C)</th>
                                <th class="text-center">Precio Compra (PC) $</th>
                                <th class="text-center">Precio Venta (PV) $</th>
                                <th class="text-center">Total (C X PC) $</th>
                                <th class="text-center">Margen de Ganancias <br>(MG)=(PV-PC)/PV</th>
                            </tr>
                        </thead>
                        <tbody id="cont">

                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="4" class="table-success">TOTAL</td>
                                <td colspan="2" class="text-right" style="font-weight: 900;" id="totalInventario">$0.00</td>
                                <td class="text-right" id="totalInventarioPorcentaje" style="font-weight: 900;">0%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div> 
    </div>
    <br>
    <div class="panel panel-default" >
        <div class="panel-heading">
            TRANSACIONES <button class="btn btn-primary btn-simple" type="button" name="btnRenovacion" rel="tooltip" title="Desplegar" id="btns2"><i class="material-icons">format_line_spacing</i></button>
        </div>
        <div class="panel-body" id="seccionDos">
            <div class="row">
                <div class="col-sm-6" style="padding: 0;" id="tabla">
                    <div class="table-responsive">
                        <table style="width: 97%; margin-left: 3%;">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="3"> C&Aacute;LCULO COMPRAS</th>
                                </tr>
                                <tr>
                                    <th class="text-center">LUGAR DE COMPRA</th>
                                    <th class="text-center">COMPRAS DIARIAS $</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Lunes</label>
                                            <input type="text" class="form-control" name="lugar1" value="{{ $transacionesCompra[0]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td style="margin: 0px">
                                        <div class="form-group" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio1" required id="vlunes1" value="{{ $transacionesCompra[0]->monto }}">
                                        </div>                            
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Martes</label>
                                            <input type="text" class="form-control" name="lugar2" value="{{ $transacionesCompra[1]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio2" required id="vmartes1" value="{{ $transacionesCompra[1]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Mi&eacute;rcoles</label>
                                            <input type="text" class="form-control" name="lugar3" value="{{ $transacionesCompra[2]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group" style="margin: 0px">                                                                                
                                            <input type="number" class="form-control" name="precio3" required id="vmiercoles1" value="{{ $transacionesCompra[2]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px; padding: 0px;">
                                            <label class="control-label">Jueves</label>
                                            <input type="text" class="form-control" name="lugar4" value="{{ $transacionesCompra[3]->lugar_compra }}">
                                        </div>
                                    </td>                                    
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio4" required id="vjueves1" value="{{ $transacionesCompra[3]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Viernes</label>
                                            <input type="text" class="form-control" name="lugar5" value="{{ $transacionesCompra[4]->lugar_compra }}">
                                        </div>
                                    </td>                                    
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio5" required id="vviernes1" value="{{ $transacionesCompra[4]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Sabado</label>
                                            <input type="text" class="form-control" name="lugar6" value="{{ $transacionesCompra[5]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td >  
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio6" required id="vsabado1" value="{{ $transacionesCompra[5]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Domingo</label>
                                            <input type="text" class="form-control" name="lugar7" value="{{ $transacionesCompra[6]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio7" required id="vdomingo1" value="{{ $transacionesCompra[6]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-success">TOTAL SEMANAL</td>
                                    <td class="text-right" style="font-weight: 900;" id="totalSemanalCompras">${{ number_format($totalc,2) }}</td>
                                </tr>
                                <tr>
                                    <td class="table-success">TOTAL MENSUAL</td>
                                    <td class="text-right" style="font-weight: 900;" id="totalMensualCompras">${{ number_format($totalc*4,2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-6" style="padding: 0;">
                    <div class="table-responsive">
                        <table style="width: 97%; margin-right: 3%;">
                            <thead>
                                <tr>
                                    <th class="text-center table-success" colspan="2">C&Aacute;LCULO VENTAS</th>
                                </tr>
                                <tr>
                                    <th class="text-center">LUGAR DE VENTA</th>
                                    <th class="text-center">VENTAS DIARIAS $</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Lunes</label>
                                            <input type="text" class="form-control" name="lugar8" value="{{ $transacionesVenta[0]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio8" required id="vlunes2" value="{{ $transacionesVenta[0]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Martes</label>
                                            <input type="text" class="form-control" name="lugar9" value="{{ $transacionesVenta[1]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio9" required id="vmartes2" value="{{ $transacionesVenta[1]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Miercoles</label>
                                            <input type="text" class="form-control" name="lugar10" value="{{ $transacionesVenta[2]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio10" required id="vmiercoles2" value="{{ $transacionesVenta[2]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Jueves</label>
                                            <input type="text" class="form-control" name="lugar11" value="{{ $transacionesVenta[3]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio11" required id="vjueves2" value="{{ $transacionesVenta[3]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Viernes</label>
                                            <input type="text" class="form-control" name="lugar12" value="{{ $transacionesVenta[4]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio12" required id="vviernes2"  value="{{ $transacionesVenta[4]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Sabado</label>
                                            <input type="text" class="form-control" name="lugar13" value="{{ $transacionesVenta[5]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio13" required id="vsabado2"  value="{{ $transacionesVenta[5]->monto }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <label class="control-label">Domingo</label>
                                            <input type="text" class="form-control" name="lugar14" value="{{ $transacionesVenta[6]->lugar_compra }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating" style="margin: 0px">
                                            <input type="number" class="form-control" name="precio14" required id="vdomingo2"  value="{{ $transacionesVenta[6]->monto }}">
                                        </div>
                                    </td>                                                                
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-weight: 900;" class="text-right" id="totalSemanalVentas">${{ number_format($totalv,2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-weight: 900;" class="text-right" id="totalMensualVentas">${{ number_format($totalv*4,2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>			
        </div> 
    </div>
    <br>
    <div class="panel panel-default">
        <div class="panel-heading">
            GASTOS <button class="btn btn-primary btn-simple" type="button" name="btnRenovacion" rel="tooltip" title="Desplegar" id="btns3"><i class="material-icons">format_line_spacing</i></button>
        </div>
        <div class="panel-body" id="seccionTres">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <h5 class="text-center table-success">GASTOS DE OPERACI&Oacute;N (mensual)</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr class="form-group">
                                    <td><br> <label for="">RENTA</label></td>
                                    <td><input class="form-control" type="number" name="gasto1" required value="{{ $gastosOperacion[0]->monto}}" id="op1"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">LUZ</label></td>
                                    <td><input class="form-control" type="number" name="gasto2" required value="{{ $gastosOperacion[1]->monto}}" id="op2"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">AGUA</label></td>
                                    <td><input class="form-control" type="number" name="gasto3" required value="{{ $gastosOperacion[2]->monto}}" id="op3"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">GAS</label></td>
                                    <td><input class="form-control" type="number" name="gasto4" required value="{{ $gastosOperacion[3]->monto}}" id="op4"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">CELULAR</label></td>
                                    <td><input class="form-control" type="number" name="gasto5" required value="{{ $gastosOperacion[4]->monto}}" id="op5"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">IMPUESTOS</label></td>
                                    <td><input class="form-control" type="number" name="gasto6" required value="{{ $gastosOperacion[5]->monto}}" id="op6"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">TRANSPORTE</label></td>
                                    <td><input class="form-control" type="number" name="gasto7" required value="{{ $gastosOperacion[6]->monto}}" id="op7"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">SALARIOS</label></td>
                                    <td><input class="form-control" type="number" name="gasto8" required value="{{ $gastosOperacion[7]->monto}}" id="op8"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">MANTENIMIENTO</label></td>
                                    <td><input class="form-control" type="number" name="gasto9" required value="{{ $gastosOperacion[8]->monto}}" id="op9"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">PAGOS DEUDAS</label></td>
                                    <td><input class="form-control" type="number" name="gasto10" required value="{{ $gastosOperacion[9]->monto}}" id="op10"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">OTROS</label></td>
                                    <td><input class="form-control" type="number" name="gasto11" required value="{{ $gastosOperacion[10]->monto}}" id="op11"></td>
                                </tr>
                                <tr>
                                    <td class="table-success">TOTAL (C)</td>
                                    <td id="totalOperacion" style="font-weight: 900;">${{ number_format($totalo,2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <h5 class="text-center table-success">GASTOS FAMILIARES (mensual)</h5>
                    <div class="table-responsive">
                        <table class="table ">
                            <tbody>
                                <tr class="form-group">
                                    <td><br> <label for="">ALIMENTOS</label></td>
                                    <td><input class="form-control" type="number" name="gasto12" required value="{{ $gastosFamiliares[0]->monto}}" id="op12"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">LUZ, AGUA</label></td>
                                    <td><input class="form-control" type="number" name="gasto13" required value="{{ $gastosFamiliares[1]->monto}}" id="op13"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">GAS</label></td>
                                    <td><input class="form-control" type="number" name="gasto14" required value="{{ $gastosFamiliares[2]->monto}}" id="op14"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">TEL&Eacute;FONO</label></td>
                                    <td><input class="form-control" type="number" name="gasto15" required value="{{ $gastosFamiliares[3]->monto}}" id="op15"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">RENTA</label></td>
                                    <td><input class="form-control" type="number" name="gasto16" required value="{{ $gastosFamiliares[4]->monto}}" id="op16"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">VESTIDO</label></td>
                                    <td><input class="form-control" type="number" name="gasto17" required value="{{ $gastosFamiliares[5]->monto}}" id="op17"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">SALUD</label></td>
                                    <td><input class="form-control" type="number" name="gasto18" required value="{{ $gastosFamiliares[6]->monto}}" id="op18"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">ESCUELA</label></td>
                                    <td><input class="form-control" type="number" name="gasto19" required value="{{ $gastosFamiliares[7]->monto}}0" id="op19"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">TRANSPORTE</label></td>
                                    <td><input class="form-control" type="number" name="gasto20" required value="{{ $gastosFamiliares[8]->monto}}" id="op20"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">PAGOS DEUDAS</label></td>
                                    <td><input class="form-control" type="number" name="gasto21" value="{{ $gastosFamiliares[9]->monto}}" id="op21"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">OTROS</label></td>
                                    <td><input class="form-control" type="number" name="gasto22" value="{{ $gastosFamiliares[10]->monto}}" id="op22"></td>
                                </tr>
                                <tr>
                                    <td class="table-success">TOTAL (E)</td>
                                    <td id="totalFamiliar" style="font-weight: 900;">${{ number_format($totalf,2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>				
        </div> 
    </div>

    <div class="panel panel-default" >
        <div class="panel-heading">
            Otros Ingresos <button class="btn btn-primary btn-simple" type="button" name="btnRenovacion" rel="tooltip" title="Desplegar" id="btns4"><i class="material-icons">format_line_spacing</i></button>
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
                                        <div class="form-group label-floating">
                                            <label class="control-label">OTRO NEGOCIO</label>
                                            <input class="form-control" value="{{ $otrosIngresos[0]->descripcion }}" type="text" name="desci1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">Catidad</label>
                                            <input class="form-control" id="ingreso1" value="{{ $otrosIngresos[0]->monto }}" type="number" name="canti1">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">EMPLEO</label>
                                            <input class="form-control" value="{{ $otrosIngresos[1]->descripcion }}"  type="text" name="desci2">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">Catidad</label>
                                            <input class="form-control" id="ingreso2" value="{{ $otrosIngresos[1]->monto }}" type="number" name="canti2">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">CÓNYUGE</label>
                                            <input class="form-control"  value="{{ $otrosIngresos[2]->descripcion }}" type="text" name="desci3">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">Catidad</label>
                                            <input class="form-control" id="ingreso3" value="{{ $otrosIngresos[2]->monto }}" type="number" name="canti3">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>TOTAL</td>
                                    <td class="text-right" style="font-weight: 900;" id="totalIngresos">${{ number_format($totaloi,2) }}</td>
                                </tr>
                                <tr>
                                    <td>DISPONIBLE DE OTROS INGRESOS (30%)</td>
                                    <td class="text-right" style="font-weight: 900;" id="totalIngresosPorcentaje">${{ number_format($totaloi*0.3,2) }}</td>
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
                                        <div class="form-group label-floating">
                                            <label class="control-label">MAQUINARIA, EQUIPO, HERRAMIENTAS</label>
                                            <input class="form-control" value="{{ $activos[0]->descripcion }}" type="text" name="descf1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">Catidad</label>
                                            <input class="form-control" id="fijo1" value="{{ $activos[0]->monto }}" type="number" value="0" name="cantf1">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">LOCAL</label>
                                            <input class="form-control" value="{{ $activos[1]->descripcion }}" type="text" name="descf2">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">Catidad</label>
                                            <input class="form-control" id="fijo2" value="{{ $activos[1]->monto }}" type="number" value="0" name="cantf2">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">VEHICULO</label>
                                            <input class="form-control" value="{{ $activos[2]->descripcion }}" type="text" name="descf3">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group label-floating">
                                            <label class="control-label">Catidad</label>
                                            <input class="form-control" id="fijo3" value="{{ $activos[2]->monto }}" type="number" value="0" name="cantf3">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-weight: 900;" class="text-right" id="totalFijos">${{ number_format($totala,2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>			
        </div> 
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Garantias <button class="btn btn-primary btn-simple" type="button" name="btnRenovacion" rel="tooltip" title="Desplegar" id="btns5"><i class="material-icons">format_line_spacing</i></button>
        </div>
        <div class="panel-body" id="seccionCinco">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr><th colspan="4" class="text-center">GARANTÍA PRENDARIA</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">
                                <div class="form-group" style="margin: 0%">
                                    <textarea class="form-control" name="garantia" required id="exampleFormControlTextarea1" rows="3">{{ $garantia->garantia }}</textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>VALOR ESTIMADO DE LA GARANTÍA:</td>
                            <td style="width: 200px;">
                                <div class="form-group">
                                    <input type="number" required name="valorEstimado" id="valorEstimado" value="{{ $garantia->valorEstimado }}" class="form-control">
                                </div>
                            </td>
                            <td>COBERTURA DE LA GARANTÍA <br> (EN REFERENCIA AL MONTO DE CRÉDITO ANTERIOR OTORGADO):</td>
                            <td style="width: 200px; font-weight: 900;" id="cobertura">{{ round($totalco*100) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="inputs">
        
    </div>
    <input type="hidden" value="{{ $urlanterior }}" name="url">
    <div class="col-md-4 col-md-offset-4">
        <button type="submit" class="btn btn-block btn-primary text-center" onclick="inputs()">Guardar</button>
    </div>
    {{Form::close()}}
@endsection

@push('styles')
    <style>
        .container-fluid {
            max-width: 95%;
		},
		#seccionUno, #seccionDos, #seccionTres{
			display: none;
		}
    </style>
@endpush
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        let s1,s2,s3,s4,s5 = 0;
        let v1,v2,v3,v4,v5 = 0;
        let v6,v7,v8,v9,v10,v11,v12,v13,v14 = 0;
        let total = 0;
        let g1,g2,g3,g4,g5,g6,g7,g8,g9,g10,g11 = 0
        let g12,g13,g14,g15,g16,g17,g18,g19,g20,g21,g22 = 0;
        let a1,a2,a3,a4 = "";
        let formulario = false;

        let i1,i2,i3 = 0;
        let f1,f2,f3 = 0;
        let valorEstimado,cobertura = 0;
        let montoPrestado = {{ $socioeconomico->montoInicial }}

        let inventario = new Array();
        let registros = new Object();
        let cont = 0;
        let invReserva = new Array();

		$(document).ready(function(){
            inventarioTotal();
			$("#btns1").click(function(){
				if(s1==0){
					$('#seccionUno').show();
					s1=1;
				}else{
					$('#seccionUno').hide();
					s1=0;
				}
			});

			$("#btns2").click(function(){
				if(s2==0){
					$('#seccionDos').show();
					s2=1;
				}else{
					$('#seccionDos').hide();
					s2=0;
				}
			});

			$("#btns3").click(function(){
				if(s3==0){
					$('#seccionTres').show();
					s3=1;
				}else{
					$('#seccionTres').hide();
					s3=0;
				}
			});

            $("#btns4").click(function(){
				if(s4==0){
					$('#seccionCuatro').show();
					s4=1;
				}else{
					$('#seccionCuatro').hide();
					s4=0;
				}
			});

            $("#btns5").click(function(){
				if(s5==0){
					$('#seccionCinco').show();
					s5=1;
				}else{
					$('#seccionCinco').hide();
					s5=0;
				}
			});

            $("#fijo1, #fijo2, #fijo3").on("keyup", function() {
                f1 = parseFloat($('#fijo1').val());
                f2 = parseFloat($('#fijo2').val());
                f3 = parseFloat($('#fijo3').val());
                total = f1+f2+f3;
                $("#totalFijos").text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(total));
                console.log(total);    
            });

            $("#ingreso1, #ingreso2, #ingreso3").on("keyup", function() {
                i1 = parseFloat($('#ingreso1').val());
                i2 = parseFloat($('#ingreso2').val());
                i3 = parseFloat($('#ingreso3').val());
                total = i1+i2+i3;
                $("#totalIngresos").text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(total));
                $("#totalIngresosPorcentaje").text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(total*0.3));
                console.log(total);    
            });

            $("#vlunes1, #vmartes1, #vmiercoles1, #vjueves1, #vviernes1, #vsabado1, #vdomingo1").on("keyup", function() {
                v1 = parseFloat($('#vlunes1').val());
                v2 = parseFloat($('#vmartes1').val());
                v3 = parseFloat($('#vmiercoles1').val());
                v4 = parseFloat($('#vjueves1').val());
                v5 = parseFloat($('#vviernes1').val());
                v6 = parseFloat($('#vsabado1').val());
                v7 = parseFloat($('#vdomingo1').val());
                total = v4+v2+v3+v1+v5+v6+v7;
                $("#totalSemanalCompras").text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(total));
                $("#totalMensualCompras").text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(total*4));
                console.log(total);    
            });

            $("#vlunes2, #vmartes2, #vmiercoles2, #vjueves2, #vviernes2, #vsabado2, #vdomingo2").on("keyup", function() {
                v8 = parseFloat($('#vlunes2').val());
                v9 = parseFloat($('#vmartes2').val());
                v10 = parseFloat($('#vmiercoles2').val());
                v11 = parseFloat($('#vjueves2').val());
                v12 = parseFloat($('#vviernes2').val());
                v13 = parseFloat($('#vsabado2').val());
                v14 = parseFloat($('#vdomingo2').val());

                total = v8+v9+v10+v11+v12+v13+v14;
                $("#totalSemanalVentas").text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(total));
                $("#totalMensualVentas").text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(total*4));
                console.log(total);    
            });

            $("#op1, #op2, #op3, #op4, #op5, #op6, #op7, #op8, #op9, #op10, #op11").on("keyup", function() {
                g1 = parseFloat($('#op1').val());
                g2 = parseFloat($('#op2').val());
                g3 = parseFloat($('#op3').val());
                g4 = parseFloat($('#op4').val());
                g5 = parseFloat($('#op5').val());
                g6 = parseFloat($('#op6').val());
                g7 = parseFloat($('#op7').val());
                g8 = parseFloat($('#op8').val());
                g9 = parseFloat($('#op9').val());
                g10 = parseFloat($('#op10').val());
                g11 = parseFloat($('#op11').val());
                total = g1+g2+g3+g4+g5+g6+g7+g8+g9+g10+g11;
                $("#totalOperacion").text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(total));
                console.log(total);  
            });

            $("#op12, #op13, #op14, #op15, #op16, #op17, #op18, #op19, #op20, #op21, #op22").on("keyup", function() {
                g12 = parseFloat($('#op12').val());
                g13 = parseFloat($('#op13').val());
                g14 = parseFloat($('#op14').val());
                g15 = parseFloat($('#op15').val());
                g16 = parseFloat($('#op16').val());
                g17 = parseFloat($('#op17').val());
                g18 = parseFloat($('#op18').val());
                g19 = parseFloat($('#op19').val());
                g20 = parseFloat($('#op20').val());
                g21 = parseFloat($('#op21').val());
                g22 = parseFloat($('#op22').val());
                total = g12+g13+g14+g15+g16+g17+g18+g19+g20+g21+g22;

                $("#totalFamiliar").text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(total));
                console.log(total);    
            });

            $("#valorEstimado").on("keyup", function() {
                valorEstimado = parseFloat($('#valorEstimado').val())
                total = valorEstimado / (montoPrestado+(montoPrestado*0.3));
                $("#cobertura").text(Math.round((total*100))+'%');
                console.log(total);
            });
		});

        function resultadoTotal() {
            var suma = 0;
            var porciento = 0;
            for(i=0;i<inventario.length;i++){
                suma = suma + (inventario[i]['precio_compra'] * inventario[i]['cantidad']);
                porciento = porciento + (Math.round(((inventario[i]['precio_venta'] - inventario[i]['precio_compra'])/inventario[i]['precio_venta'])*100))/inventario.length;
            }
            $('#totalInventario').text('$'+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(suma));
            $('#totalInventarioPorcentaje').text(porciento+"%");
        }

        function inventarioTotal() {
            $.ajax({
                url     :  "/inventario/{{ $actividad->idact }}",
                type    :  'get',
                dataType:  'json',
                success :   function (response) {
                    if(response.length>0){
                        console.log(response);
                        cont=0;
                        $('#cont').empty();
                        for(i=0;i<response.length;i++){
                            a1 = response[i]['producto'];
                            a2 = response[i]['cantidad'];
                            a3 = response[i]['precio_compra'];
                            a4 = response[i]['precio_venta'];

                            registro = {id:cont,producto:a1,cantidad:a2,precio_compra:a3,precio_venta:a4};
                            inventario.push(registro);
                            

                            $('#cont').append("<tr>"+
                                "<td> <button class='btn btn-default btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Modificar?' onclick='modificar("+cont+")' id='actualizar'><i class='material-icons'>edit</i></button>"+
                                    "<button class='btn btn-danger btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Eliminar?' onclick='borrar("+cont+")' id='eliminar'><i class='material-icons'>delete</i></button>"+
                                "</td> <td>"+a1+"</td> <td>"+a2+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a3)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a4)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a3*a2)+"</td><td>"+Math.round(((a4-a3)/a4)*100)+"%</td>"+
                            "</tr>"
                            );
                            cont = cont + 1;
                        }
                        resultadoTotal();
                    }
                    //$("#miModal").modal("show");
                },
                error   :   function() {
                    alert('error');
                }
                });
        }

        function borrar(id) {
            var opcion = confirm("Click en Aceptar o Cancelar");
            if (opcion == true) {
                $('#cont').empty();
                cont=0;
                let invReserva = new Array();
                
                for(i=0;i<inventario.length;i++){
                    if(id != inventario[i]['id']){
                        a1 = inventario[i]['producto'];
                        a2 = inventario[i]['cantidad'];
                        a3 = inventario[i]['precio_compra'];
                        a4 = inventario[i]['precio_venta'];

                        registro = {id:cont,producto:a1,cantidad:a2,precio_compra:a3,precio_venta:a4};
                        invReserva.push(registro);
                        

                        $('#cont').append("<tr>"+
                            "<td> <button class='btn btn-default btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Modificar?' onclick='modificar("+cont+")' id='actualizar'><i class='material-icons'>edit</i></button>"+
                                "<button class='btn btn-danger btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Eliminar?' onclick='borrar("+cont+")' id='eliminar'><i class='material-icons'>delete</i></button>"+
                                "</td> <td>"+a1+"</td> <td>"+a2+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a3)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a4)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a3*a2)+"</td><td>"+Math.round(((a4-a3)/a4)*100)+"%</td>"+
                        "</tr>"
                        );
                        cont = cont + 1;
                    }
                }
                inventario = invReserva;
                console.log(inventario);
                resultadoTotal();
            }
        }
        
        function modificar(id) {
            //var opcion = confirm("Click en Aceptar o Cancelar");
            //if (opcion == true) {
                $('#cont').empty();
                cont=0;
                let invReserva = new Array();
                
                for(i=0;i<inventario.length;i++){
                    if(id != inventario[i]['id']){
                        a1 = inventario[i]['producto'];
                        a2 = inventario[i]['cantidad'];
                        a3 = inventario[i]['precio_compra'];
                        a4 = inventario[i]['precio_venta'];

                        registro = {id:cont,producto:a1,cantidad:a2,precio_compra:a3,precio_venta:a4};
                        invReserva.push(registro);
                        

                        $('#cont').append("<tr>"+
                            "<td> <button class='btn btn-default btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Modificar?' onclick='modificar("+cont+")' id='actualizar'><i class='material-icons'>edit</i></button>"+
                                "<button class='btn btn-danger btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Eliminar?' onclick='borrar("+cont+")' id='eliminar'><i class='material-icons'>delete</i></button>"+
                                "</td> <td>"+a1+"</td> <td>"+a2+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a3)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a4)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a3*a2)+"</td><td>"+Math.round(((a4-a3)/a4)*100)+"%</td>"+
                        "</tr>"
                        );
                        cont = cont + 1;
                    }
                    if(id==inventario[i]['id']){
                        $('#producto').val(inventario[i]['producto']);
                        $('#cantidad').val(inventario[i]['cantidad']);
                        $('#precio_compra').val(inventario[i]['precio_compra']);
                        $('#precio_venta').val(inventario[i]['precio_venta']);
                    }
                }
                inventario = invReserva;
                console.log(inventario);
                resultadoTotal();
            //}
        }

        function agregar(){
            a1 = $('#producto').val();
            a2 = parseFloat($('#cantidad').val());
            a3 = parseFloat($('#precio_compra').val());
            a4 = parseFloat($('#precio_venta').val());

            if(a1 != '' && a2 > 1 && a3 > 1 && a4 > 1){
                registro = {id:cont,producto:a1,cantidad:a2,precio_compra:a3,precio_venta:a4};
                inventario.push(registro);
                console.log(inventario);

                $('#cont').append("<tr>"+
                    "<td> <button class='btn btn-default btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Modificar?' onclick='modificar("+cont+")' id='actualizar'><i class='material-icons'>edit</i></button>"+
                        "<button class='btn btn-danger btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Eliminar?' onclick='borrar("+cont+")' id='eliminar'><i class='material-icons'>delete</i></button>"+
                    "</td> <td>"+a1+"</td> <td>"+a2+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a3)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a4)+"</td><td>$"+new Intl.NumberFormat("en-IN",{minimumFractionDigits: 2}).format(a3*a2)+"</td><td>"+Math.round(((a4-a3)/a4)*100)+"%</td>"+
                "</tr>"
                );

                $('#producto').val("");
                $('#cantidad').val('');
                $('#precio_compra').val('');
                $('#precio_venta').val('');
                cont = cont+1;
            }
            resultadoTotal();
        }

        function inputs(){
            if (inventario.length > 0) {
                for(i=0;i<inventario.length;i++){
                    $('#inputs').append(
                        "<input type='hidden' name='producto"+i+"' value='"+inventario[i]['producto']+"'>"+
                        "<input type='hidden' name='precio_compra"+i+"' value='"+inventario[i]['precio_compra']+"'>"+
                        "<input type='hidden' name='precio_venta"+i+"' value='"+inventario[i]['precio_venta']+"'>"+
                        "<input type='hidden' name='cantidad"+i+"' value='"+inventario[i]['cantidad']+"'>"
                    );
                }
                $('#inputs').append(
                        "<input type='hidden' name='num_productos' value='"+inventario.length+"'>"
                    );
            }
        }

        function checkSubmit() {
            if(inventario.length > 0){
                if (!formulario) {
                    formulario = true;
                    return true;
                } else {
                    alert("Procesando la Información...");
                    return false;
                }
            }else{
                alert('No agregado productos al Inventario');
                return false;
            }
        }

        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
        }
        document.onkeypress = stopRKey; 
    </script>
@endpush