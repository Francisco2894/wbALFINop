<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class CondicionCm extends Model
{
    //
    protected $table='tblcondiciones';
    protected $primaryKey='idCondicion';

    public $timestamps=false;

    protected $fillable=[
      'idRegla',
      'idComision',
      'valor',
      'estatus'
    ];

    protected $guarded=[ ];
}
