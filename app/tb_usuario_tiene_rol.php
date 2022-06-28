<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tb_usuario_tiene_rol extends Model
{
    //
    protected $table = 'tb_usuario_tiene_rol';

    protected $fillable = ['idUser','idRol','idEmpresa'];

    public $timestamps = false;
}
