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
      return $this->hasMany(InformacionCrediticia::class, 'idcliente', 'idCliente');
  }

  public function ofertas()
  {
      return $this->hasMany(Oferta::class, 'idcliente', 'idCliente');
  }

  public function oferta()
  {
      return $this->hasMany(Oferta::class, 'idcliente', 'idCliente')->where('status','1');
  }

  public function product()
  {
      return $this->belongsTo(Producto::class, 'cveproducto');
  }
  
  protected $guarded=[ ];
}
