<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    //
    protected $table='tblcomisiones';
    protected $primaryKey='id';

    public $timestamps=false;

    protected $fillable=[
      'idCredito',
      'idNivel',
      'pago',
      'monto',
      'aprobado',
      'fechaCorte',
      'idPerfil'
    ];

    protected $guarded=[ ];
}
