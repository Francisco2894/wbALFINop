<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
  protected $table='tblcreditos';
  protected $primaryKey='idCredito';

  public $timestamps=false;

  protected $fillable=[
    'idCliente',
    'idSolicitud',
    'nomCliente',
    'montoInicial',
    'plazo',
    'frecuenciaPago',
    'producto',
    'fechaInicio',
    'fechaFin',
    'idPerfil',
    'cveAseCol',
    'fechaEjercido'
  ];

  protected $guarded=[ ];
}
