<?php

namespace App\Http\Controllers;

use App\Tb_kardex_almacen;
use App\Tb_gestion_materia_prima;
use App\Tb_unidad_base;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Tb_inventarioController extends Controller
{
    public function index(Request $request)
    {
        $productos = DB::table('tb_kardex_almacen')
            ->join('tb_gestion_materia_prima', 'tb_kardex_almacen.idGestionMateria', '=', 'tb_gestion_materia_prima.id')
            ->join('tb_unidad_base', 'tb_gestion_materia_prima.idUnidadBase', '=', 'tb_unidad_base.id')
            ->select('tb_kardex_almacen.cantidad', 'tb_gestion_materia_prima.gestionMateria', 'tb_unidad_base.unidadBase')
            ->paginate(5);
        
            return [
                'pagination' => [
                    'total'         =>$productos->total(),
                    'current_page'  =>$productos->currentPage(),
                    'per_page'      =>$productos->perPage(),
                    'last_page'     =>$productos->lastPage(),
                    'from'          =>$productos->firstItem(),
                    'to'            =>$productos->lastItem(),
                ],
                'productos' =>  $productos
            ];

        /*$productos = Tb_kardex_almacen::join('tb_gestion_materia_prima','tb_kardex_almacen.idGestionMateria','=','tb_gestion_materia_prima.id')
        ->join('tb_unidad_base','tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id')
        ->select('tb_kardex_almacen.id','tb_kardex_almacen.cantidad',
        'tb_kardex_almacen.idGestionMateria','tb_gestion_materia_prima.gestionMateria as producto','tb_unidad_base.unidadBase',
        'tb_gestion_materia_prima.estado',DB::raw('tb_kardex_almacen.cantidadSaldos * tb_kardex_almacen.precioSaldos as saldos'))
        ->orderBy('tb_kardex_almacen.idGestionMateria','asc')
        ->whereIn('tb_kardex_almacen.id', function($sub){$sub->selectRaw('max(id)')->from('tb_kardex_almacen')->groupBy('idGestionMateria');})
        ->paginate(5);
       
        
        return [
            'pagination' => [
                'total'         =>$productos->total(),
                'current_page'  =>$productos->currentPage(),
                'per_page'      =>$productos->perPage(),
                'last_page'     =>$productos->lastPage(),
                'from'          =>$productos->firstItem(),
                'to'            =>$productos->lastItem(),
            ],
            'productos' =>  $productos
        ];*/
    }
    public function validar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_inventario=Tb_kardex_almacen::findOrFail($request->id);
        $tb_inventario=Tb_kardex_almacen::findOrFail($request->id);
        $tb_inventario->cantidad=$request->cantidad;
        $tb_inventario->save();
    }
    
}