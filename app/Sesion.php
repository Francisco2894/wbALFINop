<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
  protected $table='tblsesiones';
  protected $primaryKey='idSesion';

  public $timestamps=false;

  protected $fillable=[
    'id',
    'f_login',
    'f_logout'
  ];

  protected $guarded=[ ];
}
