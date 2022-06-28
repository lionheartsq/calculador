<?php

namespace App\Http\Controllers;

use App\Tb_empleado;
use App\Tb_kardex_producto_terminado;
use App\Tb_producto;
use App\Tb_orden_pedido_cliente;
use App\Tb_orden_pedido_cliente_detalle;
use App\Tb_orden_produccion;
use App\Tb_orden_produccion_detalle;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Tb_kardex_producto_terminadoController extends Controller
{
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
            $kardex = Tb_kardex_producto_terminado::join('tb_producto','tb_kardex_producto_terminado.idProducto','=','tb_producto.id')
            ->where('tb_kardex_producto_terminado.idEmpresa','=',$idEmpresa)
            ->select('tb_kardex_producto_terminado.id','tb_kardex_producto_terminado.fecha','tb_kardex_producto_terminado.detalle','tb_kardex_producto_terminado.cantidad',
            'tb_kardex_producto_terminado.precio','tb_kardex_producto_terminado.cantidadSaldos','tb_kardex_producto_terminado.precioSaldos','tb_kardex_producto_terminado.idProducto',
            'tb_kardex_producto_terminado.tipologia','tb_producto.producto as producto', 'tb_producto.estado',
            DB::raw('tb_kardex_producto_terminado.cantidadSaldos * tb_kardex_producto_terminado.precioSaldos as saldos'))
            ->orderBy('tb_kardex_producto_terminado.idProducto','asc')
            ->whereIn('tb_kardex_producto_terminado.id', function($sub){$sub->selectRaw('max(id)')->from('tb_kardex_producto_terminado')->groupBy('idProducto');})
            ->paginate(5);
        }
        else {
            $kardex = Tb_kardex_producto_terminado::join('tb_producto','tb_kardex_producto_terminado.idProducto','=','tb_producto.id')
            ->where('tb_kardex_producto_terminado.idEmpresa','=',$idEmpresa)
            ->select('tb_kardex_producto_terminado.id','tb_kardex_producto_terminado.fecha','tb_kardex_producto_terminado.detalle','tb_kardex_producto_terminado.cantidad',
            'tb_kardex_producto_terminado.precio','tb_kardex_producto_terminado.cantidadSaldos','tb_kardex_producto_terminado.precioSaldos','tb_kardex_producto_terminado.idProducto',
            'tb_kardex_producto_terminado.tipologia','tb_producto.producto as producto', 'tb_producto.estado',
            DB::raw('tb_kardex_producto_terminado.cantidadSaldos * tb_kardex_producto_terminado.precioSaldos as saldos'))
            ->whereIn('tb_kardex_producto_terminado.id', function($sub){$sub->selectRaw('max(id)')->from('tb_kardex_producto_terminado')->groupBy('idProducto');})
            ->where('tb_kardex_producto_terminado.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('tb_kardex_producto_terminado.idProducto','asc')->paginate(5);
        }
        return [
            'pagination' => [
                'total'         =>$kardex->total(),
                'current_page'  =>$kardex->currentPage(),
                'per_page'      =>$kardex->perPage(),
                'last_page'     =>$kardex->lastPage(),
                'from'          =>$kardex->firstItem(),
                'to'            =>$kardex->lastItem(),
            ],
            'kardex' =>  $kardex
        ];

    }
    public function listar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $identificador= $request->identificador;

        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

            $kardex = Tb_kardex_producto_terminado::join('tb_producto','tb_kardex_producto_terminado.idProducto','=','tb_producto.id')
            ->where('tb_kardex_producto_terminado.idEmpresa','=',$idEmpresa)
            ->select('tb_kardex_producto_terminado.id as idKardex','tb_kardex_producto_terminado.fecha','tb_kardex_producto_terminado.detalle','tb_kardex_producto_terminado.cantidad',
            'tb_kardex_producto_terminado.precio',DB::raw('tb_kardex_producto_terminado.cantidad * tb_kardex_producto_terminado.precio as preciototal'),
            'tb_kardex_producto_terminado.cantidadSaldos','tb_kardex_producto_terminado.precioSaldos','tb_kardex_producto_terminado.idProducto',
            'tb_kardex_producto_terminado.tipologia','tb_producto.producto as producto','tb_producto.estado',
            DB::raw('tb_kardex_producto_terminado.cantidadSaldos * tb_kardex_producto_terminado.precioSaldos as totalsaldos'))
            ->where('tb_kardex_producto_terminado.idProducto', '=', $identificador)
            ->orderBy('tb_kardex_producto_terminado.id','asc')->paginate(5);

        return [
            'pagination' => [
                'total'         =>$kardex->total(),
                'current_page'  =>$kardex->currentPage(),
                'per_page'      =>$kardex->perPage(),
                'last_page'     =>$kardex->lastPage(),
                'from'          =>$kardex->firstItem(),
                'to'            =>$kardex->lastItem(),
            ],
            'kardex' =>  $kardex
        ];

    }
    public function general()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $productos = Tb_producto::join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
            ->where('tb_producto.estado','=','1')
            ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
            ->select('tb_producto.id as idMateria','tb_producto.producto as materia')
            ->get();

        return ['productos' =>  $productos];
    }
    public function ordenes() //PARA TRAER DATOS ACORDE
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $ordenes = DB::table('tb_orden_produccion')
        ->where('tb_orden_produccion.idEmpresa','=',$idEmpresa)
        ->select('tb_orden_produccion.id','tb_orden_produccion.consecutivo','tb_orden_produccion.fecha')
        ->whereIn('tb_orden_produccion.id', function($sub){$sub->selectRaw('max(id)')->from('tb_orden_produccion')->groupBy('consecutivo');})
        ->orderBy('tb_orden_produccion.id','desc')
        ->get();

        return ['ordenes' =>  $ordenes];

    }
    public function productos($identificador) //PARA TRAER DATOS ACORDE
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $ordenes = Tb_orden_pedido_cliente_detalle::join('tb_orden_pedido_cliente','tb_orden_pedido_cliente_detalle.idOrdenPedido','=','tb_orden_pedido_cliente.id')
            ->where('tb_orden_pedido_cliente.idEmpresa','=',$idEmpresa)
            ->select('tb_orden_pedido_cliente_detalle.idOrdenPedido as idOrdenPedido')
            ->where('tb_orden_pedido_cliente.consecutivo', '=', $identificador)
            ->get();

        foreach($ordenes as $orden){
            $identiforden = $orden->idOrdenPedido;
        }
        //if(!$request->ajax()) return redirect('/');
        $materiales = Tb_orden_pedido_cliente_detalle::join('tb_producto','tb_orden_pedido_cliente_detalle.idProducto','=','tb_producto.id')
            ->join('tb_area','tb_producto.idArea','=','tb_area.id')
            ->where('tb_area.idEmpresa','=',$idEmpresa)
            ->select('tb_producto.producto as producto','tb_producto.id','tb_orden_pedido_cliente_detalle.precioCosto',
            'tb_orden_pedido_cliente_detalle.precioVenta','tb_orden_pedido_cliente_detalle.idOrdenPedido')
            ->where('tb_orden_pedido_cliente_detalle.idOrdenPedido', '=', $identiforden)
            ->orderBy('tb_producto.id','asc')
            ->get();

        return ['materiales' =>  $materiales];

    }
    public function precioproductospromedio(Request $request) //DATOS de valor segun orden 2 5 y 6 traigo segun idmateria el valor promedio del kardex
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $identificador=$request->producto;

        $preciomaterial = DB::table('tb_kardex_producto_terminado')
        ->where('tb_kardex_producto_terminado.idEmpresa','=',$idEmpresa)
        ->select('tb_kardex_producto_terminado.id','tb_kardex_producto_terminado.precioSaldos as valorProducto')
        ->where('tb_kardex_producto_terminado.idProducto', '=', $identificador)
        ->orderByDesc('tb_kardex_producto_terminado.id')
        ->limit(1)
        ->get();

        foreach($preciomaterial as $totalg){
            $id = $totalg->id;
            $valorProducto = $totalg->valorProducto;
        }

         return [
            'id' => $id,
            'valorProducto' =>  $valorProducto
        ];
    }
    public function precioproductosorden(Request $request) //DATOS de valor segun compra 4 traigo segun idmateria el valor de compra del kardex
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $identificador=$request->producto;
        $segordenpedido=$request->segordenpedido;

        $ordenes = DB::table('tb_orden_produccion')
        ->where('tb_orden_produccion.idEmpresa','=',$idEmpresa)
        ->where('id', '=', $segordenpedido)->get();
        foreach ($ordenes as $orden) {
            $idOrdenPedido = $orden->idOrdenPedido;
        }

        $preciomaterial = Tb_orden_pedido_cliente_detalle::first() //-------------->Mirar
        ->select('tb_orden_pedido_cliente_detalle.id','tb_orden_pedido_cliente_detalle.precioCosto as valorMaterial')
        ->where([
            ['tb_orden_pedido_cliente_detalle.idOrdenPedido', '=', $idOrdenPedido],
            ['tb_orden_pedido_cliente_detalle.idProducto', '=', $identificador],
        ])
        ->get();

        foreach($preciomaterial as $totalg){
            $id = $totalg->id;
            $valorProducto = $totalg->valorMaterial;
        }
        //para calcular las cantidades que se solicitaron en la orden
        $cantidadesesperadas = Tb_orden_pedido_cliente_detalle::first() //-------------->Mirar
        ->select('tb_orden_pedido_cliente_detalle.id','tb_orden_pedido_cliente_detalle.cantidad as cantidadesesperadas')
        ->where([
            ['tb_orden_pedido_cliente_detalle.idOrdenPedido', '=', $idOrdenPedido],
            ['tb_orden_pedido_cliente_detalle.idProducto', '=', $identificador],
        ])
        ->get();

        foreach($cantidadesesperadas as $cantidadespera){
            $cantidadesperada = $cantidadespera->cantidadesesperadas;
        }
        //para calcular las cantidades que se reciben en el kardex como ingresos
        $cantidadesentrantes = Tb_kardex_producto_terminado::where('tb_kardex_producto_terminado.idEmpresa','=',$idEmpresa)
        ->select(DB::raw('SUM(cantidad) AS cantidadentrante'))
        ->where([
            ['tb_kardex_producto_terminado.detalle', '=', $idOrdenPedido],
            ['tb_kardex_producto_terminado.idProducto', '=', $identificador],
            ['tb_kardex_producto_terminado.tipologia', '=', '1'],
        ])
        ->get();

        foreach($cantidadesentrantes as $cantidadentrante){
            $cantidadentra = $cantidadentrante->cantidadentrante;
        }
        /*
        //para calcular las cantidades que se reciben en el kardex como salidas
        $cantidadesentregadas = Tb_kardex_producto_terminado::select(DB::raw('SUM(cantidad) AS cantidadsaliente'))
        ->where([
            ['tb_kardex_producto_terminado.idOrdenPedido', '=', $idOrdenPedido],
            ['tb_kardex_producto_terminado.idProducto', '=', $identificador],
            ['tb_kardex_producto_terminado.tipologia', '=', '2'],
        ])
        ->get();

        foreach($cantidadesentregadas as $cantidadentregada){
            $valorProducto = $totalg->valorMaterial;
        }
        //aca calculo la diferencia entre salidas y entradas
        */
        //aca calculo la diferencia entre solicitadas y entregadas
        $cantidaddiferente=$cantidadesperada-$cantidadentra;
        if($cantidaddiferente >= 0){
            $mensajecantidad="Faltan ".$cantidaddiferente." unidades por entregar";
        }
        else{
            $mensajecantidad="Sobran ".$cantidaddiferente." unidades de esta orden";
        }

         return [
            'id' => $id,
            'valorProducto' =>  $valorProducto,
            'mensajecantidad' =>  $mensajecantidad
        ];
    }
    public function producto(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $identificador= $request->identificador;
        $nombreproducto = Tb_producto::where('tb_producto.id', '=', $identificador)
        ->select('tb_producto.id','tb_producto.producto as producto')
        ->orderBy('tb_producto.id','asc')
        ->get();

        return ['nombreproducto' =>  $nombreproducto];
    }
    public function empleados()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $empleados = Tb_empleado::where('tb_empleado.idEmpresa','=',$idEmpresa)
        ->select('tb_empleado.id',DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
        ->orderBy('tb_empleado.id','asc')
        ->get();

        return ['empleados' =>  $empleados];
    }
    public function todos()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $todos = Tb_producto::join('tb_area','tb_producto.idArea','=','tb_area.id')
        ->where('tb_area.idEmpresa','=',$idEmpresa)
        ->select('tb_producto.id','tb_producto.producto as producto')
        ->orderBy('tb_producto.id','asc')
        ->get();

        return ['todos' =>  $todos];
    }
    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');

        $fecha=$request->fecha;
        $detalle=$request->detalle;
        $cantidad=$request->cantidad;
        $idPrecio=$request->idPrecio;
        $idProducto=$request->idProducto;
        $observaciones=$request->observaciones;
        $idEmpleado=$request->idEmpleado;
        $idDocumentos=$request->idDocumentos;
        $tipologia=$request->tipologia;
        $precio=$request->precio;
        $valorE=($precio*$cantidad);

        //capturo cantidad precio y producto; internamente busco el registro mas reciente de dicho producto y tomo el valor cantidad de ese registro,
        //ahora, miro los datos del ingreso nuevo, si es un 1 es un ingreso y sumo la nueva cantidad a la de cantidadSaldos del registro anterior,
        //si es 2 es una salida y lo que hago es restar la nueva cantidad de la de cantidadSaldos del registro anterior; el resultado va en el
        //registro nuevo en el campo cantidadSaldos; ahora para llenar el campo de precioSaldos (que es el precio promedio) lo que hago es del registro
        //anterior multiplico cantidadSaldos*precioSaldos y a ese valor le sumo o resto segun el caso el valor del registro nuevo que resulta de
        //cantidad*precio; el resultado lo divido entre el valor que calcule de cantidadSaldos y lo ingreso en precioSaldos; hay que tener muy en
        //cuenta que en el primer registro las cantidad y cantidadSaldos asi como las precio y precioSaldos son identicas.
        //Proceso para calculo de kardex

        $precioSaldos = DB::table('tb_kardex_producto_terminado')
        ->where('tb_kardex_producto_terminado.idEmpresa','=',$idEmpresa)
        ->select('id','cantidad as cantidadA','cantidadSaldos as cantidadS','precioSaldos as precioS')
        ->where('tb_kardex_producto_terminado.idProducto','=', $idProducto)
        ->orderByDesc('id')
        ->limit(1)
        ->get();

        $precioSaldoscant= DB::table('tb_kardex_producto_terminado')
        ->where('tb_kardex_producto_terminado.idEmpresa','=',$idEmpresa)
        ->where('tb_kardex_producto_terminado.idProducto','=', $idProducto)
        ->count();

        if (!$precioSaldoscant) {
            $suma1=$cantidad;
            $suma2=$precio;
        }
        else{
            foreach($precioSaldos as $precioS){
                $cantidadA = $precioS->cantidadA;
                $cantidadS = $precioS->cantidadS;
                $precioSA = $precioS->precioS;
                $valorP=($cantidadS*$precioSA);
            }
            if($tipologia == 1){
            $suma1=$cantidadS+$cantidad;
            $suma0=$valorP+$valorE;

                if($suma1 == 0){
                    $suma2=0;
                }
                else{
                    $suma2=($suma0/$suma1);
                }
            }
            else{
                $suma1=$cantidadS-$cantidad;
                $suma0=$valorP-$valorE;

                if($suma1 == 0){
                    $suma2=0;
                }
                else{
                    $suma2=($suma0/$suma1);
                }

            }
        }

        $tb_kardex_producto_terminado=new Tb_kardex_producto_terminado();
        $tb_kardex_producto_terminado->fecha=$fecha;
        $tb_kardex_producto_terminado->detalle=$detalle;
        $tb_kardex_producto_terminado->cantidad=$cantidad;
        $tb_kardex_producto_terminado->precio=$precio;
        $tb_kardex_producto_terminado->cantidadSaldos=$suma1;
        $tb_kardex_producto_terminado->precioSaldos=$suma2;
        $tb_kardex_producto_terminado->observaciones=$observaciones;
        $tb_kardex_producto_terminado->idEmpleado=$idEmpleado;
        $tb_kardex_producto_terminado->idDocumentos=$idDocumentos;
        $tb_kardex_producto_terminado->idProducto=$idProducto;
        $tb_kardex_producto_terminado->tipologia=$tipologia;
        $tb_kardex_producto_terminado->idEmpresa=$idEmpresa;
        $tb_kardex_producto_terminado->save();
    }
}
