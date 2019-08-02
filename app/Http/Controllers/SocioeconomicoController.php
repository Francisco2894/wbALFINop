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
        for ($i=1; $i <= 10; $i++) {
            $dia ++;
            if ($dia>5) { $dia = 1; }
            if ($i>5) { $tipo_transaccion = 2; }
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
    public function edit($id)
    {
        //
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
