<?php

namespace App\Http\Controllers;

use App\Tb_maquinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_maquinariaController extends Controller
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
            $maquinarias = Tb_maquinaria::orderBy('id','desc')
            ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
            ->paginate(5);
        }
        else {
            $maquinarias = Tb_maquinaria::where($criterio, 'like', '%'. $buscar . '%')
            ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
            ->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$maquinarias->total(),
                'current_page'  =>$maquinarias->currentPage(),
                'per_page'      =>$maquinarias->perPage(),
                'last_page'     =>$maquinarias->lastPage(),
                'from'          =>$maquinarias->firstItem(),
                'to'            =>$maquinarias->lastItem(),
            ],
                'maquinarias' => $maquinarias
        ];
    }

    public function selectMaquinaria(){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $maquinarias = Tb_maquinaria::where('estado','=','1')
        ->where('tb_maquinaria.idEmpresa','=',$idEmpresa)
        ->select('id as idMaquinaria','maquinaria')->orderBy('maquinaria','asc')->get();

        return ['maquinarias' => $maquinarias];
    }

    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $tb_maquinaria=new Tb_maquinaria();
        $tb_maquinaria->maquinaria=$request->maquinaria;
        $tb_maquinaria->valor=$request->valor;
        $tb_maquinaria->tiempoDeVidaUtil=$request->tiempoDeVidaUtil;
        $tb_maquinaria->depreciacionAnual=$request->depreciacionAnual;
        $tb_maquinaria->depreciacionMensual=$request->depreciacionMensual;
        $tb_maquinaria->fecha=$request->fecha;
        $tb_maquinaria->idEmpresa=$idEmpresa;
        $tb_maquinaria->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_maquinaria=Tb_maquinaria::findOrFail($request->id);
        $tb_maquinaria->maquinaria=$request->maquinaria;
        $tb_maquinaria->valor=$request->valor;
        $tb_maquinaria->tiempoDeVidaUtil=$request->tiempoDeVidaUtil;
        $tb_maquinaria->depreciacionAnual=$request->depreciacionAnual;
        $tb_maquinaria->depreciacionMensual=$request->depreciacionMensual;
        $tb_maquinaria->fecha=$request->fecha;
        $tb_maquinaria->estado='1';
        $tb_maquinaria->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_maquinaria=Tb_maquinaria::findOrFail($request->id);
        $tb_maquinaria->estado='0';
        $tb_maquinaria->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_maquinaria=Tb_maquinaria::findOrFail($request->id);
        $tb_maquinaria->estado='1';
        $tb_maquinaria->save();
    }
}
