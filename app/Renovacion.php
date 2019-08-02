<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Renovacion extends Model
{
  protected $table='tblrenovaciones';
  protected $primaryKey='idRenovacion';

  public $timestamps=false;

  protected $fillable=[
    'renueva',
    'montoRenovacion',
    'descripcion',
    'fechaRenovacion',
    'idCredito'
  ];

  protected $guarded=[ ];
}
