<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_detalle_inventario extends Model
{
    //
    protected $table = 'tb_detalle_inventario';

    protected $fillable = ['idProducto','unidadBase','cantidadSistema','cantidadActual','diferencia','observacion','idEmpresa','idInventario'];

    public $timestamps = false;
}
