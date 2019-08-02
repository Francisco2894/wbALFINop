<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
  protected $table='catregionales';
  protected $primaryKey='idRegional';

  public $timestamps=false;

  protected $fillable=[
    'descripcion'
  ];

  protected $guarded=[ ];
}
