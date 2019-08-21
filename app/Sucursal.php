<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
  protected $table='catsucursales';
  protected $primaryKey='idSucursal';
  public $keyType = 'string';


  public $timestamps=false;

  protected $fillable=[
    'sucursal',
    'idRegional'
  ];

  protected $guarded=[ ];
}
