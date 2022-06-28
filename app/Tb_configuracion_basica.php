<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_configuracion_basica extends Model
{
    //
    protected $table = 'tb_configuracion_basica';

    protected $fillable = ['nombre','direccion','nit','telefono','representante','cajaCompensacion','arl','nivelRiesgo','idTipoNomina'];

    public $timestamps = false;

}
