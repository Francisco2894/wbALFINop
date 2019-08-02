<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use wbALFINop\Http\Requests;
use wbALFINop\Acuerdo;
use Illuminate\Support\Facades\Redirect;
use wbALFINop\Http\Requests\AcuerdoFrmRequest;
use DB;

class AcuerdoController extends Controller
{
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

            $acuerdos=DB::table('tbldevengos as d')
           ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
           ->join('cattiposresultado as ctr', 'a.idResultado', '=', 'ctr.idResultado')
           ->join('tblresultado as r', 'a.idAcuerdo', '=', 'r.idAcuerdo')
           ->join('catresultado as cr', 'r.idResult', '=', 'cr.idResult')

           ->join('tblcredito as c', 'd.idCredito', '=', 'c.idCredito')
           ->join('tblsituacioncredito as s', 'd.idCredito', '=', 's.idCredito')
           ->join('tbldomicilioscredito as dc', 'd.idCredito', '=', 'dc.idCredito')
           ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
           ->select('d.idDevengo as estatus', 'c.idCredito', 'c.nomCliente', 'd.fechaDevengo','d.montoExigible', 'a.fechaAcuerdo','a.montoAcuerdo',
           DB::raw('IF(ctr.renueva=0,"No",IF(ctr.renueva=1,"Si","")) as TipoAcuerdo'), 'dc.colonia', 'dc.telefonoCelular', 'r.fecha','r.monto','cr.descripcion')
           ->where('s.estatus', '=', '1')
           ->whereNull('s.idGestor')
           ->where('c.idPerfil', '=', $query)
           ->whereRaw('c.fechaFin  > curdate()')
           ->where('d.fechaDevengo', '<=', DB::raw('curdate()'))// menor a hoy, el devengo vencido es el anterior
           ->where('d.fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))// > 30 para mostrar en un rango de 30 días
           ->orderBy('dc.colonia', 'desc')
           ->orderBy('s.montoRiesgo', 'desc')->get();

            //Se recorre la coleccion actualizando el estatus de la agenda
            $acuerdos->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                $maxFdevengo=DB::table('tbldevengos')->where('idCredito', '=', $devengo->idCredito)
                ->where('fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))
                ->where('fechaDevengo', '<=', DB::raw('curdate()'))->max('fechaDevengo');
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

          //para que los resgionales solo vean las sucursales a su cargo
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
            ->whereNull('s.idGestor')
           ->orderBy('p.nombre', 'desc')->distinct()->get();

            $vendedores= array('0' => "Ninguno") + collect($catvendedores)
           ->pluck('nombre', 'idPerfil')
           ->toArray();

            $sucursales= array('0' => "Ninguno") + collect($catsucursal)
    ->pluck('sucursal', 'idSucursal')
    ->toArray();


            return view('agenda.devengo.index')
     -> with(['devengos'=>$devengos,"searchTxt"=>$query])
     ->with(['devengos1_7'=>$devengos1_7])
     ->with(['devengos8_90'=>$devengos8_90,"searchTxts"=>$querys])
     ->with(['devengosV1_7'=>$devengosV1_7])
     ->with(['devengosV8_90'=>$devengosV8_90])
     ->with(['vendedores'=>$vendedores])
     ->with(['sucursales'=>$sucursales]);
        }
    }
    public function create(Request $id)
    {
        $query= trim($id ->get('id'));
        $acuerdo=DB::table('tblacuerdos')
    ->where('idDevengo', '=', $query)->value('idAcuerdo');

        $catTiposResultado=DB::table('cattiposresultado as t')
   ->select('t.descripcion', 't.idResultado')
   ->orderBy('t.idResultado', 'asc')->get();

        $tiposResultado=collect($catTiposResultado)
   ->pluck('descripcion', 'idResultado')
   ->toArray();

   // Hacer consulta uniendo las tablas devengos y credito y obtener nombre.

        //$countAcuerdo=$acuerdo->count();
        if ($acuerdo > 0) {
            return redirect()->action('AcuerdoController@edit', $acuerdo);
        } else {
            return View('agenda.acuerdo.create')
             ->with(['tiposResultado'=>$tiposResultado,'id'=>$query]);
        }
    }
    public function store(AcuerdoFrmRequest $request)
    {
      // Agregar validacion si existe
        $acuerdo=new Acuerdo;
        $acuerdo->acuerdo=strtoupper($request->get('txtAcuerdo'));
        $acuerdo->montoAcuerdo=$request->get('txtMontoAcuerdo');
        $acuerdo->fechaAcuerdo=$request->get('dtpFechaAcuerdo');
        $acuerdo->idDevengo=$request->get('sltIdDevengo');
        $acuerdo->idResultado=$request->get('sltIdResultado');
        $acuerdo->save();

        $gestor=$this->getGestor($request->get('sltIdDevengo'));
        if (empty($gestor)) {
          $vendedor=$this->getAsesor($request->get('sltIdDevengo'));
          return redirect()->action('DevengoController@index',['searchTxts'=>$vendedor->idSucursal,'searchTxt'=>$vendedor->idPerfil]);
        }else {
          return redirect()->action('GestorController@index',['searchTxts'=>$gestor->idSucursal,'searchTxt'=>$gestor->idGestor]);
        }
        //return Redirect::to('agenda/devengo');
    }
    public function show(Request $id)
    {
        return view("agenda.acuerdo.show", ["acuerdo"=>Acuerdo::findOrFail($id)]);
    }
    public function edit($id)
    {
      $catTiposResultado=DB::table('cattiposresultado as t')
     ->select('t.descripcion', 't.idResultado')
     ->orderBy('t.idResultado', 'asc')->get();

        $tiposResultado=collect($catTiposResultado)
     ->pluck('descripcion', 'idResultado')
     ->toArray();

        return view('agenda.acuerdo.edit', ["acuerdo"=>Acuerdo::findOrFail($id),'tiposResultado'=>$tiposResultado]);
    }
    public function update(AcuerdoFrmRequest $request, $id)
    {
        $acuerdo=Acuerdo::findOrFail($id);
        $acuerdo->acuerdo=strtoupper($request->get('txtAcuerdo'));
        $acuerdo->montoAcuerdo=$request->get('txtMontoAcuerdo');
        $acuerdo->fechaAcuerdo=$request->get('dtpFechaAcuerdo');
        $acuerdo->idDevengo=$request->get('sltIdDevengo');
        $acuerdo->idResultado=$request->get('sltIdResultado');
        $acuerdo->update();

        $gestor=$this->getGestor($request->get('sltIdDevengo'));
        if (empty($gestor)) {
          $vendedor=$this->getAsesor($request->get('sltIdDevengo'));
          return redirect()->action('DevengoController@index',['searchTxts'=>$vendedor->idSucursal,'searchTxt'=>$vendedor->idPerfil]);
        }else {
          return redirect()->action('GestorController@index',['searchTxts'=>$gestor->idSucursal,'searchTxt'=>$gestor->idGestor]);
        }
        //return redirect::to('agenda/devengo');
    }
    public function destroy()
    {
    }
    public function getAsesor($id)
    {
      $vendedor=DB::table('catperfiles as cp')
     ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
     ->join('tbldevengos as d', 'c.idCredito','=','d.idCredito')
     ->select('cp.idSucursal', 'cp.idPerfil')
     ->where('d.idDevengo', '=', $id)->first();

      return $vendedor;
    }
    public function getGestor($id)
    {
     $gestor=DB::table('catperfiles as cp')
      ->join('tblsituacioncredito as s', 'cp.idPerfil', '=', 's.idGestor')
      ->join('tbldevengos as d', 's.idCredito','=','d.idCredito')
     ->select('cp.idSucursal', 's.idGestor')
     ->where('d.idDevengo', '=', $id)->first();

      return $gestor;
    }
}
