<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Acuerdo extends Model
{
  protected $table='tblacuerdos';
  protected $primaryKey='idAcuerdo';

  public $timestamps=false;

  protected $fillable=[
    'acuerdo',
    'montoAcuerdo',
    'fechaAcuerdo',
    'idDevengo',
    'idResultado'
  ];

  protected $guarded=[ ];
}
