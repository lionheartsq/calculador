<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tb_rentabilidad extends Model
{
    //
    protected $table = 'tb_rentabilidad';

    protected $fillable = ['utilidadbruta','utilidadoperacional','utilidadneta','ingresostotales','margenbruto','margenoperacional','margenneto','detalle','idEmpresa'];

    public $timestamps = false;
}
