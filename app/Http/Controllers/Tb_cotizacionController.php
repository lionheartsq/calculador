<?php

namespace App\Http\Controllers;

use App\Tb_materia_prima_producto;
use App\Tb_gestion_materia_prima;
use App\tb_hoja_de_costo;
use App\Tb_producto;
use App\Tb_orden_pedido_cliente_detalle;
use App\Tb_orden_pedido_cliente;
use App\Tb_cliente;
use App\Tb_cotizacion;
use App\Tb_detalle_cotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class Tb_cotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            $cotizaciones = Tb_cotizacion::join('tb_clientes','tb_cotizacion.idCliente','=','tb_clientes.id')
            ->where('tb_cotizacion.idEmpresa','=',$idEmpresa)
            ->select('tb_cotizacion.id','tb_cotizacion.consecutivo','tb_cotizacion.fecha','tb_cotizacion.condicionEntrega',
            'tb_cotizacion.vigencia','tb_cotizacion.idCliente','tb_cotizacion.estado',
            DB::raw('CONCAT(tb_clientes.nombre," ",tb_clientes.apellido," - ",tb_clientes.documento) as nombreCliente'))
            ->orderBy('tb_cotizacion.consecutivo','asc')->paginate(5);
        }
        else {
            $cotizaciones = Tb_cotizacion::join('tb_clientes','tb_cotizacion.idCliente','=','tb_clientes.id')
            ->where('tb_cotizacion.idEmpresa','=',$idEmpresa)
            ->select('tb_cotizacion.id','tb_cotizacion.consecutivo','tb_cotizacion.fecha','tb_cotizacion.condicionEntrega',
            'tb_cotizacion.vigencia','tb_cotizacion.idCliente','tb_cotizacion.estado',
            DB::raw('CONCAT(tb_clientes.nombre," ",tb_clientes.apellido," - ",tb_clientes.documento) as nombreCliente'))
            ->orderBy('tb_cotizacion.consecutivo','asc')
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$cotizaciones->total(),
                'current_page'  =>$cotizaciones->currentPage(),
                'per_page'      =>$cotizaciones->perPage(),
                'last_page'     =>$cotizaciones->lastPage(),
                'from'          =>$cotizaciones->firstItem(),
                'to'            =>$cotizaciones->lastItem(),
            ],
                'cotizaciones' => $cotizaciones
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

        $maximos = Tb_cotizacion::where('tb_cotizacion.idEmpresa','=',$idEmpresa)
        ->select(DB::raw('MAX(tb_cotizacion.consecutivo) as maximo'))
        ->get();

        foreach($maximos as $maximo1){
            $maximosuma = $maximo1->maximo;
            if($maximosuma > 0){$maximo=$maximosuma+1;}
             else {$maximo=1;}
            }
        $consecutivo=$maximo;

        $tb_cotizacion=new Tb_cotizacion();
        $tb_cotizacion->consecutivo=$consecutivo;
        $tb_cotizacion->fecha=$request->fecha;
        $tb_cotizacion->condicionEntrega=$request->condicionEntrega;
        $tb_cotizacion->vigencia=$request->vigencia;
        $tb_cotizacion->idCliente=$request->idCliente;
        $tb_cotizacion->idEmpresa=$idEmpresa;
        $tb_cotizacion->save();
    }

    public function update(Request $request)
    {
        //
         //if(!$request->ajax()) return redirect('/');
         $tb_cotizacion=Tb_cotizacion::findOrFail($request->id);
         $tb_cotizacion->estado=1;
         $tb_cotizacion->save();
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

    //---------------------Funcion para generar las relaciones en las tablas que quedan con los datos-----------------------------------//

    public function estado(Request $request)
        {
            //if(!$request->ajax()) return redirect('/');

        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

            $idCotizacion= $request->id;
            $tb_cotizacion=Tb_cotizacion::findOrFail($request->id);
            $tb_cotizacion->estado=2;
            $tb_cotizacion->save();

            $tb_cotizacion2=Tb_cotizacion::findOrFail($request->id);
            $fecha=$tb_cotizacion2->fecha;
            $observacion=$tb_cotizacion2->condicionEntrega;
            $idCliente=$tb_cotizacion2->idCliente;

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
                $tb_orden_pedido_cliente->fecha=$fecha;
                $tb_orden_pedido_cliente->idCliente=$idCliente;
                $tb_orden_pedido_cliente->observacion=$observacion;
                $tb_orden_pedido_cliente->idEmpresa=$idEmpresa;
                $tb_orden_pedido_cliente->save();

                $idOrdenPedido= $tb_orden_pedido_cliente->id;

            $cotizaciones = DB::table('tb_detalle_cotizacion')->where('tb_detalle_cotizacion.idCotizacion', '=', $idCotizacion)->get();
            foreach ($cotizaciones as $cotizacion) {
                $idProducto=$cotizacion->idProducto;
                $cantidad=$cotizacion->cantidad;
                $valor=$cotizacion->valor;
                $precioVenta=$cotizacion->precioVenta;

                $tb_orden_pedido_cliente_detalle=new Tb_orden_pedido_cliente_detalle();
                $tb_orden_pedido_cliente_detalle->idProducto=$idProducto;
                $tb_orden_pedido_cliente_detalle->cantidad=$cantidad;
                $tb_orden_pedido_cliente_detalle->precioCosto=$valor;
                $tb_orden_pedido_cliente_detalle->precioVenta=$precioVenta;
                $tb_orden_pedido_cliente_detalle->idOrdenPedido=$idOrdenPedido;
                $tb_orden_pedido_cliente_detalle->estado=1;
                $tb_orden_pedido_cliente_detalle->save();
                  }
        }

//---------------------------------------------------------------------------------------------------------------------------------//

}
