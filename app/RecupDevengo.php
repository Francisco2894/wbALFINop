<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class RecupDevengo extends Model
{
    //
    protected $table='tblrecupdev';
    protected $primaryKey='idRecup';

    public $timestamps=false;

    protected $fillable=[
      'idDevengo',
      'monto',
      'recuperado',
      'f_Actualizacion'
    ];

    protected $guarded=[ ];
}
