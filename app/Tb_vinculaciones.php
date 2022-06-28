<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_vinculaciones extends Model
{
    //
    protected $table = 'tb_vinculaciones';

    protected $fillable = ['tipoVinculacion','salarioBasicoMensual','fechaInicio','tiempoContrato','fechaFin','idEmpleado','idNivelArl','idEps','idPensiones','estado','idEmpresa'];

    public $timestamps = false;
}
