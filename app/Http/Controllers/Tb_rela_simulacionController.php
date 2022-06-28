<?php

namespace App\Http\Controllers;

use App\Tb_rela_simulacion;
use App\Tb_producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_rela_simulacionController extends Controller
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
            $productos = Tb_rela_simulacion::join('tb_producto','tb_rela_simulacion.idProducto','=','tb_producto.id')
            ->join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
            ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulacion.id','tb_rela_simulacion.unidades','tb_rela_simulacion.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where('tb_rela_simulacion.idSimulacion', '=', $identificador)->get();
            return ['productos' => $productos];
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
        ->whereNotIn('tb_producto.id', DB::table('tb_rela_simulacion')->select('idProducto')->where('idSimulacion', '=', $identificador))
        ->get();

        return ['posibles' => $posibles];
    }

    public function posiblesprueba(Request $request)
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
        ->whereNotIn('tb_producto.id', DB::table('tb_rela_simulacion')->select('idProducto')->where('idSimulacion', '=', $identificador))
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
            $productos = Tb_rela_simulacion::join('tb_producto','tb_rela_simulacion.idProducto','=','tb_producto.id')
            ->join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
            ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulacion.id as idRegistro','tb_rela_simulacion.unidades','tb_rela_simulacion.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where('tb_rela_simulacion.idSimulacion', '=', $identificador)
            ->orderBy('tb_rela_simulacion.id','desc')->paginate(5);
        }
        else if($criterio=='producto'){
            $productos = Tb_rela_simulacion::join('tb_producto','tb_rela_simulacion.idProducto','=','tb_producto.id')
            ->join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
            ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulacion.id as idRegistro','tb_rela_simulacion.unidades','tb_rela_simulacion.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where([
                ['tb_producto.producto', 'like', '%'. $buscar . '%'],
                ['tb_rela_simulacion.idSimulacion', '=', $identificador],
            ])
            ->orderBy('tb_rela_simulacion.id','desc')->paginate(5);
        }
        else if($criterio=='referencia'){
            $productos = Tb_rela_simulacion::join('tb_producto','tb_rela_simulacion.idProducto','=','tb_producto.id')
            ->join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
            ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulacion.id as idRegistro','tb_rela_simulacion.unidades','tb_rela_simulacion.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where([
                ['tb_producto.referencia', 'like', '%'. $buscar . '%'],
                ['tb_rela_simulacion.idSimulacion', '=', $identificador],
            ])
            ->orderBy('tb_rela_simulacion.id','desc')->paginate(5);
        }
        else {
            # code...
            $productos = Tb_rela_simulacion::join('tb_producto','tb_rela_simulacion.idProducto','=','tb_producto.id')
            ->join('tb_coleccion','tb_producto.idColeccion','=','tb_coleccion.id')
            ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
            ->select('tb_rela_simulacion.id as idRegistro','tb_rela_simulacion.unidades','tb_rela_simulacion.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where([
                ['tb_producto.id', 'like', '%'. $buscar . '%'],
                ['tb_rela_simulacion.idSimulacion', '=', $identificador],
            ])
            ->orderBy('tb_rela_simulacion.id','desc')->paginate(5);
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
        if(!$request->ajax()) return redirect('/');
        $tb_rela_simulacion=new Tb_rela_simulacion();
        $tb_rela_simulacion->idProducto=$request->idProducto;
        $tb_rela_simulacion->unidades=$request->unidades;
        $tb_rela_simulacion->tiempo=$request->tiempo;
        $tb_rela_simulacion->idSimulacion=$request->idSimulacion;
        $tb_rela_simulacion->save();
    }
    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_rela_simulacion = Tb_rela_simulacion::findOrFail($request->id);
        $tb_rela_simulacion->unidades=$request->unidades;
        $tb_rela_simulacion->tiempo=$request->tiempo;
        $tb_rela_simulacion->idSimulacion=$request->idSimulacion;
        $tb_rela_simulacion->save();
    }
    public function delete(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_rela_simulacion = Tb_rela_simulacion::findOrFail($request->id);
        $tb_rela_simulacion->delete();
        //return ['productos' => $productos];
    }
}
