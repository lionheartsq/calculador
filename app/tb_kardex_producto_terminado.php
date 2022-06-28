<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_kardex_producto_terminado extends Model
{
    //
    protected $table = 'tb_kardex_producto_terminado';

     protected $fillable = ['fecha','detalle','cantidad','precio','cantidadSaldos','precioSaldos',
     'observaciones','idEmpleado','idDocumentos','idProducto','tipologia','idEmpresa'];

    public $timestamps = false;
}
