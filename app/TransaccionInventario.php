<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class TransaccionInventario extends Model
{
    //
    protected $table='tbltran_invent';
    protected $primaryKey='idtransac';

    public $timestamps=false;

    protected $fillable=[
        'iddia','idtipotransac','idact','lugar_compra','monto'
    ];

    protected $guarded=[ ];
}
