<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_cotizacion extends Model
{
    //
    protected $table = 'tb_cotizacion';

    protected $fillable = ['consecutivo','fecha','condicionEntrega','vigencia','idCliente','estado','idEmpresa'];

    public $timestamps = false;

    public function cliente(){
        return $this->belongsTo('App\Tb_cliente', 'id');
    }

}
