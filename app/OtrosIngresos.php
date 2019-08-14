<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class OtrosIngresos extends Model
{
    //
    protected $table='tblotros_ingresos';
    protected $primaryKey='idotros_ingresos';

    public $timestamps=false;

    protected $fillable=[
        'idact','monto','tipo','descripcion'
    ];

    protected $guarded=[ ];
}
