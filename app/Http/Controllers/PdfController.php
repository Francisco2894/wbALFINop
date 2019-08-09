<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

use DB;
use wbALFINop\AgendaDiaria;
use wbALFINop\Devengo;
use wbALFINop\Actividad;
use wbALFINop\Cliente;
use wbALFINop\Dia;
use wbALFINop\TipoTransaccion;
use wbALFINop\TransaccionInventario;
use wbALFINop\Inventario;
use wbALFINop\CatGasto;
use wbALFINop\Gastos;
use wbALFINop\TipoGasto;
use wbALFINop\OtrosIngresos;
use wbALFINop\ActivosFijos;
use wbALFINop\Credito;
use wbALFINop\Oferta;
use wbALFINop\CatOferta;
use wbALFINop\Sucursal;

class PdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function resultadosRenovacion(Request $request, Cliente $cliente){
        $actividad = Actividad::where('idcliente',$cliente->idcliente)->first();
        $gastosOperacion = Gastos::where('idact',$actividad->idact)->where('idtipogasto','1')->orderBy('idngasto','ASC')->get();
        $gastosFamiliares = Gastos::where('idact',$actividad->idact)->where('idtipogasto','2')->orderBy('idngasto','ASC')->get();
        $otrosIngresos = OtrosIngresos::where('idact',$actividad->idact)->first();
        $activos = ActivosFijos::where('idact',$actividad->idact)->first();
        $productos = Inventario::where('idact',$actividad->idact)->get();
        $transacionesVenta = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','2')->orderBy('iddia','ASC')->get();
        $transacionesCompra = TransaccionInventario::where('idact',$actividad->idact)->where('idtipotransac','1')->orderBy('iddia','ASC')->get();
        $totalc=0;
        $totalv=0;
        $totalo=0;
        $totalf=0;
        $totaloi=0;
        $totala=0;
        $totalp=0;
        $totalpv=0;

        $sucursal = Sucursal::where('idSucursal',$request->sucursal)->first();

        foreach ($transacionesVenta as $venta) {
            $totalv = $totalv + $venta->monto;
        }
        foreach ($transacionesCompra as $compra) {
            $totalc = $totalc + $compra->monto;
        }
        foreach($gastosOperacion as $operacion){
            $totalo = $totalo + $operacion->monto;
        }
        foreach($gastosFamiliares as $familiar){
            $totalf = $totalf + $familiar->monto;
        }
        foreach ($productos as $producto) {
            $totalp = $totalp + ($producto->cantidad * $producto->precio_compra);
        }
        foreach ($productos as $producto) {
            $totalpv = $totalpv + round((($producto->precio_venta - $producto->precio_compra)/$producto->precio_compra)*100);
        }
        $totalpv = $totalpv/count($productos);
        $totaloi = $otrosIngresos->otro_negocio + $otrosIngresos->conyuge + $otrosIngresos->empleo;
        $totala = $activos->local + $activos->auto + $activos->maquinaria;

        $pdf = PDF::loadView('socioeconomico.pdfinfo', compact('cliente','gastosOperacion','gastosFamiliares','otrosIngresos','activos',
        'productos','transacionesVenta','transacionesCompra','actividad','totalc','totalv','totalo','totalf','totaloi','totala','totalp',
        'totalpv','cliente','sucursal'));

        return $pdf->stream('listado.pdf');
    }

    public function getPdfagnd(request $request)
    {
        if ($request) {
            if (Auth::user()->idNivel==5) {
                $query=Auth::user()->idPerfil;
            } else {
                $query= trim($request ->get('searchTxt'));
            }

          $resumen=DB::table('tblcreditos as c')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
         ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
         ->select('c.idPerfil',DB::raw('count(c.idCredito) as cuenta'),DB::raw('sum(s.saldo) as saldo'),DB::raw('0 as corriente'),DB::raw('0 as saldocorriente'),DB::raw('0 as riesgo'),DB::raw('0 as saldoriesgo'),DB::raw('sum(s.capitalVigente) as capitalVigente'),DB::raw('sum(s.capitalVencido) as capitalVencido',DB::raw('0 as normalidad')))
         ->where('s.diasAtraso', '<', '91')
         ->where('s.estatus', '=', '1')
         ->whereNull('s.idGestor')
         ->where('c.idPerfil', '=', $query)
         ->groupBy('c.idPerfil')
         ->get();

        $resumen->transform(function ($res, $key) {
         $corriente=DB::table('tblcreditos as c')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
         ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
         ->select(DB::raw('count(c.idCredito) as cuenta'),DB::raw('sum(s.saldo) as saldo'))
         ->where('s.diasAtraso', '<=', '0')
         ->where('s.estatus', '=', '1')
         ->whereNull('s.idGestor')
         ->where('c.idPerfil', '=', $res->idPerfil)
         ->groupBy('c.idPerfil')
         ->get();
         $atraso=DB::table('tblcreditos as c')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
         ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
         ->select(DB::raw('count(c.idCredito) as cuenta'),DB::raw('sum(s.saldo) as saldo'))
         ->where('s.diasAtraso', '>', '0')
         ->where('s.diasAtraso', '<', '91')
         ->where('s.estatus', '=', '1')
         ->whereNull('s.idGestor')
         ->where('c.idPerfil', '=', $res->idPerfil)
         ->groupBy('c.idPerfil')
         ->get();

         foreach ($corriente as $val) {
           $res->corriente=$val->cuenta;
           $res->saldocorriente=$val->saldo;
         }
         foreach ($atraso as $val) {
           $res->riesgo=$val->cuenta;
           $res->saldoriesgo=$val->saldo;
         }
         if ($res->saldocorriente>0 && $res->saldo>0) {
           $res->normalidad=($res->saldocorriente/$res->saldo)*100;
         }elseif ($res->saldo>0) {
           $res->normalidad=100;
         }else {
           $res->normalidad=0;
         }
         return $res;
     });


            $devengos=DB::table('tblcreditos as c')
     ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
     ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
     ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
     ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
     ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
     ->select('d.idDevengo as estatus', 'd.idDevengo', 'c.idCredito', 'c.nomCliente', 'd.fechaDevengo', 'd.cuota', 'd.saldo', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo','a.montoAcuerdo')
     ->where('d.fechaDevengo', '>=', DB::raw('curdate()'))
     ->where('d.fechaDevengo', '<', DB::raw('curdate() + 4'))
     ->where('s.diasAtraso', '<=', '0')
     ->where('s.estatus', '=', '1')
     ->whereNull('s.idGestor')
     ->where('c.idPerfil', '=', $query)
     ->orderBy('dc.colonia', 'desc')
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

            $devengos1_7=DB::table('tbldevengos as d')
            ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
            ->join('tblcreditos as c', 'd.idCredito', '=', 'c.idCredito')
            ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
            ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
            ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
            ->select('d.idDevengo as estatus', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 'd.montoExigible', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo')
            ->where('s.diasAtraso', '>', '0')
            ->where('s.diasAtraso', '<', '31')
            ->where('s.estatus', '=', '1')
            ->whereNull('s.idGestor')
            ->where('c.idPerfil', '=', $query)
            ->whereRaw('c.fechaFin  > curdate()')
            ->where('d.fechaDevengo', '<=', DB::raw('curdate()'))// menor a hoy, el devengo vencido es el anterior
            ->where('d.fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))// > 30 para mostrar en un rango de 30 días
            ->orderBy('dc.colonia', 'desc')
            ->orderBy('s.montoRiesgo', 'desc')->get();

            //Se recorre la coleccion actualizando el estatus de la agenda
            $devengos1_7->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                return $devengo;
            });

            $devengosV1_7=DB::table('tblcreditos as c')
            ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
            ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
            ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
            ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
            ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
            ->select('d.idDevengo as estatus', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 's.saldoExigible', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo')
            ->where('s.diasAtraso', '>', '0')
            ->where('s.diasAtraso', '<', '31')
            ->where('s.estatus', '=', '1')
            ->whereNull('s.idGestor')
            ->where('c.idPerfil', '=', $query)
            ->whereRaw('c.fechaFin < curdate() and c.fechaFin = d.fechaDevengo')
            ->orderBy('dc.colonia', 'desc')
            ->orderBy('s.montoRiesgo', 'desc')
            ->get();

            //Se recorre la coleccion actualizando el estatus de la agenda
            $devengosV1_7->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                return $devengo;
            });

            $devengos8_90=DB::table('tblcreditos as c')
           ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
           ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
           ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')//se agrega para mejor control una tabla de la situacion del credito
           ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
           ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
           ->select('d.idDevengo as estatus', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 'd.montoExigible', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo')
           ->where('s.diasAtraso', '>', '30')
           ->where('s.diasAtraso', '<', '91')
           ->where('s.estatus', '=', '1')
           ->whereNull('s.idGestor')
           ->where('c.idPerfil', '=', $query)
           ->whereRaw('c.fechaFin  > curdate()')
           ->where('d.fechaDevengo', '<=', DB::raw('curdate()'))// menor a hoy, el devengo vencido es el anterior
           ->where('d.fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))// > 30 para mostrar en un rango de 30 días
           ->orderBy('dc.colonia', 'desc')
           ->orderBy('s.montoRiesgo', 'desc')
           ->get();

            $devengos8_90->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                return $devengo;
            });

            $devengosV8_90=DB::table('tblcreditos as c')
            ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
            ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
            ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')//se agrega para mejor control una tabla de la situacion del credito
            ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
            ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
            ->select('d.idDevengo as estatus', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 's.saldoExigible', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo')
            ->where('s.diasAtraso', '>', '30')
            ->where('s.diasAtraso', '<', '91')
            ->where('s.estatus', '=', '1')
            ->whereNull('s.idGestor')
            ->where('c.idPerfil', '=', $query)
            ->whereRaw('c.fechaFin < curdate() and c.fechaFin = d.fechaDevengo')
            ->orderBy('dc.colonia', 'desc')
            ->orderBy('s.montoRiesgo', 'desc')
            ->get();

            $devengosV8_90->transform(function ($devengo, $key) {
                $agendado=AgendaDiaria::where('idDevengo', '=', $devengo->idDevengo)->where('fecha', '=', DB::raw('curdate()'))->first();
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
                }
                return $devengo;
            });

            // Creditos mas de 90 días de atraso
                        $devengos_mas90=DB::table('tblcreditos as c')
                 ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
                 ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
                 ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')//se agrega para mejor control una tabla de la situacion del credito
                 ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
                 ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
                 ->select('d.idDevengo as estatus','d.idDevengo as mostrar', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 'd.montoExigible', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo')
                 ->where('s.diasAtraso', '>', '90')
                 ->where('s.estatus', '=', '1')
                 ->whereNull('s.idGestor')
                 ->where('c.idPerfil', '=', $query)
                 ->whereRaw('c.fechaFin  > curdate()')
                 ->where('d.fechaDevengo', '<=', DB::raw('curdate()'))// menor a hoy, el devengo vencido es el anterior
                 ->where('d.fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))// > 30 para mostrar en un rango de 30 días
                 ->orderBy('dc.colonia', 'desc')
                 ->orderBy('s.montoRiesgo', 'desc')
                 //->union($devengos8_90na)
                 ->get();

                        $devengos_mas90->transform(function ($devengo, $key) {
                          $maxFdevengo=DB::table('tbldevengos')->where('idCredito', '=', $devengo->idCredito)
                          ->where('fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))
                          ->where('fechaDevengo', '<=', DB::raw('curdate()'))->max('fechaDevengo');
                          //->where('fechaDevengo', '<=', DB::raw('curdate()'))
                          //->where('fechaDevengo', '>', DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)'))->max('fechaDevengo');

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
                ->select('d.idDevengo as estatus', 'd.idDevengo', 'd.idCredito', 'c.nomCliente', 'd.fechaDevengo', 's.diasAtraso', 's.montoRiesgo', 'd.cuota', 's.saldoExigible', 'dc.colonia', 'dc.telefonoCelular', 'a.fechaAcuerdo','a.montoAcuerdo')
                ->where('s.diasAtraso', '>', '90')
                ->where('s.estatus', '=', '1')
                ->whereNull('s.idGestor')
                ->where('c.idPerfil', '=', $query)
                ->whereRaw('c.fechaFin < curdate() and c.fechaFin = d.fechaDevengo')
                ->orderBy('dc.colonia', 'desc')
                ->orderBy('s.montoRiesgo', 'desc')
                ->get();


                  $date = date('dmY');
                  $view =  \View::make('agenda.devengo.pdfagenda')
           ->with(['id'=>$query])
           ->with(['date'=>$date])
           ->with(['vendedor'=>$this->getAsesoresp($query)])
           ->with(['devengos'=>$devengos])
           ->with(['devengos1_7'=>$devengos1_7])
           ->with(['devengosV1_7'=>$devengosV1_7])
           ->with(['devengos8_90'=>$devengos8_90])
           ->with(['devengosV8_90'=>$devengosV8_90])
           ->with(['devengosmas90'=>$devengos_mas90])
           ->with(['devengosVmas90'=>$devengosV_mas90])
           ->with(['resumen'=>$resumen])->render();


            $pdf = \App::make('dompdf.wrapper')->setPaper('letter', 'portrait');
            $pdf->loadHTML($view);
            // return $pdf->stream('Agenda.pdf');
            return $pdf->download('A-'.$query.'-'.$date.'.pdf');
        }
    }
    public function getPdfVenc(Request $request)
    {
      if ($request) {
           if (Auth::user()->idNivel==5) {
               $query=Auth::user()->idPerfil;
           } else {
               $query= trim($request ->get('searchTxt'));
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

           $datef=date('Y')."/".$month."/01";

           $vencimientosdb=DB::table('tblcreditos as c')
           ->leftjoin('tblrenovaciones as r', 'c.idCredito', '=', 'r.idCredito')
           ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
           ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
           ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
           ->select('c.idCredito', 'c.nomCliente', 'c.fechaFin', 's.maxDiasAtraso', 'c.montoInicial', 'dc.colonia', 'dc.telefonoCelular', DB::raw('IF(r.renueva=0,"No",IF(r.renueva=1,"Si","")) as renueva'), 'r.montoRenovacion')
           ->where('c.idPerfil', '=', $query)
           ->whereRaw('fechaFin>="'.$datei.'" and fechaFin<"'.$datef.'"')
           ->orderBy('c.fechaFin', 'asc')
           ->orderBy('dc.colonia', 'desc')
           ->get();

           //se recorre la collection returnando los ids que se sreciben
           //$vencimientos = $vencimientosdb->whereIn('idCredito', $idCreditos);
           //$vencimientos->all();

          /* $vencimientos = $vencimientosdb->filter(function ($vencimiento,$idCreditos) {
               foreach ($idCreditos as $idCredito) {
                   if ($vencimiento->idCredito == $idCredito) {
                       return true;
                   }
               }
           });*/

           $date = date('d-m-Y');
           $view =  \View::make('agenda.vencimiento.pdfvencimiento')
          ->with(['id'=>$query])
          ->with(['date'=>$date])
          ->with(['vendedor'=>$this->getAsesoresp($query)])
          ->with(['vencimientos'=>$vencimientosdb])->render();


           $pdf = \App::make('dompdf.wrapper')->setPaper('letter', 'portrait');
           $pdf->loadHTML($view);
           // return $pdf->stream('Agenda.pdf');
           return $pdf->download('V-'.$query.'-'.$date.'.pdf');
       }
    }

    public function getPdfGestor(request $request)
    {
        if ($request) {
            if (Auth::user()->idNivel==7) {
                $query=Auth::user()->idPerfil;
            } else {
                $query= trim($request ->get('searchTxt'));
            }

            $resumen=DB::table('tblcreditos as c')
     ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
     ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
     ->select('s.idGestor',DB::raw('count(c.idCredito) as cuenta'),DB::raw('sum(s.saldo) as saldo'),DB::raw('0 as corriente'),DB::raw('0 as saldocorriente'),DB::raw('0 as riesgo'),DB::raw('0 as saldoriesgo'),DB::raw('sum(s.capitalVigente) as capitalVigente'),DB::raw('sum(s.capitalVencido) as capitalVencido',DB::raw('0 as normalidad')))
     ->where('s.estatus', '=', '1')
     ->where('s.idGestor', '=', $query)
     ->groupBy('s.idGestor')
     ->get();

        $resumen->transform(function ($res, $key) {
         $corriente=DB::table('tblcreditos as c')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
         ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
         ->select('s.idGestor',DB::raw('count(s.idGestor) as cuenta'),DB::raw('sum(s.saldo) as saldo'))
         ->where('s.diasAtraso', '<=', '0')
         ->where('s.estatus', '=', '1')
         ->where('s.idGestor', '=', $res->idGestor)
         ->groupBy('s.idGestor')
         ->get();
         $atraso=DB::table('tblcreditos as c')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
         ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
         ->select('s.idGestor',DB::raw('count(s.idGestor) as cuenta'),DB::raw('sum(s.saldo) as saldo'))
         ->where('s.diasAtraso', '>', '0')
         ->where('s.estatus', '=', '1')
         ->where('s.idGestor', '=', $res->idGestor)
         ->groupBy('s.idGestor')
         ->get();

         foreach ($corriente as $val) {
           $res->corriente=$val->cuenta;
           $res->saldocorriente=$val->saldo;
         }
         foreach ($atraso as $val) {
           $res->riesgo=$val->cuenta;
           $res->saldoriesgo=$val->saldo;
         }

          $res->normalidad=($res->saldocorriente/$res->saldo)*100;

         return $res;
     });


     $devengos=DB::table('tblcreditos as c')
    ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
    ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
    ->leftjoin('tblacuerdos as a', 'd.idDevengo', '=', 'a.idDevengo')
    ->join('tbldomicilioscredito as dc', 'c.idCredito', '=', 'dc.idCredito')
    ->join('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
    ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
    ->select('d.idDevengo as estatus', 'd.idDevengo', 'c.idCredito', 'c.nomCliente', 'd.fechaDevengo', 'd.cuota', 'd.saldo', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo','suc.sucursal')
    ->where('d.fechaDevengo', '>=', DB::raw('curdate()'))
    ->where('d.fechaDevengo', '<', DB::raw('curdate() + 4'))
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
   ->select('d.idDevengo as estatus', 'd.idDevengo', 'c.idCredito', 'c.nomCliente', 'd.fechaDevengo', 'd.cuota', 'd.saldo', 'dc.colonia', 'dc.telefonoCelular','a.fechaAcuerdo', 'a.montoAcuerdo','suc.sucursal')
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
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
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
                if (count($agendado)==1) {
                    $devengo->estatus=$agendado->estatus;
                } else {
                    $devengo->estatus=0;
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


                  $date = date('dmY');
                  $view =  \View::make('agenda.gestor.pdfgestor')
           ->with(['id'=>$query])
           ->with(['date'=>$date])
           ->with(['vendedor'=>$this->getAsesoresp($query)])
           ->with(['devengos'=>$devengos])
           ->with(['devengos'=>$devengosV])
           ->with(['devengos1_90'=>$devengos1_90])
           ->with(['devengosV1_90'=>$devengosV1_90])
           ->with(['devengos_mas90'=>$devengos_mas90])
           ->with(['devengosV_mas90'=>$devengosV_mas90])
           ->with(['resumen'=>$resumen])->render();


            $pdf = \App::make('dompdf.wrapper')->setPaper('letter', 'portrait');
            $pdf->loadHTML($view);
            // return $pdf->stream('Agenda.pdf');
            return $pdf->download('G-'.$query.'-'.$date.'.pdf');
        }
    }
    public function getAsesoresp($id)
    {
      $vendedor=DB::table('catperfiles as cp')
     ->join('catpersonas as p', 'cp.idPersona', '=', 'p.idPersona')
     ->join('catsucursales as s', 'cp.idSucursal', '=', 's.idSucursal')
     ->select('cp.idPerfil', 'p.nombre', 's.sucursal')
     ->where('cp.idPerfil', '=', $id)->first();

      return $vendedor;
    }
}
