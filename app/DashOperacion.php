<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class DashOperacion extends Model
{
  protected $table='tbldashoper';
  protected $primaryKey='id';

  public $timestamps=true;

  protected $fillable=[
    'idPerfil',
    'idConcepto',
    'cuenta',
    'monto',
    'fechaCorte',
    'idPerfilUser'
  ];

  protected $guarded=[ ];
}
