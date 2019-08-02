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
        'idcliente','idto','idcredito','fechai', 'fechaf', 'plazo', 'monto', 'parcialidad', 'frecuencia'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'idcliente');
    }

    protected $guarded=[ ];
}
