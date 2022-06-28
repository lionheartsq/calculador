<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_novedades extends Model
{
    //
    protected $table = 'tb_novedades';

    protected $fillable = ['fechaNovedad','concepto','cantidad','unitario','valor','observacion','tipologia','idEmpleado','idNomina','idEmpresa'];

    public $timestamps = false;
}
