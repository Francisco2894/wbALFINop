<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class TipoGasto extends Model
{
    //
    protected $table='cattipo_gasto';
    protected $primaryKey='idtipogasto';

    public $timestamps=false;

    protected $fillable=[
        'desc_gasto'
    ];

    protected $guarded=[ ];
}
