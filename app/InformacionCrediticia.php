<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class InformacionCrediticia extends Model
{
    //
    protected $table='tblinfcrediticia';
    protected $primaryKey='folio';
    public $keyType = 'string';

    public $timestamps=false;

    protected $fillable=[
        'folio','idsoc','score','fechaconsulta','idcliente'
    ];

    protected $guarded=[ ];
}
