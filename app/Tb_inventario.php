<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_inventario extends Model
{
    //
     //
     protected $table = 'tb_inventario';

     protected $fillable = ['fecha','idEmpleado','estado','idEmpresa'];

     public $timestamps = false;

}
