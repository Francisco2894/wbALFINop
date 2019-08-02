<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use wbALFINop\Http\Requests;
use wbALFINop\AgendaDiaria;
use Illuminate\Support\Facades\Redirect;
use DB;

class AgendaDiariaController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }

  public function agendar(Request $reqs)
  {
    $idDevengos= explode(",",$reqs->ids);
    $idPerfil=trim($reqs->perfil);

    $agendados=DB::table('tblagendadiaria as a')
    ->join('tbldevengos as d', 'a.idDevengo', '=', 'd.idDevengo')
    ->join('tblcreditos as c', 'd.idCredito', '=', 'c.idCredito')
    ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
    ->select('a.idAgenda','a.idDevengo')
    ->where('c.idPerfil', '=', $idPerfil)
    ->where('fecha', '=',DB::raw('curdate()'))->get();
//se actualiza a 0 todos para posterior agendar solo los seleccionados
    foreach ($agendados as $agenda) {
        $resetagenda=AgendaDiaria::findOrFail($agenda->idAgenda);
        $resetagenda->estatus=0;
        $resetagenda->update();
    }
//se agendan los seleccionados
    foreach ($idDevengos as $idDevengo) {
      $agendado=DB::table('tblagendadiaria')
      ->where('idDevengo', '=', $idDevengo)
      ->where('fecha', '=',DB::raw('curdate()'))->first();
      if (count($agendado) == 0) {
        $agenda=new AgendaDiaria;
        $agenda->fecha=date('Y-m-d');
        $agenda->estatus=1;
        $agenda->idDevengo=$idDevengo;
        $agenda->save();
      }elseif (count($agendado) > 0) {
        $uagenda=AgendaDiaria::findOrFail($agendado->idAgenda);
        $uagenda->estatus=1;
        $uagenda->update();
      }
    }
    return response()->json(['success'=>"Creditos agendados correctamente"]);
  }
}
