<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_proveedor extends Model
{
    //
    protected $table = 'tb_proveedores';

    protected $fillable = ['nit','razonSocial','contacto','telefono','direccion','correo','estado','idEmpresa'];

    public $timestamps = false;

    public function ordenPedidoProveedores(){
        return $this->hasMany('App\Tb_orden_pedido_proveedor');
    }

}
