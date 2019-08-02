<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use wbALFINop\Http\Requests\ProspectobcFrmRequest;

use DB;
use wbALFINop\Prospectobc;

class ProspectobcController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  public function index(Request $request)
  { if ($request) {
       if (Auth::user()->idNivel>3) {
           if (Auth::user()->idNivel==4 || Auth::user()->idNivel==6) {
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
       if (trim($request ->get('searchTxt'))=='T0ALL') {
         $condicion='cp.idSucursal';
         $query= $querys;
       }else {
         $condicion='cp.idPerfil';
       }

       $prospectos=DB::table('tblprospectosbc as pr')
       ->join('cattiposprospecto as tp', 'pr.idTipoProspecto', '=', 'tp.idTipoProspecto')
       ->join('cattiposcliente as tc', 'pr.idTipoCliente', '=', 'tc.idTipoCliente')
      ->join('cattiposproducto as tpr', 'pr.idTipoProducto', '=', 'tpr.idTipoProducto')
      ->join('catestatus as e', 'pr.idEstatus', '=', 'e.idEstatus')
      ->join('catperfiles as cp', 'pr.idPerfil', '=', 'cp.idPerfil')
      ->select('pr.folio',DB::raw('concat(pr.nombre," ",pr.paterno," ",pr.materno) as nombre'),'pr.score','pr.fechaConsulta','tp.tipoProspecto','tc.tipoCliente','tpr.tipoProducto','pr.montoSolicitud','e.estatus')
      ->where($condicion, '=', $query)
      //->whereRaw('pr.fechaConsulta>="'.$datei.'" and pr.fechaConsulta<"'.$datef.'"')
      ->where('pr.fechaConsulta', '<=', DB::raw('curdate()'))
      ->where('pr.fechaConsulta', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))// intervalo de 1 mes
      ->get();

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
//Sustituir por  funcion getAsesoresp
       $catvendedores=DB::table('catperfiles as cp')
      ->join('catpersonas as p', 'cp.idPersona', '=', 'p.idPersona')
      ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
      ->select('p.nombre', 'cp.idPerfil')
      ->where('cp.idSucursal', '=', $querys)
      ->orderBy('p.nombre', 'desc')->get();

       $vendedores= array('0' => "Ninguno") + array('T0ALL' => "Todos") + collect($catvendedores)
      ->pluck('nombre', 'idPerfil')
      ->toArray();

       $sucursales= array('0' => "Ninguno") + collect($catsucursal)
      ->pluck('sucursal', 'idSucursal')
      ->toArray();


       return view('agenda.promocion.index')
      -> with(['prospectos'=>$prospectos,"searchTxt"=>$query])
      ->with(['vendedores'=>$vendedores,"searchTxts"=>$querys])
      ->with(['sucursales'=>$sucursales]);
   }
}
  public function create(Request $id)
  {
    if (Auth::user()->idNivel<>5) {

     $sucursal= trim($id ->get('searchTxts'));
     $vendedor= trim($id ->get('searchTxt'));
    $tiposProspecto=$this->getTiposProspecto();
    $tiposCliente=$this->getTiposCliente();
    $tiposProducto=$this->getTiposProducto();
    $estatus=$this->getEstatus();
    $asesores=$this->getAsesoresp($sucursal);

    return View('agenda.promocion.create')
     ->with(['tiposProspecto'=>$tiposProspecto])
     ->with(['tiposCliente'=>$tiposCliente])
     ->with(['tiposProducto'=>$tiposProducto])
     ->with(['estatus'=>$estatus])
     ->with(['vendedores'=>$asesores])
     ->with(['vendedor'=>$vendedor]);
   }else {
     return Redirect::to('agenda/promocion');
   }
  }
  public function store(ProspectobcFrmRequest $request)
  {
    $prospecto=new Prospectobc;
    $prospecto->folio=$request->get('folio');
    $prospecto->nombre=strtoupper($request->get('nombre'));
    $prospecto->paterno=strtoupper($request->get('paterno'));
    $prospecto->materno=strtoupper($request->get('materno'));
    $prospecto->score=$request->get('score');
    $prospecto->fechaConsulta=$request->get('fechaConsulta');
    $prospecto->idTipoProspecto=$request->get('tipoProspecto');
    $prospecto->idTipoCliente=$request->get('tipoCliente');
    $prospecto->idTipoProducto=$request->get('tipoProducto');
    $prospecto->montoSolicitud=$request->get('montoSolicitud');
    $prospecto->idEstatus=$request->get('estatus');
    $prospecto->idPerfil=$request->get('perfil');
    $prospecto->idPerfilCap=Auth::user()->idPerfil;
    $prospecto->save();

    $sucursal=DB::table('catperfiles')->where('idPerfil','=',$prospecto->idPerfil)->value('idSucursal');

    return redirect()->action('ProspectobcController@index',['searchTxts'=>$sucursal,'searchTxt'=>$prospecto->idPerfil]);
  }
  public function show(Request $id)
  {
    return view("agenda.promocion.show", ["prospectobc"=>Prospectobc::findOrFail($id)]);
  }
  public function edit($id)
  {
    if (Auth::user()->idNivel<>5) {
    $prospecto=Prospectobc::findOrFail($id);
    $sucursal=DB::table('catperfiles')->where('idPerfil','=',$prospecto->idPerfil)->value('idSucursal');
    $tiposProspecto=$this->getTiposProspecto();
    $tiposCliente=$this->getTiposCliente();
    $tiposProducto=$this->getTiposProducto();
    $estatus=$this->getEstatus();
    $asesores=$this->getAsesoresp($sucursal);

    return View('agenda.promocion.edit')
    ->with(["prospecto"=>$prospecto])
    ->with(['tiposProspecto'=>$tiposProspecto])
    ->with(['tiposCliente'=>$tiposCliente])
    ->with(['tiposProducto'=>$tiposProducto])
    ->with(['estatus'=>$estatus])
    ->with(['vendedores'=>$asesores]);
  }else {
    return Redirect::to('agenda/promocion');
  }
  }
  public function update(ProspectobcFrmRequest $request, $id)
  {
    $prospecto=Prospectobc::findOrFail($id);;
    $prospecto->folio=$request->get('folio');
    $prospecto->nombre=strtoupper($request->get('nombre'));
    $prospecto->paterno=strtoupper($request->get('paterno'));
    $prospecto->materno=strtoupper($request->get('materno'));
    $prospecto->score=$request->get('score');
    $prospecto->fechaConsulta=$request->get('fechaConsulta');
    $prospecto->idTipoProspecto=$request->get('tipoProspecto');
    $prospecto->idTipoCliente=$request->get('tipoCliente');
    $prospecto->idTipoProducto=$request->get('tipoProducto');
    $prospecto->montoSolicitud=$request->get('montoSolicitud');
    $prospecto->idEstatus=$request->get('estatus');
    $prospecto->idPerfil=$request->get('perfil');
    $prospecto->idPerfilCap=Auth::user()->idPerfil;
    $prospecto->update();

    $sucursal=DB::table('catperfiles')->where('idPerfil','=',$prospecto->idPerfil)->value('idSucursal');

    return redirect()->action('ProspectobcController@index',['searchTxts'=>$sucursal,'searchTxt'=>$prospecto->idPerfil]);
    //return Redirect::to('agenda/promocion');
  }
  public function destroy($id)
  {
    $idprospecto=$id;
    $prospecto=Prospectobc::findOrFail($id);
    $sucursal=DB::table('catperfiles')->where('idPerfil','=',$prospecto->idPerfil)->value('idSucursal');
    $vendedor=$prospecto->idPerfil;
    if (Auth::user()->idNivel<4) {
    $prospecto->delete();
    }
  return redirect()->action('ProspectobcController@index',['searchTxts'=>$sucursal,'searchTxt'=>$vendedor]);
  }

  //Funciones para obtener catalogos

  public function getAsesores(Request $request, $id)
  {
     if ($request->ajax()) {
         $catvendedores=DB::table('catperfiles as cp')
       ->join('catpersonas as p', 'cp.idPersona', '=', 'p.idPersona')
       ->select('p.nombre', 'cp.idPerfil')
       ->where('cp.idSucursal', '=', $id)
       ->orderBy('p.nombre', 'desc')->get();
         return response()->json($catvendedores);
     }
  }
  public function getAsesoresp($id)
  {
        if (Auth::user()->idNivel==5) {
            $condicion='cp.idPerfil';
            $query=Auth::user()->idPerfil;
        }else {
          $condicion='cp.idSucursal';
          $query=$id;
        }

        $catvendedores=DB::table('catperfiles as cp')
      ->join('catpersonas as p', 'cp.idPersona', '=', 'p.idPersona')
      ->select('p.nombre', 'cp.idPerfil')
      ->where($condicion, '=', $query)
      ->orderBy('p.nombre', 'desc')->get();
      $vendedores=collect($catvendedores)
     ->pluck('nombre', 'idPerfil')
     ->toArray();

    return $vendedores;
  }
  function getTiposProspecto()
  {
    $catTiposP=DB::table('cattiposprospecto as t')
   ->select('t.tipoProspecto', 't.idTipoProspecto')
   ->orderBy('t.idTipoProspecto', 'asc')->get();

    $tiposProspecto=collect($catTiposP)
   ->pluck('tipoProspecto', 'idTipoProspecto')
   ->toArray();

   return $tiposProspecto;
  }
  function getTiposCliente()
  {
    $catTiposC=DB::table('cattiposcliente as t')
   ->select('t.tipoCliente', 't.idTipoCliente')
   ->orderBy('t.idTipoCliente', 'asc')->get();

    $tiposCliente=collect($catTiposC)
   ->pluck('tipoCliente', 'idTipoCliente')
   ->toArray();

   return $tiposCliente;
  }
  function getTiposProducto()
  {
    $catTiposP=DB::table('cattiposproducto as t')
   ->select('t.tipoProducto', 't.idTipoProducto')
   ->orderBy('t.idTipoProducto', 'asc')->get();

    $tiposProducto=collect($catTiposP)
   ->pluck('tipoProducto', 'idTipoProducto')
   ->toArray();

   return $tiposProducto;
  }

  function getEstatus()
  {
    $catEstatus=DB::table('catestatus as t')
   ->select('t.estatus', 't.idEstatus')
   ->orderBy('t.idEstatus', 'asc')->get();

    $estatus=collect($catEstatus)
   ->pluck('estatus', 'idEstatus')
   ->toArray();

   return $estatus;
  }
}
