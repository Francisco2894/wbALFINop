<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class BlackList extends Model
{
    //
    protected $table='tblblacklist';
    protected $primaryKey='id';

    public $timestamps=false;

    protected $fillable=[
        'idcredito',
        'idcliente',
        'montocred',
        'cveProducto',
        'producto'
    ];

    protected $guarded=[ ];
}
