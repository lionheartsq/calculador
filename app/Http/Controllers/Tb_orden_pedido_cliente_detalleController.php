<?php

namespace App\Http\Controllers;

use App\Tb_orden_pedido_cliente_detalle;
use App\Tb_producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Tb_orden_pedido_cliente_detalleController extends Controller
{
    //
    public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $identificador= $request->id;
            # Modelo::join('tablaqueseune',basicamente un on)
            $productos = Tb_orden_pedido_cliente_detalle::join('tb_producto','tb_orden_pedido_cliente_detalle.idProducto','=','tb_producto.id')
            ->select('tb_orden_pedido_cliente_detalle.id','tb_orden_pedido_cliente_detalle.idProducto','tb_orden_pedido_cliente_detalle.cantidad',
            'tb_orden_pedido_cliente_detalle.precioCosto','tb_orden_pedido_cliente_detalle.precioVenta','tb_orden_pedido_cliente_detalle.estado',
            'tb_producto.producto','tb_producto.referencia','tb_producto.descripcion','tb_producto.foto')
            ->where('tb_orden_pedido_cliente_detalle.idOrdenPedido', '=', $identificador)->get();
            return ['productos' => $productos];
    }

    public function posibles(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $identificador= $request->id;

        $posibles = DB::table('tb_producto')
        ->select('id as idProducto','producto')
        ->whereNotIn('id', DB::table('tb_orden_pedido_cliente_detalle')->select('idProducto')->where('idOrdenPedido', '=', $identificador))
        ->get();

        return ['posibles' => $posibles];
    }

    public function listar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $buscar= $request->buscar;
        $criterio= $request->criterio;
        $identificador= $request->identificador;

        if ($buscar=='') {
            $productos = Tb_orden_pedido_cliente_detalle::join('tb_producto','tb_orden_pedido_cliente_detalle.idProducto','=','tb_producto.id')
            ->select('tb_orden_pedido_cliente_detalle.id','tb_orden_pedido_cliente_detalle.idProducto','tb_orden_pedido_cliente_detalle.cantidad',
            'tb_orden_pedido_cliente_detalle.precioCosto','tb_orden_pedido_cliente_detalle.precioVenta','tb_orden_pedido_cliente_detalle.estado',
            'tb_producto.producto','tb_producto.referencia','tb_producto.descripcion','tb_producto.foto')
            ->where('tb_orden_pedido_cliente_detalle.idOrdenPedido', '=', $identificador)
            ->orderBy('tb_orden_pedido_cliente_detalle.id','desc')->paginate(5);
        }
        else if($criterio=='producto'){
            $productos = Tb_orden_pedido_cliente_detalle::join('tb_producto','tb_orden_pedido_cliente_detalle.idProducto','=','tb_producto.id')
            ->select('tb_orden_pedido_cliente_detalle.id','tb_orden_pedido_cliente_detalle.idProducto','tb_orden_pedido_cliente_detalle.cantidad',
            'tb_orden_pedido_cliente_detalle.precioCosto','tb_orden_pedido_cliente_detalle.precioVenta','tb_orden_pedido_cliente_detalle.estado',
            'tb_producto.producto','tb_producto.referencia','tb_producto.descripcion','tb_producto.foto')
            ->where([
                ['tb_producto.producto', 'like', '%'. $buscar . '%'],
                ['tb_orden_pedido_cliente_detalle.idOrdenPedido', '=', $identificador],
            ])
            ->orderBy('tb_orden_pedido_cliente_detalle.id','desc')->paginate(5);
        }
        else if($criterio=='referencia'){
            $productos = Tb_orden_pedido_cliente_detalle::join('tb_producto','tb_orden_pedido_cliente_detalle.idProducto','=','tb_producto.id')
            ->select('tb_orden_pedido_cliente_detalle.id','tb_orden_pedido_cliente_detalle.idProducto','tb_orden_pedido_cliente_detalle.cantidad',
            'tb_orden_pedido_cliente_detalle.precioCosto','tb_orden_pedido_cliente_detalle.precioVenta','tb_orden_pedido_cliente_detalle.estado',
            'tb_producto.producto','tb_producto.referencia','tb_producto.descripcion','tb_producto.foto')
            ->where([
                ['tb_producto.referencia', 'like', '%'. $buscar . '%'],
                ['tb_orden_pedido_cliente_detalle.idOrdenPedido', '=', $identificador],
            ])
            ->orderBy('tb_orden_pedido_cliente_detalle.id','desc')->paginate(5);
        }
        else {
            # code...
            $productos = Tb_orden_pedido_cliente_detalle::join('tb_producto','tb_orden_pedido_cliente_detalle.idProducto','=','tb_producto.id')
            ->select('tb_orden_pedido_cliente_detalle.id','tb_orden_pedido_cliente_detalle.idProducto','tb_orden_pedido_cliente_detalle.cantidad',
            'tb_orden_pedido_cliente_detalle.precioCosto','tb_orden_pedido_cliente_detalle.precioVenta','tb_orden_pedido_cliente_detalle.estado',
            'tb_producto.producto','tb_producto.referencia','tb_producto.descripcion','tb_producto.foto')
            ->where([
                ['tb_producto.id', 'like', '%'. $buscar . '%'],
                ['tb_orden_pedido_cliente_detalle.idOrdenPedido', '=', $identificador],
            ])
            ->orderBy('tb_orden_pedido_cliente_detalle.id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$productos->total(),
                'current_page'  =>$productos->currentPage(),
                'per_page'      =>$productos->perPage(),
                'last_page'     =>$productos->lastPage(),
                'from'          =>$productos->firstItem(),
                'to'            =>$productos->lastItem(),
            ],
                'productos' => $productos
        ];
    }

    public function store(Request $request)
    {
        $identificador=$request->idProducto;

        $total = 0;
        $acumuladomd = 0;
        $acumuladomi = 0;
        $acumuladomo = 0;
        $acumuladocif = 0;
        $acumuladomaquinaria = 0;
        $acumuladocift = 0;

        # Modelo::join('tablaqueseune',basicamente un on)
        $productos = Tb_producto::join('tb_hoja_de_costo','tb_producto.id','=','tb_hoja_de_costo.idProducto')
        ->select('tb_producto.producto as producto','tb_producto.referencia as referencia','tb_producto.foto as foto','tb_hoja_de_costo.capacidadMensual as capacidadMensual')
        ->where('tb_producto.id','=',$identificador)
        ->get();

        foreach($productos as $producto){
            $unidadesprod = $producto->capacidadMensual;
            }

        //directa
        $query = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio) IS NULL THEN 0
        ELSE ROUND(SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) END) as acumuladomd");
        $materiadirecta = DB::table('tb_materia_prima_producto')
        ->select($query)
        ->where([
            ['tb_materia_prima_producto.idHoja','=',$identificador],
            ['tb_materia_prima_producto.tipoDeCosto', '=', 'Directo'],
        ])->get();
        foreach($materiadirecta as $materiad){
        $acumuladomd = $materiad->acumuladomd + $acumuladomd;
        }

        //indirecta
        $query1 = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio) IS NULL THEN 0
        ELSE ROUND(SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) END) as acumuladomi");
        $materiaindirecta = DB::table('tb_materia_prima_producto')
        ->select($query1)
        ->where([
            ['tb_materia_prima_producto.idHoja','=',$identificador],
            ['tb_materia_prima_producto.tipoDeCosto', '=', 'Indirecto'],
        ])->get();
        foreach($materiaindirecta as $materiaind){
            $acumuladomi = $materiaind->acumuladomi + $acumuladomi;
        }

        //manodeobra
        $query2 = DB::raw("(CASE WHEN SUM(tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio) IS NULL THEN 0
        ELSE ROUND(SUM(tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio),0) END) as acumuladomo");
        $manodeobra = DB::table('tb_mano_de_obra_producto')
        ->select($query2)
        ->where('tb_mano_de_obra_producto.idHoja','=',$identificador)
        ->get();
        foreach($manodeobra as $manodeo){
            $acumuladomo = $manodeo->acumuladomo + $acumuladomo;
        }

        //cif
        $acumuladocif = 0;
        $acumuladomaquinaria = 0;
        $acumuladocift = 0;

        //cif
        $query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif.valor) IS NULL THEN 0
        ELSE SUM(tb_concepto_cif.valor) END) as acumuladocif");
        $ciftotales = DB::table('tb_concepto_cif')
        ->select($query3)
        ->get();
        foreach($ciftotales as $ciftotal){
            $acumuladocif = $ciftotal->acumuladocif + $acumuladocif;
            $acumuladocift = $acumuladocift + $acumuladocif;
        }

        //maquinaria
        $query4 = DB::raw("(CASE WHEN SUM(tb_maquinaria.depreciacionMensual) IS NULL THEN 0
        ELSE SUM(tb_maquinaria.depreciacionMensual) END) as acumuladomaquinaria");
        $totales = DB::table('tb_maquinaria')
        ->select($query4)
        ->get();
        foreach($totales as $totalg){
            $acumuladomaquinaria = $totalg->acumuladomaquinaria + $acumuladomaquinaria;
            $acumuladocift = $acumuladocift + $acumuladomaquinaria;
        }

        //capacidadproduccion total
        $cifunitario=($acumuladocift/$unidadesprod);
        $cifunitariored=round($cifunitario,0);

        $total = $acumuladomd + $acumuladomi + $acumuladomo + $cifunitariored;

        //if(!$request->ajax()) return redirect('/');
        $tb_orden_pedido_cliente_detalle=new Tb_orden_pedido_cliente_detalle();
        $tb_orden_pedido_cliente_detalle->idProducto=$request->idProducto;
        $tb_orden_pedido_cliente_detalle->cantidad=$request->cantidad;
        $tb_orden_pedido_cliente_detalle->precioCosto=$total;
        $tb_orden_pedido_cliente_detalle->precioVenta=$request->precioVenta;
        $tb_orden_pedido_cliente_detalle->idOrdenPedido=$request->idOrdenPedido;
        $tb_orden_pedido_cliente_detalle->save();
    }

    public function costo(Request $request)
        {
        $identificador=$request->idProducto;

        $total = 0;
        $acumuladomd = 0;
        $acumuladomi = 0;
        $acumuladomo = 0;
        $acumuladocif = 0;
        $acumuladomaquinaria = 0;
        $acumuladocift = 0;

        # Modelo::join('tablaqueseune',basicamente un on)
        $productos = Tb_producto::join('tb_hoja_de_costo','tb_producto.id','=','tb_hoja_de_costo.idProducto')
        ->select('tb_producto.producto as producto','tb_producto.referencia as referencia','tb_producto.foto as foto','tb_hoja_de_costo.capacidadMensual as capacidadMensual')
        ->where('tb_producto.id','=',$identificador)
        ->get();

        foreach($productos as $producto){
            $unidadesprod = $producto->capacidadMensual;
            }

        //directa
        $query = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio) IS NULL THEN 0
        ELSE ROUND(SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) END) as acumuladomd");
        $materiadirecta = DB::table('tb_materia_prima_producto')
        ->select($query)
        ->where([
            ['tb_materia_prima_producto.idHoja','=',$identificador],
            ['tb_materia_prima_producto.tipoDeCosto', '=', 'Directo'],
        ])->get();
        foreach($materiadirecta as $materiad){
        $acumuladomd = $materiad->acumuladomd + $acumuladomd;
        }

        //indirecta
        $query1 = DB::raw("(CASE WHEN SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio) IS NULL THEN 0
        ELSE ROUND(SUM(tb_materia_prima_producto.cantidad*tb_materia_prima_producto.precio),0) END) as acumuladomi");
        $materiaindirecta = DB::table('tb_materia_prima_producto')
        ->select($query1)
        ->where([
            ['tb_materia_prima_producto.idHoja','=',$identificador],
            ['tb_materia_prima_producto.tipoDeCosto', '=', 'Indirecto'],
        ])->get();
        foreach($materiaindirecta as $materiaind){
            $acumuladomi = $materiaind->acumuladomi + $acumuladomi;
        }

        //manodeobra
        $query2 = DB::raw("(CASE WHEN SUM(tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio) IS NULL THEN 0
        ELSE ROUND(SUM(tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio),0) END) as acumuladomo");
        $manodeobra = DB::table('tb_mano_de_obra_producto')
        ->select($query2)
        ->where('tb_mano_de_obra_producto.idHoja','=',$identificador)
        ->get();
        foreach($manodeobra as $manodeo){
            $acumuladomo = $manodeo->acumuladomo + $acumuladomo;
        }

        //cif
        $acumuladocif = 0;
        $acumuladomaquinaria = 0;
        $acumuladocift = 0;

        //cif
        $query3 = DB::raw("(CASE WHEN SUM(tb_concepto_cif.valor) IS NULL THEN 0
        ELSE SUM(tb_concepto_cif.valor) END) as acumuladocif");
        $ciftotales = DB::table('tb_concepto_cif')
        ->select($query3)
        ->get();
        foreach($ciftotales as $ciftotal){
            $acumuladocif = $ciftotal->acumuladocif + $acumuladocif;
            $acumuladocift = $acumuladocift + $acumuladocif;
        }

        //maquinaria
        $query4 = DB::raw("(CASE WHEN SUM(tb_maquinaria.depreciacionMensual) IS NULL THEN 0
        ELSE SUM(tb_maquinaria.depreciacionMensual) END) as acumuladomaquinaria");
        $totales = DB::table('tb_maquinaria')
        ->select($query4)
        ->get();
        foreach($totales as $totalg){
            $acumuladomaquinaria = $totalg->acumuladomaquinaria + $acumuladomaquinaria;
            $acumuladocift = $acumuladocift + $acumuladomaquinaria;
        }

        //capacidadproduccion total
        $cifunitario=($acumuladocift/$unidadesprod);
        $cifunitariored=round($cifunitario,0);

        $total = $acumuladomd + $acumuladomi + $acumuladomo + $cifunitariored;

        return ['total' => $total];
    }
}