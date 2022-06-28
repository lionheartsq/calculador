<?php

namespace App\Http\Controllers;

use App\Tb_area;
use App\Tb_proceso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_procesoController extends Controller
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
            # Modelo::join('tablaqueseune',basicamente un on)
            $procesos = Tb_proceso::join('tb_area','tb_proceso.idArea','=','tb_area.id')
            ->where('tb_area.idEmpresa','=',$idEmpresa)
            ->select('tb_proceso.id','tb_proceso.proceso','tb_proceso.estado','tb_area.id as idArea','tb_area.area','tb_area.estado as estado_area')
            ->orderBy('tb_proceso.id','desc')->paginate(5);
        }
        else if($criterio=='area'){
            $procesos = Tb_proceso::join('tb_area','tb_proceso.idArea','=','tb_area.id')
            ->where('tb_area.idEmpresa','=',$idEmpresa)
            ->select('tb_proceso.id','tb_proceso.proceso','tb_proceso.estado','tb_area.id as idArea','tb_area.area','tb_area.estado as estado_area')
            ->where('tb_area.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('tb_proceso.id','desc')->paginate(5);
        }
        else {
            # code...
            $procesos = Tb_proceso::join('tb_area','tb_proceso.idArea','=','tb_area.id')
            ->where('tb_area.idEmpresa','=',$idEmpresa)
            ->select('tb_proceso.id','tb_proceso.proceso','tb_proceso.estado','tb_area.id as idArea','tb_area.area','tb_area.estado as estado_area')
            ->where('tb_proceso.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('tb_proceso.id','desc')->paginate(5);

        }

        return [
            'pagination' => [
                'total'         =>$procesos->total(),
                'current_page'  =>$procesos->currentPage(),
                'per_page'      =>$procesos->perPage(),
                'last_page'     =>$procesos->lastPage(),
                'from'          =>$procesos->firstItem(),
                'to'            =>$procesos->lastItem(),
            ],
                'procesos' => $procesos
        ];
    }

    public function selectProceso(){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $procesos = Tb_proceso::where('tb_procesos.idEmpresa','=',$idEmpresa)
        ->where('tb_proceso.estado','=','1')
        ->select('id as idProceso','proceso')->orderBy('proceso','asc')->get();

        return ['procesos' => $procesos];
    }

    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $tb_proceso=new Tb_proceso();
        $tb_proceso->proceso=$request->proceso;
        $tb_proceso->idArea=$request->idArea;
        $tb_proceso->idEmpresa=$idEmpresa;
        $tb_proceso->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_proceso=Tb_proceso::findOrFail($request->id);
        $tb_proceso->proceso=$request->proceso;
        $tb_proceso->idArea='1';
        $tb_proceso->estado='1';
        $tb_proceso->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_proceso=Tb_proceso::findOrFail($request->id);
        $tb_proceso->estado='0';
        $tb_proceso->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_proceso=Tb_proceso::findOrFail($request->id);
        $tb_proceso->estado='1';
        $tb_proceso->save();
    }

}
