<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_tipo_materia extends Model
{
    protected $table = 'tb_tipo_materia';

    protected $fillable = ['tipoMateria','estado','idEmpresa'];

    public $timestamps = false;
}
