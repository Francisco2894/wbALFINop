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
use wbALFINop\Perfil;

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
        if (Auth::user()->idNivel!=1 && Auth::user()->idNivel!=6 && Auth::user()->idNivel!=3 && Auth::user()->idNivel!=4) {
            return redirect()->route('devengo.index');
        }
        $i = 0;
        $creditos = Credito::all();
        $productos = Producto::all();
        $contador = 0;
        $noiguales = 0;
        $no_exite = array();
        
        //return count($cre = Credito::where('cveproducto',0)->get());
        //Proceso para actualizar el campo cveproducto de la tabla credito
        // foreach ($creditos as $c) {
        //     foreach($productos as $p){
        //         if($c->producto == $p->producto) {
        //             $i = 1;
        //             $id = $p->cveproducto;
        //         }
        //     }
        //     if ($i==1) {
        //         $c->update(['cveproducto' => $id]);
        //     }else{
        //         $c->update(['cveproducto' => '0']);
        //     }
        //     $i = 0;
        //     $id = '';
        // }

        //modificar registros por separado
        // $credito = Credito::where('producto','AUTOPRODUCCIÓN TERRATULUM 2018')->get();
        // foreach ($credito as $c) {
        //     $c->update(['cveproducto' => 'CAVATULUM']);

        // }


        
            
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
                $ofertasCredito = Oferta::pluck('idcredito');
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
                    ->whereNotIn('tblcreditos.idCliente',$ofertas) //que no este en ofertas
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
                    ->where('s.estatus', '=', '1')
                    ->whereIn('tblcreditos.idCredito', $ofertasCredito)
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
                    ->whereNotIn('tblcreditos.idCliente',$ofertas) //que no este en ofertas
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
                    ->where('s.estatus', '=', '1')
                    ->whereIn('tblcreditos.idCredito', $ofertasCredito)
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
                //return $vencimientosOfertas[1]->oferta;
                return view('agenda.proy_renovacion.index',compact('actividades','ofertas','vencimientosOfertas','querys'))
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
