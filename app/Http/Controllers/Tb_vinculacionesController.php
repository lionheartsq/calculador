<?php

namespace App\Http\Controllers;

use App\Tb_empleado;
use App\Tb_eps;
use App\Tb_administradora_pensiones;
use App\Tb_niveles_riesgo;
use App\Tb_vinculaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_vinculacionesController extends Controller
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

        if ($buscar=='') {
            $vinculaciones = Tb_vinculaciones::join("tb_empleado","tb_vinculaciones.idEmpleado","=","tb_empleado.id")
            ->join("tb_eps","tb_empleado.idEps","=","tb_eps.id")
            ->join("tb_administradora_pensiones","tb_empleado.idPensiones","=","tb_administradora_pensiones.id")
            ->where('tb_vinculaciones.idEmpresa','=',$idEmpresa)
            ->select('tb_vinculaciones.id','tb_vinculaciones.tipoContrato','tb_vinculaciones.tipoSalario','tb_vinculaciones.salarioBasicoMensual',
            'tb_vinculaciones.fechaInicio','tb_vinculaciones.tiempoContrato','tb_vinculaciones.fechaFin','tb_vinculaciones.idEmpleado','tb_vinculaciones.idNivelArl',
            'tb_empleado.idEps','tb_empleado.idPensiones','tb_vinculaciones.estado','tb_eps.nombreEps','tb_administradora_pensiones.nombrePensiones',
            DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
            ->orderBy('tb_vinculaciones.id','desc')
            ->where('tb_vinculaciones.estado','=','1')->paginate(5);
        }
        else {
            $vinculaciones = Tb_vinculaciones::join("tb_empleado","tb_vinculaciones.idEmpleado","=","tb_empleado.id")
            ->join("tb_eps","tb_empleado.idEps","=","tb_eps.id")
            ->join("tb_administradora_pensiones","tb_empleado.idPensiones","=","tb_administradora_pensiones.id")
            ->where('tb_vinculaciones.idEmpresa','=',$idEmpresa)
            ->select('tb_vinculaciones.id','tb_vinculaciones.tipoContrato','tb_vinculaciones.tipoSalario','tb_vinculaciones.salarioBasicoMensual',
            'tb_vinculaciones.fechaInicio','tb_vinculaciones.tiempoContrato','tb_vinculaciones.fechaFin','tb_vinculaciones.idEmpleado','tb_vinculaciones.idNivelArl',
            'tb_empleado.idEps','tb_empleado.idPensiones','tb_vinculaciones.estado','tb_eps.nombreEps','tb_administradora_pensiones.nombrePensiones',
            DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
            ->where('tb_vinculaciones.estado','=','1')
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$vinculaciones->total(),
                'current_page'  =>$vinculaciones->currentPage(),
                'per_page'      =>$vinculaciones->perPage(),
                'last_page'     =>$vinculaciones->lastPage(),
                'from'          =>$vinculaciones->firstItem(),
                'to'            =>$vinculaciones->lastItem(),
            ],
                'vinculaciones' => $vinculaciones
        ];
    }

    public function vinculacionesInactivas(Request $request)
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
            $vinculaciones = Tb_vinculaciones::join("tb_empleado","tb_vinculaciones.idEmpleado","=","tb_empleado.id")
            ->join("tb_eps","tb_empleado.idEps","=","tb_eps.id")
            ->join("tb_administradora_pensiones","tb_empleado.idPensiones","=","tb_administradora_pensiones.id")
            ->where('tb_vinculaciones.idEmpresa','=',$idEmpresa)
            ->select('tb_vinculaciones.id','tb_vinculaciones.tipoContrato','tb_vinculaciones.tipoSalario','tb_vinculaciones.salarioBasicoMensual',
            'tb_vinculaciones.fechaInicio','tb_vinculaciones.tiempoContrato','tb_vinculaciones.fechaFin','tb_vinculaciones.idEmpleado','tb_vinculaciones.idNivelArl',
            'tb_empleado.idEps','tb_empleado.idPensiones','tb_vinculaciones.estado','tb_eps.nombreEps','tb_administradora_pensiones.nombrePensiones',
            DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
            ->orderBy('tb_vinculaciones.id','desc')
            ->where('tb_vinculaciones.estado','=','0')->paginate(5);
        }
        else {
            $vinculaciones = Tb_vinculaciones::join("tb_empleado","tb_vinculaciones.idEmpleado","=","tb_empleado.id")
            ->join("tb_eps","tb_empleado.idEps","=","tb_eps.id")
            ->join("tb_administradora_pensiones","tb_empleado.idPensiones","=","tb_administradora_pensiones.id")
            ->where('tb_vinculaciones.idEmpresa','=',$idEmpresa)
            ->select('tb_vinculaciones.id','tb_vinculaciones.tipoContrato','tb_vinculaciones.tipoSalario','tb_vinculaciones.salarioBasicoMensual',
            'tb_vinculaciones.fechaInicio','tb_vinculaciones.tiempoContrato','tb_vinculaciones.fechaFin','tb_vinculaciones.idEmpleado','tb_vinculaciones.idNivelArl',
            'tb_empleado.idEps','tb_empleado.idPensiones','tb_vinculaciones.estado','tb_eps.nombreEps','tb_administradora_pensiones.nombrePensiones',
            DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
            ->where('tb_vinculaciones.estado','=','0')
            ->where($criterio, 'like', '%'. $buscar . '%')->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$vinculaciones->total(),
                'current_page'  =>$vinculaciones->currentPage(),
                'per_page'      =>$vinculaciones->perPage(),
                'last_page'     =>$vinculaciones->lastPage(),
                'from'          =>$vinculaciones->firstItem(),
                'to'            =>$vinculaciones->lastItem(),
            ],
                'vinculaciones' => $vinculaciones
        ];
    }

    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $tb_vinculaciones=new Tb_vinculaciones();
        $tb_vinculaciones->tipoContrato=$request->tipoContrato;
        $tb_vinculaciones->tipoSalario=$request->tipoSalario;
        $tb_vinculaciones->salarioBasicoMensual=$request->salarioBasicoMensual;
        $tb_vinculaciones->fechaInicio=$request->fechaInicio;
        $tb_vinculaciones->tiempoContrato=$request->tiempoContrato;
        $tb_vinculaciones->idEmpleado=$request->idEmpleado;
        $tb_vinculaciones->idNivelArl=$request->idNivelArl;
        $tb_vinculaciones->idEmpresa=$idEmpresa;
        $tb_vinculaciones->save();
    }

    public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_vinculaciones=Tb_vinculaciones::findOrFail($request->id);
        $tb_vinculaciones->tipoContrato=$request->tipoContrato;
        $tb_vinculaciones->tipoSalario=$request->tipoSalario;
        $tb_vinculaciones->salarioBasicoMensual=$request->salarioBasicoMensual;
        $tb_vinculaciones->fechaInicio=$request->fechaInicio;
        $tb_vinculaciones->tiempoContrato=$request->tiempoContrato;
        $tb_vinculaciones->idEmpleado=$request->idEmpleado;
        $tb_vinculaciones->idNivelArl=$request->idNivelArl;
        $tb_vinculaciones->estado='1';
        $tb_vinculaciones->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_vinculaciones=Tb_vinculaciones::findOrFail($request->id);
        $tb_vinculaciones->estado='0';
        $tb_vinculaciones->fechaFin=$request->fechaFin;
        $tb_vinculaciones->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_vinculaciones=Tb_vinculaciones::findOrFail($request->id);
        $tb_vinculaciones->estado='1';
        $tb_vinculaciones->fechaInicio=$request->fechaInicio;
        $tb_vinculaciones->save();
    }

    public function selectEmpleado(){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $empleados = Tb_empleado::where('tb_empleado.idEmpresa','=',$idEmpresa)
        ->where('tb_empleado.estado','=','1')
        ->select('tb_empleado.id as idEmpleado',DB::raw("CONCAT(tb_empleado.nombre,'  ',tb_empleado.apellido) AS empleado"))
        ->orderBy('empleado','asc')->get();

        return ['empleados' => $empleados];
    }

    public function selectNivel(){
        $niveles = Tb_niveles_riesgo::orderBy('id','asc')->get();
        return ['niveles' => $niveles];
    }

    public function selectEps(){
        $eps = Tb_eps::orderBy('id','asc')->get();
        return ['eps' => $eps];
    }

    public function selectPensiones(){
        $pensiones = Tb_administradora_pensiones::orderBy('id','asc')->get();
        return ['pensiones' => $pensiones];
    }

}
