<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class DomicilioCredito extends Model
{
  protected $table='tbldomicilioscredito';
  protected $primaryKey='idDomicilio';

  public $timestamps=false;

  protected $fillable=[
    'idCredito',
    'estado',
    'municipio',
    'localidad',
    'colonia',
    'calle',
    'numExt',
    'numInt',
    'cp',
    'descripcion',
    'telefonoFijo',    
    'telefonoCelular',
    'latitud',
    'longitud',
    'idTipoDomicilio'
  ];

  protected $guarded=[ ];
}
