<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class InformacionCrediticia extends Model
{
    //
    protected $table='tblgasto';
    protected $primaryKey='idgasto';

    public $timestamps=false;

    protected $fillable=[
        'idtipogasto','idact','idngasto','monto'
    ];

    protected $guarded=[ ];
}
