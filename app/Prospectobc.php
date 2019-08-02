<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Prospectobc extends Model
{
  protected $table='tblprospectosbc';
  protected $primaryKey='folio';

  public $timestamps=false;

  protected $fillable=[
    'nombre',
    'paterno',
    'materno',
    'score',
    'fechaConsulta',
    'idTipoProspecto',
    'idTipoCliente',
    'idTipoProducto',
    'montoSolicitud',
    'idEstatus',
    'idPerfil',
    'idPerfilCap'
  ];

  protected $guarded=[ ];
}
