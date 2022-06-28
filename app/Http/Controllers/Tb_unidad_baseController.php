<?php

namespace App\Http\Controllers;

use App\Tb_unidad_base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_unidad_baseController extends Controller
{
    public function index(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $buscar= $request->buscar;
        $criterio= $request->criterio;

        if ($buscar=='') {
            $unidades = Tb_unidad_base::where('tb_unidad_base.idEmpresa','=',$idEmpresa)
            ->orderBy('id','desc')->paginate(5);
        }
        else {
            $unidades = Tb_unidad_base::where('tb_unidad_base.idEmpresa','=',$idEmpresa)
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$unidades->total(),
                'current_page'  =>$unidades->currentPage(),
                'per_page'      =>$unidades->perPage(),
                'last_page'     =>$unidades->lastPage(),
                'from'          =>$unidades->firstItem(),
                'to'            =>$unidades->lastItem(),
            ],
                'unidades' => $unidades
        ];
    }

    public function selectUnidad(){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $unidades = Tb_unidad_base::where('tb_unidad_base.idEmpresa','=',$idEmpresa)
        ->where('estado','=','1')
        ->select('id as idUnidadBase','unidadBase')->orderBy('unidadBase','asc')->get();

        return ['unidades' => $unidades];
    }

    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $tb_unidad_base=new Tb_unidad_base();
        $tb_unidad_base->unidadBase=$request->unidadBase;
        $tb_unidad_base->idEmpresa=$idEmpresa;
        $tb_unidad_base->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_unidad_base=Tb_unidad_base::findOrFail($request->id);
        $tb_unidad_base->unidadBase=$request->unidadBase;
        $tb_unidad_base->estado='1';
        $tb_unidad_base->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_unidad_base=Tb_unidad_base::findOrFail($request->id);
        $tb_unidad_base->estado='0';
        $tb_unidad_base->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_unidad_base=Tb_unidad_base::findOrFail($request->id);
        $tb_unidad_base->estado='1';
        $tb_unidad_base->save();
    }
}
