<?php

namespace App\Http\Controllers;

use App\Tb_coleccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_coleccionController extends Controller
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
            $colecciones = Tb_coleccion::orderBy('id','desc')
            ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
            ->paginate(5);
        }
        else {
            $colecciones = Tb_coleccion::where($criterio, 'like', '%'. $buscar . '%')
            ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
            ->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$colecciones->total(),
                'current_page'  =>$colecciones->currentPage(),
                'per_page'      =>$colecciones->perPage(),
                'last_page'     =>$colecciones->lastPage(),
                'from'          =>$colecciones->firstItem(),
                'to'            =>$colecciones->lastItem(),
            ],
                'colecciones' => $colecciones
        ];
    }

    public function selectColeccion(){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $colecciones = Tb_coleccion::where('estado','=','1')
        ->where('tb_coleccion.idEmpresa','=',$idEmpresa)
        ->select('id as idColeccion','coleccion')->orderBy('coleccion','asc')->get();

        return ['colecciones' => $colecciones];
    }

    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $tb_coleccion=new Tb_coleccion();
        $tb_coleccion->coleccion=$request->coleccion;
        $tb_coleccion->referencia=$request->referencia;
        $tb_coleccion->idEmpresa=$idEmpresa;
        $tb_coleccion->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_coleccion=Tb_coleccion::findOrFail($request->id);
        $tb_coleccion->coleccion=$request->coleccion;
        $tb_coleccion->referencia=$request->referencia;
        $tb_coleccion->estado='1';
        $tb_coleccion->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_coleccion=Tb_coleccion::findOrFail($request->id);
        $tb_coleccion->estado='0';
        $tb_coleccion->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_coleccion=Tb_coleccion::findOrFail($request->id);
        $tb_coleccion->estado='1';
        $tb_coleccion->save();
    }
}
