<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_kardex_almacen extends Model
{
    protected $table = 'tb_kardex_almacen';

    protected $fillable = ['fecha','detalle','cantidad','precio','cantidadSaldos','precioSaldos',
    'observaciones','idEmpleado','idDocumentos','idGestionMateria','tipologia','idEmpresa'];

    public $timestamps = false;
}
