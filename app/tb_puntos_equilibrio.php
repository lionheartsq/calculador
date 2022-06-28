<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tb_puntos_equilibrio extends Model
{
    //
    protected $table = 'tb_puntos_equilibrio';

    protected $fillable = ['idProducto','preciodeventa','costosfijos','gastosfijos','materiaprima','manodeobradirecta','puntodeequilibrio','detalle','idEmpresa'];

    public $timestamps = false;
}
