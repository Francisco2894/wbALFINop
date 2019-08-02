<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    //
    protected $table='tbloferta';
    protected $primaryKey='idoferta';

    public $timestamps=false;

    protected $fillable=[
        'idact','idto','fechai', 'fechaf', 'plazo', 'monto', 'parcialidad', 'frecuencia'
    ];

    protected $guarded=[ ];
}
