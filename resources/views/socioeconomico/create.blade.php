@extends('layouts.admin')
@section('contenido')
    {!!Form::open(['route'=>'socioeconomico.store','method'=>'POST', 'onsubmit'=>'return checkSubmit();'])!!}
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
                      <input type="text" name="giro" class="form-control" placeholder="" required>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label for="">OBJETIVO DEL PR&Eacute;STAMO:</label>
                        <input type="text" name="destinoprestamo" class="form-control" placeholder="" required>
                    </div>
                </div>
            </div>
            <h5 class="text-center" style="margin: 0px;">ANTECEDENTES</h5>
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label for="">&iquest;C&oacute;mo inici&oacute; con su negocio?</label>
                        <input type="text" name="comoinicio" class="form-control" placeholder="" required>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <label for="">Describe brevemente el proceso de producci&oacute;n, venta o servicio:</label>
                        <input type="text" name="desc_negocio" class="form-control" placeholder="" required>
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
                                <input class="form-control" style="text-transform:uppercase;" type="text" name="" id="producto">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <div class="form-group label-floating">
                                <label class="control-label">Cantidad</label>
                                <input class="form-control" style="text-transform:uppercase;" type="number" name="" id="cantidad">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group label-floating">
                                <label class="control-label">Precio Compra</label>
                                <input class="form-control" style="text-transform:uppercase;" type="number" name="" id="precio_compra">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group label-floating">
                                <label class="control-label">Precio Venta</label>
                                <input class="form-control" style="text-transform:uppercase;" type="number" name="" id="precio_venta">
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
                                <td colspan="2" class="text-right" id="totalInventario">0</td>
                                <td class="text-right" id="totalInventarioPorcentaje">0</td>
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
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="3"> C&Aacute;LCULO COMPRAS</th>
                                </tr>
                                <tr>
                                    <th class="text-center">D&Iacute;A</th>
                                    <th class="text-center">LUGAR DE COMPRA</th>
                                    <th class="text-center">COMPRAS DIARIAS $</th>
                                </tr>
                            </thead>
                            <tbody >
                                <tr class="form-group">
                                    <td><br>Lunes</td>
                                    <td><input type="text" class="form-control" name="lugar1" required></td>
                                    <td><input type="number" class="form-control" name="precio1" id="vlunes1" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br>Martes</td>
                                    <td><input type="text" class="form-control" name="lugar2" required></td>
                                    <td><input type="number" class="form-control" name="precio2" id="vmartes1" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br>Miercoles</td>
                                    <td><input type="text" class="form-control" name="lugar3" required></td>
                                    <td><input type="number" class="form-control" name="precio3" id="vmiercoles1" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br>Jueves</td>
                                    <td><input type="text" class="form-control" name="lugar4" required></td>
                                    <td><input type="number" class="form-control" name="precio4" id="vjueves1" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br>Viernes</td>
                                    <td><input type="text" class="form-control" name="lugar5" required></td>
                                    <td><input type="number" class="form-control" name="precio5" id="vviernes1" value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="table-success">TOTAL SEMANAL</td>
                                    <td class="text-right" id="totalSemanalCompras">0</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="table-success">TOTAL MENSUAL</td>
                                    <td class="text-right" id="totalMensualCompras">0</td>
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
                                    <th class="text-center table-success" colspan="2">C&Aacute;LCULO VENTAS</th>
                                </tr>
                                <tr>
                                    <th class="text-center">LUGAR DE VENTA</th>
                                    <th class="text-center">VENTAS DIARIAS $</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="form-group">
                                    <td><input type="text" class="form-control" name="lugar6" required></td>
                                    <td><input type="number" class="form-control" name="precio6" id="vlunes2" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><input type="text" class="form-control" name="lugar7" required></td>
                                    <td><input type="number" class="form-control" name="precio7" id="vmartes2" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><input type="text" class="form-control" name="lugar8" required></td>
                                    <td><input type="number" class="form-control" name="precio8" id="vmiercoles2" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><input type="text" class="form-control" name="lugar9" required></td>
                                    <td><input type="number" class="form-control" name="precio9" id="vjueves2" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><input type="text" class="form-control" name="lugar10" required></td>
                                    <td><input type="number" class="form-control" name="precio10" id="vviernes2"  value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right" id="totalSemanalVentas">0</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right" id="totalMensualVentas">0</td>
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
                                    <td><input class="form-control" type="number" name="gasto1" value="0" id="op1"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">LUZ</label></td>
                                    <td><input class="form-control" type="number" name="gasto2" value="0" id="op2"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">AGUA</label></td>
                                    <td><input class="form-control" type="number" name="gasto3" value="0" id="op3"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">GAS</label></td>
                                    <td><input class="form-control" type="number" name="gasto4" value="0" id="op4"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">CELULAR</label></td>
                                    <td><input class="form-control" type="number" name="gasto5" value="0" id="op5"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">IMPUESTOS</label></td>
                                    <td><input class="form-control" type="number" name="gasto6" value="0" id="op6"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">TRANSPORTE</label></td>
                                    <td><input class="form-control" type="number" name="gasto7" value="0" id="op7"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">SALARIOS</label></td>
                                    <td><input class="form-control" type="number" name="gasto8" value="0" id="op8"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">MANTENIMIENTO</label></td>
                                    <td><input class="form-control" type="number" name="gasto9" value="0" id="op9"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">PAGOS DEUDAS</label></td>
                                    <td><input class="form-control" type="number" name="gasto10" value="0" id="op10"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">OTROS</label></td>
                                    <td><input class="form-control" type="number" name="gasto11" value="0" id="op11"></td>
                                </tr>
                                <tr>
                                    <td class="table-success">TOTAL (C)</td>
                                    <td id="totalOperacion">0</td>
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
                                    <td><input class="form-control" type="number" name="gasto12" value="0" id="op12"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">LUZ, AGUA</label></td>
                                    <td><input class="form-control" type="number" name="gasto13" value="0" id="op13"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">GAS</label></td>
                                    <td><input class="form-control" type="number" name="gasto14" value="0" id="op14"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">TEL&Eacute;FONO</label></td>
                                    <td><input class="form-control" type="number" name="gasto15" value="0" id="op15"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">RENTA</label></td>
                                    <td><input class="form-control" type="number"" name="gasto16" value="0" id="op16"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">VESTIDO</label></td>
                                    <td><input class="form-control" type="number" name="gasto17" value="0" id="op17"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">SALUD</label></td>
                                    <td><input class="form-control" type="number" name="gasto18" value="0" id="op18"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">ESCUELA</label></td>
                                    <td><input class="form-control" type="number" name="gasto19" value="0" id="op19"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">TRANSPORTE</label></td>
                                    <td><input class="form-control" type="number" name="gasto20" value="0" id="op20"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">PAGOS DEUDAS</label></td>
                                    <td><input class="form-control" type="number" name="gasto21" value="0" id="op21"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">OTROS</label></td>
                                    <td><input class="form-control" type="number" name="gasto22" value="0" id="op22"></td>
                                </tr>
                                <tr>
                                    <td class="table-success">TOTAL (E)</td>
                                    <td id="totalFamiliar">0</td>
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
                                <tr class="form-group">
                                    <td><br> <label for="">OTRO NEGOCIO</label></td>
                                    <td><input type="number" class="form-control" name="otro_negocio" id="ingreso1" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">EMPLEO</label></td>
                                    <td><input type="number" class="form-control" name="empleo" id="ingreso2" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">CÓNYUGE</label></td>
                                    <td><input type="number" class="form-control" name="conyuge" id="ingreso3" value="0"></td>
                                </tr>
                                <tr>
                                    <td>TOTAL</td>
                                    <td class="text-right" id="totalIngresos">0</td>
                                </tr>
                                <tr>
                                    <td>DISPONIBLE DE OTROS INGRESOS (30%)</td>
                                    <td class="text-right" id="totalIngresosPorcentaje">0</td>
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
                                    <td><br> <label for="">MAQUINARIA, EQUIPO, HERRAMIENTAS</label></td>
                                    <td><input type="number" class="form-control" name="maquinaria" id="fijo1" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">LOCAL</label></td>
                                    <td><input type="number" class="form-control" name="local" id="fijo2" value="0"></td>
                                </tr>
                                <tr class="form-group">
                                    <td><br> <label for="">AUTO</label></td>
                                    <td><input type="number" class="form-control" name="auto" id="fijo3" value="0"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right" id="totalFijos">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>			
        </div> 
    </div>
    <div id="inputs">
        
    </div>
    <input type="hidden" value="{{$clienteRenovacion->idcliente}}" name="cliente">
    <div class="col-md-4 col-xs-offset-4">
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
        let s1,s2,s3 = 0;
        let v1,v2,v3,v4,v5 = 0;
        let v6,v7,v8,v9,v10 = 0;
        let total = 0;
        let g1,g2,g3,g4,g5,g6,g7,g8,g9,g10,g11 = 0
        let g12,g13,g14,g15,g16,g17,g18,g19,g20,g21,g22 = 0;
        let a1,a2,a3,a4 = "";
        let formulario = false;

        let i1,i2,i3 = 0;
        let f1,f2,f3 = 0;

        let inventario = new Array();
        let registros = new Object();
        let cont = 0;
        let invReserva = new Array();

		$(document).ready(function(){
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
				if(s3==0){
					$('#seccionCuatro').show();
					s3=1;
				}else{
					$('#seccionCuatro').hide();
					s3=0;
				}
			});

            $("#fijo1, #fijo2, #fijo3").on("keyup", function() {
                f1 = parseFloat($('#fijo1').val());
                f2 = parseFloat($('#fijo2').val());
                f3 = parseFloat($('#fijo3').val());
                total = f1+f2+f3;
                $("#totalFijos").text(total);
                console.log(total);    
            });

            $("#ingreso1, #ingreso2, #ingreso3").on("keyup", function() {
                i1 = parseFloat($('#ingreso1').val());
                i2 = parseFloat($('#ingreso2').val());
                i3 = parseFloat($('#ingreso3').val());
                total = i1+i2+i3;
                $("#totalIngresos").text(total);
                $("#totalIngresosPorcentaje").text(total*0.3);
                console.log(total);    
            });

            $("#vlunes1, #vmartes1, #vmiercoles1, #vjueves1, #vviernes1").on("keyup", function() {
                v1 = parseFloat($('#vlunes1').val());
                v2 = parseFloat($('#vmartes1').val());
                v3 = parseFloat($('#vmiercoles1').val());
                v4 = parseFloat($('#vjueves1').val());
                v5 = parseFloat($('#vviernes1').val());
                total = v4+v2+v3+v1+v5;
                $("#totalSemanalCompras").text(total);
                $("#totalMensualCompras").text(total*4);
                console.log(total);    
            });

            $("#vlunes2, #vmartes2, #vmiercoles2, #vjueves2, #vviernes2").on("keyup", function() {
                v6 = parseFloat($('#vlunes2').val());
                v7 = parseFloat($('#vmartes2').val());
                v8 = parseFloat($('#vmiercoles2').val());
                v9 = parseFloat($('#vjueves2').val());
                v10 = parseFloat($('#vviernes2').val());
                total = v6+v7+v8+v9+v10;
                $("#totalSemanalVentas").text(total);
                $("#totalMensualVentas").text(total*4);
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
                $("#totalOperacion").text(total);
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

                $("#totalFamiliar").text(total);
                console.log(total);    
            });
		});

        function resultadoTotal() {
            var suma = 0;
            var porciento = 0;
            for(i=0;i<inventario.length;i++){
                suma = suma + (inventario[i]['precio_compra'] * inventario[i]['cantidad']);
                porciento = porciento + ((inventario[i]['precio_venta'] - inventario[i]['precio_compra'])/inventario[i]['precio_venta']);
            }
            $('#totalInventario').text(suma);
            $('#totalInventarioPorcentaje').text(porciento+"%");
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
                            "<td> <button class='btn btn-primary btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Modificar?' onclick='modificar("+cont+")' id='actualizar'><i class='material-icons'>cached</i></button>"+
                                "<button class='btn btn-primary btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Eliminar?' onclick='borrar("+cont+")' id='eliminar'><i class='material-icons'>delete</i></button>"+
                            "</td> <td>"+a1+"</td> <td>"+a2+"</td><td>"+a3+"</td><td>"+a4+"</td><td>"+(a2*a3)+"</td><td>"+((a4-a3)/a4)+"</td>"+
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
                            "<td> <button class='btn btn-primary btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Modificar?' onclick='modificar("+cont+")' id='actualizar'><i class='material-icons'>cached</i></button>"+
                                "<button class='btn btn-primary btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Eliminar?' onclick='borrar("+cont+")' id='eliminar'><i class='material-icons'>delete</i></button>"+
                            "</td> <td>"+a1+"</td> <td>"+a2+"</td><td>"+a3+"</td><td>"+a4+"</td><td>"+(a2*a3)+"</td><td>"+((a4-a3)/a4)+"</td>"+
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
                    "<td> <button class='btn btn-primary btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Modificar?' onclick='modificar("+cont+")' id='actualizar'><i class='material-icons'>cached</i></button>"+
                                        "<button class='btn btn-primary btn-simple btn-xs' type='button' name='btnRenovacion' rel='tooltip' title='¿Eliminar?' onclick='borrar("+cont+")' id='eliminar'><i class='material-icons'>delete</i></button>"+
                                    "</td> <td>"+a1+"</td> <td>"+a2+"</td><td>"+a3+"</td><td>"+a4+"</td><td>"+(a2*a3)+"</td><td>"+((a4-a3)/a4)+"%</td>"+
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
                        "<input type='text' name='num_productos' value='"+inventario.length+"'>"
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