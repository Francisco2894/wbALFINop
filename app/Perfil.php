<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
  protected $table='catperfiles';
  protected $primaryKey='idPerfil';
  public $keyType = 'string';

  public $timestamps=false;

  protected $fillable=[
    'idPerfil',
    'descripcion',
    'idPersona',
    'idSucursal'
  ];

  public function persona()
  {
      return $this->belongsTo(Persona::class, 'idPersona');
  }

  protected $guarded=[ ];
}
