<?php

namespace App\Http\Controllers;

use App\Tb_rela_simulador;
use App\Tb_producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Tb_rela_simuladorController extends Controller
{
    //
    public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $identificador= $request->id;
            # Modelo::join('tablaqueseune',basicamente un on)
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where('tb_rela_simulador.idSimulacion', '=', $identificador)->get();
            return ['productos' => $productos];
    }

    public function posibles(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $identificador= $request->id;

        $posibles = DB::table('tb_producto')
        ->select('id as idProducto','producto')
        ->whereNotIn('id', DB::table('tb_rela_simulador')->select('idProducto')->where('idSimulacion', '=', $identificador))
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
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where('tb_rela_simulador.idSimulacion', '=', $identificador)
            ->orderBy('tb_rela_simulador.id','desc')->paginate(5);
        }
        else if($criterio=='producto'){
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where([
                ['tb_producto.producto', 'like', '%'. $buscar . '%'],
                ['tb_rela_simulador.idSimulacion', '=', $identificador],
            ])
            ->orderBy('tb_rela_simulador.id','desc')->paginate(5);
        }
        else if($criterio=='referencia'){
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
            ->where([
                ['tb_producto.referencia', 'like', '%'. $buscar . '%'],
                ['tb_rela_simulador.idSimulacion', '=', $identificador],
            ])
            ->orderBy('tb_rela_simulador.id','desc')->paginate(5);
        }
        else {
            # code...
            $productos = Tb_rela_simulador::join('tb_producto','tb_rela_simulador.idProducto','=','tb_producto.id')
            ->select('tb_rela_simulador.id','tb_rela_simulador.unidades','tb_rela_simulador.tiempo','tb_producto.producto','tb_producto.referencia','tb_producto.descripcion')
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
        if(!$request->ajax()) return redirect('/');
        $tb_rela_simulador=new Tb_rela_simulador();
        $tb_rela_simulador->idProducto=$request->idProducto;
        $tb_rela_simulador->unidades=$request->unidades;
        $tb_rela_simulador->tiempo=$request->tiempo;
        $tb_rela_simulador->idSimulacion=$request->idSimulacion;
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
}