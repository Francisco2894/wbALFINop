<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
  protected $table='tblvendedores';
  protected $primaryKey='cveVendedor';

  public $timestamps=false;

  protected $fillable=[
    'idPersona',
    'cveSucursal'
  ];

  protected $guarded=[ ];
}
