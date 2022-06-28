<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tb_orden_pedido_cliente extends Model
{
    //
    protected $table = 'tb_orden_pedido_cliente';

    protected $fillable = ['consecutivo','fecha','idCliente','observacion','estado','idEmpresa'];

    public $timestamps = false;

    public function cliente(){
        return $this->belongsTo('App\Tb_cliente', 'id');
    }
}
