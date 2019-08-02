<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use DB;
use wbALFINop\DashOperacion;

class RptbiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (Auth::user()->idNivel<4) {
            return view('agenda.rptbi.index');
        } else {
            return back()->withInput();
        }
    }
    public function rptSesion()
    {
        if (Auth::user()->idNivel<3) {
          $sessions=DB::table('tblsesiones as s')
          ->join('users as u', 's.id', '=', 'u.id')
          ->join('catperfiles as p', 'u.idPerfil', '=', 'p.idPerfil')
          ->join('catpersonas as pr', 'p.idPersona', '=', 'pr.idPersona')
          ->join('catsucursales as suc', 'p.idSucursal', '=', 'suc.idSucursal')
          ->join('catregionales as r', 'suc.idRegional', '=', 'r.idRegional')
          ->select('r.descripcion', 'suc.sucursal', 'pr.nombre', 's.f_login', 's.f_logout',DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND,s.f_login,s.f_logout)) as horas'))
          ->where('s.f_login', '>', DB::raw('concat(curdate()," 00:00:00")'))
          ->orderBy('r.descripcion', 'asc')
          ->orderBy('suc.idRegional', 'asc')
          ->get();
        }elseif (Auth::user()->idNivel==3) {
          $idRegion=DB::table('catperfiles as cp')
          ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
          ->where('idPerfil','=',Auth::user()->idPerfil)->value('suc.idRegional');
          $sessions=DB::table('tblsesiones as s')
          ->join('users as u', 's.id', '=', 'u.id')
          ->join('catperfiles as p', 'u.idPerfil', '=', 'p.idPerfil')
          ->join('catpersonas as pr', 'p.idPersona', '=', 'pr.idPersona')
          ->join('catsucursales as suc', 'p.idSucursal', '=', 'suc.idSucursal')
          ->join('catregionales as r', 'suc.idRegional', '=', 'r.idRegional')
          ->select('r.descripcion', 'suc.sucursal', 'pr.nombre', 's.f_login', 's.f_logout',DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND,s.f_login,s.f_logout)) as horas'))
          ->where('s.f_login', '>', DB::raw('concat(curdate()," 00:00:00")'))
          ->where('r.idRegional','=', $idRegion)
          ->orderBy('r.descripcion', 'asc')
          ->orderBy('suc.idRegional', 'asc')
          ->get();
        } else {
            return back()->withInput();
        }

         return view('agenda.rptbi.rptsesion')->with(['sessions'=>$sessions]);

    }
    public function rptAgenda()
    {
        if (Auth::user()->idNivel<4) {
            return view('agenda.rptbi.rptagenda');
        } else {
            return back()->withInput();
        }
    }
    public function getRCartera($agrupador,$order,$condicion, $operador, $val,$mes,$year)
    {
      $rcartera=DB::table('tbldashoper as d')
     ->join('catconceptos as cc', 'd.idConcepto', '=', 'cc.idConcepto')
     ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
     ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
     ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
     ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
     ->select(DB::raw($agrupador),DB::raw('sum(d.cuenta) as Conteo'),DB::raw('sum(d.monto) as Monto'),DB::raw('0 as Normalidad'))
     ->where($condicion, $operador, $val)
     ->whereMonth('d.fechaCorte','=', $mes)
     ->whereYear('d.fechaCorte','=',$year)
     ->where('d.estatus', '=', '1')
     ->groupBy(DB::raw($agrupador))
     ->orderBy(DB::raw($order))
     ->get();

     return $rcartera;
    }
    public function rptCartera()
    {
        if (Auth::user()->idNivel<5) {
          if (Auth::user()->idNivel==3) {
            $condicion='cr.idRegional';
            $operador='=';
            $valor= DB::table('catperfiles as cp')
             ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
             ->where('cp.idPerfil','=',Auth::user()->idPerfil)->value('suc.idRegional');
             $agrupador='cr.descripcion';

             $rcartera=$this->getRCartera('cs.idSucursal,cs.sucursal,cc.descripcion,d.idConcepto','cs.sucursal,d.idConcepto',$condicion,$operador,$valor,date('m'),date('Y'));
              // se recorre la coleccion para obtener valor de normalidad por cada Agrupador
            $rcartera->transform(function ($cr, $key){
              $totalCv=DB::table('tbldashoper as d')
             ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
             ->select(DB::raw('sum(d.monto) as monto'))
              ->where('d.idConcepto','=','1')->where('cp.idSucursal','=',$cr->idSucursal)
              ->whereMonth('fechaCorte','=', date('m'))
              ->whereYear('fechaCorte','=',date('Y'))->first();
              // Codigo sobre total de devengos para calcular Porcentajes (Solo devengos)
              $totalDv=DB::table('tbldashoper as d')
             ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
             ->select(DB::raw('sum(d.monto) as monto'))
              ->where('d.idConcepto','=','15')->where('cp.idSucursal','=',$cr->idSucursal)
              ->whereMonth('fechaCorte','=', date('m'))
              ->whereYear('fechaCorte','=',date('Y'))->first();

              if ($cr->idConcepto==15 || $cr->idConcepto==16 || $cr->idConcepto==17 || $cr->idConcepto==18) {
                if ($totalDv->monto>0) {
                  $cr->Normalidad=($cr->Monto/$totalDv->monto)*100;
                }else {
                 $cr->Normalidad=0;
                }
              }else{
                if ($totalCv->monto>0) {
                  $cr->Normalidad=($cr->Monto/$totalCv->monto)*100;
                }else {
                 $cr->Normalidad=0;
                }
              }

              return $cr;
            });
          }elseif (Auth::user()->idNivel==4) {
            $condicion='cs.idSucursal';
            $operador='=';
            $valor=DB::table('catperfiles as cp')->where('cp.idPerfil','=',Auth::user()->idPerfil)->value('cp.idSucursal');
            $agrupador='cs.sucursal';

            $rcartera=$this->getRCartera('cs.sucursal,cp.idPerfil,cpr.nombre,cc.descripcion,d.idConcepto','cs.sucursal,cp.idPerfil,d.idConcepto',$condicion,$operador,$valor,date('m'),date('Y'));

           $rcartera->transform(function ($cr, $key){
             $totalCv=DashOperacion::where('idConcepto','=','1')->where('idPerfil','=',$cr->idPerfil)
             ->whereMonth('fechaCorte','=', date('m'))->whereYear('fechaCorte','=',date('Y'))->first();
             $totalDv=DashOperacion::where('idConcepto','=','15')->where('idPerfil','=',$cr->idPerfil)
             ->whereMonth('fechaCorte','=', date('m'))->whereYear('fechaCorte','=',date('Y'))->first();
             if ($cr->idConcepto==15 || $cr->idConcepto==16 || $cr->idConcepto==17 || $cr->idConcepto==18) {
               if ($totalDv->monto>0) {
                 $cr->Normalidad=($cr->Monto/$totalDv->monto)*100;
               }else {
                $cr->Normalidad=0;
               }
             }else{
               if ($totalCv->monto>0) {
                 $cr->Normalidad=($cr->Monto/$totalCv->monto)*100;
               }else {
                $cr->Normalidad=0;
               }
             }
             return $cr;
           });
          }else{
            return back()->withInput();
          }

          $rescartera=DB::table('tbldashoper as d')
         ->join('catconceptos as cc', 'd.idConcepto', '=', 'cc.idConcepto')
         ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
         ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
         ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
         ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
         ->select($agrupador.' as Agrupador','d.idConcepto as id','cc.descripcion as Concepto',DB::raw('sum(d.cuenta) as Conteo'),DB::raw('sum(d.monto) as Monto'),
                  DB::raw('0 as Normalidad'),DB::raw('0 as ConteoMA'),DB::raw('0 as MontoMA'))
         ->where($condicion, $operador, $valor)
         ->whereMonth('d.fechaCorte','=', date('m'))
         ->whereYear('d.fechaCorte','=',date('Y'))
         ->where('d.estatus', '=', '1')
         ->groupBy($agrupador,'d.idConcepto','cc.descripcion')
         ->orderBy('d.idConcepto')
         ->get();//DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)')

         $totalC=$rescartera->where('id','=','1')->first();
         $totalD=$rescartera->where('id','=','15')->first();
         //$rescartera->dd();
         $rescartera->transform(function ($cr, $key) use ($totalC,$totalD,$agrupador,$condicion,$operador,$valor) {

             //$cr->Normalidad=($cr->Monto/$totalC->Monto)*100;

             if ($cr->id==15 || $cr->id==16 || $cr->id==17 || $cr->id==18) {
               if ($totalD->Monto>0) {
                 $cr->Normalidad=($cr->Monto/$totalD->Monto)*100;
               }else {
                $cr->Normalidad=0;
               }
             }else{
               if ($totalC->Monto>0) {
                 $cr->Normalidad=($cr->Monto/$totalC->Monto)*100;
               }else {
                $cr->Normalidad=0;
               }
             }

             $datesAnt=strtotime ( '-1 day' ,strtotime ( '-1 month' , strtotime (date('Y-m-d'))));

             $c=DB::table('tbldashoper as d')
            ->join('catconceptos as cc', 'd.idConcepto', '=', 'cc.idConcepto')
            ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
            ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
            ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
            ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
            ->select('cc.descripcion as Concepto',DB::raw('sum(d.cuenta) as Conteo'),DB::raw('sum(d.monto) as Monto'))
            ->where($condicion, $operador, $valor)
            ->whereMonth('d.fechaCorte','=', date('m',$datesAnt))
            ->whereYear('d.fechaCorte','=',date('Y',$datesAnt))
            //->where('d.fechaCorte','<',DB::raw('DATE_SUB(curdate(), INTERVAL 1 DAY)'))
            ->where('d.idConcepto', '=', $cr->id)
            ->where('d.estatus', '=', '1')
            ->groupBy('cc.descripcion')
            ->first();

            if (is_null($c)) {
              $cr->ConteoMA=0;
              $cr->MontoMA=0;
            }else {
              $cr->ConteoMA=$c->Conteo;
              $cr->MontoMA=$c->Monto;
            }
             return $cr;
         });

            return view('agenda.rptbi.rptcartera')->with(['cartera'=>$rcartera])->with(['rscartera'=>$rescartera]);
        } else {
            return back()->withInput();
        }
    }
    public function rptDevengo()
    {
       return view('agenda.rptbi.rptdevengo');
    }
    public function rptCarteraG()
    {
      if (Auth::user()->idNivel<5) {
        if (Auth::user()->idNivel==3) {
          $condicion='cr.idRegional';
          $operador='=';
          $valor= DB::table('catperfiles as cp')
           ->join('catsucursales as suc', 'cp.idSucursal', '=', 'suc.idSucursal')
           ->where('cp.idPerfil','=',Auth::user()->idPerfil)->value('suc.idRegional');
           $agrupador='cr.descripcion';

           $rcartera=$this->getRCartera('cs.idSucursal,cs.sucursal,cc.descripcion,d.idConcepto','cs.sucursal,d.idConcepto',$condicion,$operador,$valor,date('m'),date('Y'));
            // se recorre la coleccion para obtener valor de normalidad por cada Agrupador
          $rcartera->transform(function ($cr, $key){
            $totalCv=DB::table('tbldashoper as d')
           ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
           ->select(DB::raw('sum(d.monto) as monto'))
            ->where('d.idConcepto','=','1')->where('cp.idSucursal','=',$cr->idSucursal)
            ->whereMonth('fechaCorte','=', date('m'))
            ->whereYear('fechaCorte','=',date('Y'))->first();
            // Codigo sobre total de devengos para calcular Porcentajes (Solo devengos)
            $totalDv=DB::table('tbldashoper as d')
           ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
           ->select(DB::raw('sum(d.monto) as monto'))
            ->where('d.idConcepto','=','15')->where('cp.idSucursal','=',$cr->idSucursal)
            ->whereMonth('fechaCorte','=', date('m'))
            ->whereYear('fechaCorte','=',date('Y'))->first();

            if ($cr->idConcepto==15 || $cr->idConcepto==16 || $cr->idConcepto==17 || $cr->idConcepto==18) {
              if ($totalDv->monto>0) {
                $cr->Normalidad=($cr->Monto/$totalDv->monto)*100;
              }else {
               $cr->Normalidad=0;
              }
            }else{
              if ($totalCv->monto>0) {
                $cr->Normalidad=($cr->Monto/$totalCv->monto)*100;
              }else {
               $cr->Normalidad=0;
              }
            }

            return $cr;
          });
        }elseif (Auth::user()->idNivel==4) {
          $condicion='cs.idSucursal';
          $operador='=';
          $valor=DB::table('catperfiles as cp')->where('cp.idPerfil','=',Auth::user()->idPerfil)->value('cp.idSucursal');
          $agrupador='cs.sucursal';

          $rcartera=$this->getRCartera('cs.sucursal,cp.idPerfil,cpr.nombre,cc.descripcion,d.idConcepto','cs.sucursal,cp.idPerfil,d.idConcepto',$condicion,$operador,$valor,date('m'),date('Y'));

         $rcartera->transform(function ($cr, $key){
           $totalCv=DashOperacion::where('idConcepto','=','1')->where('idPerfil','=',$cr->idPerfil)
           ->whereMonth('fechaCorte','=', date('m'))->whereYear('fechaCorte','=',date('Y'))->first();
           $totalDv=DashOperacion::where('idConcepto','=','15')->where('idPerfil','=',$cr->idPerfil)
           ->whereMonth('fechaCorte','=', date('m'))->whereYear('fechaCorte','=',date('Y'))->first();
           if ($cr->idConcepto==15 || $cr->idConcepto==16 || $cr->idConcepto==17 || $cr->idConcepto==18) {
             if ($totalDv->monto>0) {
               $cr->Normalidad=($cr->Monto/$totalDv->monto)*100;
             }else {
              $cr->Normalidad=0;
             }
           }else{
             if ($totalCv->monto>0) {
               $cr->Normalidad=($cr->Monto/$totalCv->monto)*100;
             }else {
              $cr->Normalidad=0;
             }
           }
           return $cr;
         });
        }else{
          return back()->withInput();
        }

        $rescartera=DB::table('tbldashoper as d')
       ->join('catconceptos as cc', 'd.idConcepto', '=', 'cc.idConcepto')
       ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
       ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
       ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
       ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
       ->select($agrupador.' as Agrupador','d.idConcepto as id','cc.descripcion as Concepto',DB::raw('sum(d.cuenta) as Conteo'),DB::raw('sum(d.monto) as Monto'),
                DB::raw('0 as Normalidad'),DB::raw('0 as ConteoMA'),DB::raw('0 as MontoMA'))
       ->where($condicion, $operador, $valor)
       ->whereMonth('d.fechaCorte','=', date('m'))
       ->whereYear('d.fechaCorte','=',date('Y'))
       ->where('d.estatus', '=', '1')
       ->groupBy($agrupador,'d.idConcepto','cc.descripcion')
       ->orderBy('d.idConcepto')
       ->get();//DB::raw('DATE_SUB(curdate(), INTERVAL 1 MONTH)')

       $totalC=$rescartera->where('id','=','1')->first();
       $totalD=$rescartera->where('id','=','15')->first();
       //$rescartera->dd();
       $rescartera->transform(function ($cr, $key) use ($totalC,$totalD,$agrupador,$condicion,$operador,$valor) {

           //$cr->Normalidad=($cr->Monto/$totalC->Monto)*100;

           if ($cr->id==15 || $cr->id==16 || $cr->id==17 || $cr->id==18) {
             if ($totalD->Monto>0) {
               $cr->Normalidad=($cr->Monto/$totalD->Monto)*100;
             }else {
              $cr->Normalidad=0;
             }
           }else{
             if ($totalC->Monto>0) {
               $cr->Normalidad=($cr->Monto/$totalC->Monto)*100;
             }else {
              $cr->Normalidad=0;
             }
           }

           $datesAnt=strtotime ( '-1 day' ,strtotime ( '-1 month' , strtotime (date('Y-m-d'))));

           $c=DB::table('tbldashoper as d')
          ->join('catconceptos as cc', 'd.idConcepto', '=', 'cc.idConcepto')
          ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
          ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
          ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
          ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
          ->select('cc.descripcion as Concepto',DB::raw('sum(d.cuenta) as Conteo'),DB::raw('sum(d.monto) as Monto'))
          ->where($condicion, $operador, $valor)
          ->whereMonth('d.fechaCorte','=', date('m',$datesAnt))
          ->whereYear('d.fechaCorte','=',date('Y',$datesAnt))
          //->where('d.fechaCorte','<',DB::raw('DATE_SUB(curdate(), INTERVAL 1 DAY)'))
          ->where('d.idConcepto', '=', $cr->id)
          ->where('d.estatus', '=', '1')
          ->groupBy('cc.descripcion')
          ->first();

          if (is_null($c)) {
            $cr->ConteoMA=0;
            $cr->MontoMA=0;
          }else {
            $cr->ConteoMA=$c->Conteo;
            $cr->MontoMA=$c->Monto;
          }
           return $cr;
       });

          return view('agenda.rptbi.rptcartera')->with(['cartera'=>$rcartera])->with(['rscartera'=>$rescartera]);
      } else {
          return back()->withInput();
      }
    }
}
