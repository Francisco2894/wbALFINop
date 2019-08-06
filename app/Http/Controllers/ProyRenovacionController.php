<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB;
use wbALFINop\Actividad;
use wbALFINop\Oferta;
use wbALFINop\Credito;
use wbALFINop\Cliente;
use wbALFINop\BlackList;
use wbALFINop\Producto;

class ProyRenovacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $i = 0;
        $creditos = Credito::all();
        return $productos = Producto::all();
        $contador = 0;
        $noiguales = 0;
        $no_exite = array();
        $idProducto = Producto::where('producto','CREDITO MEJORA VIVIENDA CON SUBSIDIO SUPERV FINANC')->first();

        //return count($creditos);
        //MEJORA DE VIVIENDA SUPERVISION  FINANCIADA-GLOBAL
        // $c = Credito::where('producto','AUTOPRODUCCION TERRATULUM 2018')->get();
        // return count($c);
        foreach ($creditos as $credito) {
            foreach($productos as $producto){
                $cve = $producto->producto;
                if($credito->producto == $producto->producto) {
                    $i = $i+1;
                    $id = $cve;
                }
            }
            if ($i==1) {
                //$contador = $contador + 1;
                $idProducto = Producto::where('producto',$id)->first();
                return $idProducto;
                $c->update(['cveproducto' => $id]);
            }else{
                // if (count($no_exite)>0) {
                //     foreach ($no_exite as $n) {
                //         if ($n == $c->producto) {
                //             $noiguales=0;
                //         }
                //     }
                // }else {
                //     array_push($no_exite,$c->producto);
                // }
                // if ($noiguales==1) {
                //     array_push($no_exite,$c->producto);
                // }
                $c->update(['cveproducto' => '0']);
            }
            $i = 0;
            $id = '';
            $noiguales = 1;
        }

        return 'echo';


        if (Auth::user()->idNivel==6) {
            return Redirect::to('agenda/promocion');
          }
            
                if (Auth::user()->idNivel>3) {
                    if (Auth::user()->idNivel==4) {
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
    
                $datei=date('Y')."/".$nummonth."/01";
    
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

                $ofertas = Oferta::pluck('idcliente');
                $blackList = BlackList::pluck('idcredito');

                // $venc = Credito::
                // leftjoin('tblrenovaciones as r', 'tblcreditos.idCredito', '=', 'r.idCredito')
                // ->join('tblsituacioncredito as s', 'tblcreditos.idCredito', '=', 's.idCredito')
                // ->join('tbldomicilioscredito as dc', 'tblcreditos.idCredito', '=', 'dc.idCredito')
                // ->join('catperfiles as cp', 'tblcreditos.idPerfil', '=', 'cp.idPerfil')
                // ->select('tblcreditos.idCredito','tblcreditos.idCliente', 'tblcreditos.nomCliente', 'tblcreditos.fechaFin', 's.maxDiasAtraso', 'tblcreditos.montoInicial', 'dc.colonia', 'dc.telefonoCelular', DB::raw('IF(r.renueva=0,"No",IF(r.renueva=1,"Si","")) as renueva'), 'r.montoRenovacion')
                // ->where('tblcreditos.idPerfil', '=', $query)
                // ->where("nomCliente", "LIKE", "%{$request->get('cliente')}%")
                // ->whereNotIn('idcliente',[$ofertas])
                // ->whereRaw('fechaFin>="'.$datei.'" and fechaFin<="'.$datef.'"')//fecha de hoy al 31 del otro mes.
                // ->where('s.maxDiasAtraso', "<", 16) //maximo dias atrazado es 16
                // ->orderBy('tblcreditos.fechaFin', 'asc')
                // ->orderBy('dc.colonia', 'desc')
                // ->paginate(40);
                
                // $credito = "";

                // foreach ($blackList as $black) {
                //     foreach ($venc as $v) {
                //         if ($v->idCredito == $black->idcredito) {
                //             $credito = $credito.','.$black->idcredito;
                //         }
                //     }
                // }
                            //$credito = $credito.$black->idcredito;
                // return $cred= Credito::where('idCredito',['102993','103350'])->get(); 
                // return $creditos = substr ($credito , 1);
                
                //return $creditos;
                $vencimientos = Credito::
        leftjoin('tblrenovaciones as r', 'tblcreditos.idCredito', '=', 'r.idCredito')
        ->join('tblsituacioncredito as s', 'tblcreditos.idCredito', '=', 's.idCredito')
        ->join('tbldomicilioscredito as dc', 'tblcreditos.idCredito', '=', 'dc.idCredito')
        ->join('catperfiles as cp', 'tblcreditos.idPerfil', '=', 'cp.idPerfil')
        ->select('tblcreditos.idCredito','tblcreditos.idCliente', 'tblcreditos.nomCliente', 'tblcreditos.fechaFin', 's.maxDiasAtraso', 'tblcreditos.montoInicial', 'dc.colonia', 'dc.telefonoCelular', DB::raw('IF(r.renueva=0,"No",IF(r.renueva=1,"Si","")) as renueva'), 'r.montoRenovacion')
        ->where('tblcreditos.idPerfil', '=', $query)
        ->where("nomCliente", "LIKE", "%{$request->get('cliente')}%") //busqueda de nombre
        ->whereNotIn('tblcreditos.idCliente',$ofertas) //que no este en ofertas
        ->whereNotIn('tblcreditos.idCredito',$blackList) //que no este en lista actual
        ->whereRaw('fechaFin>="'.$datei.'" and fechaFin<="'.$datef.'"')//fecha de hoy al 31 del otro mes.
        ->where('s.maxDiasAtraso', "<", 16) //maximo dias atrazado es 16
        ->orderBy('tblcreditos.fechaFin', 'asc')
        ->orderBy('dc.colonia', 'desc')
        ->paginate(40);

        $vencimientosOfertas = Credito::
        leftjoin('tblrenovaciones as r', 'tblcreditos.idCredito', '=', 'r.idCredito')
        ->join('tblsituacioncredito as s', 'tblcreditos.idCredito', '=', 's.idCredito')
        ->join('tbldomicilioscredito as dc', 'tblcreditos.idCredito', '=', 'dc.idCredito')
        ->join('catperfiles as cp', 'tblcreditos.idPerfil', '=', 'cp.idPerfil')
        ->select('tblcreditos.idCredito','tblcreditos.idCliente', 'tblcreditos.nomCliente', 'tblcreditos.fechaFin', 's.maxDiasAtraso', 'tblcreditos.montoInicial', 'dc.colonia', 'dc.telefonoCelular', DB::raw('IF(r.renueva=0,"No",IF(r.renueva=1,"Si","")) as renueva'), 'r.montoRenovacion')
        ->where('tblcreditos.idPerfil', '=', $query)
        ->where("nomCliente", "LIKE", "%{$request->get('cliente')}%") //busqueda de nombre
        ->whereNotIn('tblcreditos.idCredito',$blackList) //que no este en lista actual
        ->whereRaw('fechaFin>="'.$datei.'" and fechaFin<="'.$datef.'"')//fecha de hoy al 31 del otro mes.
        ->where('s.maxDiasAtraso', "<", 16) //maximo dias atrazado es 16
        ->orderBy('tblcreditos.fechaFin', 'asc')
        ->orderBy('dc.colonia', 'desc')
        ->paginate(40);

    
    
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
    
                $vendedores= array('0' => "Ninguno") + collect($catvendedores)
               ->pluck('nombre', 'idPerfil')
               ->toArray();
    
                $sucursales= array('0' => "Ninguno") + collect($catsucursal)
        ->pluck('sucursal', 'idSucursal')
        ->toArray();

                $actividades = Actividad::all();
                $ofertas = Oferta::all();
    
                return view('agenda.proy_renovacion.index',compact('actividades','ofertas','vencimientosOfertas'))
         -> with(['vencimientos'=>$vencimientos,"searchTxt"=>$query])
         ->with(['vendedores'=>$vendedores,"searchTxts"=>$querys])
         //->with(['mes'=>date('F')])
          ->with(['sucursales'=>$sucursales]);
            
    }

    public function getAsesores(Request $request, $id)
    {
        if ($request->ajax()) {
            $catvendedores=DB::table('catperfiles as cp')
            ->join('catpersonas as p', 'cp.idPersona', '=', 'p.idPersona')
            ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
             ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
            ->select('p.nombre', 'cp.idPerfil')
            ->where('cp.idSucursal', '=', $id)
            ->where('s.estatus', '=', '1')
            ->orderBy('p.nombre', 'desc')->distinct()->get();

            return response()->json($catvendedores);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
