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
use wbALFINop\Devengo;
use wbALFINop\OfertaDos;
use Maatwebsite\Excel\Facades\Excel;

class ConsultaController extends Controller
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

    public function ofertas()
    {
        $datei = date('Y-m-d');
        $ofertas = OfertaDos::
        join('tblcreditos as cr','cr.idCredito','=','tbloferta_2.idCredito')
        ->join('tblinfcrediticia as inf','inf.idcliente','=','cr.idCliente')
        ->join('cattipooferta as top','top.idto','=','tbloferta_2.idto')
        ->select('cr.idCliente','cr.idCredito','cr.nomCliente','cr.montoInicial','cr.frecuenciaPago','cr.plazo','tbloferta_2.idto','tbloferta_2.fechai','tbloferta_2.fechaf','tbloferta_2.plazo as plazoOferta','tbloferta_2.monto','tbloferta_2.cuota','tbloferta_2.frecuencia','top.descripcion','inf.score',DB::raw('max(fechaconsulta) as fechaconsulta'))
        ->groupBy(['inf.score','cr.idCliente','cr.idCredito','cr.nomCliente','cr.montoInicial','cr.frecuenciaPago','cr.plazo','tbloferta_2.idto','tbloferta_2.fechai','tbloferta_2.fechaf','plazoOferta','tbloferta_2.monto','tbloferta_2.cuota','tbloferta_2.frecuencia','top.descripcion'])
        ->where('tbloferta_2.fechaf','>=',$datei)//fecha de hoy al 31 del otro mes.
        ->orderBy('idCredito','ASC')
        ->get();

        Excel::create('Ofertas', function($excel) use ($ofertas){
            $excel->sheet('dtResOP', function($sheet) use($ofertas) {
                $sheet->row(1,['ID Cliente','ID Credito','Nombre','monto','frecuencia ','plazo','cuotaAnterior','Score','Tipo','Fecha Inicio','Fecha Fin','Plazo','Monto','Frecuencia','CuotaOfera']);
                $sheet->setColumnFormat(array(
                    'D'=>'0.00',
                    'I'=>'dd-mm-YYYY',
                    'J'=>'dd-mm-YYYY',
                    'L'=>'0.00',
                    'N'=>'0.00',
                ));
                foreach ($ofertas as $res) {
                    $row=[];
                    $row[0]=$res->idCliente;
                    $row[1]=$res->idCredito;
                    $row[2]=$res->nomCliente;
                    $row[3]=$res->montoInicial;
                    $row[4]=$res->frecuenciaPago;
                    $row[5]=$res->plazo;
                    $row[6]=$res-> Devengo::where('idCredito','=', $res->idCredito)->first();
                    $row[7]=$res->score;
                    $row[8]=$res->descripcion;
                    $row[9]=date("d/m/Y", strtotime($res->fechai));
                    $row[10]=date("d/m/Y", strtotime($res->fechaf));
                    $row[11]=$res->plazoOferta;
                    $row[12]=$res->monto;
                    $row[13]=$res->frecuencia==1?'Mensual':'';
                    $row[14]=$res->cuota;
                    $sheet->appendRow($row);
                }
            });
        })->download('xlsx');
    }

    public function renovaciones()
    {
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
        $blackListp = BlackList::pluck('idcliente');

        // $devengos = Devengo::select(DB::raw('max(fechaDevengo) as fechaDevengo'))
        // ->groupBy(['idCredito','cuota'])
        // ->whereIn('idCredito',$vencimientos)
        // ->get();

        $vencimientos = Credito::
        join('tbldevengos as dv', 'dv.idCredito', '=', 'tblcreditos.idCredito')
        ->join('tblsituacioncredito as s', 'tblcreditos.idCredito', '=', 's.idCredito')
        ->join('tbldomicilioscredito as dc', 'tblcreditos.idCredito', '=', 'dc.idCredito')
        ->join('catperfiles as cp', 'tblcreditos.idPerfil', '=', 'cp.idPerfil')
        ->join('catproducto as catp', 'tblcreditos.cveproducto', '=', 'catp.cveproducto') //agregamos la relacion con catproducto
        ->join('catsucursales as sc', 'cp.idSucursal','=','sc.idSucursal')
        ->join('catregionales as rg', 'rg.idRegional','=','sc.idRegional')
        ->select('tblcreditos.idCredito','tblcreditos.idCliente', 'tblcreditos.nomCliente', 'tblcreditos.fechaFin', 's.maxDiasAtraso', 'tblcreditos.montoInicial', 'dc.colonia', 'dc.telefonoCelular','catp.refinan_si','sc.sucursal','rg.descripcion','catp.producto','tblcreditos.frecuenciaPago','dv.cuota',DB::raw('max(fechaDevengo) as fechaDevengo'))
        ->groupBy(['tblcreditos.idCredito','tblcreditos.idCliente', 'tblcreditos.nomCliente', 'tblcreditos.fechaFin', 's.maxDiasAtraso', 'tblcreditos.montoInicial', 'dc.colonia', 'dc.telefonoCelular','catp.refinan_si','sc.sucursal','rg.descripcion','catp.producto','tblcreditos.frecuenciaPago','dv.cuota',])
        ->where('s.estatus', '=', '1')
        ->whereNotIn('tblcreditos.idCliente',$ofertas) //que no este en ofertas
        ->whereNotIn('tblcreditos.idCliente',$blackListp) //que no este en lista actual
        ->whereRaw('fechaFin>="'.$datei.'" and fechaFin<="'.$datef.'"')//fecha de hoy al 31 del otro mes.
        ->where('s.maxDiasAtraso', "<", 31) //maximo dias atrazado es 16
        ->where('refinan_si',1) //refinan_si con valor en 1
        ->orderBy('tblcreditos.fechaFin', 'ASC')
        ->orderBy('dc.colonia', 'DESC')
        ->get();

        Excel::create('Vencimientos', function($excel) use ($vencimientos){
            $excel->sheet('dtResOP', function($sheet) use($vencimientos) {
                $sheet->row(1,['ID Cliente','ID Credito','Nombre','Fecha Fin','Max Atraso','Monto Inicial','Colonia','Telefono','Sucursal','Regional','Producto','Frecuencia','Cuota']);
                $sheet->setColumnFormat(array(
                    'D'=>'dd/mm/yyyy',
                    'F'=>'0.00',
                    'M'=>'0.00',
                ));
                foreach ($vencimientos as $res) {
                    $row=[];
                    $row[0]=$res->idCliente;
                    $row[1]=$res->idCredito;
                    $row[2]=$res->nomCliente;
                    $row[3]=date("d/m/Y", strtotime($res->fechaFin));
                    $row[4]=$res->maxDiasAtraso;
                    $row[5]=$res->montoInicial;
                    $row[6]=$res->colonia;
                    $row[7]=$res->telefonoCelular;
                    $row[8]=$res->sucursal;
                    $row[9]=$res->descripcion;
                    $row[10]=$res->producto;
                    $row[11]=$res->frecuenciaPago;
                    $row[12]=$res->cuota;
                    $sheet->appendRow($row);
                }
            });
        })->download('xlsx');
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
