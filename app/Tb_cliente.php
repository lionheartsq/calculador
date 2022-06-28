<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_cliente extends Model
{
    //
    protected $table = 'tb_clientes';

    protected $fillable = ['documento','nombre','apellido','direccion','telefono','correo','estado','idEmpresa'];

    public $timestamps = false;

    public function cotizaciones(){
        return $this->hasMany('App\Tb_cotizacion');
    }

    public function ordenClientes(){
        return $this->hasMany('App\Tb_orden_pedido_cliente');
    }
}
