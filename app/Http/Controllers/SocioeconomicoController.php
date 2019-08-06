<?php

namespace wbALFINop\Http\Controllers;

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
        $cliente = Credito::where('idCredito',$request->id)->first();
        $clienteRenovacion = Cliente::where('idcliente',$cliente->idCliente)->first();
        return view('socioeconomico.create',compact('clienteRenovacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        $activos = ActivosFijos::create($request->all());
        $otrosIngresos = OtrosIngresos::create($request->all());

        return redirect()->route('socioeconomico.show',$actividad->idact);
    }

    public function ofertas(Request $request,Credito $idCliente)
    {
        $cliente = Cliente::where('idcliente',$idCliente->idCliente)->first();
        //$cliente->ofertas;
        return Response::json($cliente->ofertas);
    }

    public function inventario(Actividad $actividad){
        $inventario = Inventario::where('idact',$actividad->idact)->get();
        return Response::json($inventario);
    }

    public function informacion(Cliente $cliente){
        $actividad = Actividad::where('idcliente',$cliente->idcliente)->first();
        $gastosOperacion = Gastos::where('idact',$actividad->idact)->where('idtipogasto','1')->orderBy('idngasto','ASC')->get();
        $gastosFamiliares = Gastos::where('idact',$actividad->idact)->where('idtipogasto','2')->orderBy('idngasto','ASC')->get();
        $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->first();
        $activos = ActivosFijos::where('idact',$actividad->idact)->first();
        $productos = Inventario::where('idact',$actividad->idact)->get();
        $transacionesVenta = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','2')->orderBy('iddia','ASC')->get();
        $transacionesCompra = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','1')->orderBy('iddia','ASC')->get();

        return view('socioeconomico.info',compact('cliente','gastosOperacion','gastosFamiliares','otrosIngresos','activos',
        'productos','transacionesVenta','transacionesCompra','actividad'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Actividad $socioeconomico)
    {
        //
        $activos = ActivosFijos::where('idact',$socioeconomico->idact)->first();
        $otrosIngresos = OtrosIngresos::where('idact',$socioeconomico->idact)->first();
        
        $productos = Inventario::where('idact',$socioeconomico->idact)->get();
        $transacionesVenta = TransaccionInventario::where('idact',$socioeconomico->idact)->where('idtipotransac','2')->get();
        $transacionesCompra = TransaccionInventario::where('idact',$socioeconomico->idact)->where('idtipotransac','1')->get();
        $gastosOperacion = Gastos::where('idact',$socioeconomico->idact)->where('idtipogasto','1')->get();
        $gastosFamiliares = Gastos::where('idact',$socioeconomico->idact)->where('idtipogasto','2')->get();
        
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
        $capacidadPago = $disponible * 0.3;
        
        return view('socioeconomico.show',compact('activos','otrosIngresos','inventario','totalActivoFijo','totalOtrosIngresos',
        'ventasMensuales','compraMensuales','utilidadBruta','operacion','utilidadNeta','porcentajeOtrosIngresos','familiares','disponible','capacidadPago'));
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
        $actividad = Actividad::where('idcliente',$socioeconomico->idCliente)->first();
        $gastosOperacion = Gastos::where('idact',$actividad->idact)->where('idtipogasto','1')->orderBy('idngasto','ASC')->get();
        $gastosFamiliares = Gastos::where('idact',$actividad->idact)->where('idtipogasto','2')->orderBy('idngasto','ASC')->get();
        $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->first();
        $activos = ActivosFijos::where('idact',$actividad->idact)->first();
        $productos = Inventario::where('idact',$actividad->idact)->get();
        $transacionesVenta = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','2')->orderBy('iddia','ASC')->get();
        $transacionesCompra = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','1')->orderBy('iddia','ASC')->get();

        return view('socioeconomico.edit',compact('socioeconomico','gastosOperacion','gastosFamiliares','otrosIngresos','activos',
        'productos','transacionesVenta','transacionesCompra','actividad'));
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
        $activo = ActivosFijos::where('idact',$actividad->idact)->first();
        $activo->update($request->all());
        $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->first();
        $otrosIngresos->update($request->all());

        return redirect()->route('socioeconomico.show',$actividad->idact);
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
}
