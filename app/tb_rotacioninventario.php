<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tb_rotacioninventario extends Model
{
    //
    protected $table = 'tb_rotacioninventario';

    protected $fillable = ['fechainicial','fechafinal','tipoperiodo','saldoperiodoactual','saldoperiodoanterior','costodeventas','sumasaldos',
    'promediosaldos','rotacioninventario','detalle','idEmpresa'];

    public $timestamps = false;
}
