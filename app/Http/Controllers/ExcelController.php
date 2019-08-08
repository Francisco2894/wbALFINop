<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Input;
use Excel;
use DB;
use wbALFINop\Credito;
use wbALFINop\SituacionCredito;
use wbALFINop\Devengo;
use wbALFINop\Perfil;
use wbALFINop\DomicilioCredito;
use wbALFINop\CondicionCm;
use wbALFINop\Pagos;
use wbALFINop\Cliente;

ini_set('max_execution_time', 360);

$r=0;
$u=0;

class ExcelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->idNivel<3) {
            return view('agenda.dataexcel.index')
            ->with(['records'=>0,'updaterecords'=>0,'records2'=>0,'updaterecords2'=>0,'records3'=>0,'updaterecords3'=>0]);
        } else {
            return back()->withInput();
        }
    }
    public function viewDevengo()
    {
        if (Auth::user()->idNivel<3) {
            return view('agenda.dataexcel.importdevengo')
            ->with(['records'=>0,'updaterecords'=>0,'records2'=>0,'updaterecords2'=>0]);
        } else {
            return back()->withInput();
        }
    }
    public function viewPago()
    {
        if (Auth::user()->idNivel<3) {
            return view('agenda.dataexcel.importpagos')
            ->with(['records'=>0,'updaterecords'=>0,'records2'=>0,'updaterecords2'=>0]);
        } else {
            return back()->withInput();
        }
    }

    public function viewRenovacion(){
      if (Auth::user()->idNivel<3) {
        return view('agenda.dataexcel.importdatosrenovacion')
        ->with(['records'=>0,'updaterecords'=>0,'records2'=>0,'updaterecords2'=>0,'records3'=>0,'updaterecords3'=>0]);
      } else {
          return back()->withInput();
      }
    }

    public function importInfCreticia(){
      global $r, $u;
        $r=0;
        $u=0;

        if (Input::hasFile('infc')) {
            $path = Input::file('infc')->getRealPath();

            $name = Input::file('infc')->getClientOriginalName();
            $ext = Input::file('infc')->getClientOriginalExtension();
            List($pref,$date)=explode("-",trim($name));

            return $pref;
            if ($pref=="RCE") {

            Excel::filter('chunk')->load($path)->chunk(250, function ($reader) {
              //Se crea archivo con detalle de salida
             // $fr=fopen("result.txt","w");
             global $r, $u;
                if (!empty($reader) && $reader->count()) {
                    foreach ($reader as $fila) {
                        $existId=Credito::where('idCredito', '=', $fila->id_credito)->first();
                        if (count($existId)==1) {
                            $existId->cveAseCol=$fila->clave_asesor;
                            $existId->fechaEjercido=$this->getDateYmd($fila->fecha_ejercido);
                            $existId->update();
                            $u++;
                        }
                    }
                }
            });
          }
        } else {
            return view('agenda.dataexcel.index')
          ->with(['records'=>0,'updaterecords'=>0,'proceso'=>"Importación Fecha Ejercido ".$name])
          ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Importación Fecha Ejercido ".$name])
          ->with(['records3'=>0,'updaterecords3'=>0,'proceso3'=>"Importación Fecha Ejercido ".$name]);
        }
        return view('agenda.dataexcel.index')
        ->with(['records'=>$r,'updaterecords'=>$u,'proceso'=>"Importación Fecha Ejercido ".$name])
        ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Importación Fecha Ejercido ".$name])
        ->with(['records3'=>$r,'updaterecords3'=>$u,'proceso3'=>"Importación Fecha Ejercido ".$name]);

    }

    public function importsca()
    {
      global $r,$u,$r2,$u2;
      $r=0;
      $u=0;
      $r2=0;
      $u2=0;
      //return count($cliente = Cliente::where('idcliente',1)->first());
        if (Input::hasFile('scafile')) {
            $path = Input::file('scafile')->getRealPath();
            $name = Input::file('scafile')->getClientOriginalName();
            $ext = Input::file('scafile')->getClientOriginalExtension();
            List($pref,$date)=explode("-",trim($name));

           if ($pref=="SC") {

            Excel::filter('chunk')->load($path)->chunk(250, function ($reader) {
             global $r,$u,$r2,$u2;

                if (!empty($reader) && $reader->count()) {
                    foreach ($reader as $fila) {

                        $cliente = Cliente::where('idcliente',$fila->id_persona)->first();
                        if (is_null($cliente)) {
                          $crearCliente = Cliente::create([
                            'idcliente'=>$fila->id_persona,
                            'nombre'=>$fila->vnombre_completo_cliente
                          ]);
                        }
                        $existId=Credito::where('idCredito', '=', $fila->id_credito)->first();//se valida que el crédito no exista en la BD
                        $existPerfil=Perfil::where('idPerfil', '=', trim($fila->clave_vendedor))->first();//se valida que el crédito no exista en la BD
                        if (is_null($existId) && !is_null($existPerfil)) {
                            $credito=new Credito;
                            $credito->idCredito=$fila->id_credito;
                            $credito->idCliente=$fila->id_persona;
                            $credito->idSolicitud=$fila->id_solicitud;
                            $credito->nomCliente=$fila->vnombre_completo_cliente;
                            $credito->montoInicial=$fila->capital_inicial;
                            $credito->plazo=$fila->plazo;
                            $credito->frecuenciaPago=$fila->frecuencia_de_pago;
                            $credito->cveproducto=$fila->producto;
                            $credito->producto=$fila->producto_financiero;
                            $credito->negocio=$fila->negocio;
                            $credito->fechaInicio=$this->getDateYmd($fila->fecha_inicio);
                            $credito->fechaFin=$this->getDateYmd($fila->fecha_fin);
                            $credito->idPerfil=$fila->clave_vendedor;
                            $credito->save();
                            $r=$r+1;
                        }else if (!is_null($existId) && !is_null($existPerfil)) {
                          $existId->nomCliente=$fila->vnombre_completo_cliente;
                          $existId->negocio=$fila->negocio;
                          $existId->cveproducto=$fila->producto;
                          $existId->fechaInicio=$this->getDateYmd($fila->fecha_inicio);
                          $existId->fechaFin=$this->getDateYmd($fila->fecha_fin);
                          $existId->idPerfil=$fila->clave_vendedor;
                          $existId->update();
                          $u=$u+1;
                        }
                            // Importacion de situacion credito
                          $existIdc=Credito::where('idCredito', '=', $fila->id_credito)->first();//se valida que el crédito exista en la BD
                          $existIds=SituacionCredito::where('idCredito', '=', $fila->id_credito)->first();
                          if (is_null($existIds) && !is_null($existIdc)) {
                              $situacioncredito=new SituacionCredito;
                              $situacioncredito->saldo=$fila->saldo_capital;
                              $situacioncredito->capitalVigente=$fila->capital_vigente;
                              $situacioncredito->capitalVencido=$fila->capital_vencido;
                              $situacioncredito->montoRiesgo=$fila->capital_en_riesgo;
                              $situacioncredito->saldoExigible=$fila->exigible;
                              $situacioncredito->diasAtraso=$fila->dias_atraso;
                              $situacioncredito->maxDiasAtraso=$fila->maximo_retraso_historico;
                              $situacioncredito->diasAtrasoDev=$fila->retraso_en_ultimo_devengo;
                              $situacioncredito->fechaSituacion=date('Y-m-d');
                              $situacioncredito->estatus=1;
                              $situacioncredito->idCredito=$fila->id_credito;
                              $situacioncredito->idGestor=$fila->id_gestor;
                              $situacioncredito->f_ult_pago=$this->getDateYmd($fila->fecha_de_ult_pago);
                              $situacioncredito->monto_ult_pago=$fila->importe_ult_pago;
                              $situacioncredito->save();
                              $r2++;
                          } elseif (!is_null($existIds) && !is_null($existIdc)) {
                              //$situacioncredito=SituacionCredito::where('idCredito', '=', $fila->id_credito)->first();
                              //$situacioncredito=SituacionCredito::findOrFail($ids);
                              $existIds->saldo=$fila->saldo_capital;
                              $existIds->capitalVigente=$fila->capital_vigente;
                              $existIds->capitalVencido=$fila->capital_vencido;
                              $existIds->montoRiesgo=$fila->capital_en_riesgo;
                              $existIds->saldoExigible=$fila->exigible;
                              $existIds->diasAtraso=$fila->dias_atraso;
                              $existIds->maxDiasAtraso=$fila->maximo_retraso_historico;
                              $existIds->diasAtrasoDev=$fila->retraso_en_ultimo_devengo;
                              $existIds->fechaSituacion=date('Y-m-d');
                              $existIds->estatus=1;
                              $existIds->idGestor=$fila->id_gestor;
                              $existIds->f_ult_pago=$this->getDateYmd($fila->fecha_de_ult_pago);
                              $existIds->monto_ult_pago=$fila->importe_ult_pago;
                              $existIds->update();
                              $u2++;
                          }
                      }

                    }
                });
          }else {
            return view('agenda.dataexcel.index')
            ->with(['records'=>0,'updaterecords'=>0,'proceso'=>"Favor de revisar el nombre del archivo ".$name])
            ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Importación Situación de Creditos ".$name])
            ->with(['records3'=>0,'updaterecords3'=>0,'proceso3'=>"Importación Fecha Ejercido ".$name]);
          }
          }

        return view('agenda.dataexcel.index')
        ->with(['records'=>$r,'updaterecords'=>$u,'proceso'=>"Importación de Créditos ".$name])
        ->with(['records2'=>$r2,'updaterecords2'=>$u2,'proceso2'=>"Importación Situación de Créditos ".$name])
        ->with(['records3'=>0,'updaterecords3'=>0,'proceso3'=>"Importación Fecha Ejercido ".$name]);
    }

    public function importFliq()
    {
      global $r, $u;
       $r=0;
       $u=0;

       if (Input::hasFile('rptliqc')) {
           $path = Input::file('rptliqc')->getRealPath();

           $name = Input::file('rptliqc')->getClientOriginalName();
           $ext = Input::file('rptliqc')->getClientOriginalExtension();
           List($pref,$date)=explode("-",trim($name));

           if ($pref=="LC") {

           Excel::filter('chunk')->load($path)->chunk(250, function ($reader) {
             //Se crea archivo con detalle de salida
            // $fr=fopen("result.txt","w");
            global $r, $u;
               if (!empty($reader) && $reader->count()) {
                   foreach ($reader as $fila) {
                       $existId=SituacionCredito::where('idCredito', '=', $fila->id_credito)->first();
                       if (count($existId)==1) {
                           $existId->fCierre=$this->getDateYmd($fila->vdate);
                           $existId->nPagos=$fila->ipays;
                           $existId->update();
                           $u++;
                       }
                   }
               }
           });
         }
       } else {
           return view('agenda.dataexcel.index')
         ->with(['records'=>0,'updaterecords'=>0,'proceso'=>"Importación Liquidacion y Cierre ".$name])
         ->with(['records3'=>0,'updaterecords3'=>0,'proceso3'=>"Importación Liquidacion y Cierre ".$name])
         ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Importación Liquidacion y Cierre ".$name]);
       }
       return view('agenda.dataexcel.index')
       ->with(['records'=>$r,'updaterecords'=>$u,'proceso'=>"Importación Liquidacion y Cierre ".$name])
       ->with(['records3'=>0,'updaterecords3'=>0,'proceso3'=>"Importación Liquidacion y Cierre ".$name])
       ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Importación Liquidacion y Cierre ".$name]);
    }

    public function importFeje()
    {
        global $r, $u;
        $r=0;
        $u=0;

        if (Input::hasFile('rptrce')) {
            $path = Input::file('rptrce')->getRealPath();

            $name = Input::file('rptrce')->getClientOriginalName();
            $ext = Input::file('rptrce')->getClientOriginalExtension();
            List($pref,$date)=explode("-",trim($name));

            if ($pref=="RCE") {

            Excel::filter('chunk')->load($path)->chunk(250, function ($reader) {
              //Se crea archivo con detalle de salida
             // $fr=fopen("result.txt","w");
             global $r, $u;
                if (!empty($reader) && $reader->count()) {
                    foreach ($reader as $fila) {
                        $existId=Credito::where('idCredito', '=', $fila->id_credito)->first();
                        if (count($existId)==1) {
                            $existId->cveAseCol=$fila->clave_asesor;
                            $existId->fechaEjercido=$this->getDateYmd($fila->fecha_ejercido);
                            $existId->update();
                            $u++;
                        }
                    }
                }
            });
          }
        } else {
            return view('agenda.dataexcel.index')
          ->with(['records'=>0,'updaterecords'=>0,'proceso'=>"Importación Fecha Ejercido ".$name])
          ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Importación Fecha Ejercido ".$name])
          ->with(['records3'=>0,'updaterecords3'=>0,'proceso3'=>"Importación Fecha Ejercido ".$name]);
        }
        return view('agenda.dataexcel.index')
        ->with(['records'=>$r,'updaterecords'=>$u,'proceso'=>"Importación Fecha Ejercido ".$name])
        ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Importación Fecha Ejercido ".$name])
        ->with(['records3'=>$r,'updaterecords3'=>$u,'proceso3'=>"Importación Fecha Ejercido ".$name]);
    }

    public function importDev()
    {
      global $r, $u,$r2, $u2;
      $r=0;
      $u=0;
      $r2=0;
      $u2=0;

        if (Input::hasFile('devfile')) {
            $path = Input::file('devfile')->getRealPath();

            $name = Input::file('devfile')->getClientOriginalName();
            $ext = Input::file('devfile')->getClientOriginalExtension();
            List($pref,$date,$prod)=explode("-",trim($name));

           if ($pref=="DEVENGO") {

            Excel::filter('chunk')->load($path)->chunk(250, function ($reader) {
              global $r, $u,$r2, $u2;
                if (!empty($reader) && $reader->count()) {
                    foreach ($reader as $fila) {
                      if (strlen($fila->fecha_de_devengo)>8) {
                        $existIdc=Credito::where('idCredito', '=', $fila->id_credito)->first();//se valida que el crédito exista en la BD
                        $existId=Devengo::where('idCredito', '=', $fila->id_credito)->where('fechaDevengo', '=', $this->getDateYmd(trim($fila->fecha_de_devengo)))->first();
                        if (count($existId)==0 && count($existIdc)>=1) {
                            $devengo=new Devengo;
                            $devengo->numCuota=1;
                            $devengo->cuota=$fila->pago_requerido;
                            $devengo->fechaDevengo=$this->getDateYmd($fila->fecha_de_devengo);
                            $devengo->saldo=$fila->saldo_capital;
                            $devengo->diasAtraso=$fila->dias_de_atraso;
                            $devengo->maxDiasAtraso=$fila->maxdiasatraso;
                            $devengo->diasAtrasoDev=$fila->diasatrasodev;
                            $devengo->fechaPagoReciente=$this->getDateYmd($fila->f_ult_pago);
                            $devengo->montoPagado=$fila->monto_ult_pag;
                            $devengo->montoExigible=$fila->saldo_venc;
                            $devengo->idCredito=$fila->id_credito;
                            $devengo->estatus=0;
                            $devengo->save();
                            $r++;
                        } elseif (count($existId)==1 && count($existIdc)>=1) {
                            $existId->numCuota=1;
                            $existId->cuota=$fila->pago_requerido;
                            $existId->saldo=$fila->saldo_capital;
                            $existId->diasAtraso=$fila->dias_de_atraso;
                            //$existId->maxDiasAtraso=$fila->maxdiasatraso;
                            //$existId->diasAtrasoDev=$fila->diasatrasodev;
                            $existId->fechaPagoReciente=$this->getDateYmd($fila->f_ult_pago);
                            $existId->montoPagado=$fila->monto_ult_pag;
                            $existId->montoExigible=$fila->saldo_venc;
                            $existId->update();
                            $u++;
                        }
                        //Importacion de domicilioscredito

                        $existIdd=DomicilioCredito::where('idCredito', '=', $fila->id_credito)->first();
                        if (count($existIdd)==0 && count($existIdc)>=1) {
                            $domicilio=new DomicilioCredito;
                            $domicilio->idCredito=$fila->id_credito;
                            $domicilio->estado=$fila->edo;
                            $domicilio->municipio=$fila->ciudad;
                            $domicilio->localidad=$fila->ciudad;
                            $domicilio->colonia=$fila->colonia;
                            $domicilio->calle=$fila->calle;
                            $domicilio->numExt=$fila->numero_ext;
                            $domicilio->numInt=$fila->numero_int;
                            $domicilio->cp=$fila->cp;
                            $domicilio->descripcion="Descripcion Domicilio";
                            $domicilio->telefonoFijo=$fila->telefono;
                            $domicilio->telefonoCelular=$fila->celular;
                            $domicilio->latitud=$fila->latitud;
                            $domicilio->longitud=$fila->longitud;
                            $domicilio->idTipoDomicilio=1;
                            $domicilio->save();
                            $r2++;
                        } elseif (count($existIdd)==1 && count($existIdc)>=1) {
                            $existIdd->estado=$fila->edo;
                            $existIdd->municipio=$fila->ciudad;
                            $existIdd->localidad=$fila->ciudad;
                            $existIdd->colonia=$fila->colonia;
                            $existIdd->calle=$fila->calle;
                            $existIdd->numExt=$fila->numero_ext;
                            $existIdd->numInt=$fila->numero_int;
                            $existIdd->cp=$fila->cp;
                            $existIdd->descripcion="Descripcion Domicilio";
                            $existIdd->telefonoFijo=$fila->telefono;
                            $existIdd->telefonoCelular=$fila->celular;
                            $existIdd->latitud=$fila->latitud;
                            $existIdd->longitud=$fila->longitud;
                            $existIdd->idTipoDomicilio=1;
                            $existIdd->update();
                            $u2++;
                        }
                    }
                  }
                }
            });
          }else {
            return view('agenda.dataexcel.importdevengo')
            ->with(['records'=>0,'updaterecords'=>0,'proceso'=>"Revisar nombre de archivo"])
            ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Revisar nombre de archivo"]);
          }
        }
        //return back();
        return view('agenda.dataexcel.importdevengo')
        ->with(['records'=>$r,'updaterecords'=>$u,'proceso'=>"Importación Devengos ".$name])
        ->with(['records2'=>$r2,'updaterecords2'=>$u2,'proceso2'=>"Importación Domicilio Credito ".$name]);
    }

    public function importPago()
    {
        global $r, $u;
        $r=0;
        $u=0;

        if (Input::hasFile('apfile')) {
            $path = Input::file('apfile')->getRealPath();

            $name = Input::file('apfile')->getClientOriginalName();
            $ext = Input::file('apfile')->getClientOriginalExtension();
            List($pref,$date)=explode("-",trim($name));

            if ($pref=="AP") {

            Excel::filter('chunk')->load($path)->chunk(250, function ($reader) {
              //Se crea archivo con detalle de salida
             // $fr=fopen("result.txt","w");
             global $r, $u;
                if (!empty($reader) && $reader->count()) {
                    foreach ($reader as $fila) {
                        $existIdc=Credito::where('idCredito', '=', $fila->id_credito)->first();//se valida que el crédito exista en la BD
                        $existId=Pagos::where('idPago', '=', $fila->id_pago)->first();
                        if (count($existId)==0 && count($existIdc)>=1) {
                            $pago=new Pagos;
                            $pago->idPago=$fila->id_pago;
                            $pago->idCredito=$fila->id_credito;
                            $pago->f_Movimiento=$this->getDateYmd($fila->fecha_movimiento);
                            $pago->estatus=$fila->estatus;
                            $pago->f_Aplicacion=$this->getDateYmd($fila->fecha_aplicacion);
                            $pago->ref_Pago=$fila->ref_Pago;
                            $pago->origen=$fila->origen;
                            $pago->monto=$fila->monto;
                            $pago->capital=$fila->capital;
                            $pago->interes=$fila->interes;
                            $pago->iva_int=$fila->iva_de_int;
                            $pago->save();
                            $r++;
                        } elseif (count($existId)==1 && count($existIdc)>=1) {
                            $existId->idCredito=$fila->id_credito;
                            $existId->f_Movimiento=$this->getDateYmd($fila->fecha_movimiento);
                            $existId->estatus=$fila->estatus;
                            $existId->f_Aplicacion=$this->getDateYmd($fila->fecha_aplicacion);
                            $existId->ref_Pago=$fila->ref_Pago;
                            $existId->origen=$fila->origen;
                            $existId->monto=$fila->monto;
                            $existId->capital=$fila->capital;
                            $existId->interes=$fila->interes;
                            $existId->iva_int=$fila->iva_de_int;
                            $existId->update();
                            $u++;
                        }
                    }
                }
            });
          }
        } else {
            return view('agenda.dataexcel.importpagos')
          ->with(['records'=>0,'updaterecords'=>0,'proceso'=>"Importación de Aplicación de pagos ".$name])
          ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Importación de Aplicación de pagos ".$name]);
        }
        return view('agenda.dataexcel.importpagos')
        ->with(['records'=>$r,'updaterecords'=>$u,'proceso'=>"Importación de Aplicación de pagos ".$name])
        ->with(['records2'=>0,'updaterecords2'=>0,'proceso2'=>"Importación de Aplicación de pagos ".$name]);
    }

    //  Export Functions
    public function DashOperacion(request $request)
    {
       if ($request) {
         $dateini= trim($request->get('dtpFechaIni'));
         $datefin= trim($request->get('dtpFechaFin'));
         $resumen=DB::table('tbldashoper as d')
        ->join('catconceptos as cc', 'd.idConcepto', '=', 'cc.idConcepto')
        ->join('catperfiles as cp', 'd.idPerfil', '=', 'cp.idPerfil')
        ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
        ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
        ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
        ->select('cr.descripcion as Regional','cs.sucursal as Sucursal','cp.idPerfil as Clave_Vendedor','cpr.nombre as Nombre_Vendedor',
        'cc.descripcion as Concepto','d.cuenta as Conteo','d.monto as Monto','d.fechaCorte as Fecha_Actualizacion',DB::raw('MONTH(d.fechaCorte) as Numes'),DB::raw('MONTHNAME(d.fechaCorte) as Mes'),DB::raw('YEAR(d.fechaCorte) as Year'))
        ->where('d.fechaCorte', '>=', $dateini)
        ->where('d.fechaCorte', '<=', $datefin)
        ->where('d.estatus', '=', '1')
        ->get();
        //$resumen->dd();

       Excel::create('dtResOp'.date('Ymd'), function($excel) use($resumen) {
      $excel->sheet('dtResOP', function($sheet) use($resumen) {
      $sheet->row(1,['Regional','Sucursal','Clave_Vendedor','Nombre_Vendedor','Concepto','Cuenta','Monto','Fecha_Actualizacion','NUMMES','Mes','Año']);
      //$data=[];
      $sheet->setColumnFormat(array(
    'C' => '@',
    'F' => '0',
    'G' => '0.00',
    'H' => 'dd/mm/yyyy',
    'I' => '@',
    ));
      foreach ($resumen as $res) {
        $row=[];
        $row[0]=$res->Regional;
        $row[1]=$res->Sucursal;
        $row[2]=$res->Clave_Vendedor;
        $row[3]=$res->Nombre_Vendedor;
        $row[4]=$res->Concepto;
        $row[5]=$res->Conteo;
        $row[6]=$res->Monto;
        $row[7]=$res->Fecha_Actualizacion;
        $row[8]=$res->Numes;
        $row[9]=$res->Mes;
        $row[10]=$res->Year;
        //$data[]=$row;
        $sheet->appendRow($row);
      }
      //$sheet->fromArray($data);

    });
  })->export('xlsx');

     }
    }
    public function downCom(request $request)
    {
       if ($request) {
         $dateini= trim($request->get('dtpFechaIni'));
         $datefin= trim($request->get('dtpFechaFin'));

         $resumen=DB::table('tblcomisiones as com')
        ->join('tblcreditos as c', 'com.idCredito', '=', 'c.idCredito')
        ->join('catperfiles as cp', 'com.idPerfil', '=', 'cp.idPerfil')
        ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
        ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
        ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
        ->select('com.id','cr.descripcion as Regional','cs.sucursal as Sucursal','com.idPerfil as Clave_Vendedor','cpr.nombre as Nombre_Vendedor',
        'c.idCredito as Credito',DB::raw('IF(com.idNivel=5,"Asesor",IF(com.idNivel=4,"Gerente","")) as Nivel'),'com.pago as Pago','com.monto as Monto',DB::raw('IF(com.aprobado=1,"Aprobado",IF(com.aprobado=0,"No Aprobado","")) as Estatus'),
        'com.fechaCorte as Fecha_Actualizacion',DB::raw('MONTHNAME(com.fechaCorte) as Mes'),DB::raw('YEAR(com.fechaCorte) as Year'),'c.negocio as Negocio','c.producto as Producto')
        ->where('com.fechaCorte', '>=', $dateini)
        ->where('com.fechaCorte', '<=', $datefin)
        ->get();
        //$resumen->dd();
        $resG0=DB::table('tblcomisiones as com')
       ->join('tblcreditos as c', 'com.idCredito', '=', 'c.idCredito')
       ->leftjoin('catperfiles as cp', 'c.idPerfil', '=', 'cp.idPerfil')
       ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
       ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
       ->select('com.id','cr.descripcion as Regional','cs.sucursal as Sucursal','com.idPerfil as Clave_Vendedor',
       'c.idCredito as Credito',DB::raw('IF(com.idNivel=5,"Asesor",IF(com.idNivel=4,"Gerente","")) as Nivel'),'com.pago as Pago','com.monto as Monto',DB::raw('IF(com.aprobado=1,"Aprobado",IF(com.aprobado=0,"No Aprobado","")) as Estatus'),
       'com.fechaCorte as Fecha_Actualizacion',DB::raw('MONTHNAME(com.fechaCorte) as Mes'),DB::raw('YEAR(com.fechaCorte) as Year'),'c.negocio as Negocio','c.producto as Producto')
       ->where('com.fechaCorte', '>=', $dateini)
       ->where('com.fechaCorte', '<=', $datefin)
       ->where('com.idPerfil', '=', '0')
       ->get();

       Excel::create('dtCom'.date('Ymd'), function($excel) use($resumen,$resG0) {
      $excel->sheet('dtCom', function($sheet) use($resumen,$resG0) {
      $sheet->row(1,['Regional','Sucursal','Clave_Vendedor','Nombre_Vendedor','Credito','Nivel','Pago','Monto','Estatus','Fecha_Actualizacion',
      'Mes','Año','Negocio','Producto','R+3% cartera en atraso','R+3% valor','R+0 dias atraso','R+0 valor','R+90 dias atraso historico','R+90 valor',
      'R+30 dias liq despues de Ffin','R+30 liq valor']);
      //$data=[];
      $sheet->setColumnFormat(array(
    'C' => '@',
    'E' => '0',
    'G' => '0',
    'H' => '0.00',
    'J' => 'dd/mm/yyyy',
    ));
      foreach ($resumen as $res) {
        $row=[];
        $row[0]=$res->Regional;
        $row[1]=$res->Sucursal;
        $row[2]=$res->Clave_Vendedor;
        $row[3]=$res->Nombre_Vendedor;
        $row[4]=$res->Credito;
        $row[5]=$res->Nivel;
        $row[6]=$res->Pago;
        $row[7]=$res->Monto;
        $row[8]=$res->Estatus;
        $row[9]=$res->Fecha_Actualizacion;
        $row[10]=$res->Mes;
        $row[11]=$res->Year;
        $row[12]=$res->Negocio;
        $row[13]=$res->Producto;
        $row[14]=$this->getCond($res->id,3,"estatus");
        $row[15]=$this->getCond($res->id,3,"valor");
        $row[16]=$this->getCond($res->id,4,"estatus");
        $row[17]=$this->getCond($res->id,4,"valor");
        $row[18]=$this->getCond($res->id,1,"estatus");
        $row[19]=$this->getCond($res->id,1,"valor");
        $row[20]=$this->getCond($res->id,2,"estatus");
        $row[21]=$this->getCond($res->id,2,"valor");
        //$data[]=$row;
        $sheet->appendRow($row);
      }
      //Para las sucursales sin gerente
      foreach ($resG0 as $res) {
        $row=[];
        $row[0]=$res->Regional;
        $row[1]=$res->Sucursal;
        $row[2]=$res->Clave_Vendedor;
        $row[3]='N/A';
        $row[4]=$res->Credito;
        $row[5]=$res->Nivel;
        $row[6]=$res->Pago;
        $row[7]=$res->Monto;
        $row[8]=$res->Estatus;
        $row[9]=$res->Fecha_Actualizacion;
        $row[10]=$res->Mes;
        $row[11]=$res->Year;
        $row[12]=$res->Negocio;
        $row[13]=$res->Producto;
        $row[14]=$this->getCond($res->id,3,"estatus");
        $row[15]=$this->getCond($res->id,3,"valor");
        $row[16]=$this->getCond($res->id,4,"estatus");
        $row[17]=$this->getCond($res->id,4,"valor");
        $row[18]=$this->getCond($res->id,1,"estatus");
        $row[19]=$this->getCond($res->id,1,"valor");
        $row[20]=$this->getCond($res->id,2,"estatus");
        $row[21]=$this->getCond($res->id,2,"valor");
        //$data[]=$row;
        $sheet->appendRow($row);
      }
      //$sheet->fromArray($data);

    });
  })->export('xlsx');

     }
    }

    public function downNoAbonado()
    {
      $queryr= DB::table('catperfiles as cp')
       ->join('catsucursales as s', 'cp.idSucursal', '=', 's.idSucursal')
       ->select('cp.idPerfil','s.idSucursal' ,'s.idRegional')
       ->where('cp.idPerfil', '=', Auth::user()->idPerfil)->first();

      $condicion;
       if (Auth::user()->idNivel=3) {
         $condicion="cr.idRegional=".$queryr->idRegional;
       } elseif (Auth::user()->idNivel=4) {
         $condicion="cs.idSucursal=".$queryr->idSucursal;
       }else {
         $condicion="cs.idSucursal=0";
       }
         $resumen=DB::table('catperfiles as cp')
         ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
         ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
         ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
         ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
         ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=','s.idCredito')
         ->select('cr.descripcion','cs.sucursal','c.producto','c.idPerfil','cpr.nombre','c.idCredito','c.nomCliente',DB::raw('count(d.idDevengo) as devengos'),DB::raw('sum(d.cuota) as cuota'))
         ->whereRaw($condicion)
         ->where('s.monto_ult_pago', '=', '0')
         ->where('s.estatus', '=', '1')
         //->where('s.fechaSituacion', '=', DB::raw('curdate()'))
         //->where('s.diasAtraso','<','91')
         ->groupBy('cr.descripcion','cs.sucursal','c.producto','c.idPerfil','cpr.nombre','c.idCredito','c.nomCliente')
         ->havingRaw('MIN(d.fechaDevengo) < curdate()')
         ->get();

       Excel::create('rptNoAbonados'.date('Ymd'), function($excel) use($resumen) {
      $excel->sheet('dtNoAbonados', function($sheet) use($resumen) {
      $sheet->row(1,['Regional','Sucursal','Producto','Clave_Vendedor','Nombre_Vendedor','id_credito','Nombre_Cliente','Devengos','Suma_Devengos']);
      //$data=[];
      $sheet->setColumnFormat(array(
    'F' => '@',
    'H' => '0',
    'I' => '0.00',
    ));
      foreach ($resumen as $res) {
        $row=[];
        $row[0]=$res->descripcion;
        $row[1]=$res->sucursal;
        $row[2]=$res->producto;
        $row[3]=$res->idPerfil;
        $row[4]=$res->nombre;
        $row[5]=$res->idCredito;
        $row[6]=$res->nomCliente;
        $row[7]=$res->devengos;
        $row[8]=$res->cuota;
        //$data[]=$row;
        $sheet->appendRow($row);
      }
      //$sheet->fromArray($data);

    });
  })->export('xlsx');

    }
    public function downInactivo()
    {
      $queryr= DB::table('catperfiles as cp')
       ->join('catsucursales as s', 'cp.idSucursal', '=', 's.idSucursal')
       ->select('cp.idPerfil','s.idSucursal' ,'s.idRegional')
       ->where('cp.idPerfil', '=', Auth::user()->idPerfil)->first();

      $condicion;
       if (Auth::user()->idNivel=3) {
         $condicion="cs.idRegional=".$queryr->idRegional;
       } elseif (Auth::user()->idNivel=4) {
         $condicion="cs.idSucursal=".$queryr->idSucursal;
       }else {
         $condicion="cs.idSucursal=0";
       }

         $inactivosem=DB::table('catperfiles as cp')
         ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
         ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
         ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
         ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=','s.idCredito')
         ->select('cr.descripcion','cs.sucursal','c.producto','c.idPerfil','cpr.nombre','c.idCredito','c.nomCliente','s.diasAtraso','s.capitalVencido')
         ->whereRaw($condicion.' and s.f_ult_pago < DATE_SUB(curdate(), INTERVAL 21 DAY) and c.frecuenciaPago="Semanal" and s.diasAtraso>30')
         ->where('s.estatus', '=', '1')
         //->where('s.fechaSituacion', '=', DB::raw('curdate()'))
         //->where('s.diasAtraso','<','91')
         ->get();

         $inactivoquin=DB::table('catperfiles as cp')
         ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
         ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
         ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
         ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=','s.idCredito')
         ->select('cr.descripcion','cs.sucursal','c.producto','c.idPerfil','cpr.nombre','c.idCredito','c.nomCliente','s.diasAtraso','s.capitalVencido')
         ->whereRaw($condicion.' and s.f_ult_pago < DATE_SUB(curdate(), INTERVAL 45 DAY) and c.frecuenciaPago="Quincenal" and s.diasAtraso>30')
         ->where('s.estatus', '=', '1')
         //->where('s.fechaSituacion', '=', DB::raw('curdate()'))
         //->where('s.diasAtraso','<','91')
         ->get();

         $inactivomen=DB::table('catperfiles as cp')
         ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
         ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
         ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
         ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=','s.idCredito')
         ->select('cr.descripcion','cs.sucursal','c.producto','c.idPerfil','cpr.nombre','c.idCredito','c.nomCliente','s.diasAtraso','s.capitalVencido')
         ->whereRaw($condicion.' and s.f_ult_pago < DATE_SUB(curdate(), INTERVAL 90 DAY) and c.frecuenciaPago="Mensual" and s.diasAtraso>30')
         ->where('s.estatus', '=', '1')
         //->where('s.fechaSituacion', '=', DB::raw('curdate()'))
         //->where('s.diasAtraso','<','91')
         ->get();

       Excel::create('rptInactivos'.date('Ymd'), function($excel) use($inactivosem,$inactivoquin,$inactivomen) {
      $excel->sheet('dtInactivos', function($sheet) use($inactivosem,$inactivoquin,$inactivomen) {
      $sheet->row(1,['Regional','Sucursal','Producto','Clave_Vendedor','Nombre_Vendedor','id_credito','Nombre_Cliente','Dias_Atraso','Capital_Vencido']);
      //$data=[];
      $sheet->setColumnFormat(array(
    'F' => '@',
    'H' => '0',
    'I' => '0.00',
    ));
      foreach ($inactivosem as $res) {
        $row=[];
        $row[0]=$res->descripcion;
        $row[1]=$res->sucursal;
        $row[2]=$res->producto;
        $row[3]=$res->idPerfil;
        $row[4]=$res->nombre;
        $row[5]=$res->idCredito;
        $row[6]=$res->nomCliente;
        $row[7]=$res->diasAtraso;
        $row[8]=$res->capitalVencido;
        //$data[]=$row;
        $sheet->appendRow($row);
      }
      foreach ($inactivoquin as $res) {
        $row=[];
        $row[0]=$res->descripcion;
        $row[1]=$res->sucursal;
        $row[2]=$res->producto;
        $row[3]=$res->idPerfil;
        $row[4]=$res->nombre;
        $row[5]=$res->idCredito;
        $row[6]=$res->nomCliente;
        $row[7]=$res->diasAtraso;
        $row[8]=$res->capitalVencido;
        //$data[]=$row;
        $sheet->appendRow($row);
      }
      foreach ($inactivomen as $res) {
        $row=[];
        $row[0]=$res->descripcion;
        $row[1]=$res->sucursal;
        $row[2]=$res->producto;
        $row[3]=$res->idPerfil;
        $row[4]=$res->nombre;
        $row[5]=$res->idCredito;
        $row[6]=$res->nomCliente;
        $row[7]=$res->diasAtraso;
        $row[8]=$res->capitalVencido;
        //$data[]=$row;
        $sheet->appendRow($row);
      }
      //$sheet->fromArray($data);

    });
  })->export('xlsx');

    }
    public function downDevVenc()
    {
      $queryr= DB::table('catperfiles as cp')
       ->join('catsucursales as s', 'cp.idSucursal', '=', 's.idSucursal')
       ->select('cp.idPerfil','s.idSucursal' ,'s.idRegional')
       ->where('cp.idPerfil', '=', Auth::user()->idPerfil)->first();

      $condicion;
       if (Auth::user()->idNivel=3) {
         $condicion="cr.idRegional=".$queryr->idRegional;
       } elseif (Auth::user()->idNivel=4) {
         $condicion="cs.idSucursal=".$queryr->idSucursal;
       }else {
         $condicion="cs.idSucursal=0";
       }
       $fechastr=strtotime ( '-1 day' , strtotime ( date('Y-m-d')));
       //$fechastr=strtotime ( '-1 day' , strtotime ('2018-10-01'));//Fecha fijada
       $datem=date('m',$fechastr);
       $dateY=date('Y',$fechastr);


         $resumen=DB::table('catperfiles as cp')
         ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
         ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
         ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
         ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=','s.idCredito')
         ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
         ->leftjoin('tblrecupdev as r', 'd.idDevengo', '=', 'r.idDevengo')
         ->select('cr.descripcion','cs.sucursal','c.producto','c.idPerfil','cpr.nombre','c.idCredito','c.nomCliente','d.fechaDevengo','d.cuota','r.monto','d.saldo_venc')
         ->whereRaw($condicion.' and MONTH(d.fechaDevengo)='.$datem.' and YEAR(d.fechaDevengo)='.$dateY.' and d.fechaDevengo < curdate()')
         ->get();

       Excel::create('rptDevVencido'.date('Ymd'), function($excel) use($resumen) {
      $excel->sheet('dtDevVencido', function($sheet) use($resumen) {
      $sheet->row(1,['Regional','Sucursal','Producto','Clave_Vendedor','Nombre_Vendedor','id_credito','Nombre_Cliente','Fecha_Devengo','Cuota','Monto_Pagado','Saldo_Vencido']);
      //$data=[];
      $sheet->setColumnFormat(array(
    'F' => '@',
    'H' => 'dd/mm/yyyy',
    'I' => '0.00',
    'J' => '0.00',
    'K' => '0.00',
    ));
      foreach ($resumen as $res) {
        $row=[];
        $row[0]=$res->descripcion;
        $row[1]=$res->sucursal;
        $row[2]=$res->producto;
        $row[3]=$res->idPerfil;
        $row[4]=$res->nombre;
        $row[5]=$res->idCredito;
        $row[6]=$res->nomCliente;
        $row[7]=$res->fechaDevengo;
        $row[8]=$res->cuota;
        $row[9]=$res->monto;
        $row[10]=$res->saldo_venc;
        //$data[]=$row;
        $sheet->appendRow($row);
      }
      //$sheet->fromArray($data);

    });
  })->export('xlsx');

    }
    public function downDevParc()
    {
      $queryr= DB::table('catperfiles as cp')
       ->join('catsucursales as s', 'cp.idSucursal', '=', 's.idSucursal')
       ->select('cp.idPerfil','s.idSucursal' ,'s.idRegional')
       ->where('cp.idPerfil', '=', Auth::user()->idPerfil)->first();

      $condicion;
       if (Auth::user()->idNivel=3) {
         $condicion="cr.idRegional=".$queryr->idRegional;
       } elseif (Auth::user()->idNivel=4) {
         $condicion="cs.idSucursal=".$queryr->idSucursal;
       }else {
         $condicion="cs.idSucursal=0";
       }
       $fechastr=strtotime ( '-1 day' , strtotime ( date('Y-m-d')));
       //$fechastr=strtotime ( '-1 day' , strtotime ('2018-10-01'));//Fecha fijada
       $datem=date('m',$fechastr);
       $dateY=date('Y',$fechastr);


         $resumen=DB::table('catperfiles as cp')
         ->join('tblcreditos as c', 'cp.idPerfil', '=', 'c.idPerfil')
         ->join('catpersonas as cpr', 'cp.idPersona', '=', 'cpr.idPersona')
         ->join('catsucursales as cs', 'cp.idSucursal', '=', 'cs.idSucursal')
         ->join('catregionales as cr', 'cs.idRegional', '=', 'cr.idRegional')
         ->join('tbldevengos as d', 'c.idCredito', '=', 'd.idCredito')
         ->join('tblsituacioncredito as s', 'c.idCredito', '=','s.idCredito')
         ->join('tblrecupdev as r', 'd.idDevengo', '=', 'r.idDevengo')
         ->select('cr.descripcion','cs.sucursal','c.producto','c.idPerfil','cpr.nombre','c.idCredito','c.nomCliente','d.fechaDevengo','d.cuota','r.monto','d.saldo_venc')
         ->whereRaw($condicion.' and MONTH(d.fechaDevengo)='.$datem.' and YEAR(d.fechaDevengo)='.$dateY.' and r.recuperado=0 and r.monto>0')
         ->get();

       Excel::create('rptDevParcial'.date('Ymd'), function($excel) use($resumen) {
      $excel->sheet('dtDevParcial', function($sheet) use($resumen) {
      $sheet->row(1,['Regional','Sucursal','Producto','Clave_Vendedor','Nombre_Vendedor','id_credito','Nombre_Cliente','Fecha_Devengo','Cuota','Monto_Pagado','Saldo_Vencido']);
      //$data=[];
      $sheet->setColumnFormat(array(
    'F' => '@',
    'H' => 'dd/mm/yyyy',
    'I' => '0.00',
    'J' => '0.00',
    'K' => '0.00',
    ));
      foreach ($resumen as $res) {
        $row=[];
        $row[0]=$res->descripcion;
        $row[1]=$res->sucursal;
        $row[2]=$res->producto;
        $row[3]=$res->idPerfil;
        $row[4]=$res->nombre;
        $row[5]=$res->idCredito;
        $row[6]=$res->nomCliente;
        $row[7]=$res->fechaDevengo;
        $row[8]=$res->cuota;
        $row[9]=$res->monto;
        $row[10]=$res->saldo_venc;
        //$data[]=$row;
        $sheet->appendRow($row);
      }
      //$sheet->fromArray($data);

    });
  })->export('xlsx');

    }

    // Obtener las condiciones y reglas
    public function getCond($idcom,$idRegla,$campo)
    {
      return CondicionCm::where('idComision', '=', $idcom)->where('idRegla', '=', $idRegla)->value($campo);
    }

    //  Util Functions
    public function getDateYmd($date)
    {
      if (strlen(trim($date))>8) {
        List($d,$m,$a)=explode("/",trim($date));
        return $a."/".$m."/".$d;
      }else
      {
        return $date;
      }

    }
}
