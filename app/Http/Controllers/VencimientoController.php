<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use DB;

class VencimientoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
      if (Auth::user()->idNivel==6) {
        return Redirect::to('agenda/promocion');
      }
        if ($request) {
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
            if ($nummonth>9) {
                $month=$nummonth;
            } else {
                $month="0".$nummonth;
            }

            $datei=date('Y')."/".$month."/01";

            $nummonth=0;

            $nummonth=date('m') + 1;
            if ($nummonth>9) {
                $month=$nummonth;
            } else {
                $month="0".$nummonth;
            }
            if ($nummonth>12) {
               $datef=(date('Y')+ 1)."/"."01"."/01";
            }else {
              $datef=date('Y')."/".$month."/01";
            }

            $vencimientos=DB::table('tblcreditos as c')
    ->leftjoin('tblrenovaciones as r', 'c.idCredito', '=', 'r.idCredito')
    ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
    ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
    ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
    ->select('c.idCredito', 'c.nomCliente', 'c.fechaFin', 's.maxDiasAtraso', 'c.montoInicial', 'dc.colonia', 'dc.telefonoCelular', DB::raw('IF(r.renueva=0,"No",IF(r.renueva=1,"Si","")) as renueva'), 'r.montoRenovacion')
    ->where('c.idPerfil', '=', $query)
    ->whereRaw('fechaFin>="'.$datei.'" and fechaFin<"'.$datef.'"')
    ->orderBy('c.fechaFin', 'asc')
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


            return view('agenda.vencimiento.index')
     -> with(['vencimientos'=>$vencimientos,"searchTxt"=>$query])
     ->with(['vendedores'=>$vendedores,"searchTxts"=>$querys])
     //->with(['mes'=>date('F')])
      ->with(['sucursales'=>$sucursales]);
        }
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
}
