<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class GarantiaPrendaria extends Model
{
    //
    protected $table='tblgarantia_prendaria';
    protected $primaryKey='idgarantia';

    public $timestamps=false;

    protected $fillable=[
        'idact','garantia','valorEstimado'
    ];

    protected $guarded=[ ];
}
