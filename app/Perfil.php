<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
  protected $table='catperfiles';
  protected $primaryKey='idPerfil';

  public $timestamps=false;

  protected $fillable=[
    'descripcion',
    'idPersona',
    'idSucurusal'
  ];

  protected $guarded=[ ];
}
