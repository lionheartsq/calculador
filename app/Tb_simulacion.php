<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_simulacion extends Model
{
    //
    protected $table = 'tb_simulacion';

    protected $fillable = ['detalle','fecha','tipoCif','idEmpresa'];

    public $timestamps = false;
}
