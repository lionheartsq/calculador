<?php

namespace App\Http\Controllers;

use App\Tb_detalle_inventario;
use App\Tb_inventario;
use App\Tb_kardex_almacen;
use App\Tb_gestion_materia_prima;
use App\Tb_unidad_base;
use App\Tb_almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_detalle_inventarioController extends Controller
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
        $identificador= $request->id;
            # Modelo::join('tablaqueseune',basicamente un on)
            $detalles = Tb_detalle_inventario::join('tb_gestion_materia_prima','tb_detalle_inventario.idProducto','=','tb_gestion_materia_prima.id')
            ->join('tb_unidad_base','tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id')
            ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
            ->select('tb_detalle_inventario.id','tb_detalle_inventario.idProducto','tb_detalle_inventario.unidadBase','tb_detalle_inventario.cantidadSistema',
            'tb_detalle_inventario.cantidadActual','tb_detalle_inventario.diferencia','tb_detalle_inventario.observacion','tb_detalle_inventario.idInventario',
            'tb_gestion_materia_prima.gestionMateria as producto','tb_unidad_base.unidadBase')
            ->where('tb_detalle_inventario.idInventario', '=', $identificador)->get();

            return ['detalles' => $detalles];
    }


    public function store(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $tb_detalle_inventario=new Tb_detalle_inventario();
        $tb_detalle_inventario->idProducto=$request->idProducto;
        $tb_detalle_inventario->unidadBase=$request->unidadBase;
        $tb_detalle_inventario->cantidadSistema=$request->cantidadSistema;
        $tb_detalle_inventario->cantidadActual=$request->cantidadActual;
        $tb_detalle_inventario->observacion=$request->observacion;
        $tb_detalle_inventario->idEmpresa=$idEmpresa;
        $tb_detalle_inventario->idInventario=$request->idInventario;
        $tb_detalle_inventario->save();

    }

    public function verificar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $respuesta=$request;

        return ['respuesta' => $respuesta];

    }

    public function validar(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');

        $cantidadActual=$request->cantidadActual;
        $cantidadSistema=$request->cantidadSistema;
        $diferencia=$request->diferencia;
        $idProducto=0;

        $validarCantidad = DB::table('tb_kardex_almacen')
        ->where('tb_kardex_almacen.idEmpresa','=',$idEmpresa)
        ->select('id','cantidad','cantidadSaldos as cantidadS','precioSaldos as precioS')
        ->where('tb_kardex_almacen.idGestionMateria','=', $idProducto)
        ->orderByDesc('id')
        ->limit(1)
        ->get();

    }
    public function verificar1(Request $request){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $cantidad=$request->data;
        //\Log::debug($cantidad[0]);
        $inventario=Tb_detalle_inventario::where('tb_detalle_inventario.idEmpresa','=',$idEmpresa)
        ->where('tb_detalle_inventario.idInventario','=',$cantidad[0])->get();
        foreach($inventario as $i){
            $i->cantidadActual=$cantidad[$i->id];
            $i->diferencia=$i->cantidadActual-$i->cantidadSistema;
            //\Log::debug($i);
            $i->save();
        }
        $inventario=Tb_detalle_inventario::where('tb_detalle_inventario.idEmpresa','=',$idEmpresa)
        ->where('diferencia','!=',0)->where('idInventario','=',$cantidad[0])->get();
        //$cantidadActual=$request->cantidadActual;
        //$output = new Symfony\Component\Console\Output\ConsoleOutput();
        //$output->writeln("<info>error</info>");
        //\Log::debug($cantidad);
        $idInventario=$request->id;
        $tb_inventario=Tb_inventario::findOrFail($request->id);
        $tb_inventario->estado=2;
        $tb_inventario->save();
        return ['inventario'=>$inventario];
    }
    public function observacion(Request $request){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $observacion=$request->data;
        $inventario=Tb_detalle_inventario::where('tb_detalle_inventario.idEmpresa','=',$idEmpresa)
        ->where('diferencia','!=',0)->where('idInventario','=',$observacion[0])->get();
        //$inventario=Tb_detalle_inventario::where('diferencia','!=',0)->where('idInventario','=',$request->idInventario)->get();
/*
        $inventario=Tb_detalle_inventario::join('tb_inventario','tb_detalle_inventario.idInventario','=','tb_inventario.id')
        ->select('tb_inventario.idEmpleado as idEmpleado','tb_inventario.fecha as fecha','tb_detalle_inventario.idProducto as idProducto',
        'tb_detalle_inventario.diferencia as diferencia','tb_detalle_inventario.observacion as observacion',
        'tb_detalle_inventario.idInventario as idInventario')
        ->where('tb_detalle_inventario.diferencia','!=',0)->where('tb_detalle_inventario.idInventario','=',$observacion[0])->get();
*/
        foreach($inventario as $i){
            $i->observacion=$observacion[$i->id];
            //$i->observacion=$i->idInventario;
            $i->save();

            $identinv=$i->idInventario;
            $identprod=$i->idProducto;

        /**/
        $inventariocom=Tb_detalle_inventario::join('tb_inventario','tb_detalle_inventario.idInventario','=','tb_inventario.id')
        ->where('tb_detalle_inventario.idEmpresa','=',$idEmpresa)
        ->select('tb_inventario.idEmpleado as idEmpleado','tb_inventario.fecha as fecha','tb_detalle_inventario.idProducto as idProducto',
        'tb_detalle_inventario.diferencia as diferencia','tb_detalle_inventario.observacion as observacion',
        'tb_detalle_inventario.idInventario as idInventario')
        ->where('tb_detalle_inventario.idInventario','=',$identinv)
        ->where('tb_detalle_inventario.idProducto','=',$identprod)->get();

        foreach($inventariocom as $inv){
            $fecha=$inv->fecha;
            $idProducto = $inv->idProducto;
            $observaciones=$inv->observacion;
            $idDocumentos=7;
            $idEmpleado=$inv->idEmpleado;
            $cons=$inv->idInventario;
            $detalle="INV".$cons;
            }

            if($inv->diferencia>0){
                //realizar entrada
                $tipologia=1;
                $cantidad=$i->diferencia;
            }
            else{
                //realizar salida
                $tipologia=2;
                $cantidadneg=$i->diferencia;
                $cantidad=abs($cantidadneg);
            }

                $valorparcial=0;

                $preciomaterial = Tb_kardex_almacen::first()
                ->where('tb_kardex_almacen.idEmpresa','=',$idEmpresa)
                ->select('tb_kardex_almacen.id','tb_kardex_almacen.precioSaldos as valorMaterial')
                ->whereIn('tb_kardex_almacen.id', function($sub){$sub->selectRaw('max(id)')->from('tb_kardex_almacen')
                ->groupBy('tb_kardex_almacen.idGestionMateria');})
                ->where('tb_kardex_almacen.idGestionMateria', '=', $idProducto)
                ->get();

                foreach($preciomaterial as $totalg){
                    $id = $totalg->id;
                    $valorMaterial = $totalg->valorMaterial;
                    $valorparcial=$valorparcial+$valorMaterial;
                }

                $precio=$valorparcial;
                $valorE=($precio*$cantidad);

                $precioSaldos = DB::table('tb_kardex_almacen')
                ->where('tb_kardex_almacen.idEmpresa','=',$idEmpresa)
                ->select('id','cantidad as cantidadA','cantidadSaldos as cantidadS','precioSaldos as precioS')
                ->where('tb_kardex_almacen.idGestionMateria','=', $idProducto)
                ->orderByDesc('id')
                ->limit(1)
                ->get();

                $precioSaldoscant= DB::table('tb_kardex_almacen')
                ->where('tb_kardex_almacen.idEmpresa','=',$idEmpresa)
                ->where('tb_kardex_almacen.idGestionMateria','=', $idProducto)
                ->count();

                if (!$precioSaldoscant) {
                    $suma1=$cantidad;
                    $suma2=$precio;
                }
                else{
                    foreach($precioSaldos as $precioS){
                        $cantidadA = $precioS->cantidadA;
                        $cantidadS = $precioS->cantidadS;
                        $precioSA = $precioS->precioS;
                        $valorP=($cantidadS*$precioSA);
                    }
                    if($tipologia == 1){
                    $suma1=$cantidadS+$cantidad;
                    $suma0=$valorP+$valorE;

                        if($suma1 == 0){
                            $suma2=0;
                            }
                        else{
                            $suma2=($suma0/$suma1);
                        }
                    }
                    else{
                        $suma1=$cantidadS-$cantidad;
                        $suma0=$valorP-$valorE;

                        if($suma1 == 0){
                            $suma2=0;
                            }
                        else{
                            $suma2=($suma0/$suma1);
                        }
                    }
                }

                $tb_kardex_almacen=new Tb_kardex_almacen();
                $tb_kardex_almacen->detalle=$detalle;
                $tb_kardex_almacen->cantidad=$cantidad;
                $tb_kardex_almacen->precio=$precio;
                $tb_kardex_almacen->idGestionMateria=$idProducto;
                $tb_kardex_almacen->observaciones=$observaciones;
                $tb_kardex_almacen->idEmpleado=$idEmpleado;
                $tb_kardex_almacen->idDocumentos=$idDocumentos;
                $tb_kardex_almacen->tipologia=$tipologia;
                $tb_kardex_almacen->fecha=$fecha;
                $tb_kardex_almacen->idEmpresa=$idEmpresa;
                $tb_kardex_almacen->cantidadSaldos=$suma1;
                $tb_kardex_almacen->precioSaldos=$suma2;
                $tb_kardex_almacen->save();
        /* */
        }
        $idInventario=$request->id;
        $tb_inventario=Tb_inventario::findOrFail($request->id);
        $tb_inventario->estado=0;
        $tb_inventario->save();
        return ['inventario'=>$inventario];
    }
    public function parakardex(Request $request){
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        $observacion=$request->data;
        $inventario=Tb_detalle_inventario::where('tb_detalle_inventario.idEmpresa','=',$idEmpresa)
        ->where('diferencia','!=',0)->where('idInventario','=',$observacion)->get();
        foreach($inventario as $i){
            if($i->diferencia>0){
                //realizar entrada
                $now = new \DateTime();
                $fecha=$now->format('Y-m-d');
                $idGestionMateria=$i->idProducto;

                $kardex=Tb_detalle_inventario::where('tb_detalle_inventario.idEmpresa','=',$idEmpresa)
                ->where('tb_detalle_inventario.diferencia','!=',0)
                ->where('tb_detalle_inventario.idInventario','=',$observacion)->get();
                foreach($kardex as $kar){
                    $idProducto = $kar->idProducto;

                }
            }
        }
        return ['kardex'=>$kardex];
    }
    public function listar(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $id=$request->id;
        $productos = Tb_detalle_inventario::join('tb_gestion_materia_prima','tb_detalle_inventario.idProducto','=','tb_gestion_materia_prima.id')
        ->join('tb_unidad_base','tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id')
        ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
        ->select('tb_detalle_inventario.id','tb_detalle_inventario.idProducto','tb_detalle_inventario.cantidadSistema',
        'tb_gestion_materia_prima.gestionMateria as producto','tb_unidad_base.unidadBase')
        ->where('tb_detalle_inventario.idInventario','=',$id)
        ->orderBy('tb_detalle_inventario.idProducto','asc')
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
    }
    public function listar2(Request $request)
    {
        //cambios multiempresa
        foreach (Auth::user()->empresas as $empresa){
            $idEmpresa=$empresa['id'];
         }
        //cambios multiempresa

        //if(!$request->ajax()) return redirect('/');
        $id=$request->id;
        $productos = Tb_detalle_inventario::join('tb_gestion_materia_prima','tb_detalle_inventario.idProducto','=','tb_gestion_materia_prima.id')
        ->join('tb_unidad_base','tb_gestion_materia_prima.idUnidadBase','=','tb_unidad_base.id')
        ->where('tb_gestion_materia_prima.idEmpresa','=',$idEmpresa)
        ->select('tb_detalle_inventario.id','tb_detalle_inventario.idProducto','tb_detalle_inventario.cantidadSistema',
        'tb_detalle_inventario.cantidadActual','tb_detalle_inventario.diferencia',
        'tb_gestion_materia_prima.gestionMateria as producto','tb_unidad_base.unidadBase')
        ->where('tb_detalle_inventario.idInventario','=',$id)
        ->where('tb_detalle_inventario.diferencia','!=',0)
        ->orderBy('tb_detalle_inventario.idProducto','asc')
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

    }
}
