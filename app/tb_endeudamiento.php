<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tb_endeudamiento extends Model
{
    //
    protected $table = 'tb_endeudamiento';

    protected $fillable = ['activototal','pasivototal','pasivocorriente','patrimoniototal','endeudamientototal','leverage','cortoplazo','detalle','idEmpresa'];

    public $timestamps = false;
}
