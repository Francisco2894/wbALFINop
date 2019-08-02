<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use wbALFINop\Http\Requests;
use wbALFINop\Renovacion;
use Illuminate\Support\Facades\Redirect;
use wbALFINop\Http\Requests\RenovacionFrmRequest;
use DB;

class RenovacionController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
  public function index(Request $request)
  {
  }

  public function create(Request $id)
  {
      $query= trim($id ->get('id'));
      $renovacion=DB::table('tblrenovaciones')
  ->where('idCredito', '=', $query)->value('idRenovacion');

  $opcionRenueva=array('0' => "No") + array('1' => "Si");

      if ($renovacion > 0) {
          return redirect()->action('RenovacionController@edit', $renovacion);
      } else {
          return View('agenda.renovacion.create')
           ->with(['renueva'=>$opcionRenueva,'id'=>$query]);
      }
  }
  public function store(RenovacionFrmRequest $request)
  {
        $idr=DB::table('tblrenovaciones')
    ->where('idCredito', '=', $request->get('sltIdCredito'))->value('idRenovacion');
      if ($idr > 0) {
      }else {
        $renovacion=new Renovacion;
        $renovacion->renueva=$request->get('sltRenueva');
        $renovacion->montoRenovacion=$request->get('txtMontoRenovacion');
        $renovacion->descripcion=strtoupper($request->get('txtDescripcion'));
        $renovacion->fechaRenovacion=$request->get('dtpFechaRenovacion');
        $renovacion->idCredito=$request->get('sltIdCredito');
        $renovacion->save();
      }

      $vendedor=$this->getAsesor($request->get('sltIdCredito'));

      return redirect()->action('VencimientoController@index',['searchTxts'=>$vendedor->idSucursal,'searchTxt'=>$vendedor->idPerfil]);
      //return Redirect::to('agenda/vencimiento');
  }
  public function show(Request $id)
  {
      return view("agenda.renovacion.show", ["renovacion"=>Renovacion::findOrFail($id)]);
  }
  public function edit($id)
  {
    $opcionRenueva=array('0' => "No") + array('1' => "Si");
      return view('agenda.renovacion.edit', ["renovacion"=>Renovacion::findOrFail($id),'renueva'=>$opcionRenueva]);
  }
  public function update(RenovacionFrmRequest $request, $id)
  {
      $renovacion=Renovacion::findOrFail($id);
      $renovacion->renueva=$request->get('sltRenueva');
      $renovacion->montoRenovacion=$request->get('txtMontoRenovacion');
      $renovacion->descripcion=strtoupper($request->get('txtDescripcion'));
      $renovacion->fechaRenovacion=$request->get('dtpFechaRenovacion');
      $renovacion->idCredito=$request->get('sltIdCredito');
      $renovacion->update();

      $vendedor=$this->getAsesor($request->get('sltIdCredito'));

      return redirect()->action('VencimientoController@index',['searchTxts'=>$vendedor->idSucursal,'searchTxt'=>$vendedor->idPerfil]);

      //return redirect::to('agenda/vencimiento');
  }
  public function destroy()
  {
  }
  public function getAsesor($id)
  {
    $vendedor=DB::table('catperfiles as cp')
   ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
   ->select('cp.idSucursal', 'cp.idPerfil')
   ->where('c.idCredito', '=', $id)->first();

    return $vendedor;
  }
}
