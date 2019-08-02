<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class AgendaDiaria extends Model
{
  protected $table='tblagendadiaria';
  protected $primaryKey='idAgenda';

  public $timestamps=false;

  protected $fillable=[
    'fecha',
    'estatus',
    'idDevengo'
  ];

  protected $guarded=[ ];
}
