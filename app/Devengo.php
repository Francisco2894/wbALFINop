<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Devengo extends Model
{
    protected $table='tbldevengos';
    protected $primaryKey='idDevengo';

    public $timestamps=false;

    protected $fillable=[
      'numCuota',
      'cuota',
      'fechaDevengo',
      'saldo',
      'diasAtraso',
      'maxDiasAtraso',
      'diasAtrasoDev',
      'fechaPagoReciente',
      'montoPagado',
      'montoExigible',
      'estatus',
      'idCredito'
    ];

    protected $guarded=[ ];
    
}
