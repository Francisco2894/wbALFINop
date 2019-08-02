<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use DB;
use wbALFINop\DashOperacion;
use wbALFINop\Credito;
use wbALFINop\SituacionCredito;
use wbALFINop\Devengo;
use wbALFINop\Perfil;
use wbALFINop\RecupDevengo;

ini_set('max_execution_time', 240);
class DashOperacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (Auth::user()->idNivel<3) {
            return view('proceso.operacion.index')
            ->with(['records'=>0,'updaterecords'=>0]);
            return back()->withInput();
        }
    }
    public function devengo()
    {
        if (Auth::user()->idNivel<3) {
            return view('proceso.operacion.devengo')
            ->with(['records'=>0,'updaterecords'=>0]);
            return back()->withInput();
        }
    }
    public function getResumenSC($idPerfil,$cuenta,$monto,$condicion,$concepto)
    {
      $resumen=DB::table('catperfiles as cp')
       ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
       ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
       ->select('c.idPerfil',DB::raw('count('.$cuenta.') as cuenta'),DB::raw('sum('.$monto.') as monto'),DB::raw($concepto.' as concepto'))
       ->where('c.idPerfil', '=', $idPerfil)
       ->whereRaw($condicion)
       ->where('s.estatus', '=', '1')
       ->where('s.fechaSituacion', '=', DB::raw('curdate()'))
       //->where('s.diasAtraso','<','91')
       ->groupBy('c.idPerfil')
       ->first();

       if (is_null($resumen)) {
         $x=DB::table('catperfiles as cp')
          ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
          ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
          ->select('c.idPerfil',DB::raw('0 as cuenta'),DB::raw('0 as monto'),DB::raw($concepto.' as concepto'))
          ->where('c.idPerfil', '=', $idPerfil)
          ->where('s.estatus', '=', '1')
          ->first();
          return $x;
       }
         return $resumen;

    }
    public function getNoAbonado($idPerfil,$concepto)
    {
       $resumen=DB::table('catperfiles as cp')
       ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
       ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
       ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
       ->select('c.idPerfil',DB::raw('count(distinct(c.idCredito)) as cuenta'),DB::raw('sum(distinct(s.saldo)) as monto'),DB::raw($concepto.' as concepto'))
       ->where('c.idPerfil', '=', $idPerfil)
       ->where('s.monto_ult_pago', '=', '0')
       ->where('s.estatus', '=', '1')
       ->where('s.fechaSituacion', '=', DB::raw('curdate()'))
       //->where('s.diasAtraso','<','91')
       ->groupBy('c.idPerfil')
       ->havingRaw('MIN(d.fechaDevengo) < curdate()')
       ->first();
       if (is_null($resumen)) {
         $x=DB::table('catperfiles as cp')
          ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
          ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
          ->select('c.idPerfil',DB::raw('0 as cuenta'),DB::raw('0 as monto'),DB::raw($concepto.' as concepto'))
          ->where('c.idPerfil', '=', $idPerfil)
          ->where('s.estatus', '=', '1')
          ->first();
          //$x->dd();
          return $x;
       }
       return $resumen;
    }

    public function getResumenDv($idPerfil,$cuenta,$monto,$condicion,$concepto)
    {
      $resumen=DB::table('catperfiles as cp')
      ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
      ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
      ->select('c.idPerfil',DB::raw('count('.$cuenta.') as cuenta'),DB::raw('sum('.$monto.') as monto'),DB::raw($concepto.' as concepto'))
      ->where('c.idPerfil', '=', $idPerfil)
      ->whereRaw($condicion)
      ->groupBy('c.idPerfil')
      ->first();
      if (is_null($resumen)) {
        $x=DB::table('catperfiles as cp')
         ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
         ->select('c.idPerfil',DB::raw('0 as cuenta'),DB::raw('0 as monto'),DB::raw($concepto.' as concepto'))
         ->where('c.idPerfil', '=', $idPerfil)
         ->where('s.estatus', '=', '1')
         ->first();
         //$x->dd();
         return $x;
      }

       return $resumen;
    }
    public function getResumenDr($idPerfil,$cuenta,$monto,$condicion,$concepto)
    {
      $resumen=DB::table('catperfiles as cp')
      ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
      ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
      ->join('tblrecupdev as r', 'd.idDevengo', '=', 'r.idDevengo')
      ->select('c.idPerfil',DB::raw('count('.$cuenta.') as cuenta'),DB::raw('sum('.$monto.') as monto'),DB::raw($concepto.' as concepto'))
      ->where('c.idPerfil', '=', $idPerfil)
      ->whereRaw($condicion)
      ->groupBy('c.idPerfil')
      ->first();

      if (is_null($resumen)) {
        $x=DB::table('catperfiles as cp')
         ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
         ->select('c.idPerfil',DB::raw('0 as cuenta'),DB::raw('0 as monto'),DB::raw($concepto.' as concepto'))
         ->where('c.idPerfil', '=', $idPerfil)
         ->where('s.estatus', '=', '1')
         ->first();
         //$x->dd();
         return $x;
      }

       return $resumen;
    }
    public function getResumenP($idPerfil,$cuenta,$monto,$condicion,$concepto)
    {
      $resumen=DB::table('catperfiles as cp')
       ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
       ->join('tblpagos as p', 'c.idCredito', '=', 'p.idCredito')
       ->select('c.idPerfil',DB::raw('count('.$cuenta.') as cuenta'),DB::raw('sum('.$monto.') as monto'),DB::raw($concepto.' as concepto'))
       ->where('c.idPerfil', '=', $idPerfil)
       ->whereRaw($condicion)
       ->groupBy('c.idPerfil')
       ->first();

       if (is_null($resumen)) {
         $x=DB::table('catperfiles as cp')
          ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
          ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
          ->select('c.idPerfil',DB::raw('0 as cuenta'),DB::raw('0 as monto'),DB::raw($concepto.' as concepto'))
          ->where('c.idPerfil', '=', $idPerfil)
          ->first();
          return $x;
       }
         return $resumen;

    }

    public function execDashOper()
    {
      global $r, $u;
      $r=0;
      $u=0;
      $fechastr=strtotime ( '-1 day' , strtotime ( date('Y-m-d')));
      //$fechastr=strtotime ( '-1 day' , strtotime ('2019-05-01'));//Fecha fijada
      $fCorte=date('Y-m-d',$fechastr);
      //$fCorte='2017-12-31';
      $datem=date('m',$fechastr);
      $dateY=date('Y',$fechastr);

       $perfiles=DB::table('catperfiles as cp')
       ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
       ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
       ->select('c.idPerfil')
       ->where('s.estatus', '=', '1')
       //->where('s.fechaSituacion', '=', DB::raw('curdate()'))
       //->where('s.diasAtraso','<','91')
       ->distinct()->get();
       //$perfiles->dd();

       //se resetea estatus de resumen
       $allResum=DashOperacion::where(DB::raw('MONTH(fechaCorte)'),'=',$datem)->where(DB::raw('YEAR(fechaCorte)'),'=',$dateY)->get();

       foreach ($allResum as $resum) {
         $resum->estatus=0;
         $resum->update();
       }
       foreach ($perfiles as $idPerfiles) {
         $idPerfil=trim($idPerfiles->idPerfil);
         $clientes=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.diasAtraso>=0',1);
         $cAtraso=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.diasAtraso>0',2);
         $cVencida=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','c.fechaFin<="'.$fCorte.'"',3); //Se ajusta a la fecha de corte el 22052019
         $noAbonados=$this->getNoAbonado($idPerfil,4);
         //$noAbonados->dd();
         $cIsemanal=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.f_ult_pago < DATE_SUB(curdate(), INTERVAL 21 DAY) and c.frecuenciaPago="Semanal" and s.diasAtraso>30',5);
         $cIquincenal=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.f_ult_pago < DATE_SUB(curdate(), INTERVAL 45 DAY) and c.frecuenciaPago="Quincenal" and s.diasAtraso>30',5);
         $cImensual=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.f_ult_pago < DATE_SUB(curdate(), INTERVAL 90 DAY) and c.frecuenciaPago="Mensual" and s.diasAtraso>30',5);
         $ceroDias=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.diasAtraso=0',6);
         $b1_30Dias=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.diasAtraso>0 and s.diasAtraso<31',7);
         $b31_60Dias=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.diasAtraso>30 and s.diasAtraso<61',8);
         $b61_90Dias=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.diasAtraso>60 and s.diasAtraso<91',9);
         $bmas90Dias=$this->getResumenSC($idPerfil,'c.idCredito','s.saldo','s.diasAtraso>90',10);
         $cVivienda=$this->getResumenSC($idPerfil,'c.idCredito','c.montoInicial','c.negocio="VIVIENDA" and c.producto<>"REESTRUCTURA VIVIENDA - MENS 2015" and c.producto<>"REESTRUCTURA VIVIENDA - QUINC 2015" and MONTH(c.fechaInicio)='.$datem.' and YEAR(c.fechaInicio)='.$dateY,11);
         $cProductivo=$this->getResumenSC($idPerfil,'c.idCredito','c.montoInicial','c.negocio="PRODUCTIVO" and c.producto<>"REESTRUCTURA PRODUCTIVO GRAMEEN-ALFIN - MENSUAL" and c.producto<>"REESTRUCTURA PRODUCTIVO GRAMEEN-ALFIN - QUINCENAL" and MONTH(c.fechaInicio)='.$datem.' and YEAR(c.fechaInicio)='.$dateY,12);
         $colocacion=$this->getResumenSC($idPerfil,'c.idCredito','c.montoInicial','c.producto<>"REESTRUCTURA VIVIENDA - MENS 2015" and c.producto<>"REESTRUCTURA VIVIENDA - QUINC 2015" and c.producto<>"REESTRUCTURA PRODUCTIVO GRAMEEN-ALFIN - MENSUAL" and c.producto<>"REESTRUCTURA PRODUCTIVO GRAMEEN-ALFIN - QUINCENAL" and MONTH(c.fechaInicio)='.$datem.' and YEAR(c.fechaInicio)='.$dateY,13);
         $capVencido=$this->getResumenSC($idPerfil,'c.idCredito','s.capitalVencido','s.diasAtraso>0',14);
         $devengosm=$this->getResumenDv($idPerfil,'c.idCredito','d.cuota','MONTH(d.fechaDevengo)='.$datem.' and YEAR(d.fechaDevengo)='.$dateY,15);
         $devengosv=$this->getResumenDv($idPerfil,'c.idCredito','d.cuota','MONTH(d.fechaDevengo)='.$datem.' and YEAR(d.fechaDevengo)='.$dateY.' and d.fechaDevengo<="'.$fCorte.'"',16);
         // Devengos cobrados vencidos (agregar d.fechaDevengo<="'.$fCorte.'"') ?   Aqui se calcula todos los cobrados del mes
         $devengosc=$this->getResumenDr($idPerfil,'d.idDevengo','r.monto','MONTH(d.fechaDevengo)='.$datem.' and YEAR(d.fechaDevengo)='.$dateY.' and r.recuperado=1',17);
         $devengosp=$this->getResumenDr($idPerfil,'d.idDevengo','r.monto','MONTH(d.fechaDevengo)='.$datem.' and YEAR(d.fechaDevengo)='.$dateY.' and r.recuperado=0 and r.monto>0',18);
         //Recuperación vencidos plazo
         $vencPlazoR=$this->getResumenP($idPerfil,'p.idCredito','p.monto','MONTH(c.fechaFin)<'.$datem.' and YEAR(c.fechaFin)='.$dateY.' and MONTH(p.f_Aplicacion)='.$datem.' and YEAR(p.f_Aplicacion)='.$dateY,19);
         $recupTotal=$this->getResumenP($idPerfil,'p.idCredito','p.monto','MONTH(p.f_Aplicacion)='.$datem.' and YEAR(p.f_Aplicacion)='.$dateY,20);

         $cIcuenta=$cIsemanal->cuenta + $cIquincenal->cuenta + $cImensual->cuenta;
         $cIsuma=$cIsemanal->monto + $cIquincenal->monto + $cImensual->monto;

         $cInactivos=DB::table('catperfiles as cp')
          ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
          ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
          ->select('c.idPerfil',DB::raw($cIcuenta.' as cuenta'),DB::raw($cIsuma.' as monto'),DB::raw('5 as concepto'))
          ->where('c.idPerfil', '=', $idPerfil)
          ->where('s.estatus', '=', '1')->first();

         $collectInsert=NULL;
         $collectInsert=collect([$clientes,$cAtraso,$cVencida,$noAbonados,$cInactivos,$ceroDias,$b1_30Dias,$b31_60Dias,$b61_90Dias,$bmas90Dias,$cVivienda,$cProductivo,$colocacion,$capVencido,$devengosm,$devengosv,$devengosc,$devengosp,$vencPlazoR,$recupTotal]);

         //$collectInsert->dd();
         //si existe registro con el mes y año, solo se actualizara, lo otro sería hacer constante como corte el ultimo día del mes
            foreach ($collectInsert as $fila) {
              $existRes=DashOperacion::where('idPerfil', '=', $fila->idPerfil)
              ->where(DB::raw('MONTH(fechaCorte)'),'=',$datem)->where(DB::raw('YEAR(fechaCorte)'),'=',$dateY)
              ->where('idConcepto','=',$fila->concepto)->first();//se valida que el registro no exista en la BD

              if (count($existRes)==0) {
              $resumen=new DashOperacion;
              $resumen->idPerfil=$fila->idPerfil;
              $resumen->idConcepto=$fila->concepto;
              $resumen->cuenta=$fila->cuenta;
              $resumen->monto=$fila->monto;
              $resumen->fechaCorte=$fCorte;
              $resumen->idPerfilUser=Auth::user()->idPerfil;
              $resumen->estatus=1;
              $resumen->save();
              $r++;
            }
            elseif (count($existRes)==1) {
              $existRes->idConcepto=$fila->concepto;
              $existRes->cuenta=$fila->cuenta;
              $existRes->monto=$fila->monto;
              $existRes->fechaCorte=$fCorte;
              $existRes->idPerfilUser=Auth::user()->idPerfil;
              $existRes->estatus=1;
              $existRes->update();
              $u++;
            }
          }
       }
        //return back();
        return view('proceso.operacion.index')
        ->with(['records'=>$r])
        ->with(['updaterecords'=>$u,'proceso'=>"Proceso de operaciones"]);
    }
    // Proceso Recuperación Devengos
    public function execDev(request $request)
    {
      global $r, $u;
      $r=0;
      $u=0;
      if ($request) {
        $dateini= trim($request->get('dtpFechaIni'));
        $datefin= trim($request->get('dtpFechaFin'));
      }

       $perfiles=DB::table('catperfiles as cp')
       ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
       ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
       ->select('c.idPerfil')
       //->where('s.estatus', '=', '1')
       ->where('s.fechaSituacion', '>=', $dateini)
       //->where('s.diasAtraso','<','91')
       ->distinct()->get();

        foreach ($perfiles as $idPerfiles) {
          $idPerfil=trim($idPerfiles->idPerfil);

          $creditosdev=DB::table('catperfiles as cp')
          ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
          ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
          ->select('c.idCredito',DB::raw('count(c.idCredito)'))
          ->where('c.idPerfil', '=', $idPerfil)
          //->where('s.monto_ult_pago', '=', '0')
          //->where('s.estatus', '=', '1')
          //->where('s.diasAtraso','<','91')
          ->where('d.fechaDevengo', '>=', $dateini)
          ->where('d.fechaDevengo', '<=', $datefin)
          ->groupBy('c.idCredito')
          ->havingRaw('count(c.idCredito) > 0')
          ->get();
          // Se recorre los creditos y se obtienen los devengos
          if (is_null($creditosdev)) {}
            else {
          foreach ($creditosdev as $credito) {
            $devengos=DB::table('tbldevengos as d')
            ->select('d.idDevengo','d.fechaDevengo','d.cuota','d.idCredito')
            ->where('d.idCredito', '=', $credito->idCredito)
            ->where('d.fechaDevengo', '>=', $dateini)
            ->where('d.fechaDevengo', '<=', $datefin)
            ->orderBy('d.fechaDevengo',"asc")
            ->get();

            $pagos=DB::table('tblpagos as p')
            ->select('p.idCredito',DB::raw('sum(p.monto) as monto'),DB::raw('sum(p.capital) as capital'),DB::raw('sum(p.interes) as interes'),DB::raw('sum(p.iva_int) as iva_int'))
            ->where('p.idCredito', '=', $credito->idCredito)
            ->where('p.f_Movimiento', '>=', $dateini)
            ->where('p.f_Movimiento', '<=', $datefin)
            ->groupBy('p.idCredito')
            ->first();
            // se empieza a cubrir cada devengo hasta agotar el monto del total de pagos
            $totalpago=0;
            if (count($pagos)>=1) {
              $totalpago=$pagos->capital + $pagos->interes + $pagos->iva_int;
            }

            if (is_null($devengos)) {}
              else {
            if ($totalpago > 0) {
              foreach ($devengos as $devengo) {
                $pagado=0;
                $recuperado=0;
                if ($totalpago >= $devengo->cuota) {
                  $pagado=$devengo->cuota;
                  $totalpago=$totalpago-$pagado;
                  $recuperado=1;
                }else {
                  $pagado=$totalpago;
                  $totalpago=$totalpago-$pagado;
                }
                //Insertar en la BD
                $existId=RecupDevengo::where('idDevengo', '=', $devengo->idDevengo)->first();
                if (count($existId)==0) {
                    $recupdev=new RecupDevengo;
                    $recupdev->idDevengo=$devengo->idDevengo;
                    $recupdev->monto=$pagado;
                    $recupdev->recuperado=$recuperado;
                    $recupdev->f_Actualizacion=date('Y-m-d');
                    $recupdev->save();
                    $r++;
                } elseif (count($existId)==1) {
                    $existId->monto=$pagado;
                    $existId->recuperado=$recuperado;
                    $existId->f_Actualizacion=date('Y-m-d');
                    $existId->update();
                    $u++;
                }
              }
            }
          }
          }
        }
        }
        return view('proceso.operacion.devengo')
        ->with(['records'=>$r])
        ->with(['updaterecords'=>$u,'proceso'=>"Proceso de operaciones"]);
    }
}
