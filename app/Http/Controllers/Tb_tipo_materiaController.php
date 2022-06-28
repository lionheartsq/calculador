<?php

namespace App\Http\Controllers;

use App\Tb_tipo_materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_tipo_materiaController extends Controller
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
            $materias = Tb_tipo_materia::orderBy('id','desc')
            ->where('tb_tipo_materia.idEmpresa','=',$idEmpresa)
            ->paginate(5);
        }
        else {
            $materias = Tb_tipo_materia::where('tb_tipo_materia.idEmpresa','=',$idEmpresa)
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$materias->total(),
                'current_page'  =>$materias->currentPage(),
                'per_page'      =>$materias->perPage(),
                'last_page'     =>$materias->lastPage(),
                'from'          =>$materias->firstItem(),
                'to'            =>$materias->lastItem(),
            ],
                'materias' => $materias
        ];
    }

    public function selectMateria(Request $request){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $materias = Tb_tipo_materia::where('tb_tipo_materia.idEmpresa','=',$idEmpresa)
        ->where('estado','=','1')
        ->select('id','tipoMateria')->orderBy('tipoMateria','asc')->get();

        return ['materias' => $materias];
    }

    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $tb_tipo_materia=new Tb_tipo_materia();
        $tb_tipo_materia->tipoMateria=$request->tipoMateria;
        $tb_tipo_materia->idEmpresa=$idEmpresa;
        $tb_tipo_materia->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_tipo_materia=Tb_tipo_materia::findOrFail($request->id);
        $tb_tipo_materia->tipoMateria=$request->tipoMateria;
        $tb_tipo_materia->estado='1';
        $tb_tipo_materia->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_tipo_materia=Tb_tipo_materia::findOrFail($request->id);
        $tb_tipo_materia->estado='0';
        $tb_tipo_materia->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_tipo_materia=Tb_tipo_materia::findOrFail($request->id);
        $tb_tipo_materia->estado='1';
        $tb_tipo_materia->save();
    }
}
