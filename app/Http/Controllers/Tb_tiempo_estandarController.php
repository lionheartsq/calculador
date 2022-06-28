<?php

namespace App\Http\Controllers;

use App\Tb_empleado;
use App\Tb_pds;
use App\Tb_westing_house;
use App\Tb_perfil;
use App\Tb_proceso;
use App\Tb_tiempo_estandar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_tiempo_estandarController extends Controller
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

         //if(!$request->ajax()) return redirect('/');
         $buscar= $request->buscar;
         $criterio= $request->criterio;

         if ($buscar=='') {
             # Modelo::join('tablaqueseune',basicamente un on)
             $tiempoestandar = Tb_tiempo_estandar::join('tb_empleado','tb_tiempo_estandar.idEmpleado','=','tb_empleado.id')
             ->join('tb_perfil','tb_empleado.idPerfil','=','tb_perfil.id')
             ->where('tb_tiempo_estandar.idEmpresa','=',$idEmpresa)
             ->select('tb_tiempo_estandar.id','tb_tiempo_estandar.fecha','tb_tiempo_estandar.idEmpleado','tb_tiempo_estandar.estado',
             DB::raw('CONCAT(tb_empleado.nombre," ",tb_empleado.apellido," - ",tb_empleado.documento) as nombreEmpleado'))
             ->orderBy('tb_tiempo_estandar.id','desc')->paginate(5);
         }
         else {
            $tiempoestandar = Tb_tiempo_estandar::join('tb_empleado','tb_tiempo_estandar.idEmpleado','=','tb_empleado.id')
             ->join('tb_perfil','tb_empleado.idPerfil','=','tb_perfil.id')
             ->where('tb_tiempo_estandar.idEmpresa','=',$idEmpresa)
             ->select('tb_tiempo_estandar.id','tb_tiempo_estandar.fecha','tb_tiempo_estandar.idEmpleado','tb_tiempo_estandar.estado',
             DB::raw('CONCAT(tb_empleado.nombre," ",tb_empleado.apellido," - ",tb_empleado.documento) as nombreEmpleado'))
            ->where('tb_tiempo_estandar.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('tb_tiempo_estandar.id','desc')->paginate(5);
         }
         return [
             'pagination' => [
                 'total'         =>$tiempoestandar->total(),
                 'current_page'  =>$tiempoestandar->currentPage(),
                 'per_page'      =>$tiempoestandar->perPage(),
                 'last_page'     =>$tiempoestandar->lastPage(),
                 'from'          =>$tiempoestandar->firstItem(),
                 'to'            =>$tiempoestandar->lastItem(),
             ],
                 'tiempoestandar' => $tiempoestandar
         ];
     }

     public function store(Request $request)
     {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

         //if(!$request->ajax()) return redirect('/');
         $tb_tiempo_estandar=new Tb_tiempo_estandar();
         $tb_tiempo_estandar->fecha=$request->fecha;
         $tb_tiempo_estandar->idEmpleado=$request->idEmpleado;
         $tb_tiempo_estandar->tiempoElemental=0;
         $tb_tiempo_estandar->tiempoNormal=0;
         $tb_tiempo_estandar->factorPds=0;
         $tb_tiempo_estandar->tiempoEstandar=0;
         $tb_tiempo_estandar->numeroPiezas=0;
         $tb_tiempo_estandar->piezasPar=$request->piezasPar;
         $tb_tiempo_estandar->tiempoPiezas=0;
         $tb_tiempo_estandar->factorValoracion=0;
         $tb_tiempo_estandar->estado=1;
         $tb_tiempo_estandar->idEmpresa=$idEmpresa;
         $tb_tiempo_estandar->save();

         $tb_westing_house = new Tb_westing_house();
         $tb_westing_house->idHabilidad=1;
         $tb_westing_house->idEsfuerzo=1;
         $tb_westing_house->idCondiciones=1;
         $tb_westing_house->idConsistencia=1;
         $tb_westing_house->idTiempoEstandar=$tb_tiempo_estandar->id;
         $tb_westing_house->save();

         $tb_pds = new Tb_pds();
         $tb_pds->idEsfuerzoMental=1;
         $tb_pds->idEsfuerzoFisico=1;
         $tb_pds->idSuplementario=1;
         $tb_pds->idPersonales=1;
         $tb_pds->idMonotonia=1;
         $tb_pds->idEspera=1;
         $tb_pds->valorEspera=0;
         $tb_pds->idTiempoEstandar=$tb_tiempo_estandar->id;
         $tb_pds->save();
     }
     public function empleados()
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $empleados = Tb_empleado::where('tb_empleado.idEmpresa','=',$idEmpresa)
        ->select('tb_empleado.id','tb_empleado.nombre','tb_empleado.apellido',
        'tb_empleado.documento',
        DB::raw('CONCAT(tb_empleado.nombre," ",tb_empleado.apellido," - ",tb_empleado.documento) as nombreEmpleado'))
        ->get();
        return ['empleados' => $empleados];
    }
     public function estado(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        $idTiempoEstandar= $request->id;
        $tb_tiempo_estandar=Tb_tiempo_estandar::findOrFail($request->id);
        $tb_tiempo_estandar->estado=0;
        $tb_tiempo_estandar->save();
    }
}
