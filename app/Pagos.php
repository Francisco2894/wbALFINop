<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    //
    protected $table='tblpagos';
    protected $primaryKey='idPago';

    public $timestamps=false;

    protected $fillable=[
      'idCredito',
      'f_Movimiento',
      'estatus',
      'f_Aplicacion',
      'ref_Pago',
      'origen',
      'monto',
      'capital',
      'interes',
      'iva_int'
    ];

    protected $guarded=[ ];
}
