<?php

namespace App\Http\Controllers;

use App\Tb_mano_de_obra_producto;
use App\Tb_proceso;
use App\Tb_perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_mano_de_obra_productoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $buscar= $request->buscar;
        $criterio= $request->criterio;
        $identificador= $request->identificador;

        if ($buscar=='') {
            # Modelo::join('tablaqueseune',basicamente un on)
            $manodeobraproductos = Tb_mano_de_obra_producto::join("tb_perfil","tb_mano_de_obra_producto.idPerfil","=","tb_perfil.id")
            ->join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->select('tb_mano_de_obra_producto.id','tb_mano_de_obra_producto.idPerfil','tb_mano_de_obra_producto.tiempo','tb_mano_de_obra_producto.precio',
            'tb_mano_de_obra_producto.tipoPago','tb_perfil.perfil','tb_perfil.idProceso','tb_perfil.valorMinuto','tb_proceso.proceso','tb_proceso.idArea','tb_area.area',
            'tb_mano_de_obra_producto.idHoja',DB::raw('ROUND((tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio),0) as subtotal'))
            ->where('tb_mano_de_obra_producto.idHoja', '=', $identificador)
            ->orderBy('tb_mano_de_obra_producto.id','desc')->paginate(5);
        }
        else if($criterio=='perfil'){
            $manodeobraproductos = Tb_mano_de_obra_producto::join("tb_perfil","tb_mano_de_obra_producto.idPerfil","=","tb_perfil.id")
            ->join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->select('tb_mano_de_obra_producto.id','tb_mano_de_obra_producto.idPerfil','tb_mano_de_obra_producto.tiempo','tb_mano_de_obra_producto.precio',
            'tb_mano_de_obra_producto.tipoPago','tb_perfil.perfil','tb_perfil.idProceso','tb_perfil.valorMinuto','tb_proceso.proceso','tb_proceso.idArea','tb_area.area',
            'tb_mano_de_obra_producto.idHoja',DB::raw('ROUND((tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio),0) as subtotal'))
            ->where([
                ['tb_perfil.perfil', 'like', '%'. $buscar . '%'],
                ['tb_mano_de_obra_producto.idHoja', '=', $identificador],
                     ])
            ->orderBy('tb_mano_de_obra_producto.id','desc')->paginate(5);
        }
        else {
            $manodeobraproductos = Tb_mano_de_obra_producto::join("tb_perfil","tb_mano_de_obra_producto.idPerfil","=","tb_perfil.id")
            ->join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->select('tb_mano_de_obra_producto.id','tb_mano_de_obra_producto.idPerfil','tb_mano_de_obra_producto.tiempo','tb_mano_de_obra_producto.precio',
            'tb_mano_de_obra_producto.tipoPago','tb_perfil.perfil','tb_perfil.idProceso','tb_perfil.valorMinuto','tb_proceso.proceso','tb_proceso.idArea','tb_area.area',
            'tb_mano_de_obra_producto.idHoja',DB::raw('ROUND((tb_mano_de_obra_producto.tiempo*tb_mano_de_obra_producto.precio),0) as subtotal'))
            ->where([
                ['tb_proceso.proceso', 'like', '%'. $buscar . '%'],
                ['tb_mano_de_obra_producto.idHoja', '=', $identificador],
                     ])
            ->orderBy('tb_mano_de_obra_producto.id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$manodeobraproductos->total(),
                'current_page'  =>$manodeobraproductos->currentPage(),
                'per_page'      =>$manodeobraproductos->perPage(),
                'last_page'     =>$manodeobraproductos->lastPage(),
                'from'          =>$manodeobraproductos->firstItem(),
                'to'            =>$manodeobraproductos->lastItem(),
            ],
                'manodeobraproductos' => $manodeobraproductos
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_mano_de_obra_producto=new Tb_mano_de_obra_producto();
        $tb_mano_de_obra_producto->idPerfil=$request->idPerfil;
        $tb_mano_de_obra_producto->tiempo=$request->tiempo;
        $tb_mano_de_obra_producto->precio=$request->precio;
        $tb_mano_de_obra_producto->tipoPago=$request->tipoPago;
        $tb_mano_de_obra_producto->idHoja=$request->idHoja;
        $tb_mano_de_obra_producto->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_mano_de_obra_producto=Tb_mano_de_obra_producto::findOrFail($request->id);
        $tb_mano_de_obra_producto->tiempo=$request->tiempo;
        $tb_mano_de_obra_producto->precio=$request->precio;
        $tb_mano_de_obra_producto->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tb_mano_de_obra_producto=Tb_mano_de_obra_producto::findOrFail($request->id);
        $tb_mano_de_obra_producto->delete();
       DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function selectRelacionPerfil($id){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $perfilrelaciones = tb_perfil::join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
                ->join("tb_area","tb_proceso.idArea","=","tb_area.id")
                ->where('tb_area.idEmpresa','=',$idEmpresa)
                ->where([
                    ['tb_perfil.estado','=','1'],
                    ['tb_perfil.idProceso','=',$id],
                ])
                ->select('tb_perfil.id as idPerfil','tb_perfil.perfil','tb_perfil.valorMinuto as valor')
                ->orderBy('perfil','asc')->get();
                return ['perfilrelaciones' => $perfilrelaciones];
        }

    public function valorMinuto($id){
            $valor = 0;

            //valorMinuto Perfil
            $valores = tb_perfil::where([
                ['estado','=','1'],
                ['id','=',$id],
            ])
            ->select('valorMinuto')->get();

            foreach($valores as $totalg){
                $valor = $totalg->valorMinuto + $valor;
            }

            $valorminutos = $valor;
            return ['valorminutos' => $valorminutos];
        }

}
