<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Dia extends Model
{
    //
    protected $table='catdia';
    protected $primaryKey='iddia';

    public $timestamps=false;

    protected $fillable=[
        'dia',
    ];

    protected $guarded=[ ];
}
