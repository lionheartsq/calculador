<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_maquinaria extends Model
{
    protected $table = 'tb_maquinaria';

    protected $fillable = ['maquina','valor','tiempoDeVidaUtil','depreciacionAnual','depreciacionMensual','fecha','idEmpresa'];

    public $timestamps = false;
}
