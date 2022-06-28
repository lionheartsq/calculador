<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tb_liquidez extends Model
{
    //
    protected $table = 'tb_liquidez';

    protected $fillable = ['activocorriente','pasivocorriente','razoncorriente','capitaldetrabajo','inventario','pruebaacida','detalle','idEmpresa'];

    public $timestamps = false;
}
