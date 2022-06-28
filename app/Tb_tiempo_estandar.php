<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_tiempo_estandar extends Model
{
    //
    protected $table = 'tb_tiempo_estandar';

    protected $fillable = ['fecha','idEmpleado','tiempoElemental','tiempoNormal','factorPds','tiempoEstandar','numeroPiezas',
    'piezasPar','tiempoPiezas','factorValoracion','idEmpresa'];

    public $timestamps = false;

    public function ciclos(){
        return $this->belongsTo('App\Tb_tiempo_estandar', 'id');
    }
}
