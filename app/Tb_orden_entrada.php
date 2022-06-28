<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_orden_entrada extends Model
{
    //
    protected $table = 'tb_orden_entrada';

    protected $fillable = ['numeroOrden','fecha','cantidad','idProduccion','estado','idEmpresa'];

    public $timestamps = false;

    public function ordenProduccion(){
        return $this->hasMany('App\Tb_orden_produccion');
    }
}
