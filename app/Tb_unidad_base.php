<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_unidad_base extends Model
{
    protected $table = 'tb_unidad_base';

    protected $fillable = ['unidadBase','estado','idEmpresa'];

    public $timestamps = false;
}
