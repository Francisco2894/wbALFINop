<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    //
    protected $table='tblinventario';
    protected $primaryKey='idinventario';

    public $timestamps=false;

    protected $fillable=[
        'idact','producto','cantidad','precio_compra','precio_venta'
    ];

    protected $guarded=[ ];
}
