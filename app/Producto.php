<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    protected $table='catproducto';
    protected $primaryKey='cveproducto';

    public $timestamps=false;

    protected $fillable=[
        'producto',
        'negocio_op',
        'refinan_si'
    ];

    protected $guarded=[ ];
}
