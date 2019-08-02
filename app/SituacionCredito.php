<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class SituacionCredito extends Model
{
  protected $table='tblsituacioncredito';
  protected $primaryKey='idSituacion';

  public $timestamps=false;

  protected $fillable=[
    'saldo',
    'capitalVigente',
    'capitalVencido',
    'montoRiesgo',
    'saldoExigible',
    'diasAtraso',
    'maxDiasAtraso',
    'diasAtrasoDev',
    'fechaSituacion',
    'estatus',
    'idCredito',
    'idGestor',
    'f_ult_pago',
    'monto_ult_pago',
    'fCierre',
    'nPagos'
  ];

  protected $guarded=[ ];
}
