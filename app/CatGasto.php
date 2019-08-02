<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class CatGasto extends Model
{
    //
    protected $table='catgasto';
    protected $primaryKey='idngasto';

    public $timestamps=false;

    protected $fillable=[
        'descripcion'
    ];

    protected $guarded=[ ];
}
