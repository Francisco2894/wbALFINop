<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class ActivosFijos extends Model
{
    //
    protected $table='tblactivos_fijos';
    protected $primaryKey='idtblactivos_fijos';

    public $timestamps=false;

    protected $fillable=[
        'idact','local','auto','maquinaria'
    ];

    protected $guarded=[ ];
}
