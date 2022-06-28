<?php

namespace App\Http\Controllers;

use App\Tb_inventario;
use App\Tb_kardex_almacen;
use App\Tb_gestion_materia_prima;
use App\Tb_unidad_base;
use App\Tb_empleado;
use App\Tb_detalle_inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_inventarioController extends Controller
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
             # Modelo::join('tablaqueseune',basicamente un on)
             $inventarios = Tb_inventario::join('tb_empleado','tb_inventario.idEmpleado','=','tb_empleado.id')
             ->where('tb_inventario.idEmpresa','=',$idEmpresa)
             ->select('tb_inventario.id','tb_inventario.fecha','tb_inventario.idEmpleado','tb_inventario.estado',
             DB::raw('CONCAT(tb_empleado.nombre," ",tb_empleado.apellido," - ",tb_empleado.documento) as nombreEmpleado'))
             ->orderBy('tb_inventario.id','asc')->paginate(5);
         }
         else {
             $inventarios = Tb_inventario::join('tb_empleado','tb_inventario.idEmpleado','=','tb_empleado.id')
             ->where('tb_inventario.idEmpresa','=',$idEmpresa)
             ->select('tb_inventario.id','tb_inventario.fecha','tb_inventario.idEmpleado','tb_inventario.estado',
             DB::raw('CONCAT(tb_empleado.nombre," ",tb_empleado.apellido," - ",tb_empleado.documento) as nombreEmpleado'))
            ->where('tb_inventario.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('tb_inventario.id','desc')->paginate(5);
         }

         return [
             'pagination' => [
                 'total'         =>$inventarios->total(),
                 'current_page'  =>$inventarios->currentPage(),
                 'per_page'      =>$inventarios->perPage(),
                 'last_page'     =>$inventarios->lastPage(),
                 'from'          =>$inventarios->firstItem(),
                 'to'            =>$inventarios->lastItem(),
             ],
                 'inventarios' => $inventarios
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

         $tb_inventario=new Tb_inventario();
         $tb_inventario->fecha=$request->fecha;
         $tb_inventario->idEmpleado=$request->idEmpleado;
         $tb_inventario->idEmpresa=$idEmpresa;
         $tb_inventario->save();
        /*
         $detalles = Tb_kardex_almacen::join('tb_gestion_materia_prima','tb_kardex_almacen.idGestionMateria','=','tb_gestion_materia_prima.id')
         ->select('tb_kardex_almacen.idGestionMateria','tb_gestion_materia_prima.gestionMateria as producto','tb_gestion_materia_prima.idUnidadBase',
         'tb_gestion_materia_prima.id','tb_kardex_almacen.cantidadSaldos')
         ->distinct('tb_kardex_almacen.idGestionMateria')
         ->orderBy('tb_gestion_materia_prima.gestionMateria','asc')
         ->get();
         */

        $detalles = Tb_kardex_almacen::join('tb_gestion_materia_prima','tb_kardex_almacen.idGestionMateria','=','tb_gestion_materia_prima.id')
        ->where('tb_kardex_almacen.idEmpresa','=',$idEmpresa)
        ->select('tb_kardex_almacen.idGestionMateria','tb_gestion_materia_prima.gestionMateria as producto','tb_gestion_materia_prima.idUnidadBase',
        'tb_gestion_materia_prima.id','tb_kardex_almacen.cantidadSaldos')
        ->orderBy('tb_gestion_materia_prima.gestionMateria','asc')
        ->whereIn('tb_kardex_almacen.id', function($sub){$sub->selectRaw('max(id)')->from('tb_kardex_almacen')->groupBy('idGestionMateria');})
        ->get();

         /*$tb_inventario=Tb_inventario::all()->first();*/
         foreach($detalles as $detalle){
             $obj_detalle= new Tb_detalle_inventario();
             $obj_detalle->idInventario=$tb_inventario->id;
             $obj_detalle->idProducto=$detalle->idGestionMateria;
             $obj_detalle->cantidadSistema=$detalle->cantidadSaldos;
             $obj_detalle->unidadBase=$detalle->idUnidadBase;
             $obj_detalle->idEmpresa=$idEmpresa;
             $obj_detalle->save();
         }
         /*echo var_dump($detalles);*/

            /*foreach($detalles as $detalle){
                $obj_detalle= new Tb_detalle_inventario();
                $obj_detalle->idInventario=$tb_inventario->idInventario;
                $obj_detalle->idProducto=$detalle->idProducto;
                $obj_detalle->cantidadSisteme=$detalle->

            }*/
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
    /*public function estado(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        $idInventario= $request->id;
        $tb_inventario=Tb_inventario::findOrFail($request->id);
        $tb_inventario->estado=0;
        $tb_inventario->save();

    }*/
        /**/
}
