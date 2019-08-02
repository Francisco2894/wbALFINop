<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use DB;
use wbALFINop\Comision;
use wbALFINop\Perfil;
use wbALFINop\CondicionCm;
use DateTime;

ini_set('max_execution_time', 180);
date_default_timezone_set('America/Mexico_City');

class ComisionController extends Controller
{
    //
    public function index()
    {
        if (Auth::user()->idNivel<3) {
            return view('proceso.operacion.comision')
            ->with(['records'=>0])
            ->with(['updaterecords'=>0,'proceso'=>"Proceso de comisiones"]);
        } else {
            return back()->withInput();
        }
    }
    public function execCalcCom()
    {
        $r=0;
        $u=0;
        //$fecha=date('Y-m-d');
        $fecha='2019-02-01';

        $fechastr=strtotime('-1 day', strtotime($fecha));
        $fCorte=date('Y-m-d', $fechastr);

        $pago1Viv13=$this->getViv13(1, $fCorte);
        //$pago1Viv13->dd();
        foreach ($pago1Viv13 as $p1) {
            $result=$this->pago1($p1->idCredito, trim($p1->idPerfil), $p1->plazomes, $p1->montoInicial, $p1->negocio, $fCorte);
            if ($result==1) {
                $r++;
            } elseif ($result==2) {
                $u++;
            }
        }

        $pago1Viv19=$this->getViv19(1, $fCorte);
        //$pago1Viv19->dd();
        foreach ($pago1Viv19 as $p1) {
            $result=$this->pago1($p1->idCredito, $p1->idPerfil, $p1->plazomes, $p1->montoInicial, $p1->negocio, $fCorte);
            if ($result==1) {
                $r++;
            } elseif ($result==2) {
                $u++;
            }
        }

        //Segundo pago
        $pago2Viv=$this->getViv2(2);
        //$pago1Viv13->dd();
        foreach ($pago2Viv as $p1) {
            $dateI=new DateTime($p1->fechaEjercido);
            $dateF=new DateTime($p1->fechaFin);
            $dateM=$dateI->diff($dateF);
            $datestr=strtotime('+'.round($dateM->days/2).' day', strtotime($p1->fechaEjercido));
            //$idc=$p1->idCredito;
            $datesN=strtotime('-1 day', strtotime($fecha));
            //$dateC=date('Y-m-d',$datestr);
            if (date('m', $datestr)==date('m', $datesN) && date('Y', $datestr)==date('Y', $datesN)) {
                $result=$this->pago2($p1->idCredito, $p1->idPerfil, $p1->plazomes, $p1->montoInicial, $p1->negocio, $p1->diasAtraso, $p1->maxDiasAtraso, $fCorte);
                if ($result==1) {
                    $r++;
                } elseif ($result==2) {
                    $u++;
                }
            }
        }
        //Tercer pago
        $pago3Viv=$this->getViv2(3);
        //$pago2Prod->dd();
        foreach ($pago3Viv as $p1) {
            $dateC=new DateTime($p1->fCierre);
            $fcierre=strtotime($p1->fCierre);
            $dateF=new DateTime($p1->fechaFin);
            $fFin=strtotime($p1->fechaFin);

            //$idc=$p1->idCredito;
            $datesN=strtotime('-1 day', strtotime($fecha));
            //$dateC=date('Y-m-d',$datestr);
            $aprobado=0;
            $r30Dias=0;
            $r30Diasv=0;
            // Si la fecha de cierre es del mes actual, se procesa
            if (date('m', $fcierre)==date('m', $datesN) && date('Y', $fcierre)==date('Y', $datesN)) {
                $diasC=($dateF->diff($dateC))->days;//calcula los días transcurridos desde liq.
                if ($diasC<31) {//si no ha exedido los 30 días de liquidado desde el vencimiento
                  $r30Dias=1;
                  $r30Diasv=$diasC;
                }elseif ($fFin>$datesN) {// si ya liquido y no ha vencido
                  $r30Dias=1;
                  $r30Diasv='-'.$diasC;
                }
                $result=$this->pago3($p1->idCredito, $p1->idPerfil, $p1->plazomes, $p1->montoInicial, $p1->negocio, $p1->diasAtraso, $p1->maxDiasAtraso, $aprobado, $fCorte,$r30Dias,$r30Diasv);
                if ($result==1) {
                    $r++;
                } elseif ($result==2) {
                    $u++;
                }

            }
        }

        //Pago 1 PRODUCTIVO

        $pago1Prod=$this->getProd(1, $fCorte);
        //$pago1Prod->dd();
        foreach ($pago1Prod as $p1) {
            $result=$this->pago1($p1->idCredito, $p1->idPerfil, $p1->plazomes, $p1->montoInicial, $p1->negocio, $fCorte);
            if ($result==1) {
                $r++;
            } elseif ($result==2) {
                $u++;
            }
        }

        //Segundo pago
        $pago2Prod=$this->getProd2(2);
        //$pago2Prod->dd();
        foreach ($pago2Prod as $p1) {
            $dateI=new DateTime($p1->fechaEjercido);
            $dateF=new DateTime($p1->fechaFin);
            $dateM=$dateI->diff($dateF);
            $datestr=strtotime('+'.round($dateM->days/2).' day', strtotime($p1->fechaEjercido));
            //$idc=$p1->idCredito;
            $datesN=strtotime('-1 day', strtotime($fecha));
            //$dateC=date('Y-m-d',$datestr);
            $ms=date('m', $datestr);
            $yr=date('Y', $datestr);
            if (date('m', $datestr)==date('m', $datesN) && date('Y', $datestr)==date('Y', $datesN)) {
                $result=$this->pago2($p1->idCredito, $p1->idPerfil, $p1->plazomes, $p1->montoInicial, $p1->negocio, $p1->diasAtraso, $p1->maxDiasAtraso, $fCorte);
                if ($result==1) {
                    $r++;
                } elseif ($result==2) {
                    $u++;
                }
            }
        }

        //Tercer pago
        $pago3Prod=$this->getProd2(3);
        //$pago2Prod->dd();
        foreach ($pago3Prod as $p1) {
            $dateC=new DateTime($p1->fCierre);
            $fcierre=strtotime($p1->fCierre);
            $dateF=new DateTime($p1->fechaFin);
            $fFin=strtotime($p1->fechaFin);

            //$idc=$p1->idCredito;
            $datesN=strtotime('-1 day', strtotime($fecha));
            //$dateC=date('Y-m-d',$datestr);
            $aprobado=0;
            $r30Dias=0;
            $r30Diasv=0;
            // Si la fecha de cierre es del mes actual, se procesa
            if (date('m', $fcierre)==date('m', $datesN) && date('Y', $fcierre)==date('Y', $datesN)) {
                $diasC=($dateF->diff($dateC))->days;//calcula los días transcurridos desde la liq.
                if ($diasC<31) {//si no ha exedido los 30 días de liquidado desde el vencimiento
                  $r30Dias=1;
                  $r30Diasv=$diasC;
                }elseif ($fFin>$datesN) {// si ya liquido y no ha vencido
                  $r30Dias=1;
                  $r30Diasv='-'.$diasC;
                }
                $result=$this->pago3($p1->idCredito, $p1->idPerfil, $p1->plazomes, $p1->montoInicial, $p1->negocio, $p1->diasAtraso, $p1->maxDiasAtraso, $aprobado, $fCorte,$r30Dias,$r30Diasv);
                if ($result==1) {
                    $r++;
                } elseif ($result==2) {
                    $u++;
                }
            }
        }
        return view('proceso.operacion.comision')
     ->with(['records'=>$r])
     ->with(['updaterecords'=>$u,'proceso'=>"Proceso de comisiones"]);
    }
    public function pago1($idCredito, $idPerfil, $plazomes, $montoInicial, $negocio, $fecha)
    {
        $i=0;
        $clientes=$this->getResumenSC($idPerfil, 'c.idCredito', 's.saldo', 's.diasAtraso>=0 and c.fechaEjercido>"30/06/2017"'); //se cambia fecha de inicio por fecha Ejercido
        $cAtraso=$this->getResumenSC($idPerfil, 'c.idCredito', 's.saldo', 's.diasAtraso>0 and c.fechaEjercido>"30/06/2017"');

        $vCartera=($cAtraso->monto/$clientes->monto)*100;
        if ($negocio=='VIVIENDA') {
            if ($plazomes=='13') {
                $monto=378.00;
                $montog=198.00;
            } elseif ($plazomes=='19') {
                $monto=468.00;
                $montog=222.00;
            }
        } elseif ($negocio=='PRODUCTIVO') {
            $monto=($montoInicial * 0.035) * 0.429;
            $montog=($montoInicial * 0.0175) * 0.429;
        }

        $exist=Comision::where('idCredito', '=', $idCredito)->where('pago', '=', '1')->where('idNivel', '=', '5')->first();
        $existg=Comision::where('idCredito', '=', $idCredito)->where('pago', '=', '1')->where('idNivel', '=', '4')->first();
        $aprobado=0;// definira si ya se ha pagado, 0 no se ha pagado
        //Regla, cartera en atraso menor o igual al 3%
        if ($vCartera<=3.0) {
            $estatusC=1; // = 1, condición cumplida
        } else {
            $estatusC=0;// = 0, condición no cumplida
        }
        //Asesor
        if (count($exist)>0) {
            $idcom=$this->updateCom($idCredito, 5, 1, $monto, $aprobado, $fecha, $idPerfil);
            $i=$i + $this->updateCond(3, $exist->id, $vCartera, $estatusC);
        } else {
            $idcom= $this->createCom($idCredito, 5, 1, $monto, $aprobado, $fecha, $idPerfil);
            // Insetar registro en tabla transaccional condiciones
            $i=$i + $this->createCond(3, $idcom, $vCartera, $estatusC);
        }

        //Gerente
        $idSuc=Perfil::where('idPerfil', '=', $idPerfil)->first();
        $Perfil=DB::table('catperfiles as cp')->where('idSucursal', '=', $idSuc->idSucursal)->where('descripcion', '=', 'Gerente')->value('idPerfil');
        if (count($Perfil)>0) {
            $idPerfilG=$Perfil;
        } else {
            $idPerfilG="0";
        }
        if (count($existg)>0) {
            $idcom= $this->updateCom($idCredito, 4, 1, $montog, $aprobado, $fecha, $idPerfilG);
            $this->updateCond(3, $existg->id, $vCartera, $estatusC);//actualizacion de regla
            return 2;
        } else {
            $idcom=$this->createCom($idCredito, 4, 1, $montog, $aprobado, $fecha, $idPerfilG);
            $this->createCond(3, $idcom, $vCartera, $estatusC);//insercion de regla
            return 1;
        }
    }
    public function pago2($idCredito, $idPerfil, $plazomes, $montoInicial, $negocio, $diasAtraso, $maxAtraso, $fecha)
    {
        $i=0;

        if ($negocio=='VIVIENDA') {
            if ($plazomes=='13' || $plazomes=='18' || $plazomes=='26' || $plazomes=='57') {
                $monto=126.00;
                $montog=66.00;
            } elseif ($plazomes=='19' || $plazomes=='38' || $plazomes=='83' || $plazomes=='25' || $plazomes=='50' || $plazomes=='109') {
                $monto=156.00;
                $montog=74.00;
            }
        } elseif ($negocio=='PRODUCTIVO') {
            $monto=($montoInicial * 0.035) * 0.143;
            $montog=($montoInicial * 0.0175) * 0.143;
        }

        $exist=Comision::where('idCredito', '=', $idCredito)->where('pago', '=', '2')->where('idNivel', '=', '5')->first();
        $existg=Comision::where('idCredito', '=', $idCredito)->where('pago', '=', '2')->where('idNivel', '=', '4')->first();

        //Condición, cartera en atraso menor o igual al 3%
        if ($diasAtraso==0) {
            $estatusC=1;
        } else {
            $estatusC=0;
        }
        $aprobado=0; // definira si ya se ha pagado, 0 no se ha pagado
        //Asesor
        if (count($exist)>0) {
            $i=$i + $this->updateCom($idCredito, 5, 2, $monto, $aprobado, $fecha, $idPerfil);
            $this->updateCond(4, $exist->id, $diasAtraso, $estatusC);//actualizacion de regla
            if ($maxAtraso>90) {
                $this->updateCond(1, $exist->id, $maxAtraso, 0);//insercion de regla mas de 90 dias de atraso historico
            }
        } else {
            $idcom= $this->createCom($idCredito, 5, 2, $monto, $aprobado, $fecha, $idPerfil);
            $this->createCond(4, $idcom, $diasAtraso, $estatusC);//insercion de regla
            if ($maxAtraso>90) {
                $this->createCond(1, $idcom, $maxAtraso, 0);//insercion de regla mas de 90 dias de atraso historico
            }else {
              $this->createCond(1, $idcom, $maxAtraso, 1);//insercion de regla mas de 90 dias de atraso historico
            }
        }
        //Gerente
        $idSuc=Perfil::where('idPerfil', '=', $idPerfil)->first();
        $Perfil=DB::table('catperfiles as cp')->where('idSucursal', '=', $idSuc->idSucursal)->where('descripcion', '=', 'Gerente')->value('idPerfil');
        if (count($Perfil)>0) {
            $idPerfilG=$Perfil;
        } else {
            $idPerfilG="0";
        }
        if (count($existg)>0) {
            $i=$i + $this->updateCom($idCredito, 4, 2, $montog, $aprobado, $fecha, $idPerfilG);
            $this->updateCond(4, $existg->id, $diasAtraso, $estatusC);//actualizacion de regla
            if ($maxAtraso>90) {
                $this->updateCond(1, $existg->id, $maxAtraso, 0);//insercion de regla mas de 90 dias de atraso historico
            }
            return 2;
        } else {
            $idcom= $this->createCom($idCredito, 4, 2, $montog, $aprobado, $fecha, $idPerfilG);
            $this->createCond(4, $idcom, $diasAtraso, $estatusC);//insercion de regla
            if ($maxAtraso>90) {
                $this->createCond(1, $idcom, $maxAtraso, 0);//insercion de regla mas de 90 dias de atraso historico
            }else {
              $this->createCond(1, $idcom, $maxAtraso, 1);//insercion de regla mas de 90 dias de atraso historico
            }
            return 1;
        }
    }
    public function pago3($idCredito, $idPerfil, $plazomes, $montoInicial, $negocio, $diasAtraso, $maxAtraso, $aprobado, $fecha,$r30Dias,$r30Diasv)
    {
        $i=0;

        if ($negocio=='VIVIENDA') {
            if ($plazomes=='13' || $plazomes=='18' || $plazomes=='26' || $plazomes=='57') {
                $monto=378.00;
                $montog=198.00;
            } elseif ($plazomes=='19' || $plazomes=='38' || $plazomes=='83' || $plazomes=='25' || $plazomes=='50' || $plazomes=='109') {
                $monto=468.00;
                $montog=222.00;
            }
        } elseif ($negocio=='PRODUCTIVO') {
            $monto=($montoInicial * 0.035) * 0.429;
            $montog=($montoInicial * 0.0175) * 0.429;
        }

        $exist=Comision::where('idCredito', '=', $idCredito)->where('pago', '=', '3')->where('idNivel', '=', '5')->first();
        $existg=Comision::where('idCredito', '=', $idCredito)->where('pago', '=', '3')->where('idNivel', '=', '4')->first();
        //Asesor
        if (count($exist)>0) {
            $i=$i + $this->updateCom($idCredito, 5, 3, $monto, $aprobado, $fecha, $idPerfil);
            $this->updateCond(2, $existg->id, $r30Diasv, $r30Dias);//actualizacion de regla mas de no mas de 30 dias liq
            if ($maxAtraso>90) {
                $this->updateCond(1, $exist->id, $maxAtraso, 0);//insercion de regla mas de 90 dias de atraso historico
            }
        } else {
            $idcom= $this->createCom($idCredito, 5, 3, $monto, $aprobado, $fecha, $idPerfil);
            $this->createCond(2, $idcom, $r30Diasv, $r30Dias);//insercion de regla mas de no mas de 30 dias liq
            if ($maxAtraso>90) {
                $this->createCond(1, $idcom, $maxAtraso, 0);//insercion de regla mas de 90 dias de atraso historico
            }else {
              $this->createCond(1, $idcom, $maxAtraso, 1);//insercion de regla mas de 90 dias de atraso historico
            }
        }
        //Gerente
        $idSuc=Perfil::where('idPerfil', '=', $idPerfil)->first();
        $Perfil=DB::table('catperfiles as cp')->where('idSucursal', '=', $idSuc->idSucursal)->where('descripcion', '=', 'Gerente')->value('idPerfil');
        if (count($Perfil)>0) {
            $idPerfilG=$Perfil;
        } else {
            $idPerfilG="0";
        }
        if (count($existg)>0) {
            $i=$i + $this->updateCom($idCredito, 4, 3, $montog, $aprobado, $fecha, $idPerfilG);
            $this->updateCond(2, $existg->id, $r30Diasv, $r30Dias);//actualizacion de regla mas de no mas de 30 dias liq
            if ($maxAtraso>90) {
                $this->updateCond(1, $existg->id, $maxAtraso, 0);//insercion de regla mas de 90 dias de atraso historico
            }
            return 2;
        } else {
            $idcom= $this->createCom($idCredito, 4, 3, $montog, $aprobado, $fecha, $idPerfilG);
            $this->createCond(2, $idcom, $r30Diasv, $r30Dias);//insercion de regla mas de no mas de 30 dias liq
            if ($maxAtraso>90) {
                $this->createCond(1, $idcom, $maxAtraso, 0);//insercion de regla mas de 90 dias de atraso historico
            }else {
              $this->createCond(1, $idcom, $maxAtraso, 1);//insercion de regla mas de 90 dias de atraso historico
            }
            return 1;
        }
    }
    public function getViv13($pago, $fecha)
    {
        if ($pago==1) {
            $fechastr=strtotime($fecha);
        }
        //$fCorte=date('Y-m-d',$fechastr);
        $datem=date('m', $fechastr);
        $dateY=date('Y', $fechastr);

        $viv13=DB::table('tblcreditos as c')
      ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
      ->select('c.idCredito', 'c.idPerfil', 'c.montoInicial', DB::raw('13 as plazomes'), 'c.negocio', 's.diasAtraso')
      ->where('c.negocio', '=', 'VIVIENDA')
      //->whereRaw('MONTH(c.fechaInicio)='.$datem.' and YEAR(c.fechaInicio)='.$dateY)
      ->whereMonth('c.fechaEjercido', $datem) // se cambia fecha de fechaInicio por fecha Ejercido
      ->whereYear('c.fechaEjercido', $dateY)
      ->where('s.estatus', '=', '1')
       //->where('s.maxDiasAtraso', '<', '91')// Se quita filtro para guardar todos los registros
      ->whereNull('s.idGestor')
      ->where('c.producto', '<>', 'REESTRUCTURA VIVIENDA - MENS 2015')
      ->where('c.producto', '<>', 'REESTRUCTURA VIVIENDA - QUINC 2015')
      //->where('c.fechaInicio','>','"30/06/2017"')
      ->whereIn('c.plazo', [13,18,26,57])
      ->get();

        return $viv13;
    }
    public function getViv19($pago, $fecha)
    {
        if ($pago==1) {
            $fechastr= strtotime($fecha);
        }
        //$fCorte=date('Y-m-d',$fechastr);
        $datem=date('m', $fechastr);
        $dateY=date('Y', $fechastr);

        $viv19=DB::table('tblcreditos as c')
     ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
     ->select('c.idCredito', 'c.idPerfil', 'c.montoInicial', DB::raw('19 as plazomes'), 'c.negocio', 's.diasAtraso')
     ->where('c.negocio', '=', 'VIVIENDA')
     //->whereRaw('MONTH(c.fechaInicio)='.$datem.' and YEAR(c.fechaInicio)='.$dateY)
     ->whereMonth('c.fechaEjercido', $datem)// se cambia fecha de fechaInicio por fecha Ejercido
     ->whereYear('c.fechaEjercido', $dateY)
     ->where('s.estatus', '=', '1')
      //->where('s.maxDiasAtraso', '<', '91')// Se quita filtro para guardar todos los registros
     ->whereNull('s.idGestor')
     ->where('c.producto', '<>', 'REESTRUCTURA VIVIENDA - MENS 2015')
     ->where('c.producto', '<>', 'REESTRUCTURA VIVIENDA - QUINC 2015')
     //->where('c.fechaInicio','>','"30/06/2017"')
     ->whereIn('c.plazo', [19,38,83,25,50,109])
     ->get();
        return $viv19;
    }
    public function getViv2($pago)
    {
        if ($pago==2) {
            $operador='=';
            $estatus='1';
        } elseif ($pago==3) {
            $operador='>=';
            $estatus='0';
        }

        $viv=DB::table('tblcreditos as c')
     ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
     ->select('c.idCredito', 'c.idPerfil', 'c.montoInicial', 'c.plazo as plazomes', 'c.negocio', 's.diasAtraso', 's.maxDiasAtraso', 'c.fechaEjercido', 'c.fechaFin', 's.fCierre')
     ->where('c.negocio', '=', 'VIVIENDA')
     //->whereRaw('MONTH(c.fechaInicio)='.$datem.' and YEAR(c.fechaInicio)='.$dateY)
     //->whereMonth('c.fechaInicio',$datem)
     //->whereYear('c.fechaInicio',$dateY)
     ->whereDate('c.fechaEjercido', '>', '2017-06-30')//solo creditos ministrados apartir de julio2017
     ->where('s.estatus', $operador, $estatus)
      //->where('s.maxDiasAtraso', '<', '91')// Se quita filtro para guardar todos los registros
     ->whereNull('s.idGestor')
     ->where('c.producto', '<>', 'REESTRUCTURA VIVIENDA - MENS 2015')
     ->where('c.producto', '<>', 'REESTRUCTURA VIVIENDA - QUINC 2015')
     //->whereIn('c.plazo',[19,38,83])[13,18,26,57]
     ->get();
        return $viv;
    }
    public function getProd($pago, $fecha)
    {
        if ($pago==1) {
            $fechastr= strtotime($fecha);
        }
        //$fCorte=date('Y-m-d',$fechastr);
        $datem=date('m', $fechastr);
        $dateY=date('Y', $fechastr);

        $prod=DB::table('tblcreditos as c')
     ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
     ->select('c.idCredito', 'c.idPerfil', 'c.montoInicial', 'c.plazo as plazomes', 'c.negocio', 's.diasAtraso')
     ->where('c.negocio', '=', 'PRODUCTIVO')
     //->whereRaw('MONTH(c.fechaInicio)='.$datem.' and YEAR(c.fechaInicio)='.$dateY)
     ->whereMonth('c.fechaEjercido', $datem)
     ->whereYear('c.fechaEjercido', $dateY)
     ->where('s.estatus', '=', '1')
     ->whereNull('s.idGestor')
      //->where('s.maxDiasAtraso', '<', '91')// Se quita filtro para guardar todos los registros
     ->where('c.producto', '<>', 'REESTRUCTURA PRODUCTIVO GRAMEEN-ALFIN - QUINCENAL')
     ->where('c.producto', '<>', 'REESTRUCTURA PRODUCTIVO GRAMEEN-ALFIN - MENSUAL')
     ->where('c.producto', '<>', '6.6% GLOBALES FRONTERA INDIVIDUAL - QUINCENAL')
     //->where('c.producto', '<>', '6.6% GLOBALES FRONTERA INDIVIDUAL - QUINCENAL') Quitarproducto Maiz
     ->get();
        return $prod;
    }
    public function getProd2($pago)
    {
        if ($pago==2) {
            $operador='=';
            $estatus='1';
        } elseif ($pago==3) {
            $operador='>=';
            $estatus='0';
        }
        $prod=DB::table('tblcreditos as c')
     ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
     ->select('c.idCredito', 'c.idPerfil', 'c.montoInicial', 'c.plazo as plazomes', 'c.negocio', 's.diasAtraso', 's.maxDiasAtraso', 'c.fechaEjercido', 'c.fechaFin', 's.fCierre')
     ->where('c.negocio', '=', 'PRODUCTIVO')
     //->whereRaw('MONTH(c.fechaInicio)='.$datem.' and YEAR(c.fechaInicio)='.$dateY)
     //->whereMonth('c.fechaInicio',$datem)
     //->whereYear('c.fechaInicio',$dateY)
     ->whereDate('c.fechaEjercido', '>', '2017-06-30')//solo creditos ministrados apartir de julio2017
     ->where('s.estatus', $operador, $estatus)
     ->whereNull('s.idGestor')
     //->where('s.maxDiasAtraso', '<', '91')// Se quita filtro para guardar todos los registros
     ->where('c.producto', '<>', 'REESTRUCTURA PRODUCTIVO GRAMEEN-ALFIN - QUINCENAL')
     ->where('c.producto', '<>', 'REESTRUCTURA PRODUCTIVO GRAMEEN-ALFIN - MENSUAL')
     ->where('c.producto', '<>', '6.6% GLOBALES FRONTERA INDIVIDUAL - QUINCENAL')
     ->get();
        return $prod;
    }

    public function getResumenSC($idPerfil, $cuenta, $monto, $condicion)
    {
        $resumen=DB::table('catperfiles as cp')
       ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
       ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
       ->select('c.idPerfil', DB::raw('count('.$cuenta.') as cuenta'), DB::raw('sum('.$monto.') as monto'))
       ->where('c.idPerfil', '=', $idPerfil)
       ->whereRaw($condicion)
       ->where('s.estatus', '=', '1')
       //->where('s.fechaSituacion', '=', DB::raw('curdate()'))
       //->where('s.diasAtraso','<','91')
       ->groupBy('c.idPerfil')
       ->first();

        if (is_null($resumen)) {
            $x=DB::table('catperfiles as cp')
          ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
          ->join('tblsituacioncredito as s', 'c.idCredito', '=', 's.idCredito')
          ->select('c.idPerfil', DB::raw('0 as cuenta'), DB::raw('0 as monto'))
          ->where('c.idPerfil', '=', $idPerfil)
          ->where('s.estatus', '=', '1')
          ->first();
            return $x;
        }
        return $resumen;
    }
    public function createCom($idCredito, $idNivel, $pago, $monto, $aprobado, $date, $idPerfil)
    {
        $comision=new Comision;
        $comision->idCredito=$idCredito;
        $comision->idNivel=$idNivel;
        $comision->pago=$pago;
        $comision->monto=$monto;
        $comision->aprobado=$aprobado;
        $comision->fechaCorte=$date;
        $comision->idPerfil=$idPerfil;
        $comision->save();
        return $comision->id;
    }
    public function updateCom($idCredito, $idNivel, $pago, $monto, $aprobado, $date, $idPerfil)
    {
        $existg=Comision::where('idCredito', '=', $idCredito)->where('pago', '=', $pago)->where('idNivel', '=', $idNivel)->first();
        $existg->idNivel=$idNivel;
        $existg->monto=$monto;
        $existg->aprobado=$aprobado;
        $existg->fechaCorte=$date;
        $existg->idPerfil=$idPerfil;
        $existg->update();
        return $existg->id;
    }
    public function createCond($idRegla, $idComision, $valor, $estatus)
    {
        $condicion=new CondicionCm;
        $condicion->idRegla=$idRegla;
        $condicion->idComision=$idComision;
        $condicion->valor=$valor;
        $condicion->estatus=$estatus;
        $condicion->save();
        return 1;
    }
    public function updateCond($idRegla, $idComision, $valor, $estatus)
    {
        $existCond=CondicionCm::where('idRegla', '=', $idRegla)->where('idComision', '=', $idComision)->first();
        $existCond->valor=$valor;
        $existCond->estatus=$estatus;
        $existCond->update();
        return 1;
    }
}
