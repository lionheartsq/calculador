<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_tiempo_estandar extends Model
{
    //
    protected $table = 'tb_tiempo_estandar';

    protected $fillable = ['fecha','idEmpleado','tiempoElemental','tiempoNormal','factorPds','tiempoEstandar','numeroPiezas','piezasPar','tiempoPiezas','factorValoracion'];

    public $timestamps = false;
}