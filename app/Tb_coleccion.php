<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_coleccion extends Model
{
    protected $table = 'tb_coleccion';

    protected $fillable = ['coleccion','referencia','estado','idEmpresa'];

    public $timestamps = false;

     // Relacion con la tabla producto
    public function productos(){
        return $this->hasMany('App\Tb_producto', 'idColeccion', 'id');
    }
}
