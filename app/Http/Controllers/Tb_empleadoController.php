<?php

namespace App\Http\Controllers;


use App\Tb_perfil;
use App\Tb_area;
use App\Tb_proceso;
use App\Tb_empleado;
use App\Tb_administradora_pensiones;
use App\Tb_porcentaje_riesgo;
use App\Tb_eps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_empleadoController extends Controller
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
        $identificador= $request->identificador;

        if ($buscar=='') {
            # Modelo::join('tablaqueseune',basicamente un on)
            $empleados = Tb_empleado::join("tb_perfil","tb_empleado.idPerfil","=","tb_perfil.id")
            ->join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->where('tb_empleado.idEmpresa','=',$idEmpresa)
            ->select('tb_empleado.id','tb_empleado.documento','tb_empleado.nombre','tb_empleado.apellido','tb_empleado.direccion','tb_empleado.telefono',
            'tb_empleado.correo','tb_empleado.idPerfil','tb_empleado.genero','tb_empleado.estado as estado','tb_perfil.perfil','tb_perfil.idProceso',
            'tb_proceso.proceso','tb_proceso.idArea','tb_area.area','tb_empleado.tipoSangre','tb_empleado.enfermedades')
            ->orderBy('tb_empleado.id','desc')->paginate(5);
        }
        else if($criterio=='perfil'){
            $empleados = Tb_empleado::join("tb_perfil","tb_empleado.idPerfil","=","tb_perfil.id")
            ->join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->where('tb_empleado.idEmpresa','=',$idEmpresa)
            ->select('tb_empleado.id','tb_empleado.documento','tb_empleado.nombre','tb_empleado.apellido','tb_empleado.direccion','tb_empleado.telefono',
            'tb_empleado.correo','tb_empleado.idPerfil','tb_empleado.genero','tb_empleado.estado as estado','tb_perfil.perfil','tb_perfil.idProceso',
            'tb_proceso.proceso','tb_proceso.idArea','tb_area.area','tb_empleado.tipoSangre','tb_empleado.enfermedades')
            ->where('tb_perfil.perfil', 'like', '%'. $buscar . '%')
            ->orderBy('tb_empleado.id','desc')->paginate(5);
        }
        else {
            $empleados = Tb_empleado::join("tb_perfil","tb_empleado.idPerfil","=","tb_perfil.id")
            ->join("tb_proceso","tb_perfil.idProceso","=","tb_proceso.id")
            ->leftJoin('tb_area',function($join){
                $join->on('tb_proceso.idArea','=','tb_area.id');
            })
            ->where('tb_empleado.idEmpresa','=',$idEmpresa)
            ->select('tb_empleado.id','tb_empleado.documento','tb_empleado.nombre','tb_empleado.apellido','tb_empleado.direccion','tb_empleado.telefono',
            'tb_empleado.correo','tb_empleado.idPerfil','tb_empleado.genero','tb_empleado.estado as estado','tb_perfil.perfil','tb_perfil.idProceso',
            'tb_proceso.proceso','tb_proceso.idArea','tb_area.area','tb_empleado.tipoSangre','tb_empleado.enfermedades')
            ->where('tb_perfil.perfil', 'like', '%'. $buscar . '%')
            ->orderBy('tb_empleado.id','desc')->paginate(5);
        }

        return [
            'pagination' => [
                'total'         =>$empleados->total(),
                'current_page'  =>$empleados->currentPage(),
                'per_page'      =>$empleados->perPage(),
                'last_page'     =>$empleados->lastPage(),
                'from'          =>$empleados->firstItem(),
                'to'            =>$empleados->lastItem(),
            ],
                'empleados' => $empleados
        ];
    }

    public function selectRelacion($id){
    $relaciones = tb_proceso::where([
                ['estado','=','1'],
                ['idArea','=',$id],
            ])
            ->select('id as idProceso','proceso')
            ->orderBy('proceso','asc')->get();
            return ['relaciones' => $relaciones];
    }

    public function selectRelacionPerfil($id){
        $perfilrelaciones = tb_perfil::where([
                    ['estado','=','1'],
                    ['idProceso','=',$id],
                ])
                ->select('id as idPerfil','perfil')
                ->orderBy('perfil','asc')->get();
                return ['perfilrelaciones' => $perfilrelaciones];
        }

    public function selectEmpleado(){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $empleados = Tb_empleado::where('estado','=','1')
        ->where('tb_empleado.idEmpresa','=',$idEmpresa)
        ->select('id as idEmpleado','empleado')->orderBy('empleado','asc')->get();

        return ['empleados' => $empleados];
    }
    public function selectEps(){
        $eps = Tb_eps::select('tb_eps.id','tb_eps.nombreEps',
        DB::raw('(tb_eps.nombreEps) as nombreEps'))
        ->get();
        return ['eps' => $eps];
    }
    public function selectPensiones(){
        $pensiones = Tb_administradora_pensiones::select('tb_administradora_pensiones.id','tb_administradora_pensiones.nombrePensiones',
        DB::raw('(tb_administradora_pensiones.nombrePensiones) as nombrePensiones'))
        ->get();
        return ['pensiones' => $pensiones];
    }
    public function detalleEmpleado(Request $request){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $buscar= $request->id;
        $detalleempleados = Tb_empleado::join("tb_eps","tb_empleado.idEps","=","tb_eps.id")
        ->join("tb_administradora_pensiones","tb_empleado.idPensiones","=","tb_administradora_pensiones.id")
        ->where('tb_empleado.idEmpresa','=',$idEmpresa)
        ->where('tb_empleado.id','=',$buscar)
        ->select('tb_empleado.id as id','tb_empleado.nombre','tb_empleado.apellido','tb_empleado.direccion','tb_empleado.telefono','tb_empleado.correo',
        'tb_empleado.contacto','tb_empleado.telefonocontacto','tb_empleado.genero','tb_empleado.idEps','tb_empleado.idPensiones','tb_empleado.idEps',
        'tb_empleado.idPensiones','tb_eps.nombreEps','tb_administradora_pensiones.nombrePensiones','tb_empleado.tipoSangre','tb_empleado.enfermedades')
        ->orderBy('tb_empleado.id','asc')->get();
        return ['detalleempleados' => $detalleempleados];
    }
    public function vinculacionEmpleado(Request $request){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $buscar= $request->id;
        $detalleempleados = Tb_empleado::join("tb_vinculaciones","tb_empleado.id","=","tb_vinculaciones.idEmpleado")
        ->join("tb_porcentaje_riesgo","tb_vinculaciones.idNivelArl","=","tb_porcentaje_riesgo.id")
        ->where('tb_empleado.idEmpresa','=',$idEmpresa)
        ->where('tb_empleado.id','=',$buscar)
        ->where('tb_vinculaciones.estado','=','1')
        ->select('tb_empleado.id as id','tb_empleado.nombre','tb_empleado.apellido','tb_empleado.direccion','tb_vinculaciones.tipocontrato',
        'tb_vinculaciones.tiposalario','tb_vinculaciones.salarioBasicoMensual','tb_vinculaciones.fechainicio','tb_porcentaje_riesgo.nivel')
        ->orderBy('tb_empleado.id','asc')->get();
        return ['vinculacionempleados' => $detalleempleados];
    }
    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        if(!$request->ajax()) return redirect('/');
        $tb_empleado = new Tb_empleado();
        $tb_empleado->documento=$request->documento;
        $tb_empleado->idPerfil=$request->idPerfil;
        $tb_empleado->nombre=$request->nombre;
        $tb_empleado->apellido=$request->apellido;
        $tb_empleado->direccion=$request->direccion;
        $tb_empleado->genero=$request->genero;
        $tb_empleado->telefono=$request->telefono;
        $tb_empleado->correo=$request->correo;
        $tb_empleado->contacto=$request->contacto;
        $tb_empleado->telefonocontacto=$request->telefonocontacto;
        $tb_empleado->idEps=$request->idEps;
        $tb_empleado->idPensiones=$request->idPensiones;
        $tb_empleado->tipoSangre=$request->tipoSangre;
        $tb_empleado->enfermedades=$request->enfermedades;
        $tb_empleado->idEmpresa=$idEmpresa;
        $tb_empleado->save();
    }

    public function update(Request $request)
    {
        //
        if(!$request->ajax()) return redirect('/');
        $tb_empleado =Tb_empleado::findOrFail($request->id);
        $tb_empleado->documento=$request->documento;
        $tb_empleado->idPerfil=$request->idPerfil;
        $tb_empleado->nombre=$request->nombre;
        $tb_empleado->apellido=$request->apellido;
        $tb_empleado->direccion=$request->direccion;
        $tb_empleado->genero=$request->genero;
        $tb_empleado->telefono=$request->telefono;
        $tb_empleado->correo=$request->correo;
        $tb_empleado->contacto=$request->contacto;
        $tb_empleado->telefonocontacto=$request->telefonocontacto;
        $tb_empleado->idEps=$request->idEps;
        $tb_empleado->idPensiones=$request->idPensiones;
        $tb_empleado->tipoSangre=$request->tipoSangre;
        $tb_empleado->enfermedades=$request->enfermedades;
        $tb_empleado->estado='1';
        $tb_empleado->save();
    }

    public function deactivate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_empleado=Tb_empleado::findOrFail($request->id);
        $tb_empleado->estado='0';
        $tb_empleado->save();
    }

    public function activate(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_empleado=Tb_empleado::findOrFail($request->id);
        $tb_empleado->estado='1';
        $tb_empleado->save();
    }
}
