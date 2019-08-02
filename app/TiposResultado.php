<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class TiposResultado extends Model
{
  protected $table='cattiposresultado';
  protected $primaryKey='idResultado';

  public $timestamps=false;

  protected $fillable=[
    'descripcion'
  ];

  protected $guarded=[ ];
}
