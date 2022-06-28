<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_proceso extends Model
{
    protected $table = 'tb_proceso';

    protected $fillable = ['proceso','idArea','estado','idEmpresa'];

    public $timestamps = false;

    public function areas(){
        return $this->belongsTo('App\Tb_area', 'idArea');
    }

    public function perfiles(){
        return $this->hasMany('App\Tb_perfil');
    }
}
