<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_producto extends Model
{
    protected $table = 'tb_producto';

    protected $fillable = ['producto','referencia','foto','descripcion','idColeccion','idArea','presentacion','estado'];

    public $timestamps = false;

    // Relacion con la tabla coleccion
    public function coleccion(){
        return $this->belongsTo('App\Tb_coleccion', 'idColeccion');
    }

    public function ordenProducciones(){
        return $this->hasMany('App\Tb_orden_produccion');
    }
}
