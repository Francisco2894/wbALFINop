<?php

namespace wbALFINop;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    protected $table='tblcliente';
    protected $primaryKey='idcliente';

    public $timestamps=false;

    protected $fillable=[
        'nombre','paterno','materno','nombre2','curp'
    ];

    protected $guarded=[ ];

    public function ofertas()
    {
        return $this->hasMany(Oferta::class,'idcliente');
    }
    
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'idcliente','idcliente');
    }
}
