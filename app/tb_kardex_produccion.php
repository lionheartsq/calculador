<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_kardex_produccion extends Model
{
    //
    protected $table = 'tb_kardex_produccion';

    protected $fillable = ['fecha','detalle','cantidad','precio','cantidadSaldos','precioSaldos',
     'idGestionMateria','tipologia','idEmpresa'];

    public $timestamps = false;
}
