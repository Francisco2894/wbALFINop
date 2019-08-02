<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class CatOferta extends Model
{
    //
    protected $table='cattipooferta';
    protected $primaryKey='idto';

    public $timestamps=false;

    protected $fillable=[
        'descripcion',
    ];

    protected $guarded=[ ];
}
