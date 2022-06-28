<?php

namespace App\Http\Controllers;

use App\Tb_empleado;
use App\Tb_eps;
use App\Tb_administradora_pensiones;
use App\Tb_niveles_riesgo;
use App\Tb_nomina;
use App\Tb_novedades;
use App\Tb_vinculaciones;
use App\Tb_factores_nomina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class Tb_novedadesController extends Controller
{
    //
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
        $identificador= $request->identificador;

        if ($buscar=='') {
            $novedades = Tb_novedades::join("tb_empleado","tb_novedades.idEmpleado","=","tb_empleado.id")
            ->join("tb_nomina","tb_novedades.idNomina","=","tb_nomina.id")
            ->where('tb_novedades.idEmpresa','=',$idEmpresa)
            ->select('tb_novedades.id','tb_novedades.fechaNovedad','tb_novedades.concepto','tb_novedades.valor','tb_novedades.cantidad','tb_novedades.unitario',
            'tb_novedades.observacion','tb_novedades.tipologia','tb_novedades.idEmpleado','tb_novedades.idNomina','tb_nomina.fechaInicio','tb_nomina.fechaFin',
            'tb_nomina.estado',DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
            ->orderBy('tb_novedades.id','desc')
            ->where('tb_nomina.estado','=','1')->paginate(5);
        }
        else {
            $novedades = Tb_novedades::join("tb_empleado","tb_novedades.idEmpleado","=","tb_empleado.id")
            ->join("tb_nomina","tb_novedades.idNomina","=","tb_nomina.id")
            ->where('tb_novedades.idEmpresa','=',$idEmpresa)
            ->select('tb_novedades.id','tb_novedades.fechaNovedad','tb_novedades.concepto','tb_novedades.valor','tb_novedades.cantidad','tb_novedades.unitario',
            'tb_novedades.observacion','tb_novedades.tipologia','tb_novedades.idEmpleado','tb_novedades.idNomina','tb_nomina.fechaInicio','tb_nomina.fechaFin',
            'tb_nomina.estado',DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
            ->orderBy('tb_novedades.id','desc')
            ->where('tb_nomina.estado','=','1')
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$novedades->total(),
                'current_page'  =>$novedades->currentPage(),
                'per_page'      =>$novedades->perPage(),
                'last_page'     =>$novedades->lastPage(),
                'from'          =>$novedades->firstItem(),
                'to'            =>$novedades->lastItem(),
            ],
                'novedades' => $novedades
        ];
    }
    public function index2(Request $request)
    {
    //cambios multiempresa
    foreach (Auth::user()->empresas as $empresa){
        $idEmpresa=$empresa['id'];
     }
    //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $buscar= $request->buscar;
        $criterio= $request->criterio;
        $identificador= $request->identificador;

        if ($buscar=='') {
            $novedades = Tb_novedades::join("tb_empleado","tb_novedades.idEmpleado","=","tb_empleado.id")
            ->join("tb_nomina","tb_novedades.idNomina","=","tb_nomina.id")
            ->where('tb_novedades.idEmpresa','=',$idEmpresa)
            ->select('tb_novedades.id','tb_novedades.fechaNovedad','tb_novedades.concepto','tb_novedades.valor','tb_novedades.cantidad','tb_novedades.unitario',
            'tb_novedades.observacion','tb_novedades.tipologia','tb_novedades.idEmpleado','tb_novedades.idNomina','tb_nomina.fechaInicio','tb_nomina.fechaFin',
            'tb_nomina.estado',DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
            ->orderBy('tb_novedades.id','desc')
            ->where('tb_nomina.id','=',$identificador)->paginate(5);
        }
        else {
            $novedades = Tb_novedades::join("tb_empleado","tb_novedades.idEmpleado","=","tb_empleado.id")
            ->join("tb_nomina","tb_novedades.idNomina","=","tb_nomina.id")
            ->where('tb_novedades.idEmpresa','=',$idEmpresa)
            ->select('tb_novedades.id','tb_novedades.fechaNovedad','tb_novedades.concepto','tb_novedades.valor','tb_novedades.cantidad','tb_novedades.unitario',
            'tb_novedades.observacion','tb_novedades.tipologia','tb_novedades.idEmpleado','tb_novedades.idNomina','tb_nomina.fechaInicio','tb_nomina.fechaFin',
            'tb_nomina.estado',DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
            ->orderBy('tb_novedades.id','desc')
            ->where('tb_nomina.id','=',$identificador)
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$novedades->total(),
                'current_page'  =>$novedades->currentPage(),
                'per_page'      =>$novedades->perPage(),
                'last_page'     =>$novedades->lastPage(),
                'from'          =>$novedades->firstItem(),
                'to'            =>$novedades->lastItem(),
            ],
                'novedades' => $novedades
        ];
    }
    public function detallado(Request $request)
    {
    //cambios multiempresa
    foreach (Auth::user()->empresas as $empresa){
        $idEmpresa=$empresa['id'];
     }
    //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $idEmpleado= $request->idEmpleado;
        $idNomina= $request->idNomina;

            $novedades = Tb_novedades::join("tb_empleado","tb_novedades.idEmpleado","=","tb_empleado.id")
            ->join("tb_nomina","tb_novedades.idNomina","=","tb_nomina.id")
            ->where('tb_novedades.idEmpresa','=',$idEmpresa)
            ->select('tb_novedades.id','tb_novedades.fechaNovedad','tb_novedades.concepto','tb_novedades.valor','tb_novedades.cantidad','tb_novedades.unitario',
            'tb_novedades.observacion','tb_novedades.tipologia','tb_novedades.idEmpleado','tb_novedades.idNomina','tb_nomina.fechaInicio','tb_nomina.fechaFin',
            'tb_nomina.estado',DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
            ->orderBy('tb_novedades.id','asc')
            ->where('tb_novedades.idNomina','=',$idNomina)
            ->where('tb_novedades.idEmpleado','=',$idEmpleado)->get();

        return [
                'novedades' => $novedades
             ];
    }
    public function selectEmpleado(Request $request){
    //cambios multiempresa
    foreach (Auth::user()->empresas as $empresa){
        $idEmpresa=$empresa['id'];
     }
    //cambios multiempresa

        $identificador= $request->identificador;

        $extra = Tb_nomina::where('tb_nomina.id','=',$identificador)
        ->where('tb_nomina.idEmpresa','=',$idEmpresa)
        ->get();

        foreach($extra as $extrar){
            $tipoNomina = $extrar->tipo;
        }

        $empleados = Tb_vinculaciones::join("tb_empleado","tb_vinculaciones.idEmpleado","=","tb_empleado.id")
        ->where('tb_vinculaciones.estado','=','1')
        ->where('tb_vinculaciones.tipoSalario','=',$tipoNomina)
        ->select('tb_empleado.id as idEmpleado','tb_vinculaciones.tipoSalario as tipoSalario',DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
        ->orderBy('empleado','asc')->get();

        return ['empleados' => $empleados];
    }

    public function selectTipologia(Request $request){
    //cambios multiempresa
    foreach (Auth::user()->empresas as $empresa){
        $idEmpresa=$empresa['id'];
     }
    //cambios multiempresa

        $identificador= $request->identificador;

        $extra = Tb_nomina::where('tb_nomina.id','=',$identificador)
        ->where('tb_nomina.idEmpresa','=',$idEmpresa)
        ->get();

        foreach($extra as $extrar){
            $tipoNomina = $extrar->tipo;
        }

        return ['tipoNomina' => $tipoNomina];
    }

    public function selectExtra(){
        $extra = Tb_factores_nomina::where('tb_factores_nomina.id','=','1')->get();

        foreach($extra as $extrar){
            $extraDiurna = $extrar->extraDiurna;
            $extraNocturna = $extrar->extraNocturna;
            $horaDominical = $extrar->horaDominical;
            $festivaDiurna = $extrar->festivaDiurna;
            $festivaNocturna = $extrar->festivaNocturna;
            $recargos = $extrar->recargos;
        }

        //var_dump($salarios);

        return [
            'extraDiurna' => $extraDiurna,
            'extraNocturna' => $extraNocturna,
            'horaDominical' => $horaDominical,
            'festivaDiurna' => $festivaDiurna,
            'festivaNocturna' => $festivaNocturna,
            'recargos' => $recargos
        ];
    }

    public function eliminar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_novedades = Tb_novedades::findOrFail($request->identificador);
        $tb_novedades->delete();
        //return ['productos' => $productos];
    }

    public function store(Request $request){
    //cambios multiempresa
    foreach (Auth::user()->empresas as $empresa){
        $idEmpresa=$empresa['id'];
     }
    //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $fechaValidacion=$request->fechaNovedad;

        $nominas = Tb_nomina::select('tb_nomina.id','tb_nomina.tipo')
        ->where('tb_nomina.idEmpresa','=',$idEmpresa)
        ->where('tb_nomina.id','=',$request->idNomina)->get();


        foreach($nominas as $nomina){
            $idNominax = $nomina->id;
            $tiponomina = $nomina->tipo;
            $idNomina=$idNominax;
        }

        //para destajo tengo en cuenta tipologias de entradas: 3- sin provision 4- solo liquidacion 5- solo parafiscales 6-con todo
        //para destajo tengo en cuenta tipologias de salidas: 2- salida
        //para fija solo tengo en cuenta tipologias 1- entrada 2- salida

        if($request->concepto<50){ //Margen de 50 conceptos para tipologia 1 -> Entradas

            if(($request->concepto==1) && ($tiponomina==2)){
                $tipologia=$request->seguimiento;
            }
            else{
                $tipologia=1;
            }

        }
        else{//Margen superior a 50 conceptos para tipologia 0 -> Deducciones
            $tipologia=0;
        }

        $tb_novedades=new Tb_novedades();
        $tb_novedades->fechaNovedad=$request->fechaNovedad;
        $tb_novedades->concepto=$request->concepto;
        $tb_novedades->cantidad=$request->cantidad;
        $tb_novedades->unitario=$request->unitario;
        $tb_novedades->valor=$request->valor;
        $tb_novedades->observacion=$request->observacion;
        $tb_novedades->tipologia=$tipologia;
        $tb_novedades->idEmpleado=$request->idEmpleado;
        $tb_novedades->idNomina=$request->idNomina;
        $tb_novedades->idEmpresa=$idEmpresa;
        //$tb_novedades->idNomina=$request->idNomina;
        $tb_novedades->save();
    }

    public function selectSalario(Request $request)//tipo sueldo
    {
    //cambios multiempresa
    foreach (Auth::user()->empresas as $empresa){
        $idEmpresa=$empresa['id'];
     }
    //cambios multiempresa

        $idEmpleado=$request->identificador;
        $tipoSal='';
        $baseSal='';

        $salarios = Tb_vinculaciones::first()
        ->where('tb_nomina.idEmpresa','=',$idEmpresa)
        ->where('tb_vinculaciones.estado','=','1')
        ->where('tb_vinculaciones.idEmpleado','=',$idEmpleado)
        ->orderByDesc('tb_vinculaciones.id')
        ->get();

        foreach($salarios as $salario){
            $tipoSal = $salario->tipoSalario;
            $baseSal = $salario->salarioBasicoMensual;
            $baseDia=($baseSal/30);
            $baseHora=($baseDia/8);
        }

        //var_dump($salarios);

        return [
            'tipologiasalario' => $tipoSal,
            'baseSal' => $baseSal,
            'baseDia' => $baseDia,
            'baseHora' => $baseHora
        ];
    }
}
