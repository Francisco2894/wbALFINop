<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class TipoTransaccion extends Model
{
    //
    protected $table='cattipo_transac';
    protected $primaryKey='idtipotransac';

    public $timestamps=false;

    protected $fillable=[
        'descripcion'
    ];

    protected $guarded=[ ];
}
