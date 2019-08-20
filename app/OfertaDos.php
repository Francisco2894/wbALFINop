<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class OfertaDos extends Model
{
    //
    protected $table='tbloferta_2';
    protected $primaryKey='idoferta';

    public $timestamps=false;

    protected $fillable=[
        'idcliente','idto','idcredito','fechai', 'fechaf', 'plazo', 'monto', 'cuota', 'frecuencia','status'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'idcliente');
    }
}
