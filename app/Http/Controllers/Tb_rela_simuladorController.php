<?php

namespace App\Http\Controllers;

use App\Tb_rela_simulador;
use App\Tb_producto;
use App\Tb_detallado_simulador;
use App\Tb_precios_venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_rela_simuladorController extends Controller
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
        $identificador= $request->id;
            # Modelo::join('tablaqueseune',basicamente un on)
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
            ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.tiempo','tb_producto.producto',
            'tb_producto.referencia','tb_producto.descripcion')
            ->where('tb_rela_simulador.idSimulador', '=', $identificador)->get(); //para revisar 24-02-2022
            return ['productos' => $productos];
    }

    public function productos()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $posibles = DB::table('tb_producto')
        ->join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
        ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
        ->select('tb_producto.id as idProducto','producto')
        ->get();

        return ['posibles' => $posibles];
    }

    public function posibles(Request $request) //esta funcion no estoy seguro porque la hice pero no esta funcionando bien
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
        ->whereNotIn('id', DB::table('tb_rela_simulacion')->select('idProducto')->where('idSimulacion', '=', $identificador))
        ->get();

        return ['posibles' => $posibles];
    }

    public function posiblesPrecios(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $posibles = DB::table('tb_precios_venta')
        ->where('tb_precios_venta.idEmpresa','=',$idEmpresa)
        ->select('id','idProducto','detalle as producto')
        ->get();

        return ['posibles' => $posibles];
    }

    public function listar(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $buscar= $request->buscar;
        $criterio= $request->criterio;
        $identificador= $request->identificador;

        if ($buscar=='') {
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->join('tb_precios_venta','tb_rela_simulador.idPrecio','=','tb_precios_venta.id')
            ->where('tb_precios_venta.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.idPrecio','tb_precios_venta.preciodeventa',
            'tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where('tb_rela_simulador.idSimulador', '=', $identificador)
            ->orderBy('tb_rela_simulador.id','desc')->paginate(5);
        }
        else if($criterio=='producto'){
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->join('tb_precios_venta','tb_rela_simulador.idPrecio','=','tb_precios_venta.id')
            ->where('tb_precios_venta.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.idPrecio','tb_precios_venta.preciodeventa',
            'tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where([
                ['tb_producto.producto', 'like', '%'. $buscar . '%'],
                ['tb_rela_simulador.idSimulador', '=', $identificador],
            ])
            ->orderBy('tb_rela_simulador.id','desc')->paginate(5);
        }
        else if($criterio=='referencia'){
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->join('tb_precios_venta','tb_rela_simulador.idPrecio','=','tb_precios_venta.id')
            ->where('tb_precios_venta.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.idPrecio','tb_precios_venta.preciodeventa',
            'tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where([
                ['tb_producto.referencia', 'like', '%'. $buscar . '%'],
                ['tb_rela_simulador.idSimulador', '=', $identificador],
            ])
            ->orderBy('tb_rela_simulador.id','desc')->paginate(5);
        }
        else {
            # code...
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->join('tb_precios_venta','tb_rela_simulador.idPrecio','=','tb_precios_venta.id')
            ->where('tb_precios_venta.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.idPrecio','tb_precios_venta.preciodeventa',
            'tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where([
                ['tb_producto.id', 'like', '%'. $buscar . '%'],
                ['tb_rela_simulador.idSimulacion', '=', $identificador],
            ])
            ->orderBy('tb_rela_simulador.id','desc')->paginate(5);
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
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $idPrecio=$request->idRelacion;

        $productos = Tb_precios_venta::where('id','=',$idPrecio)
        ->where('tb_precios_venta.idEmpresa','=',$idEmpresa)
        ->select('idProducto')->get();
        foreach($productos as $producto){
            $nombrep = $producto->idProducto;
            }

        $tb_rela_simulador=new Tb_rela_simulador();
        $tb_rela_simulador->idPrecio=$idPrecio; //este es el id de los precios venta
        $tb_rela_simulador->unidades=$request->unidades;
        $tb_rela_simulador->idProducto=$nombrep; //este es el id del producto
        $tb_rela_simulador->idSimulador=$request->idSimulacion;
        $tb_rela_simulador->save();
    }
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_rela_simulador = Tb_rela_simulador::findOrFail($request->id);
        $tb_rela_simulador->idProducto=$request->idProducto;
        $tb_rela_simulador->unidades=$request->unidades;
        $tb_rela_simulador->tiempo=$request->tiempo;
        $tb_rela_simulador->idSimulacion=$request->idSimulacion;
        $tb_rela_simulador->save();
    }
    public function delete(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_rela_simulador = Tb_rela_simulador::findOrFail($request->id);
        $tb_rela_simulador->delete();
        //return ['productos' => $productos];
    }

    public function selectDetallado(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $idSimulador=$request->idSimulador;

        $detallado = DB::table('tb_detallado_simulador')
        ->join('tb_producto','tb_detallado_simulador.idProducto','=','tb_producto.id')
        ->where('tb_detallado_simulador.idSimulador','=',$idSimulador)
        ->get();

        return ['detallado' => $detallado];
    }

    public function pruebasPosibles(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $idSimulador=$request->idSimulador;

        $posibles = DB::table('tb_precios_venta')
        ->join('tb_rela_simulador','tb_rela_simulador.idProducto','=','tb_precios_venta.idProducto')
        ->where('tb_rela_simulador.idSimulador','=',$idSimulador)
        ->select('tb_precios_venta.id','tb_precios_venta.idProducto','tb_precios_venta.detalle as producto')
        ->whereNotIn('tb_precios_venta.idProducto', DB::table('tb_rela_simulador')->select('idProducto')->where('idSimulador', '=', $idSimulador))
        ->get();

        return ['posibles' => $posibles];
    }
}
