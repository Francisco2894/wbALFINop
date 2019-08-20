<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use wbALFINop\Dia;
use wbALFINop\TipoTransaccion;
use wbALFINop\TransaccionInventario;
use wbALFINop\Inventario;
use wbALFINop\Actividad;
use wbALFINop\CatGasto;
use wbALFINop\Gastos;
use wbALFINop\TipoGasto;
use wbALFINop\Cliente;
use wbALFINop\OtrosIngresos;
use wbALFINop\ActivosFijos;
use wbALFINop\Credito;
use wbALFINop\Oferta;
use wbALFINop\CatOferta;
use wbALFINop\InformacionCrediticia;
use wbALFINop\BlackList;
use wbALFINop\Producto;
use wbALFINop\GarantiaPrendaria;
use wbALFINop\Perfil;
use wbALFINop\Events\SendOffer;
use wbALFINop\OfertaDos;
use DB;
use Response;

class SocioeconomicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $urlanterior = $_SERVER['HTTP_REFERER'];
        if (Auth::user()->idNivel!=1 && Auth::user()->idNivel!=6) {
            return redirect()->route('devengo.index');
        }
        $cliente = Credito::where('idCredito',$request->id)->first();
        $clienteRenovacion = Cliente::where('idcliente',$cliente->idCliente)->first();
        return view('socioeconomico.create',compact('clienteRenovacion','urlanterior','cliente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->idNivel!=1 && Auth::user()->idNivel!=6) {
            return redirect()->route('devengo.index');
        }
        //creo actividad
        $cliente = Cliente::where('idcliente',$request->cliente)->first();
        $request['idcliente'] = $cliente->idcliente;
        $actividad = Actividad::create($request->all());
        
        //agrego gastos
        //$actividad = Actividad::first(); //borrar linea
        $request['idact'] = $actividad->idact;
        $tipo_gasto = 1;
        for ($i=1; $i <= 22; $i++) {
            if ($i>11) { $tipo_gasto = 2; }
            $gasto = Gastos::create([
                'idtipogasto'   =>$tipo_gasto,
                'idact'         =>$actividad->idact,
                'idngasto'      =>$i,
                'monto'         =>$request["gasto$i"]
            ]);
        }
        
        //agrego transaciones
        $dia = 0;
        $tipo_transaccion = 1;
        for ($i=1; $i <= 14; $i++) {
            $dia ++;
            if ($dia>7) { $dia = 1; }
            if ($i>7) { $tipo_transaccion = 2; }
            if (is_null($request["lugar$i"])) {
                $request["lugar$i"] = 'ninguno';
            }
            $transaccion = TransaccionInventario::create([
                'iddia'         => $dia,
                'idtipotransac' => $tipo_transaccion,
                'idact'         => $actividad->idact,
                'lugar_compra'  => $request["lugar$i"],
                'monto'         => $request["precio$i"]
            ]);
        }

        //agrego inventario
        for ($i=0; $i < $request->num_productos; $i++) { 
            $inventario = Inventario::create([
                'idact'         =>$actividad->idact,
                'producto'      =>$request["producto$i"],
                'cantidad'      =>$request["cantidad$i"],
                'precio_compra' =>$request["precio_compra$i"],
                'precio_venta'  =>$request["precio_venta$i"]
            ]);
        }

        //agrego activos fijos
        for ($i=1; $i <4 ; $i++) {
            if (is_null($request["desci$i"])) {
                $request["desci$i"] = 'ninguno';
            }
            if (is_null($request["descf$i"])) {
                $request["descf$i"] = 'ninguno';
            }

            $activos = ActivosFijos::create([
                'idact'=> $actividad->idact,
                'monto'=> $request["cantf$i"],
                'tipo'=> $i,
                'descripcion'=> $request["descf$i"]
            ]);
            $otrosIngresos = OtrosIngresos::create([
                'idact'=> $actividad->idact,
                'monto'=> $request["canti$i"],
                'tipo'=> $i,
                'descripcion'=> $request["desci$i"]
            ]);
        }

        //garantia prendaria
        $garantia = GarantiaPrendaria::create($request->all());

        //return back()->withInput();
        return redirect("$request->url");
        //return redirect()->route('socioeconomico.show',$actividad->idact);
    }

    public function ofertas(Request $request,Credito $idCliente)
    {
        $cliente = Cliente::where('idcliente',$idCliente->idCliente)->first();
        //$cliente->ofertas;
        return Response::json(['ofertas'=>$cliente->ofertas,'nombre'=>$idCliente->nomCliente]);
    }
    public function ofertaAceptada(Oferta $oferta)
    {
        $ofertas = Oferta::where('idcredito',$oferta->idcredito)->where('status',1)->get();
        if (count($ofertas) == 0) {
            $oferta->update(['status'=>1]);
            $ofertas = Oferta::where('idcredito',$oferta->idcredito)->get();
            $credito = Credito::where('idCredito',$oferta->idcredito)->first();
            SendOffer::dispatch($oferta,$credito);
            return Response::json(['ofertas'=>$ofertas, 'nombre'=>$credito->nomCliente]);   
        }
    }
    public function verificarOferta(Credito $cliente)
    {
        $ofertaAceptada = Oferta::where('idcliente',$cliente->idCliente)->where('status',1)->first();
        return Response::json($ofertaAceptada);
    }

    public function inventario(Actividad $actividad){
        $inventario = Inventario::where('idact',$actividad->idact)->get();
        return Response::json($inventario);
    }

    public function informacion(Credito $cliente){
        if (Auth::user()->idNivel!=1 && Auth::user()->idNivel!=6) {
            return redirect()->route('devengo.index');
        }
        $urlanterior = $_SERVER['HTTP_REFERER'];
        $actividad = Actividad::where('idcliente',$cliente->idCliente)->first();
        $gastosOperacion = Gastos::where('idact',$actividad->idact)->where('idtipogasto','1')->orderBy('idngasto','ASC')->get();
        $gastosFamiliares = Gastos::where('idact',$actividad->idact)->where('idtipogasto','2')->orderBy('idngasto','ASC')->get();
        $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->orderBy('tipo','ASC')->get();
        $activos = ActivosFijos::where('idact',$actividad->idact)->orderBy('tipo','ASC')->get();
        $productos = Inventario::where('idact',$actividad->idact)->get();
        $transacionesVenta = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','2')->orderBy('iddia','ASC')->get();
        $transacionesCompra = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','1')->orderBy('iddia','ASC')->get();

        $garantia = GarantiaPrendaria::where('idact',$actividad->idact)->first();
        $oferta = Oferta::where('idcredito',$cliente->idCredito)->where('status',1)->first();

        if (!is_null($oferta)) {
            $totalco = $garantia->valorEstimado / $oferta->monto;
        } else {
            $totalco = $garantia->valorEstimado / ($cliente->montoInicial+($cliente->montoInicial*0.3));
        }


        $totalc=0;
        $totalv=0;
        $totalo=0;
        $totalf=0;
        $totaloi=0;
        $totala=0;

        foreach ($transacionesVenta as $venta) {
            $totalv = $totalv + $venta->monto;
        }
        foreach ($transacionesCompra as $compra) {
            $totalc = $totalc + $compra->monto;
        }
        foreach($gastosOperacion as $operacion){
            $totalo = $totalo + $operacion->monto;
        }
        foreach($gastosFamiliares as $familiar){
            $totalf = $totalf + $familiar->monto;
        }
        foreach ($otrosIngresos as $otros) {
            $totaloi = $totaloi + $otros->monto;
        }
        foreach ($activos as $activo) {
            $totala = $totala + $activo->monto;
        }

        $inventario = 0;
        $venta = 0;
        $compra = 0;
        $operacion = 0;
        $familiares = 0;
        $totalOtrosIngresos = 0;
        $totalActivoFijo = 0;
        $totalco = $garantia->valorEstimado / ($cliente->montoInicial+($cliente->montoInicial*0.3));

        foreach ($otrosIngresos as $otros) {
            $totalOtrosIngresos = $totalOtrosIngresos + $otros->monto;
        }
        foreach ($activos as $activo) {
            $totalActivoFijo = $totalActivoFijo + $activo->monto;
        }

        $porcentajeOtrosIngresos = ($totalOtrosIngresos) * 0.3;

        foreach ($productos as $producto) {
            $inventario = $inventario + ($producto->cantidad * $producto->precio_compra); 
        }

        foreach ($transacionesVenta as $trans) {
            $venta = $venta + $trans->monto;
        }
        $ventasMensuales = $venta * 4;

        foreach ($transacionesCompra as $trans) {
            $compra = $compra + $trans->monto;
        }
        $compraMensuales = $compra * 4;
        $utilidadBruta = $ventasMensuales - $compraMensuales;

        foreach ($gastosOperacion as $trans) {
            $operacion = $operacion + $trans->monto;
        }

        $utilidadNeta = $utilidadBruta - $operacion;

        foreach ($gastosFamiliares as $trans) {
            $familiares = $familiares + $trans->monto;
        }

        $disponible = $utilidadNeta + $porcentajeOtrosIngresos - $familiares;
        $capacidadPago = $disponible * 0.3;
        $capacidadPago50 = $disponible * 0.5;

        return view('socioeconomico.info',compact('cliente','gastosOperacion','gastosFamiliares','otrosIngresos','activos',
        'productos','transacionesVenta','transacionesCompra','actividad','totalc','totalv','totalo','totalf','totaloi','totala','urlanterior',
        'garantia','totalco','oferta','totalOtrosIngresos','totalActivoFijo','inventario','ventasMensuales','compraMensuales','utilidadBruta',
        'operacion','utilidadNeta','porcentajeOtrosIngresos','familiares','disponible','capacidadPago','capacidadPago50'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Credito $socioeconomico)
    {
        //
        if (Auth::user()->idNivel!=1 && Auth::user()->idNivel!=6) {
            return redirect()->route('devengo.index');
        }
        $urlanterior = $_SERVER['HTTP_REFERER'];
        $actividad = Actividad::where('idcliente',$socioeconomico->idCliente)->first();
        $activos = ActivosFijos::where('idact',$actividad->idact)->orderBy('tipo','ASC')->get();
        $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->orderBy('tipo','ASC')->get();
        
        $productos = Inventario::where('idact',$actividad->idact)->get();
        $transacionesVenta = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','2')->get();
        $transacionesCompra = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','1')->get();
        $gastosOperacion = Gastos::where('idact',$actividad->idact)->where('idtipogasto','1')->get();
        $gastosFamiliares = Gastos::where('idact',$actividad->idact)->where('idtipogasto','2')->get();
        $garantia = GarantiaPrendaria::where('idact',$actividad->idact)->first();

        $inventario = 0;
        $venta = 0;
        $compra = 0;
        $operacion = 0;
        $familiares = 0;
        $totalOtrosIngresos = 0;
        $totalActivoFijo = 0;
        $totalco = $garantia->valorEstimado / ($socioeconomico->montoInicial+($socioeconomico->montoInicial*0.3));

        foreach ($otrosIngresos as $otros) {
            $totalOtrosIngresos = $totalOtrosIngresos + $otros->monto;
        }
        foreach ($activos as $activo) {
            $totalActivoFijo = $totalActivoFijo + $activo->monto;
        }

        $porcentajeOtrosIngresos = ($totalOtrosIngresos) * 0.3;

        foreach ($productos as $producto) {
            $inventario = $inventario + ($producto->cantidad * $producto->precio_compra); 
        }

        foreach ($transacionesVenta as $trans) {
            $venta = $venta + $trans->monto;
        }
        $ventasMensuales = $venta * 4;

        foreach ($transacionesCompra as $trans) {
            $compra = $compra + $trans->monto;
        }
        $compraMensuales = $compra * 4;
        $utilidadBruta = $ventasMensuales - $compraMensuales;

        foreach ($gastosOperacion as $trans) {
            $operacion = $operacion + $trans->monto;
        }

        $utilidadNeta = $utilidadBruta - $operacion;

        foreach ($gastosFamiliares as $trans) {
            $familiares = $familiares + $trans->monto;
        }

        $disponible = $utilidadNeta + $porcentajeOtrosIngresos - $familiares;
        $capacidadPago = $disponible * 0.3;
        $capacidadPago50 = $disponible * 0.5;
        
        return view('socioeconomico.show',compact('activos','otrosIngresos','inventario','totalActivoFijo','totalOtrosIngresos',
        'ventasMensuales','compraMensuales','utilidadBruta','operacion','utilidadNeta','porcentajeOtrosIngresos','familiares','disponible',
        'capacidadPago','capacidadPago50','urlanterior','totalco','garantia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Credito $socioeconomico)
    {
        //
        if (Auth::user()->idNivel!=1 && Auth::user()->idNivel!=6) {
            return redirect()->route('devengo.index');
        }
        $urlanterior = $_SERVER['HTTP_REFERER'];
        $actividad = Actividad::where('idcliente',$socioeconomico->idCliente)->first();
        $gastosOperacion = Gastos::where('idact',$actividad->idact)->where('idtipogasto','1')->orderBy('idngasto','ASC')->get();
        $gastosFamiliares = Gastos::where('idact',$actividad->idact)->where('idtipogasto','2')->orderBy('idngasto','ASC')->get();
        $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->orderBy('tipo','ASC')->get();
        $activos = ActivosFijos::where('idact',$actividad->idact)->orderBy('tipo','ASC')->get();
        $productos = Inventario::where('idact',$actividad->idact)->get();
        $transacionesVenta = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','2')->orderBy('iddia','ASC')->get();
        $transacionesCompra = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','1')->orderBy('iddia','ASC')->get();
        $garantia = GarantiaPrendaria::where('idact',$actividad->idact)->first();

        $totalc=0;
        $totalv=0;
        $totalo=0;
        $totalf=0;
        $totaloi=0;
        $totala=0;
        $totalco = $garantia->valorEstimado / ($socioeconomico->montoInicial+($socioeconomico->montoInicial*0.3));

        foreach ($transacionesVenta as $venta) {
            $totalv = $totalv + $venta->monto;
        }
        foreach ($transacionesCompra as $compra) {
            $totalc = $totalc + $compra->monto;
        }
        foreach($gastosOperacion as $operacion){
            $totalo = $totalo + $operacion->monto;
        }
        foreach($gastosFamiliares as $familiar){
            $totalf = $totalf + $familiar->monto;
        }
        foreach ($otrosIngresos as $otros) {
            $totaloi = $totaloi + $otros->monto;
        }
        foreach ($activos as $activo) {
            $totala = $totala + $activo->monto;
        }

        return view('socioeconomico.edit',compact('socioeconomico','gastosOperacion','gastosFamiliares','otrosIngresos','activos',
        'productos','transacionesVenta','transacionesCompra','actividad','totalv','totalc','totalo','totalf','totaloi','totala','urlanterior',
        'totalco','garantia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if (Auth::user()->idNivel!=1 && Auth::user()->idNivel!=6) {
            return redirect()->route('devengo.index');
        }
        //actualizar actividad
        $actividad = Actividad::where('idcliente',$id)->first();
        $actividad->update($request->all());

        //actualizar gastos
        //$actividad = Actividad::first(); //borrar linea
        $tipo_gasto = 1;
        for ($i=1; $i <= 22; $i++) {
            if ($i>11) { $tipo_gasto = 2; }
            $gasto = Gastos::where('idact',$actividad->idact)->where('idtipogasto',$tipo_gasto)->where('idngasto',$i)->first();
            $gasto->update([
                'monto'         =>$request["gasto$i"]
            ]);
        }
        
        //actualizo transaciones
        $dia = 0;
        $tipo_transaccion = 1;
        for ($i=1; $i <= 14; $i++) {
            $dia ++;
            if ($dia>7) { $dia = 1; }
            if ($i>7) { $tipo_transaccion = 2; }
            $transaccion = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac',$tipo_transaccion)->where('iddia',$dia)->first();
            if (is_null($request["lugar$i"])) {
                $request["lugar$i"] = 'ninguno';
            }
            $transaccion->update([
                'lugar_compra'  => $request["lugar$i"],
                'monto'         => $request["precio$i"]
            ]);
        }

        //actualizar inventario
        $articulos = Inventario::where('idact',$actividad->idact)->get();
        if (count($articulos) > 0) {
            foreach ($articulos as $articulo) {
                $articulo->delete();
            }
        }
        for ($i=0; $i < $request->num_productos; $i++) { 
            $inventario = Inventario::create([
                'idact'         =>$actividad->idact,
                'producto'      =>$request["producto$i"],
                'cantidad'      =>$request["cantidad$i"],
                'precio_compra' =>$request["precio_compra$i"],
                'precio_venta'  =>$request["precio_venta$i"]
            ]);
        }

        //actualizo activos fijos

        for ($i=1; $i <4 ; $i++) { 
            $activo = ActivosFijos::where('idact',$actividad->idact)->where('tipo',$i)->first();
            $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->where('tipo',$i)->first();
            $activo->update([
                'monto'=> $request["cantf$i"],
                'descripcion'=> $request["descf$i"]
                ]);

            $otrosIngresos->update([
                'monto'=> $request["canti$i"],
                'descripcion'=> $request["desci$i"]
                ]);
        }

        //garantia prendaria
        $garantia = GarantiaPrendaria::where('idact',$actividad->idact)->first();
        $garantia->update($request->all());

        return redirect("$request->url");
        //return redirect()->route('socioeconomico.show',$actividad->idact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function calificarOferta(Request $request)
    {
        $credito = Credito::where('idCredito',$request->idCredito)->first();
        $informacion = InformacionCrediticia::where('idcliente',$credito->idCliente)->orderBy('fechaconsulta',' DESC')->get();//buscamos la informacion crediticia
        if (count($informacion)>0) {
            $fecha_actual = date("Y-m-d");
            $fecha = round((strtotime($fecha_actual)-strtotime($informacion[0]->fechaconsulta))/86400);//calcular los dias si es menor al dia de hoy
            $listaNegra = BlackList::where('idcliente',$credito->idCliente)->where('idcredito',$credito->idCredito)->first();// verifica si el cliente con el credito seleccionado estan en la black list
            
            //analisis de solvencia
            $actividad = Actividad::where('idcliente',$credito->idCliente)->first();
            if (!is_null($actividad)) {
                $activos = ActivosFijos::where('idact',$actividad->idact)->first();
                $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->first();
                
                $productos = Inventario::where('idact',$actividad->idact)->get();
                $transacionesVenta = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','2')->get();
                $transacionesCompra = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','1')->get();
                $gastosOperacion = Gastos::where('idact',$actividad->idact)->where('idtipogasto','1')->get();
                $gastosFamiliares = Gastos::where('idact',$actividad->idact)->where('idtipogasto','2')->get();
                
                $inventario = 0;
                $venta = 0;
                $compra = 0;
                $operacion = 0;
                $familiares = 0;
                $totalActivoFijo = $activos->auto + $activos->local + $activos->maquinaria;
                $totalOtrosIngresos = $otrosIngresos->conyuge + $otrosIngresos->empleo + $otrosIngresos->otro_negocio;
                $porcentajeOtrosIngresos = ($otrosIngresos->conyuge + $otrosIngresos->empleo + $otrosIngresos->otro_negocio) * 0.3;

                foreach ($productos as $producto) {
                    $inventario = $inventario + ($producto->cantidad * $producto->precio_compra); 
                }

                foreach ($transacionesVenta as $trans) {
                    $venta = $venta + $trans->monto;
                }
                $ventasMensuales = $venta * 4;

                foreach ($transacionesCompra as $trans) {
                    $compra = $compra + $trans->monto;
                }
                $compraMensuales = $compra * 4;
                $utilidadBruta = $ventasMensuales - $compraMensuales;

                foreach ($gastosOperacion as $trans) {
                    $operacion = $operacion + $trans->monto;
                }

                $utilidadNeta = $utilidadBruta - $operacion;

                foreach ($gastosFamiliares as $trans) {
                    $familiares = $familiares + $trans->monto;
                }

                $disponible = $utilidadNeta + $porcentajeOtrosIngresos - $familiares;
                //$capacidadPago = $disponible * 0.3;

                //proceso de filtrado
                if ($fecha<=59) {
                    if ($informacion[0]->score >= 500) {
                        if (is_null($listaNegra)) {

                            //$disponible=10748;
                            //return $credito->product->negocio_op;
                            //return $credito->frecuenciaPago;
                            //return $credito->plazo;// aqui quede hacer comparacion

                            //verifico la frecuencia de pago
                            $plazo = $credito->plazo;
                            if ($credito->frecuenciaPago == 'Quincenal') {
                                $plazo = floor($plazo / 2);
                            }elseif ($credito->frecuenciaPago == 'Semanal') {
                                $plazo = floor($plazo / 4.345);
                            }
                            
                            $incremento = 0.30;
                            $creditoAnterior = $credito->montoInicial;
                            $tasa = 0.041;
                            $capacidadPago50 = $disponible * 0.5;
                            $capacidadPago35 = $disponible * 0.35;
                            
                            //
                            // if ($informacion[0]->score > 600) {
                            //     $capacidadPago = $disponible * 0.5;
                            // }else{
                            //     $capacidadPago = $disponible * 0.35;
                            // }

                            // //prueba
                            // $creditoAnterior =  30000;
                            // $capacidadPago50 =  5374;
                            // $capacidadPago35 =  3761.80;
                            // $plazo = 13;

                            
                            //fecha fin
                            $fechaFin = $credito->fechaFin;
                            $nuevaFecha = strtotime ('+5 day',strtotime ($fechaFin)) ;
                            $nuevaFecha = date ('Y-m-j',$nuevaFecha);
                            //return  $credito->product->negocio_op;
                            if ($credito->product->negocio_op == 'VIVIENDA') {

                                //ofertas Vivienda 30%
                                for ($i=$plazo; $i <= 19; $i++) { 
                                    $monto = $creditoAnterior + ($creditoAnterior * $incremento);
                                    $ical = $monto * $tasa;
                                    $kcal = $monto / $i;
                                    $cuota = $ical + $kcal;
                                    if ($cuota <= $capacidadPago35) {
                                        $oferta = Oferta::create([
                                            'idcliente'     =>  $credito->idCliente,
                                            'idto'          =>  2,
                                            'idcredito'     =>  $credito->idCredito,
                                            'fechai'        =>  date('Y-m-d'),
                                            'fechaf'        =>  $nuevaFecha,
                                            'plazo'         =>  $i,
                                            'monto'         =>  $monto,
                                            'cuota'         =>  $cuota,
                                            'frecuencia'    =>  1,
                                            'status'        =>  0
                                        ]);
                                    }
                                }

                                //oferta Vivieda 0%
                                $monto = $creditoAnterior;
                                $ical = $monto * $tasa;
                                $kcal = $monto / 13;
                                $cuota = $ical + $kcal;
                                if ($cuota <= $capacidadPago35) {
                                    $oferta = Oferta::create([
                                        'idcliente'     =>  $credito->idCliente,
                                        'idto'          =>  2,
                                        'idcredito'     =>  $credito->idCredito,
                                        'fechai'        =>  date('Y-m-d'),
                                        'fechaf'        =>  $nuevaFecha,
                                        'plazo'         =>  13,
                                        'monto'         =>  $monto,
                                        'cuota'         =>  $cuota,
                                        'frecuencia'    =>  1,
                                        'status'        =>  0
                                    ]);
                                }

                                //ofertas productivo 0%
                                for ($i=6; $i <=12 ; $i++) { 
                                    $monto = $creditoAnterior;
                                    $ical = $monto * $tasa;
                                    $kcal = $monto / $i;
                                    $cuota = $ical + $kcal;
                                    if ($cuota <= $capacidadPago50) {
                                        $oferta = Oferta::create([
                                            'idcliente'     =>  $credito->idCliente,
                                            'idto'          =>  1,
                                            'idcredito'     =>  $credito->idCredito,
                                            'fechai'        =>  date('Y-m-d'),
                                            'fechaf'        =>  $nuevaFecha,
                                            'plazo'         =>  $i,
                                            'monto'         =>  $monto,
                                            'cuota'         =>  $cuota,
                                            'frecuencia'    =>  1,
                                            'status'        =>  0
                                        ]);
                                    }
                                }

                            }
                            elseif($credito->product->negocio_op == 'PRODUCTIVO'){

                                //ofertas productivo 30%
                                for ($i=$plazo; $i <=12 ; $i++) { 
                                    $monto = $creditoAnterior + ($creditoAnterior * $incremento);
                                    $ical = $monto * $tasa;
                                    $kcal = $monto / $i;
                                    $cuota = $ical + $kcal;
                                    if ($cuota <= $capacidadPago50) {
                                        $oferta = Oferta::create([
                                            'idcliente'     =>  $credito->idCliente,
                                            'idto'          =>  1,
                                            'idcredito'     =>  $credito->idCredito,
                                            'fechai'        =>  date('Y-m-d'),
                                            'fechaf'        =>  $nuevaFecha,
                                            'plazo'         =>  $i,
                                            'monto'         =>  $monto,
                                            'cuota'         =>  $cuota,
                                            'frecuencia'    =>  1,
                                            'status'        =>  0
                                        ]);
                                    }
                                }

                                //oferta productivo 0%
                                $monto = $creditoAnterior;
                                $ical = $monto * $tasa;
                                $kcal = $monto / 6;
                                $cuota = $ical + $kcal;
                                if ($cuota <= $capacidadPago50) {
                                    $oferta = Oferta::create([
                                        'idcliente'     =>  $credito->idCliente,
                                        'idto'          =>  1,
                                        'idcredito'     =>  $credito->idCredito,
                                        'fechai'        =>  date('Y-m-d'),
                                        'fechaf'        =>  $nuevaFecha,
                                        'plazo'         =>  6,
                                        'monto'         =>  $monto,
                                        'cuota'         =>  $cuota,
                                        'frecuencia'    =>  1,
                                        'status'        =>  0
                                    ]);
                                }
                                
                                //ofertas Vivienda 30%
                                for ($j=13; $j <=19 ; $j++) {
                                
                                    $monto = $creditoAnterior + ($creditoAnterior * $incremento);
                                    $ical = $monto * $tasa;
                                    $kcal = $monto / $j;
                                    $cuota = $ical + $kcal;
                                    if ($cuota <= $capacidadPago35) {
                                        $oferta = Oferta::create([
                                            'idcliente'     =>  $credito->idCliente,
                                            'idto'          =>  2,
                                            'idcredito'     =>  $credito->idCredito,
                                            'fechai'        =>  date('Y-m-d'),
                                            'fechaf'        =>  $nuevaFecha,
                                            'plazo'         =>  $j,
                                            'monto'         =>  $monto,
                                            'cuota'         =>  $cuota,
                                            'frecuencia'    =>  1,
                                            'status'        =>  0
                                        ]);
                                    }
                                }

                                //oferta Vivienda 0%
                                $monto = $creditoAnterior;
                                $ical = $monto * $tasa;
                                $kcal = $monto / 13;
                                $cuota = $ical + $kcal;
                                //return $capacidadPago;
                                if ($cuota <= $capacidadPago35) {
                                    echo "$incremento - 6 - $cuota <br>";
                                    $oferta = Oferta::create([
                                        'idcliente'     =>  $credito->idCliente,
                                        'idto'          =>  2,
                                        'idcredito'     =>  $credito->idCredito,
                                        'fechai'        =>  date('Y-m-d'),
                                        'fechaf'        =>  $nuevaFecha,
                                        'plazo'         =>  13,
                                        'monto'         =>  $monto,
                                        'cuota'         =>  $cuota,
                                        'frecuencia'    =>  1,
                                        'status'        =>  0
                                    ]);
                                }
                            }

                            //return redirect()->route('renovacion.index')->with(['mensaje'=>"Calificado con &Eacute;xito"]);
                            return back()->withInput()->with(['mensaje'=>"Calificado con &Eacute;xito"]);
                        }else{
                            return back()->withInput()->with(['error'=>'Esta Presente en Lista Negra']);
                            return 'esta en lista negra';
                        }
                    }else{
                        return back()->withInput()->with(['error'=>'Score < 500']);
                    }
                }else{
                    return back()->withInput()->with(['error'=>'Fecha Mayor a 59 días']);
                    return 'fecha pasada';
                }
            }else{
                return back()->withInput()->with(['error'=>'No tiene Información Socioeconómica ']);
                return 'no tiene datos socioeconomicos';
            }
        }else{
            return back()->withInput()->with(['error'=>'No tiene Información Crediticia']);   
        }
    }

    public function calificarOfertaDos(Request $request)
    {   
        $creditos = Credito::all();
        $ofertasHechas = 0 ;
        foreach ($creditos as $key => $credito) {
            $cliente = Cliente::where('idcliente',$credito->idCliente)->first();
            if (!is_null($cliente)) {
                //$credito = Credito::where('idCredito',$request->idCredito)->first();
                $informacion = InformacionCrediticia::where('idcliente',$credito->idCliente)->orderBy('fechaconsulta',' DESC')->get();//buscamos la informacion crediticia
                if (count($informacion)>0) {
                    $fecha_actual = date("Y-m-d");
                    $fecha = round((strtotime($fecha_actual)-strtotime($informacion[0]->fechaconsulta))/86400);//calcular los dias si es menor al dia de hoy
                    $listaNegra = BlackList::where('idcliente',$credito->idCliente)->where('idcredito',$credito->idCredito)->first();// verifica si el cliente con el credito seleccionado estan en la black list
                    
                    //analisis de solvencia
                    $actividad = Actividad::where('idcliente',$credito->idCliente)->first();
                    if (!is_null($actividad)) {
                        $activos = ActivosFijos::where('idact',$actividad->idact)->first();
                        $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->first();
                        
                        $productos = Inventario::where('idact',$actividad->idact)->get();
                        $transacionesVenta = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','2')->get();
                        $transacionesCompra = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','1')->get();
                        $gastosOperacion = Gastos::where('idact',$actividad->idact)->where('idtipogasto','1')->get();
                        $gastosFamiliares = Gastos::where('idact',$actividad->idact)->where('idtipogasto','2')->get();
                        
                        $inventario = 0;
                        $venta = 0;
                        $compra = 0;
                        $operacion = 0;
                        $familiares = 0;
                        $totalActivoFijo = $activos->auto + $activos->local + $activos->maquinaria;
                        $totalOtrosIngresos = $otrosIngresos->conyuge + $otrosIngresos->empleo + $otrosIngresos->otro_negocio;
                        $porcentajeOtrosIngresos = ($otrosIngresos->conyuge + $otrosIngresos->empleo + $otrosIngresos->otro_negocio) * 0.3;

                        foreach ($productos as $producto) {
                            $inventario = $inventario + ($producto->cantidad * $producto->precio_compra); 
                        }

                        foreach ($transacionesVenta as $trans) {
                            $venta = $venta + $trans->monto;
                        }
                        $ventasMensuales = $venta * 4;

                        foreach ($transacionesCompra as $trans) {
                            $compra = $compra + $trans->monto;
                        }
                        $compraMensuales = $compra * 4;
                        $utilidadBruta = $ventasMensuales - $compraMensuales;

                        foreach ($gastosOperacion as $trans) {
                            $operacion = $operacion + $trans->monto;
                        }

                        $utilidadNeta = $utilidadBruta - $operacion;

                        foreach ($gastosFamiliares as $trans) {
                            $familiares = $familiares + $trans->monto;
                        }

                        $disponible = $utilidadNeta + $porcentajeOtrosIngresos - $familiares;
                        //$capacidadPago = $disponible * 0.3;

                        //proceso de filtrado
                        if ($fecha<=59) {
                            if ($informacion[0]->score >= 500) {
                                if (is_null($listaNegra)) {
                                    $ofertasHechas++;
                                    //$disponible=10748;
                                    //return $credito->product->negocio_op;
                                    //return $credito->frecuenciaPago;
                                    //return $credito->plazo;// aqui quede hacer comparacion

                                    //verifico la frecuencia de pago
                                    $plazo = $credito->plazo;
                                    if ($credito->frecuenciaPago == 'Quincenal') {
                                        $plazo = floor($plazo / 2);
                                    }elseif ($credito->frecuenciaPago == 'Semanal') {
                                        $plazo = floor($plazo / 4.345);
                                    }
                                    
                                    $incremento = 0.30;
                                    $creditoAnterior = $credito->montoInicial;
                                    $tasa = 0.041;
                                    $capacidadPago50 = $disponible * 0.5;
                                    $capacidadPago35 = $disponible * 0.35;
                                    
                                    //fecha fin
                                    $fechaFin = $credito->fechaFin;
                                    $nuevaFecha = strtotime ('+5 day',strtotime ($fechaFin)) ;
                                    $nuevaFecha = date ('Y-m-j',$nuevaFecha);
                                    //return  $credito->product->negocio_op;
                                    if ($credito->product->negocio_op == 'VIVIENDA') {

                                        //ofertas Vivienda 30%
                                        for ($i=$plazo; $i <= 19; $i++) { 
                                            $monto = $creditoAnterior + ($creditoAnterior * $incremento);
                                            $ical = $monto * $tasa;
                                            $kcal = $monto / $i;
                                            $cuota = $ical + $kcal;
                                            if ($cuota <= $capacidadPago35) {
                                                $oferta = OfertaDos::create([
                                                    'idcliente'     =>  $credito->idCliente,
                                                    'idto'          =>  2,
                                                    'idcredito'     =>  $credito->idCredito,
                                                    'fechai'        =>  date('Y-m-d'),
                                                    'fechaf'        =>  $nuevaFecha,
                                                    'plazo'         =>  $i,
                                                    'monto'         =>  $monto,
                                                    'cuota'         =>  $cuota,
                                                    'frecuencia'    =>  1,
                                                    'status'        =>  0
                                                ]);
                                            }
                                        }

                                        //oferta Vivieda 0%
                                        $monto = $creditoAnterior;
                                        $ical = $monto * $tasa;
                                        $kcal = $monto / 13;
                                        $cuota = $ical + $kcal;
                                        if ($cuota <= $capacidadPago35) {
                                            $oferta = OfertaDos::create([
                                                'idcliente'     =>  $credito->idCliente,
                                                'idto'          =>  2,
                                                'idcredito'     =>  $credito->idCredito,
                                                'fechai'        =>  date('Y-m-d'),
                                                'fechaf'        =>  $nuevaFecha,
                                                'plazo'         =>  13,
                                                'monto'         =>  $monto,
                                                'cuota'         =>  $cuota,
                                                'frecuencia'    =>  1,
                                                'status'        =>  0
                                            ]);
                                        }

                                        //ofertas productivo 0%
                                        for ($i=6; $i <=12 ; $i++) { 
                                            $monto = $creditoAnterior;
                                            $ical = $monto * $tasa;
                                            $kcal = $monto / $i;
                                            $cuota = $ical + $kcal;
                                            if ($cuota <= $capacidadPago50) {
                                                $oferta = OfertaDos::create([
                                                    'idcliente'     =>  $credito->idCliente,
                                                    'idto'          =>  1,
                                                    'idcredito'     =>  $credito->idCredito,
                                                    'fechai'        =>  date('Y-m-d'),
                                                    'fechaf'        =>  $nuevaFecha,
                                                    'plazo'         =>  $i,
                                                    'monto'         =>  $monto,
                                                    'cuota'         =>  $cuota,
                                                    'frecuencia'    =>  1,
                                                    'status'        =>  0
                                                ]);
                                            }
                                        }

                                    }
                                    elseif($credito->product->negocio_op == 'PRODUCTIVO'){

                                        //ofertas productivo 30%
                                        for ($i=$plazo; $i <=12 ; $i++) { 
                                            $monto = $creditoAnterior + ($creditoAnterior * $incremento);
                                            $ical = $monto * $tasa;
                                            $kcal = $monto / $i;
                                            $cuota = $ical + $kcal;
                                            if ($cuota <= $capacidadPago50) {
                                                $oferta = OfertaDos::create([
                                                    'idcliente'     =>  $credito->idCliente,
                                                    'idto'          =>  1,
                                                    'idcredito'     =>  $credito->idCredito,
                                                    'fechai'        =>  date('Y-m-d'),
                                                    'fechaf'        =>  $nuevaFecha,
                                                    'plazo'         =>  $i,
                                                    'monto'         =>  $monto,
                                                    'cuota'         =>  $cuota,
                                                    'frecuencia'    =>  1,
                                                    'status'        =>  0
                                                ]);
                                            }
                                        }

                                        //oferta productivo 0%
                                        $monto = $creditoAnterior;
                                        $ical = $monto * $tasa;
                                        $kcal = $monto / 6;
                                        $cuota = $ical + $kcal;
                                        if ($cuota <= $capacidadPago50) {
                                            $oferta = OfertaDos::create([
                                                'idcliente'     =>  $credito->idCliente,
                                                'idto'          =>  1,
                                                'idcredito'     =>  $credito->idCredito,
                                                'fechai'        =>  date('Y-m-d'),
                                                'fechaf'        =>  $nuevaFecha,
                                                'plazo'         =>  6,
                                                'monto'         =>  $monto,
                                                'cuota'         =>  $cuota,
                                                'frecuencia'    =>  1,
                                                'status'        =>  0
                                            ]);
                                        }
                                        
                                        //ofertas Vivienda 30%
                                        for ($j=13; $j <=19 ; $j++) {
                                        
                                            $monto = $creditoAnterior + ($creditoAnterior * $incremento);
                                            $ical = $monto * $tasa;
                                            $kcal = $monto / $j;
                                            $cuota = $ical + $kcal;
                                            if ($cuota <= $capacidadPago35) {
                                                $oferta = OfertaDos::create([
                                                    'idcliente'     =>  $credito->idCliente,
                                                    'idto'          =>  2,
                                                    'idcredito'     =>  $credito->idCredito,
                                                    'fechai'        =>  date('Y-m-d'),
                                                    'fechaf'        =>  $nuevaFecha,
                                                    'plazo'         =>  $j,
                                                    'monto'         =>  $monto,
                                                    'cuota'         =>  $cuota,
                                                    'frecuencia'    =>  1,
                                                    'status'        =>  0
                                                ]);
                                            }
                                        }

                                        //oferta Vivienda 0%
                                        $monto = $creditoAnterior;
                                        $ical = $monto * $tasa;
                                        $kcal = $monto / 13;
                                        $cuota = $ical + $kcal;
                                        //return $capacidadPago;
                                        if ($cuota <= $capacidadPago35) {
                                            $oferta = OfertaDos::create([
                                                'idcliente'     =>  $credito->idCliente,
                                                'idto'          =>  2,
                                                'idcredito'     =>  $credito->idCredito,
                                                'fechai'        =>  date('Y-m-d'),
                                                'fechaf'        =>  $nuevaFecha,
                                                'plazo'         =>  13,
                                                'monto'         =>  $monto,
                                                'cuota'         =>  $cuota,
                                                'frecuencia'    =>  1,
                                                'status'        =>  0
                                            ]);
                                        }
                                    }

                                    //return redirect()->route('renovacion.index')->with(['mensaje'=>"Calificado con &Eacute;xito"]);
                                    //return back()->withInput()->with(['mensaje'=>"Calificado con &Eacute;xito"]);
                                }
                            }
                        }
                    }
                }
            }   
        }
        return $ofertasHechas;
    }

    public function resumenAvance(Request $request){
        //searchTxts=44V&searchTxt=853&cliente=

        $i = 0;
        $creditos = Credito::all();
        $productos = Producto::all();
        $contador = 0;
        $noiguales = 0;
        $no_exite = array();        
            
                if (Auth::user()->idNivel>3) {
                    if (Auth::user()->idNivel==6) {
                        $query= trim($request ->get('searchTxt'));
                    } else {
                        $query=Auth::user()->idPerfil;
                    }
                    $querys= DB::table('catperfiles as cp')
               ->where('cp.idPerfil', '=', Auth::user()->idPerfil)->value('idSucursal');
                } else {
                    $query= trim($request ->get('searchTxt'));
                    $querys= trim($request ->get('searchTxts'));
                }
    
                $nummonth=0;
                $nummonth=date('m');
                // if ($nummonth>9) {
                //     $month=$nummonth;
                // } else {
                //     $month="0".$nummonth;
                // }
    
                //$datei=date('Y')."/".$nummonth."/01";
                $datei = date('Y-m-d');
    
                $nummonth=0;
    
                $nummonth=date('m') + 1;
                if ($nummonth>9) {
                    $month=$nummonth;
                } else {
                    $month="0".$nummonth;
                }

                if ($nummonth>11) {
                   $datef=(date('Y')+ 1)."/"."01"."/31";
                }else {
                  $datef=date('Y')."/".$month."/31";
                }
                
                $dateio = strtotime ('-5 day',strtotime ($datei)) ;
                $dateio = date ('Y-m-j',$dateio);

                $datefo = strtotime ('+5 day',strtotime ($datef)) ;
                $datefo = date ('Y-m-j',$datefo);

                $ofertas = Oferta::pluck('idcliente');
                $blackList = BlackList::pluck('idcredito');
                $blackListp = BlackList::pluck('idcliente');
                
                $per = Perfil::where('idPerfil',$query)->first();
                
                if ($query != 'T0ALL') {
                    $vencimientos = Credito::
                    leftjoin('tblrenovaciones as r', 'tblcreditos.idCredito', '=', 'r.idCredito')
                    ->join('tblsituacioncredito as s', 'tblcreditos.idCredito', '=', 's.idCredito')
                    ->join('tbldomicilioscredito as dc', 'tblcreditos.idCredito', '=', 'dc.idCredito')
                    ->join('catperfiles as cp', 'tblcreditos.idPerfil', '=', 'cp.idPerfil')
                    ->join('catproducto as catp', 'tblcreditos.cveproducto', '=', 'catp.cveproducto') //agregamos la relacion con catproducto
                    ->select('tblcreditos.idCredito','tblcreditos.idCliente', 'tblcreditos.nomCliente', 'cp.idSucursal', 'tblcreditos.fechaFin', 's.maxDiasAtraso', 'tblcreditos.montoInicial', 'dc.colonia', 'dc.telefonoCelular', DB::raw('IF(r.renueva=0,"No",IF(r.renueva=1,"Si","")) as renueva'), 'r.montoRenovacion','catp.refinan_si')
                    ->where('tblcreditos.idPerfil', '=', $query)
                    ->where('s.estatus', '=', '1')
                    ->where("nomCliente", "LIKE", "%{$request->get('cliente')}%") //busqueda de nombre
                    //->whereNotIn('tblcreditos.idCliente',$ofertas) //que no este en ofertas
                    ->whereNotIn('tblcreditos.idCliente',$blackListp) //que no este en lista actual
                    ->whereRaw('fechaFin>="'.$datei.'" and fechaFin<="'.$datef.'"')//fecha de hoy al 31 del otro mes.
                    ->where('s.maxDiasAtraso', "<", 31) //maximo dias atrazado es 16
                    ->where('refinan_si',1) //refinan_si con valor en 1
                    ->orderBy('tblcreditos.fechaFin', 'asc')
                    ->orderBy('dc.colonia', 'desc')
                    ->paginate(40);

                    $vencimientosOfertas = Credito::
                    leftjoin('tblrenovaciones as r', 'tblcreditos.idCredito', '=', 'r.idCredito')
                    ->join('tblsituacioncredito as s', 'tblcreditos.idCredito', '=', 's.idCredito')
                    ->join('tbldomicilioscredito as dc', 'tblcreditos.idCredito', '=', 'dc.idCredito')
                    ->join('catperfiles as cp', 'tblcreditos.idPerfil', '=', 'cp.idPerfil')
                    ->join('catproducto as catp', 'tblcreditos.cveproducto', '=', 'catp.cveproducto') //agregamos la relacion con catproducto
                    ->select('tblcreditos.idCredito','tblcreditos.idCliente', 'tblcreditos.nomCliente','cp.idSucursal', 'tblcreditos.fechaFin', 's.maxDiasAtraso', 'tblcreditos.montoInicial', 'dc.colonia', 'dc.telefonoCelular', DB::raw('IF(r.renueva=0,"No",IF(r.renueva=1,"Si","")) as renueva'), 'r.montoRenovacion','catp.refinan_si')
                    ->where('tblcreditos.idPerfil', '=', $query)
                    ->where("nomCliente", "LIKE", "%{$request->get('cliente')}%") //busqueda de nombre
                    ->whereNotIn('tblcreditos.idCliente',$blackListp) //que no este en lista actual
                    ->whereRaw('fechaFin>="'.$dateio.'" and fechaFin<="'.$datefo.'"')//fecha de hoy al 31 del otro mes.
                    ->where('s.maxDiasAtraso', "<", 30) //maximo dias atrazado es 16
                    //->where('refinan_si',1) //refinan_si con valor en 1
                    ->orderBy('tblcreditos.fechaFin', 'asc')
                    ->orderBy('dc.colonia', 'desc')
                    ->paginate(40);
                }
                else {
                    $vencimientos = Credito::
                    leftjoin('tblrenovaciones as r', 'tblcreditos.idCredito', '=', 'r.idCredito')
                    ->join('tblsituacioncredito as s', 'tblcreditos.idCredito', '=', 's.idCredito')
                    ->join('tbldomicilioscredito as dc', 'tblcreditos.idCredito', '=', 'dc.idCredito')
                    ->join('catperfiles as cp', 'tblcreditos.idPerfil', '=', 'cp.idPerfil')
                    ->join('catproducto as catp', 'tblcreditos.cveproducto', '=', 'catp.cveproducto') //agregamos la relacion con catproducto
                    ->select('tblcreditos.idCredito','tblcreditos.idCliente', 'tblcreditos.nomCliente', 'cp.idSucursal', 'tblcreditos.fechaFin', 's.maxDiasAtraso', 'tblcreditos.montoInicial', 'dc.colonia', 'dc.telefonoCelular', DB::raw('IF(r.renueva=0,"No",IF(r.renueva=1,"Si","")) as renueva'), 'r.montoRenovacion','catp.refinan_si')
                    ->where('idSucursal', '=', $querys)
                    ->where('s.estatus', '=', '1')
                    ->where("nomCliente", "LIKE", "%{$request->get('cliente')}%") //busqueda de nombre
                    //->whereNotIn('tblcreditos.idCliente',$ofertas) //que no este en ofertas
                    ->whereNotIn('tblcreditos.idCliente',$blackListp) //que no este en lista actual
                    ->whereRaw('fechaFin>="'.$datei.'" and fechaFin<="'.$datef.'"')//fecha de hoy al 31 del otro mes.
                    ->where('s.maxDiasAtraso', "<", 31) //maximo dias atrazado es 16
                    ->where('refinan_si',1) //refinan_si con valor en 1
                    ->orderBy('tblcreditos.fechaFin', 'asc')
                    ->orderBy('dc.colonia', 'desc')
                    ->paginate(40);

                    $vencimientosOfertas = Credito::
                    leftjoin('tblrenovaciones as r', 'tblcreditos.idCredito', '=', 'r.idCredito')
                    ->join('tblsituacioncredito as s', 'tblcreditos.idCredito', '=', 's.idCredito')
                    ->join('tbldomicilioscredito as dc', 'tblcreditos.idCredito', '=', 'dc.idCredito')
                    ->join('catperfiles as cp', 'tblcreditos.idPerfil', '=', 'cp.idPerfil')
                    ->join('catproducto as catp', 'tblcreditos.cveproducto', '=', 'catp.cveproducto') //agregamos la relacion con catproducto
                    ->select('tblcreditos.idCredito','tblcreditos.idCliente', 'tblcreditos.nomCliente','cp.idSucursal', 'tblcreditos.fechaFin', 's.maxDiasAtraso', 'tblcreditos.montoInicial', 'dc.colonia', 'dc.telefonoCelular', DB::raw('IF(r.renueva=0,"No",IF(r.renueva=1,"Si","")) as renueva'), 'r.montoRenovacion','catp.refinan_si')
                    ->where('idSucursal', '=', $querys)
                    ->where("nomCliente", "LIKE", "%{$request->get('cliente')}%") //busqueda de nombre
                    ->whereNotIn('tblcreditos.idCliente',$blackListp) //que no este en lista actual
                    ->whereRaw('fechaFin>="'.$dateio.'" and fechaFin<="'.$datefo.'"')//fecha de hoy al 31 del otro mes.
                    ->where('s.maxDiasAtraso', "<", 30) //maximo dias atrazado es 16
                    //->where('refinan_si',1) //refinan_si con valor en 1
                    ->orderBy('tblcreditos.fechaFin', 'asc')
                    ->orderBy('dc.colonia', 'desc')
                    ->paginate(40);
                }

        

    
    
                if (Auth::user()->idNivel==3) {
                    $queryr= DB::table('catperfiles as cp')
               ->join('catsucursales as s', 'cp.idSucursal', '=', 's.idSucursal')
               ->select('cp.idPerfil', 's.idRegional')
               ->where('cp.idPerfil', '=', Auth::user()->idPerfil)->first();
    
                    $catsucursal=DB::table('catperfiles as cp')
                    ->join('catsucursales as s', 'cp.idSucursal', '=', 's.idSucursal')
                    ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
                    ->select('cp.idSucursal', 's.sucursal')
                    ->where('s.idRegional', '=', $queryr->idRegional)
                    ->get();
                } else {
                    $catsucursal=DB::table('catperfiles as cp')
                    ->join('catsucursales as s', 'cp.idSucursal', '=', 's.idSucursal')
                    ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
                    ->select('cp.idSucursal', 's.sucursal')
                    ->get();
                }
    
                $catvendedores=DB::table('catperfiles as cp')
                ->join('catpersonas as p', 'cp.idPersona', '=', 'p.idPersona')
               ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
                ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
               ->select('p.nombre', 'cp.idPerfil')
                ->where('cp.idSucursal', '=', $querys)
                ->where('s.estatus', '=', '1')
               ->orderBy('p.nombre', 'desc')->distinct()->get();
    
                $vendedores= array('0' => "Ninguno",'T0ALL'=>'TODOS') + collect($catvendedores)
               ->pluck('nombre', 'idPerfil')
               ->toArray();
    
                $sucursales= array('0' => "Ninguno") + collect($catsucursal)
        ->pluck('sucursal', 'idSucursal')
        ->toArray();

                $actividades = Actividad::all();
                $ofertas = Oferta::all();

                //return $vencimientosOfertas;
                $cantidadVencimientos = count($vencimientos);
                $porRecopilar = 0;
                $recopilados = 0;
                $calificados = 0;

                if ($cantidadVencimientos>0) {
                    $porRecopilar = $cantidadVencimientos;
                    foreach ($vencimientos as $vencimiento) {
                        foreach ($actividades as $actividad){
                            if ($vencimiento->idCliente == $actividad->idcliente){
                                $recopilados ++;
                                $porRecopilar --;
                                $ofertasDesignadas = Oferta::where('idcliente',$actividad->idcliente)->get();
                                if (count($ofertasDesignadas)>0) {
                                    $calificados ++;
                                }
                            } 
                        }
                    }
                }

        return view('socioeconomico.resumenAvance',compact('porRecopilar','recopilados','calificados','cantidadVencimientos'))-> with(['vencimientos'=>$vencimientos,"searchTxt"=>$query])
        ->with(['vendedores'=>$vendedores,"searchTxts"=>$querys])
        //->with(['mes'=>date('F')])
         ->with(['sucursales'=>$sucursales]);;
    }
}
