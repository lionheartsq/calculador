<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tb_precios_venta extends Model
{
    //
    protected $table = 'tb_precios_venta';

    protected $fillable = ['idProducto','costo','porcentaje','cifunitario','costosfijos','materiaprima','manodeobradirecta','preciodeventa','detalle','idEmpresa'];

    public $timestamps = false;
}
