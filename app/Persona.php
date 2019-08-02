<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
  protected $table='catpersonas';
  protected $primaryKey='idPersona';

  public $timestamps=false;

  protected $fillable=[
    'nombre',
    'paterno',
    'materno'
  ];

  protected $guarded=[ ];
}
