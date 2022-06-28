<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_area extends Model
{
    protected $table = 'tb_area';

    protected $fillable = ['area','estado','idEmpresa'];

    public $timestamps = false;

    public function procesos(){
        return $this->hasMany('App\Tb_proceso');
    }
}
