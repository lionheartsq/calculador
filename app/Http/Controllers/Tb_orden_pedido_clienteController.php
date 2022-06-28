<?php

namespace App\Http\Controllers;

use App\Tb_materia_prima_producto;
use App\Tb_gestion_materia_prima;
use App\tb_hoja_de_costo;
use App\Tb_producto;
use App\Tb_orden_produccion_detalle;
use App\Tb_cliente;
use App\Tb_orden_pedido_cliente;
use App\Tb_orden_produccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class Tb_orden_pedido_clienteController extends Controller
{
    //
    public function index(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $buscar= $request->buscar;
        $criterio= $request->criterio;

        if ($buscar=='') {
            # Modelo::join('tablaqueseune',basicamente un on)
            $ordenes = Tb_orden_pedido_cliente::join('tb_clientes','tb_orden_pedido_cliente.idCliente','=','tb_clientes.id')
            ->where('tb_orden_pedido_cliente.idEmpresa','=',$idEmpresa)
            ->select('tb_orden_pedido_cliente.id','tb_orden_pedido_cliente.consecutivo','tb_orden_pedido_cliente.fecha',
            'tb_orden_pedido_cliente.idCliente','tb_orden_pedido_cliente.observacion','tb_orden_pedido_cliente.estado',
            DB::raw('CONCAT(tb_clientes.nombre," ",tb_clientes.apellido," - ",tb_clientes.documento) as nombreCliente'))
            ->orderBy('tb_orden_pedido_cliente.consecutivo','asc')->paginate(5);
        }
        else {
            $ordenes = Tb_orden_pedido_cliente::join('tb_clientes','tb_orden_pedido_cliente.idCliente','=','tb_clientes.id')
            ->where('tb_orden_pedido_cliente.idEmpresa','=',$idEmpresa)
            ->select('tb_orden_pedido_cliente.id','tb_orden_pedido_cliente.consecutivo','tb_orden_pedido_cliente.fecha',
            'tb_orden_pedido_cliente.idCliente','tb_orden_pedido_cliente.observacion','tb_orden_pedido_cliente.estado',
            DB::raw('CONCAT(tb_clientes.nombre," ",tb_clientes.apellido," - ",tb_clientes.documento) as nombreCliente'))
            ->orderBy('tb_orden_pedido_cliente.consecutivo','asc')
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$ordenes->total(),
                'current_page'  =>$ordenes->currentPage(),
                'per_page'      =>$ordenes->perPage(),
                'last_page'     =>$ordenes->lastPage(),
                'from'          =>$ordenes->firstItem(),
                'to'            =>$ordenes->lastItem(),
            ],
                'ordenes' => $ordenes
        ];
    }

    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $maximos = Tb_orden_pedido_cliente::where('tb_orden_pedido_cliente.idEmpresa','=',$idEmpresa)
        ->select(DB::raw('MAX(tb_orden_pedido_cliente.consecutivo) as maximo'))
        ->get();

        foreach($maximos as $maximo1){
            $maximosuma = $maximo1->maximo;
            if($maximosuma > 0){$maximo=$maximosuma+1;}
             else {$maximo=1;}
            }
        $consecutivo=$maximo;

        $tb_orden_pedido_cliente=new Tb_orden_pedido_cliente();
        $tb_orden_pedido_cliente->consecutivo=$consecutivo;
        $tb_orden_pedido_cliente->fecha=$request->fecha;
        $tb_orden_pedido_cliente->idCliente=$request->idCliente;
        $tb_orden_pedido_cliente->observacion=$request->observacion;
        $tb_orden_pedido_cliente->idEmpresa=$idEmpresa;
        $tb_orden_pedido_cliente->save();
    }

    public function update(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $tb_orden_pedido_cliente=Tb_orden_pedido_cliente::findOrFail($request->id);
        $tb_orden_pedido_cliente->estado=1;
        $tb_orden_pedido_cliente->save();
    }

    public function clientes()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        // $clientes = Tb_cliente::all();
        $clientes = Tb_cliente::where('tb_clientes.idEmpresa','=',$idEmpresa)
        ->select('tb_clientes.id','tb_clientes.nombre','tb_clientes.apellido',
        'tb_clientes.documento','tb_clientes.direccion','tb_clientes.telefono','tb_clientes.correo',
        DB::raw('CONCAT(tb_clientes.nombre," ",tb_clientes.apellido," - ",tb_clientes.documento) as nombreCliente'))
        ->get();

        return ['clientes' => $clientes];
    }


    public function posibles(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $identificador= $request->id;

        $posibles = DB::table('tb_producto')
        ->join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
        ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
        ->select('tb_producto.id as idProducto','producto')
        ->whereNotIn('tb_producto.id', DB::table('tb_detalle_cotizacion')->select('idProducto')->where('idCotizacion', '=', $identificador))
        ->get();

        return ['posibles' => $posibles];
    }

    //---------------------Funcion para generar las relaciones en las tablas que quedan con los datos-----------------------------------//

    public function estado(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        // Tomo los productos de la orden de pedido para crear la orden de produccion

        $idOrdenPedido= $request->id;
        $tb_orden_pedido_cliente=Tb_orden_pedido_cliente::findOrFail($request->id);
        $tb_orden_pedido_cliente->estado=2;
        $tb_orden_pedido_cliente->save();

        $tb_orden_pedido_cliente2=Tb_orden_pedido_cliente::findOrFail($request->id);
        $fecha=$tb_orden_pedido_cliente->fecha;

        $maximos = Tb_orden_produccion::where('tb_orden_produccion.idEmpresa','=',$idEmpresa)
        ->select(DB::raw('MAX(tb_orden_produccion.consecutivo) as maximo'))
        ->get();

        foreach($maximos as $maximo1){
            $maximosuma = $maximo1->maximo;
            if($maximosuma > 0){$maximo=$maximosuma+1;}
             else {$maximo=1;}
            }
        $consecutivo=$maximo;

        $ordenes = DB::table('tb_orden_pedido_cliente_detalle')
            ->where('tb_orden_pedido_cliente_detalle.idOrdenPedido', '=', $idOrdenPedido)->get();

        foreach ($ordenes as $orden) {
            $idProducto=$orden->idProducto;
            $cantidad=$orden->cantidad;

            $tb_orden_produccion=new Tb_orden_produccion();
            $tb_orden_produccion->consecutivo=$consecutivo;
            $tb_orden_produccion->fecha=$fecha;
            $tb_orden_produccion->idProducto=$idProducto;
            $tb_orden_produccion->cantidad=$cantidad;
            $tb_orden_produccion->idOrdenPedido=$idOrdenPedido;
             $tb_orden_produccion->idEmpresa=$idEmpresa;
            $tb_orden_produccion->save();

            $idOrdenProduccion= $tb_orden_produccion->id;

            // En esta seccion voy a tomar de cada producto los materiales que necesito para el detalle de la orden de produccion

                $productos = DB::table('tb_materia_prima_producto')->where('tb_materia_prima_producto.idHoja', '=', $idProducto)->get();
                foreach ($productos as $producto) {
                    $cantidadRequerida=0;
                    $idMateriaPrima=$producto->idMateriaPrima;
                    $cantidadMateria=$producto->cantidad;
                    $cantidadRequerida=$cantidad*$cantidadMateria;

                        // Verificaria primero si la materia prima ya se ha ingresado en esta orden, si es asi actualizo, si no la creo.
                        $flag=0;
                        $cantidadRequeridaParcial=0;
//---------------------------------------------------------------------------------------------------------------------------------------------
                        $verificable = Tb_orden_produccion_detalle::join('tb_orden_produccion','tb_orden_produccion_detalle.idOrdenProduccion','=','tb_orden_produccion.id')
                        ->select('tb_orden_produccion_detalle.id','tb_orden_produccion_detalle.cantidadRequerida','tb_orden_produccion.idOrdenPedido')
                        ->where([
                            ['tb_orden_produccion.idOrdenPedido', '=', $idOrdenPedido],
                            ['tb_orden_produccion_detalle.idGestionMateria', '=', $idMateriaPrima],
                        ])
                        ->get();
                        foreach($verificable as $verifica){
                            $parcialRequerida = $verifica->cantidadRequerida;
                            $verificador = $verifica->id;
                            if($parcialRequerida > 0){$cantidadRequeridaParcial=$parcialRequerida+$cantidadRequerida; $flag=1;}
                             else {$flag=0;}
                            }
//-----------------------------------------------------------------------------------------------------------------------------------------------

                            if($flag==1){
                                $tb_orden_produccion_detalle=Tb_orden_produccion_detalle::findOrFail($verificador);
                                $tb_orden_produccion_detalle->cantidadRequerida=$cantidadRequeridaParcial;
                                $tb_orden_produccion_detalle->save();
                            }
                            elseif($flag==0){
                                $tb_orden_produccion_detalle=new Tb_orden_produccion_detalle();
                                $tb_orden_produccion_detalle->idGestionMateria=$idMateriaPrima;
                                $tb_orden_produccion_detalle->cantidadRequerida=$cantidadRequerida;
                                $tb_orden_produccion_detalle->idOrdenProduccion=$idOrdenProduccion;
                                $tb_orden_produccion_detalle->estado=1;
                                $tb_orden_produccion_detalle->save();
                            }

                    }
              }
    }
    //---------------------------------------------------------------------------------------------------------------------------------//

}
