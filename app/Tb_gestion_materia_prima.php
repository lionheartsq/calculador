<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_gestion_materia_prima extends Model
{
    protected $table = 'tb_gestion_materia_prima';

    protected $fillable = ['gestionMateria','idUnidadBase','precioBase','idTipoMateria','estado','idEmpresa'];

    public $timestamps = false;
}
