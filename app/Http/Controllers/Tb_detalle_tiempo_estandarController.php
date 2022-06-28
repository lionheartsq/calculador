<?php

namespace App\Http\Controllers;

use App\Tb_tiempo_estandar;
use App\Tb_empleado;
use App\Tb_perfil;
use App\Tb_proceso;
use App\Tb_ciclos;
use App\Tb_westing_house;
use App\Tb_calificacion_habilidades;
use App\Tb_calificacion_esfuerzo;
use App\Tb_calificacion_condiciones;
use App\Tb_calificacion_consistencia;
use App\Tb_pds;
use App\Tb_esfuerzo_mental;
use App\Tb_esfuerzo_fisico;
use App\Tb_suplementarios;
use App\Tb_necesidades_personales;
use App\Tb_monotonia;
use App\Tb_espera;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Storage;


class Tb_detalle_tiempo_estandarController extends Controller
{
    // Aca comienza la tabla de ciclos
    public function listarciclos(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $id=$request->id;
        $ciclos = Tb_ciclos::join('tb_tiempo_estandar','tb_ciclos.idTiempoEstandar','=','tb_tiempo_estandar.id')
        ->select('tb_ciclos.tiempo','tb_ciclos.piezas','tb_ciclos.idTiempoEstandar')
        ->where('tb_ciclos.idTiempoEstandar','=',$id)
        ->orderBy('tb_ciclos.id','desc')
        ->paginate(5);

        return [
            'pagination' => [
                'total'         =>$ciclos->total(),
                'current_page'  =>$ciclos->currentPage(),
                'per_page'      =>$ciclos->perPage(),
                'last_page'     =>$ciclos->lastPage(),
                'from'          =>$ciclos->firstItem(),
                'to'            =>$ciclos->lastItem(),
            ],
            'ciclos' =>  $ciclos
        ];
    }
    public function guardarciclos(Request $request)
     {
         //if(!$request->ajax()) return redirect('/');

         //\Log::debug(var_dump($request));
         $tb_ciclos=new Tb_ciclos();
         $tb_ciclos->tiempo=$request->tiempo;
         $tb_ciclos->piezas=$request->piezas;
         $tb_ciclos->idTiempoEstandar=$request->idTiempoEstandar;
         $tb_ciclos->save();

         $piezas = DB::table('tb_ciclos')
         ->where('tb_ciclos.idTiempoEstandar','=',$request->idTiempoEstandar)
         ->avg('piezas');

         $tiempos = DB::table('tb_ciclos')
         ->where('tb_ciclos.idTiempoEstandar','=',$request->idTiempoEstandar)
         ->avg('tiempo');

        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

         $tiempo_estandar= Tb_tiempo_estandar::find($request->idTiempoEstandar);
         /*Storage::put('file.txt', 'tiempoElemental:'.$tiempo_estandar->tiempoElemental.',numPiezas:'.$tiempo_estandar->numeroPiezas.
         ',piezasPar:'.$tiempo_estandar->piezasPar);*/
         $tiempo_estandar->numeroPiezas=intval($piezas);
         $tiempo_estandar->tiempoElemental=floatval($tiempos);
         $tiempoPiezas=$tiempo_estandar->tiempoPiezas;
         if($tiempo_estandar->numeroPiezas != 0){
            $tiempoPiezas=(($tiempo_estandar->tiempoElemental/60)/$tiempo_estandar->numeroPiezas)*$tiempo_estandar->piezasPar;
         }
         else{
            $tiempoPiezas = 1;
         }
         $tiempo_estandar->tiempoPiezas=$tiempoPiezas;
         $tiempo_estandar->idEmpresa=$idEmpresa;
         $tiempo_estandar->save();
     }
    // Aca termina la tabla de ciclos

     // Aca comienza las westing house

     public function listarwesting(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $id=$request->id;
        $westinghouse = Tb_westing_house::where('idTiempoEstandar','=',$id)
        ->orderBy('tb_westing_house.id','asc')
        ->paginate(5);
        //\Log::debug(var_dump($westinghouse));
        return ['westinghouse' =>  $westinghouse];
    }

    public function habilidad()
    {
        $habilidades = Tb_calificacion_habilidades::select('tb_calificacion_habilidades.id','tb_calificacion_habilidades.rango','tb_calificacion_habilidades.porcentaje',
        'tb_calificacion_habilidades.clasificacion',
        DB::raw('(tb_calificacion_habilidades.rango) as nombreHabilidad'))
        ->get();
        return ['habilidades' => $habilidades];
    }
    public function esfuerzo()
    {
        $esfuerzo = Tb_calificacion_esfuerzo::select('tb_calificacion_esfuerzo.id','tb_calificacion_esfuerzo.rango','tb_calificacion_esfuerzo.porcentaje',
        'tb_calificacion_esfuerzo.clasificacion',
        DB::raw('(tb_calificacion_esfuerzo.rango) as nombreEsfuerzo'))
        ->get();
        return ['esfuerzo' => $esfuerzo];
    }
    public function condiciones()
    {
        $condiciones = Tb_calificacion_condiciones::select('tb_calificacion_condiciones.id','tb_calificacion_condiciones.rango','tb_calificacion_condiciones.porcentaje',
        'tb_calificacion_condiciones.clasificacion',
        DB::raw('(tb_calificacion_condiciones.rango) as nombreCondicion'))
        ->get();
        return ['condiciones' => $condiciones];
    }
    public function consistencia()
    {
        $consistencia = Tb_calificacion_consistencia::select('tb_calificacion_consistencia.id','tb_calificacion_consistencia.rango','tb_calificacion_consistencia.porcentaje',
        'tb_calificacion_consistencia.clasificacion',
        DB::raw('(tb_calificacion_consistencia.rango) as nombreConsistencia'))
        ->get();
        return ['consistencia' => $consistencia];
    }
    public function guardarWestingHouse(Request $request)
     {
         //if(!$request->ajax()) return redirect('/');
         $tb_westing_house=Tb_westing_house::findOrFail($request->id);
         $tb_westing_house->idHabilidad=$request->idHabilidad;
         $tb_westing_house->idEsfuerzo=$request->idEsfuerzo;
         $tb_westing_house->idCondiciones=$request->idCondiciones;
         $tb_westing_house->idConsistencia=$request->idConsistencia;
         $tb_westing_house->idTiempoEstandar=$request->idTiempoEstandar;
         $tb_westing_house->save();

         $habilidad=Tb_calificacion_habilidades::findOrFail($request->idHabilidad);
         $esfuerzos=Tb_calificacion_esfuerzo::findOrFail($request->idEsfuerzo);
         $condicion=Tb_calificacion_condiciones::findOrFail($request->idCondiciones);
         $consistencias=Tb_calificacion_consistencia::findOrFail($request->idConsistencia);

         $factor=1+$habilidad->porcentaje+$esfuerzos->porcentaje+$condicion->porcentaje+$consistencias->porcentaje;
         $tiempo_estandar=Tb_tiempo_estandar::findOrFail($request->idTiempoEstandar);
         $tiempo_estandar->factorValoracion=$factor;
         $tiempo_estandar->tiempoNormal=$tiempo_estandar->tiempoPiezas*$factor;
         $tiempo_estandar->save();
     }
     // Aca termina las westing house

     // Aca comienza las pds
     public function listarpds(Request $request)
     {
         //if(!$request->ajax()) return redirect('/');
         $id=$request->id;
         $pds = Tb_pds::where('idTiempoEstandar','=',$id)
         ->orderBy('tb_pds.id','asc')
         ->paginate(5);
         //\Log::debug(var_dump($pds));
         return ['pds' =>  $pds];
     }

     public function esfuerzomental()
    {
        $esfuerzomental = Tb_esfuerzo_mental::select('tb_esfuerzo_mental.id','tb_esfuerzo_mental.grado','tb_esfuerzo_mental.porcentaje',
        DB::raw('(tb_esfuerzo_mental.grado) as nombreEsfuerzoMental'))
        ->get();
        return ['esfuerzomental' => $esfuerzomental];
    }
     public function esfuerzofisico()
     {
         $esfuerzofisico = Tb_esfuerzo_fisico::select('tb_esfuerzo_fisico.id','tb_esfuerzo_fisico.grado','tb_esfuerzo_fisico.porcentaje',
         DB::raw('(tb_esfuerzo_fisico.grado) as nombreEsfuerzoFisico'))
         ->get();
         return ['esfuerzofisico' => $esfuerzofisico];
     }
    public function suplementarios()
    {
        $suplementarios = Tb_suplementarios::select('tb_suplementarios.id','tb_suplementarios.grado','tb_suplementarios.porcentaje',
        DB::raw('(tb_suplementarios.grado) as nombreSuplementario'))
        ->get();
        return ['suplementarios' => $suplementarios];
    }
    public function personales()
    {
        $personales = Tb_necesidades_personales::select('tb_necesidades_personales.id','tb_necesidades_personales.rango','tb_necesidades_personales.porcentajeHombre',
        'tb_necesidades_personales.porcentajeMujer',
        DB::raw('(tb_necesidades_personales.rango) as nombrePersonales'))
        ->get();
        return ['personales' => $personales];
    }
    public function espera()
    {
        $espera = Tb_espera::select('tb_espera.id','tb_espera.rangoMin','tb_espera.rangoMax',
        'tb_espera.factor',
        DB::raw('(tb_espera.factor) as nombreEspera'))
        ->get();
        return ['espera' => $espera];
    }

    public function guardarPds(Request $request)
     {
         //if(!$request->ajax()) return redirect('/');
         $tb_pds=Tb_pds::findOrFail($request->id);
         $tb_pds->idEsfuerzoMental=$request->idEsfuerzoMental;
         $tb_pds->idEsfuerzoFisico=$request->idEsfuerzoFisico;
         $tb_pds->idSuplementario=$request->idSuplementario;
         $tb_pds->idPersonales=$request->idPersonales;
         $tb_pds->idMonotonia=1;
         $tb_pds->idEspera=1;
         $tb_pds->valorEspera=$request->valorEspera;
         $tb_pds->idTiempoEstandar=$request->idTiempoEstandar;
         $tb_pds->save();

         $tiempo_estandar= Tb_tiempo_estandar::find($request->idTiempoEstandar);
         $valorEspera=intval(($request->valorEspera/$tiempo_estandar->tiempoElemental)*100);
         $esperap = Tb_espera::select('tb_espera.id')
         ->where('rangoMin', '<=', $valorEspera)
         ->where('rangoMax', '>=', $valorEspera)
         ->first();
         $tb_pds->idEspera=$esperap->id;
         $tb_pds->save();

         $valor=$tiempo_estandar->tiempoPiezas*$tiempo_estandar->factorValoracion;
         if ($valor>16) {
             $valor=16;
         }
         $monotonia = Tb_monotonia::select('tb_monotonia.id')
         ->where('rangoMin', '<=', $valor)
         ->where('rangoMax', '>=', $valor)
         ->first();
         $tb_pds->idMonotonia=$monotonia->id;
         $tb_pds->save();

         //tiempo espera/tiempo ciclos= *100

         $esfuerzom=Tb_esfuerzo_mental::findOrFail($request->idEsfuerzoMental);
         $esfuerzof=Tb_esfuerzo_fisico::findOrFail($request->idEsfuerzoFisico);
         $esperas=Tb_espera::findOrFail($tb_pds->idEspera);
         $empleado = Tb_empleado::findOrFail($tiempo_estandar->idEmpleado);
         $personal=Tb_necesidades_personales::findOrFail($request->idPersonales);
         $suplementario=Tb_suplementarios::findOrFail($request->idSuplementario);
         $monoto=Tb_monotonia::findOrFail($request->idMonotonia);

         //= ( ( (em+ef) * espera) + (personales) + (suplementarios)+ (monotonia) )/1+1
         /*Storage::put('file.txt', 'em:'.$esfuerzom->porcentaje.',ef:'.$esfuerzof->porcentaje.',esp:'.$esperas->factor.',pers:'.
         $personal->porcentajeHombre.',supl:'.$suplementario->porcentaje.',monot:'.$monoto->porcentaje);*/
         $factorPds=(( ((($esfuerzom->porcentaje/100.0)+($esfuerzof->porcentaje/100.0)) * $esperas->factor) +
         (($empleado->genero==1)?($personal->porcentajeHombre/100.0):($personal->porcentajeMujer/100.0)) +
         ($suplementario->porcentaje/100.0) + ($monoto->porcentaje/100.0))/1)+1;
         $tiempo_estandar=Tb_tiempo_estandar::findOrFail($request->idTiempoEstandar);
         $tiempo_estandar->factorPds=$factorPds;
         $tiempo_estandar->tiempoEstandar=$tiempo_estandar->tiempoNormal*$factorPds;
         $tiempo_estandar->save();
     }
     // Aca termina las pds

     // Aca comienza el detalle del tiempo estandar
    public function listardetalle(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $id=$request->id;
        $detalles = Tb_tiempo_estandar::join('tb_empleado','tb_tiempo_estandar.idEmpleado','=','tb_empleado.id')
        ->join('tb_perfil','tb_empleado.idPerfil','=','tb_perfil.id')
        ->join('tb_proceso','tb_perfil.idProceso','=','tb_proceso.id')
        ->where('tb_tiempo_estandar.idEmpresa','=',$idEmpresa)
        ->select('tb_tiempo_estandar.fecha','tb_tiempo_estandar.idEmpleado','tb_tiempo_estandar.tiempoElemental',
        'tb_tiempo_estandar.tiempoNormal','tb_tiempo_estandar.factorPds','tb_tiempo_estandar.tiempoEstandar','tb_tiempo_estandar.numeroPiezas',
        'tb_tiempo_estandar.piezasPar','tb_tiempo_estandar.tiempoPiezas','tb_tiempo_estandar.factorValoracion','tb_empleado.nombre as idEmpleado',
        'tb_empleado.genero','tb_perfil.perfil','tb_proceso.proceso')
        ->where('tb_tiempo_estandar.id','=',$id)
        ->orderBy('tb_tiempo_estandar.idEmpleado','asc')
        ->paginate(5);

        return [
            'pagination' => [
                'total'         =>$detalles->total(),
                'current_page'  =>$detalles->currentPage(),
                'per_page'      =>$detalles->perPage(),
                'last_page'     =>$detalles->lastPage(),
                'from'          =>$detalles->firstItem(),
                'to'            =>$detalles->lastItem(),
            ],
            'detalles' =>  $detalles
        ];
    }
    // Aca termina el detalle del tiempo estandar
}
