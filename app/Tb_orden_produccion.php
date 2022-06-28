<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_orden_produccion extends Model
{
    //
    protected $table = 'tb_orden_produccion';

    protected $fillable = ['fecha','detalle','responsable','bodega','medida','cantidad','precioUnitario','precioTotal','idProducto','estado','idEmpresa'];

    public $timestamps = false;

    public function producto(){
        return $this->belongsTo('App\Tb_producto', 'id');
    }
    public function ordenEntrada(){
        return $this->belongsTo('App\Tb_orden_entrada', 'id');
    }
}
