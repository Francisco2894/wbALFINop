<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class DashOpNeg extends Model
{
  protected $table='tbldashopneg';
  protected $primaryKey='id';

  public $timestamps=true;

  protected $fillable=[
    'idConcepto',
    'cuenta',
    'monto',
    'fechaCorte',
    'idPerfilUser',
    'idSucursal',
    'cveproducto',
    'estatus'  
  ];

  protected $guarded=[ ];
}
