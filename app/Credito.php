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
    'fechaEjercido',
    'cveproducto'
  ];

  public function actividades()
  {
      return $this->hasMany(Actividad::class, 'idcliente','idCliente');
  }

  public function informacionCrediticia()
  {
      return $this->hasMany(informacionCrediticia::class, 'idcliente', 'idCliente');
  }
  
  protected $guarded=[ ];
}
