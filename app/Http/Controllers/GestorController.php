<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use wbALFINop\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use DB;
use wbALFINop\Devengo;
use wbALFINop\AgendaDiaria;

class GestorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
      if (Auth::user()->idNivel==6) {
        return Redirect::to('agenda/promocion');
      }elseif (Auth::user()->idNivel==5) {
        return Redirect::to('agenda/devengo');
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
              if (strlen(trim($request ->get('searchTxt')))>0) {
                $query= trim($request ->get('searchTxt'));
                $querys= trim($request ->get('searchTxts'));
              }else {
                $query= 'xxx';
                $querys= 'xxx';
              }

            }

            $devengos=DB::table('tblcreditos as c')
           ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
           ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
           ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
           ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
           ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
           ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
           ->select('d.idDevengo as estatus', 'd.idDevengo', 'c.idCredito', 'c.nomCliente', 'd.fechaDevengo', 'd.cuota', 'd.saldo', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo' ,'a.montoAcuerdo','suc.sucursal')
           ->where('d.fechaDevengo', '>=', DB::raw('curdate()'))
           ->where('d.fechaDevengo', '<', DB::raw('curdate() + 7'))
           ->where('s.diasAtraso', '<=', '0')
           ->where('s.estatus', '=', '1')
           ->where('s.idGestor', '=', $query)
           ->orderBy('d.fechaDevengo', 'asc')
           ->orderBy('dc.colonia', 'asc')
           ->orderBy('d.saldo', 'desc')
           ->get();

            //Se recorre la coleccion actualizando el estatus de la agenda
            $devengos->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                return $devengo;
            });

            $devengosV=DB::table('tblcreditos as c')
           ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
           ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
           ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
           ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
           ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
           ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
           ->select('d.idDevengo as estatus', 'd.idDevengo', 'c.idCredito', 'c.nomCliente', 'd.fechaDevengo', 'd.cuota', 'd.saldo', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo' ,'a.montoAcuerdo','suc.sucursal')
           ->where('s.diasAtraso', '<=', '0')
           ->where('s.estatus', '=', '1')
           ->where('s.idGestor', '=', $query)
           ->whereRaw('c.fechaFin < curdate() and c.fechaFin = d.fechaDevengo')
           ->orderBy('d.fechaDevengo', 'asc')
           ->orderBy('dc.colonia', 'asc')
           ->orderBy('d.saldo', 'desc')
           ->get();

            //Se recorre la coleccion actualizando el estatus de la agenda
            $devengosV->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                return $devengo;
            });

            $devengos1_90=DB::table('tbldevengos as d')
            ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
            ->join('tblcreditos as c', 'd.idCredito', '=', 'c.idCredito')
            ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
            ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
            ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
            ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
            ->select('d.idDevengo as estatus','d.idDevengo as mostrar', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 'd.montoExigible', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo','suc.sucursal')
            ->where('s.diasAtraso', '>', '0')
            ->where('s.diasAtraso', '<', '91')
            ->where('s.estatus', '=', '1')
            ->where('s.idGestor', '=', $query)
            ->whereRaw('c.fechaFin  > curdate()')
            ->where('d.fechaDevengo', '<=', DB::raw('curdate()'))// menor a hoy, el devengo vencido es el anterior
            ->where('d.fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))// > 30 para mostrar en un rango de 30 días
            ->orderBy('dc.colonia', 'desc')
            ->orderBy('s.montoRiesgo', 'desc')->get();

            //Se recorre la coleccion actualizando el estatus de la agenda
            $devengos1_90->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                $maxFdevengo=DB::table('tbldevengos')->where('idCredito', '=', $devengo->idCredito)
                ->where('fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))
                ->where('fechaDevengo', '<=', DB::raw('curdate()'))->max('fechaDevengo');
                //->where('fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))->max('fechaDevengo');
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                if ($devengo->fechaDevengo == $maxFdevengo) {
                  $devengo->mostrar=1;
                }else
                {
                  $devengo->mostrar=0;
                }
                return $devengo;
            });

            $devengosV1_90=DB::table('tblcreditos as c')
            ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
            ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
            ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
            ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
            ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
            ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
            ->select('d.idDevengo as estatus', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 's.saldoExigible', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo','suc.sucursal')
            ->where('s.diasAtraso', '>', '0')
            ->where('s.diasAtraso', '<', '91')
            ->where('s.estatus', '=', '1')
            ->where('s.idGestor', '=', $query)
            ->whereRaw('c.fechaFin < curdate() and c.fechaFin = d.fechaDevengo')
            ->orderBy('dc.colonia', 'desc')
            ->orderBy('s.montoRiesgo', 'desc')
            ->get();

            //Se recorre la coleccion actualizando el estatus de la agenda
            $devengosV1_90->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                return $devengo;
            });

            $devengos_mas90=DB::table('tblcreditos as c')
     ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
     ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
     ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')//se agrega para mejor control una tabla de la situacion del credito
     ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
     ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
     ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
     ->select('d.idDevengo as estatus','d.idDevengo as mostrar', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 'd.montoExigible', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo','suc.sucursal')
     ->where('s.diasAtraso', '>', '90')
     ->where('s.estatus', '=', '1')
     ->where('s.idGestor', '=', $query)
     ->whereRaw('c.fechaFin  > curdate()')
     ->where('d.fechaDevengo', '<=', DB::raw('curdate()'))// menor a hoy, el devengo vencido es el anterior
     ->where('d.fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))// > 30 para mostrar en un rango de 30 días
     ->orderBy('dc.colonia', 'desc')
     ->orderBy('s.montoRiesgo', 'desc')
     //->union($devengos8_90na)
     ->get();

            $devengos_mas90->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                $maxFdevengo=DB::table('tbldevengos')->where('idCredito', '=', $devengo->idCredito)
                ->where('fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))
                ->where('fechaDevengo', '<=', DB::raw('curdate()'))->max('fechaDevengo');
                //->where('fechaDevengo', '<=', DB::raw('curdate()'))
                //->where('fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))->max('fechaDevengo');
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                if ($devengo->fechaDevengo == $maxFdevengo) {
                  $devengo->mostrar=1;
                }else
                {
                  $devengo->mostrar=0;
                }
                return $devengo;
            });

            $devengosV_mas90=DB::table('tblcreditos as c')
    ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
    ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
    ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')//se agrega para mejor control una tabla de la situacion del credito
    ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
    ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
    ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
    ->select('d.idDevengo as estatus', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 's.saldoExigible', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo','suc.sucursal')
    ->where('s.diasAtraso', '>', '90')
    ->where('s.estatus', '=', '1')
    ->where('s.idGestor', '=', $query)
    ->whereRaw('c.fechaFin < curdate() and c.fechaFin = d.fechaDevengo')
    ->orderBy('dc.colonia', 'desc')
    ->orderBy('s.montoRiesgo', 'desc')
    ->get();

            $devengosV_mas90->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                return $devengo;
            });

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
             ->join('tblsituacioncredito as s', 'cp.idPerfil', '=', 's.idGestor')
            ->select('p.nombre', 'cp.idPerfil')
            ->where('cp.idSucursal', '=', $querys)
            //->where('cp.idPerfil','=','s.idGestor')
            //->whereNotNull('s.idGestor')
            ->where('s.estatus', '=', '1')
            ->orderBy('p.nombre', 'desc')->distinct()->get();

            $vendedores= array('0' => "Ninguno") + collect($catvendedores)
           ->pluck('nombre', 'idPerfil')
           ->toArray();

            $sucursales= array('0' => "Ninguno") + collect($catsucursal)
    ->pluck('sucursal', 'idSucursal')
    ->toArray();

            return view('agenda.gestor.index')
     -> with(['devengos'=>$devengos,"searchTxt"=>$query])
     ->with(['devengosV'=>$devengosV])
     ->with(['devengos1_90'=>$devengos1_90])
     ->with(['devengosV1_90'=>$devengosV1_90])
     ->with(['devengos_mas90'=>$devengos_mas90,"searchTxts"=>$querys])
     ->with(['devengosV_mas90'=>$devengosV_mas90])
     ->with(['vendedores'=>$vendedores])
     ->with(['sucursales'=>$sucursales]);
        }
    }

    public function getGestores(Request $request, $id)
    {
        if ($request->ajax()) {
            $catvendedores=DB::table('catperfiles as cp')
            ->join('catpersonas as p', 'cp.idPersona', '=', 'p.idPersona')
             ->join('tblsituacioncredito as s', 'cp.idPerfil', '=', 's.idGestor')
            ->select('p.nombre', 'cp.idPerfil')
            ->where('cp.idSucursal', '=', $id)
            //->where('cp.idPerfil','=','s.idGestor')
            //->whereNotNull('s.idGestor')
            ->where('s.estatus', '=', '1')
            ->orderBy('p.nombre', 'desc')->distinct()->get();

            return response()->json($catvendedores);
        }
    }

    public function create()
    {
    }
    public function store()
    {
    }
    public function show()
    {
    }
    public function edit()
    {
    }
    public function update()
    {
    }
    public function destroy()
    {
    }
}
