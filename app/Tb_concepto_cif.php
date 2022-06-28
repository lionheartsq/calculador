<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_concepto_cif extends Model
{
    protected $table = 'tb_concepto_cif';

    protected $fillable = ['concepto','valor','estado','idEmpresa'];

    public $timestamps = false;
}
