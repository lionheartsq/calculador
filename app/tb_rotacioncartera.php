<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tb_rotacioncartera extends Model
{
    //
    protected $table = 'tb_rotacioncartera';

    protected $fillable = ['fechainicial','fechafinal','tipoperiodo','saldoperiodoactual','saldoperiodoanterior','costodeventas','sumasaldos',
    'promediosaldos','rotacioncartera','detalle','idEmpresa'];

    public $timestamps = false;
}
