<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    //
    protected $table='tblactividad';
    protected $primaryKey='idact';

    public $timestamps=false;

    protected $fillable=[
        'idcliente','giro','destinoprestamo','comoinicio','desc_negocio'
    ];

    protected $guarded=[ ];
}
