<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class InformacionCrediticia extends Model
{
    //
    protected $table='tblinfcrediticia';
    protected $primaryKey='idnfc';

    public $timestamps=false;

    protected $fillable=[
        'idsoc','score','fechaconsulta','idcliente'
    ];

    protected $guarded=[ ];
}
