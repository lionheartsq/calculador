<?php

namespace App\Http\Controllers;

use App\Tb_perfil;
use App\Tb_proceso;
use App\Tb_area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_perfilController extends Controller
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

        if(!$request->ajax()) return redirect('/');
        $buscar= $request->buscar;
        $criterio= $request->criterio;

        if ($buscar=='') {
            $perfiles = Tb_perfil::join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->where('tb_proceso.idEmpresa','=',$idEmpresa)
            ->select('tb_perfil.id','tb_perfil.perfil','tb_perfil.valorMinuto','tb_perfil.estado','tb_proceso.id as idProceso','tb_proceso.proceso','tb_proceso.estado as estado_proceso','tb_area.id as idArea','tb_area.area','tb_area.estado as estado_area')
            ->orderBy('tb_perfil.id','desc')->paginate(5);
        }
        else if($criterio=='area'){
            $perfiles = Tb_perfil::join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->where('tb_proceso.idEmpresa','=',$idEmpresa)
            ->select('tb_perfil.id','tb_perfil.perfil','tb_perfil.valorMinuto','tb_perfil.estado','tb_proceso.id as idProceso','tb_proceso.proceso','tb_proceso.estado as estado_proceso','tb_area.id as idArea','tb_area.area','tb_area.estado as estado_area')
            ->where('tb_area.area', 'like', '%'. $buscar . '%')
            ->orderBy('tb_perfil.id','desc')->paginate(5);
        }
        else if($criterio=='proceso'){
            $perfiles = Tb_perfil::join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->where('tb_proceso.idEmpresa','=',$idEmpresa)
            ->select('tb_perfil.id','tb_perfil.perfil','tb_perfil.valorMinuto','tb_perfil.estado','tb_proceso.id as idProceso','tb_proceso.proceso','tb_proceso.estado as estado_proceso','tb_area.id as idArea','tb_area.area','tb_area.estado as estado_area')
            ->where('tb_proceso.proceso', 'like', '%'. $buscar . '%')
            ->orderBy('tb_perfil.id','desc')->paginate(5);
        }
        else {
            $perfiles = Tb_perfil::join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->where('tb_proceso.idEmpresa','=',$idEmpresa)
            ->select('tb_perfil.id','tb_perfil.perfil','tb_perfil.valorMinuto','tb_perfil.estado','tb_proceso.id as idProceso','tb_proceso.proceso','tb_proceso.estado as estado_proceso','tb_area.id as idArea','tb_area.area','tb_area.estado as estado_area')
            ->where('tb_perfil.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('tb_perfil.id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$perfiles->total(),
                'current_page'  =>$perfiles->currentPage(),
                'per_page'      =>$perfiles->perPage(),
                'last_page'     =>$perfiles->lastPage(),
                'from'          =>$perfiles->firstItem(),
                'to'            =>$perfiles->lastItem(),
            ],
                'perfiles' => $perfiles
        ];
    }

    public function selectRelacion($id){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $relaciones = tb_proceso::join("tb_area","tb_proceso.idArea","=","tb_area.id")
            ->where('tb_proceso.idEmpresa','=',$idEmpresa)
            ->where([
                    ['tb_area.estado','=','1'],
                    ['idArea','=','1'],
                ])
                ->select('tb_proceso.id as idProceso','proceso')
                ->orderBy('proceso','asc')->get();
                return ['relaciones' => $relaciones];
        }

    public function selectPerfil(){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $perfiles = tb_perfil::join("tb_proceso","tb_proceso.id","=","tb_perfil.idProceso")
        ->where('tb_proceso.idEmpresa','=',$idEmpresa)
        ->all();
        return ['perfiles' => $perfiles];
        }

    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_perfil=new Tb_perfil();
        $tb_perfil->perfil=$request->perfil;
        $tb_perfil->idProceso=$request->idProceso;
        $tb_perfil->valorMinuto=$request->valorMinuto;
        $tb_perfil->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_perfil=Tb_perfil::findOrFail($request->id);
        $tb_perfil->perfil=$request->perfil;
        $tb_perfil->idProceso=$request->idProceso;
        $tb_perfil->valorMinuto=$request->valorMinuto;
        $tb_perfil->estado='1';
        $tb_perfil->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_perfil=Tb_perfil::findOrFail($request->id);
        $tb_perfil->estado='0';
        $tb_perfil->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_perfil=Tb_perfil::findOrFail($request->id);
        $tb_perfil->estado='1';
        $tb_perfil->save();
    }

}
