<?php


namespace App\Http\Controllers;

use App\Tb_tipo_nomina;
use App\Tb_administradora_pensiones;
use App\Tb_eps;
use App\tb_caja_compensacion;
use App\tb_arl;
use App\Tb_niveles_riesgo;
use App\Tb_configuracion_basica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_configuracion_basicaController extends Controller
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
        $configuracion = Tb_configuracion_basica::where('id','=',$idEmpresa)
        ->select('id','nombre','direccion','telefono','cajaCompensacion','arl','nivelRiesgo','idTipoNomina')->get();

        foreach($configuracion as $c){
            $id = $c->id;
            $nombre = $c->nombre;
            $direccion = $c->direccion;
            $telefono = $c->telefono;
            $cajaCompensacion = $c->cajaCompensacion;
            $arl = $c->arl;
            $nivelRiesgo = $c->nivelRiesgo;
            $idTipoNomina = $c->idTipoNomina;
        }

        return ['id' => $id,
        'nombre' => $nombre,
        'direccion' => $direccion,
        'telefono' => $telefono,
        'cajaCompensacion' => $cajaCompensacion,
        'arl' => $arl,
        'nivelRiesgo' => $nivelRiesgo,
        'idTipoNomina' => $idTipoNomina
        ];
    }

    public function CajaCompensacion()
    {
        $cajacompensacion = tb_caja_compensacion::select('tb_caja_compensacion.id','tb_caja_compensacion.cajaCompensacion',
        DB::raw('(tb_caja_compensacion.cajaCompensacion) as NombreCajaCompensacion'))
        ->get();

        return ['cajacompensacion' => $cajacompensacion];
    }

    public function Eps()
    {
        $eps = Tb_Eps::select('tb_eps.id','tb_eps.nombreEps',
        DB::raw('(tb_eps.nombreEps) as NombreCajaCompensacion'))
        ->get();

        return ['eps' => $eps];
    }

    public function Arl()
    {
        $arl = tb_arl::select('tb_arl.id','tb_arl.arl',
        DB::raw('(tb_arl.arl) as nombreArl'))
        ->get();

        return ['arl' => $arl];
    }

    public function Pensiones()
    {
        $pensiones = Tb_administradora_pensiones::select('tb_administradora_pensiones.id','tb_administradora_pensiones.nombrePensiones',
        DB::raw('(tb_administradora_pensiones.nombrePensiones) as nombreArl'))
        ->get();

        return ['pensiones' => $pensiones];
    }

    public function NivelArl(){
        $niveles = Tb_niveles_riesgo::orderBy('id','asc')->get();
        return ['niveles' => $niveles];
    }

    public function tipoNomina()
    {
        $tiponomina = Tb_tipo_nomina::select('tb_tipo_nomina.id','tb_tipo_nomina.periodicidad','tb_tipo_nomina.dias',
        DB::raw('(tb_tipo_nomina.periodicidad) as tipoNomina'))
        ->get();

        return ['tiponomina' => $tiponomina];
    }
    public function store(Request $request)
    {
        if(Tb_configuracion_basica::count()>0){
            $tb_configuracion_basica=Tb_configuracion_basica::findOrFail('1');
            $tb_configuracion_basica->nombre=$request->nombre;
            $tb_configuracion_basica->direccion=$request->direccion;
            $tb_configuracion_basica->telefono=$request->telefono;
            $tb_configuracion_basica->cajaCompensacion=$request->cajaCompensacion;
            $tb_configuracion_basica->arl=$request->arl;
            $tb_configuracion_basica->nivelRiesgo=$request->nivelRiesgo;
            $tb_configuracion_basica->idTipoNomina=$request->idTipoNomina;
            $tb_configuracion_basica->save();
        }
        else{
            $tb_configuracion_basica= new Tb_configuracion_basica();
            $tb_configuracion_basica->nombre=$request->nombre;
            $tb_configuracion_basica->direccion=$request->direccion;
            $tb_configuracion_basica->telefono=$request->telefono;
            $tb_configuracion_basica->cajaCompensacion=$request->cajaCompensacion;
            $tb_configuracion_basica->arl=$request->arl;
            $tb_configuracion_basica->nivelRiesgo=$request->nivelRiesgo;
            $tb_configuracion_basica->idTipoNomina=$request->idTipoNomina;
            $tb_configuracion_basica->save();
        }

    }
    public function actualizar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $tb_configuracion_basica=Tb_configuracion_basica::findOrFail($request->id);
        $tb_configuracion_basica->nombre=$request->nombre;
        $tb_configuracion_basica->direccion=$request->direccion;
        $tb_configuracion_basica->telefono=$request->telefono;
        $tb_configuracion_basica->cajaCompensacion=$request->cajaCompensacion;
        $tb_configuracion_basica->arl=$request->arl;
        $tb_configuracion_basica->nivelRiesgo=$request->nivelRiesgo;
        $tb_configuracion_basica->idTipoNomina=$request->idTipoNomina;
        $tb_configuracion_basica->save();
    }
}
