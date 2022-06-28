<?php

namespace App\Http\Controllers;
use App\Tb_nomina;
use App\Tb_vinculaciones;
use App\Tb_novedades;
use App\Tb_resumen_nomina;
use App\Tb_niveles_riesgo;
use App\Tb_riesgo_adicional;
use App\Tb_factores_nomina;
use App\Jobs\CalcularNomina;
use App\Jobs\CalculaNominaDestajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Tb_nominaController extends Controller
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
            $nomina = Tb_nomina::select('tb_nomina.id','tb_nomina.fechaInicio as fecha','tb_nomina.fechaFin','tb_nomina.tipo','tb_nomina.observacion','tb_nomina.estado')
            ->where('tb_nomina.idEmpresa','=',$idEmpresa)
            ->orderBy('tb_nomina.id','desc')->paginate(5);
        }
        else {
            # code...
            $nomina = Tb_nomina::select('tb_nomina.id','tb_nomina.fechaInicio as fecha','tb_nomina.fechaFin','tb_nomina.tipo','tb_nomina.observacion','tb_nomina.estado')
            ->where('tb_nomina.idEmpresa','=',$idEmpresa)
            ->where('tb_nomina.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('tb_nomina.id','desc')->paginate(5);

        }

        return [
            'pagination' => [
                'total'         =>$nomina->total(),
                'current_page'  =>$nomina->currentPage(),
                'per_page'      =>$nomina->perPage(),
                'last_page'     =>$nomina->lastPage(),
                'from'          =>$nomina->firstItem(),
                'to'            =>$nomina->lastItem(),
            ],
                'nomina' => $nomina
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
        $tb_nomina=new Tb_nomina();
        $tb_nomina->fechaInicio=$request->fechaInicio;
        $tb_nomina->fechaFin=$request->fechaFin;
        $tb_nomina->tipo=$request->tipo;
        $tb_nomina->observacion=$request->observacion;
        $tb_nomina->estado=1;
        $tb_nomina->idEmpresa=$idEmpresa;
        //$tb_nomina->fechaFin=$request->fechaFin;
        $tb_nomina->save();
    }

    /*public function update(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_nomina=Tb_nomina::findOrFail($request->id);
        //$tb_nomina->fechaInicio=$request->fechaInicio;
        $tb_nomina->fechaFIn=$request->fechaFin;
        $tb_nomina->estado=1;
        $tb_nomina->save();
    }*/
    public function delete(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        // busco el padre
        $tb_nomina=Tb_nomina::find($request->id);
        // busco el hijo y lo borro
        if($tb_nomina){
        $tb_novedades=Tb_novedades::where('idNomina',$request->id);
        if($tb_novedades){
        $tb_novedades->idNomina=$request->idNomina;
        $tb_novedades->delete();
        }
        // borro el padre
        $tb_nomina->delete();
        }
        //return ['nomina' => $nomina];
    }
    /*public function estado(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $tb_nomina=Tb_nomina::findOrFail($request->id);
        $tb_nomina->estado=0;
        $tb_nomina->save();
    }*/

//---------------------------------------------------------------------------------------------------//
// Cálculo de nómina fija
//---------------------------------------------------------------------------------------------------//

    //apertura función cálculo como jobs
    public function calcularNomina(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $nominaid=$request->id;
        $tb_nomina=Tb_nomina::findOrFail($nominaid);
        $flag=$tb_nomina->tipo;
        $tb_nomina->estado=2;
        $tb_nomina->save();
        //echo "El valor del id es $nominaid <br>";
        if($flag==1){
            //echo "El flag es 1";
            //
            CalcularNomina::dispatch($nominaid);
        }
        else{
            //echo "El flag es 2";
            //
            CalculaNominaDestajo::dispatch($nominaid);
            //aca iria la otra funcion
        }

    } //cierre función cálculo

//---------------------------------------------------------------------------------------------------//
// Cálculo de nómina fija antigua
//---------------------------------------------------------------------------------------------------//

public function pruebacalculo(Request $request)
{ //abre función cálculo
// recibo el id de nómina a calcular y saco los datos; de aca tomaré las fechas para revisar las novedades
    $nominaid = $request->id;

    $nomina = Tb_nomina::where('id','=',$request->id)
    ->select('id','fechaInicio','fechaFin','tipo','estado')->get();

    foreach($nomina as $guianomina){ //apertura foreach nomina
        $nominaid = $guianomina->id;
        $nominafechaInicio = $guianomina->fechaInicio;
        $nominafechaFin = $guianomina->fechaFin;
        $nominatipo = $guianomina->tipo;
        $nominaestado = $guianomina->estado;
        }

    //Distinct de novedades para sacar los empleados involucrados

    $empleadonovedades = Tb_novedades::where('idNomina','=',$nominaid)->groupby('idEmpleado')->distinct()->select('idEmpleado')->get();

     //dentro de este foreach voy a sacar los datos de los empleados que hacen parte de la nómina para buscar sus novedades
    foreach($empleadonovedades as $empleadonovedad){ //abre foreach vinculaciones

        $empleadonovedadid = $empleadonovedad->idEmpleado;

        // basado en el rango de fechas entre inicio y fin verifico cuales vinculaciones están activas para trabajar con los empleados
        $vinculaciones = Tb_vinculaciones::where('idEmpleado','=',$empleadonovedadid)
        ->whereNull('fechaFin')
        ->orWhere([
            ['tb_vinculaciones.fechaInicio', '>', $nominafechaInicio],
            ['tb_vinculaciones.fechaFin', '<', $nominafechaFin],
        ])
        ->orWhere([
            ['tb_vinculaciones.fechaInicio', '<', $nominafechaInicio],
            ['tb_vinculaciones.fechaFin', '<', $nominafechaFin],
        ])
        ->select('id','tipoContrato','tipoSalario','salarioBasicoMensual','fechaInicio','tiempoContrato','fechaFin','idEmpleado','idNivelArl','estado')->get();

         //dentro de este foreach voy a sacar los datos de los empleados que hacen parte de la nómina para buscar sus novedades
        foreach($vinculaciones as $guiavinculaciones){ //abre foreach vinculaciones

            $vinculacionesid = $guiavinculaciones->id;
            $vinculacionestipoVinculacion = $guiavinculaciones->tipoContrato;
            $vinculacionessalarioBasicoMensual = $guiavinculaciones->salarioBasicoMensual;
            $vinculacionesfechaInicio = $guiavinculaciones->fechaInicio;
            $vinculacionestiempoContrato = $guiavinculaciones->tiempoContrato;
            $vinculacionesfechaFin = $guiavinculaciones->fechaFin;
            $vinculacionesidEmpleado = $guiavinculaciones->idEmpleado;
            $vinculacionesidNivelArl = $guiavinculaciones->idNivelArl;
            $vinculacionesidEps = $guiavinculaciones->idEps;
            $vinculacionesidPensiones = $guiavinculaciones->idPensiones;
            $vinculacionesestado = $guiavinculaciones->estado;

            $sueldobase=$vinculacionessalarioBasicoMensual; //valor del empleado
        }

        $diaslabor=0;
        $valordiaslabor=0;
        $extrasdiurnas=0; // ejemplo
        $valorextrasdiurnas=0;
        $extrasnocturnas=0;
        $valorextrasnocturnas=0;
        $horasdominicales=0;
        $valorhorasdominicales=0;
        $extrasdominicalesdiurnas=0;
        $valorextrasdominicalesdiurnas=0;
        $extrasdominicalesnocturnas=0;
        $valorextrasdominicalesnocturnas=0;
        $horasrecargos=0;
        $valorhorasrecargos=0;
        $primaextralegal=0;
        $valorprimaextralegal=0;
        $bonificaciones=0; // valor bonif
        $valorbonificaciones=0;
        $comisiones=0; // valor comisiones
        $valorcomisiones=0;
        $viaticos=0; // valor viaticos
        $valorviaticos=0;
        $nofactorsalarial=0; // valor no factor
        $valornofactorsalarial=0;
        // seccion descuentos
        $porcentajeAdicional=0;
        $retencion=0; // valor retencion
        $valorretencion=0;
        $sindicato=0; // valor sindicato
        $valorsindicato=0;
        $prestamos=0; // valor prestamos
        $valorprestamos=0;
        $embargos=0; // valor embargos
        $valorembargos=0;
        $otros=0; // valor otros
        $valorotros=0;
        $descuentosalud=0;
        $descuentopension=0;
        $fondoSolidaridad=0;
        //hasta aca vienen de la tabla novedades

        //estos vienen de la tabla novedades

    $novedades = Tb_novedades::where('tb_novedades.idEmpleado','=',$empleadonovedadid)
    ->where('tb_novedades.idNomina','=',$nominaid)->get(); //De aca me voy a traer los de la nomina sin liquidar, ojo tengo que ir cambiando el valor de ella

    foreach($novedades as $guianovedades){ //apertura calculo por empleado de novedades
        $novedadesid = $guianovedades->id;
        $novedadesfechaNovedad = $guianovedades->fechaNovedad;
        $novedadesconcepto = $guianovedades->concepto;
        $novedadescantidad = $guianovedades->cantidad;
        $novedadesunitario = $guianovedades->unitario;
        $novedadesvalor = $guianovedades->valor;
        $novedadesobservacion = $guianovedades->observacion;
        $novedadestipologia = $guianovedades->tipologia;
        $novedadesidEmpleado = $guianovedades->idEmpleado;
        $novedadesidNomina = $guianovedades->idNomina;

        if($novedadesconcepto==1){
            $diaslabor=$diaslabor+$novedadescantidad;
            $valordiaslabor=$valordiaslabor+$novedadesvalor;
        }
        else if($novedadesconcepto==2 and $novedadesobservacion=='Extra Diurna'){
            $extrasdiurnas=$extrasdiurnas+$novedadescantidad;
            $valorextrasdiurnas=$valorextrasdiurnas+$novedadesvalor;
        }
        else if($novedadesconcepto==2 and $novedadesobservacion=='Extra Nocturna'){
            $extrasnocturnas=$extrasnocturnas+$novedadescantidad;
            $valorextrasnocturnas=$valorextrasnocturnas+$novedadesvalor;
        }
        else if($novedadesconcepto==2 and $novedadesobservacion=='Hora Dominical o Festiva'){
            $horasdominicales=$horasdominicales+$novedadescantidad;
            $valorhorasdominicales=$valorhorasdominicales+$novedadesvalor;
        }
        else if($novedadesconcepto==2 and $novedadesobservacion=='Extra Dominical o Festiva Diurna'){
            $extrasdominicalesdiurnas=$extrasdominicalesdiurnas+$novedadescantidad;
            $valorextrasdominicalesdiurnas=$valorextrasdominicalesdiurnas+$novedadesvalor;
        }
        else if($novedadesconcepto==2 and $novedadesobservacion=='Extra Dominical o Festiva Nocturna'){
            $extrasdominicalesnocturnas=$extrasdominicalesnocturnas+$novedadescantidad;
            $valorextrasdominicalesnocturnas=$valorextrasdominicalesnocturnas+$novedadesvalor;
        }
        else if($novedadesconcepto==2 and $novedadesobservacion=='Recargos'){
            $horasrecargos=$horasrecargos+$novedadescantidad;
            $valorhorasrecargos=$valorhorasrecargos+$novedadesvalor;
        }
        else if($novedadesconcepto==3){
            $primaextralegal=$primaextralegal+$novedadescantidad;
            $valorprimaextralegal=$valorprimaextralegal+$novedadesvalor;
        }
        else if($novedadesconcepto==4){
            $bonificaciones=$bonificaciones+$novedadescantidad;
            $valorbonificaciones=$valorbonificaciones+$novedadesvalor;
        }
        else if($novedadesconcepto==5){
            $comisiones=$comisiones+$novedadescantidad;
            $valorcomisiones=$valorcomisiones+$novedadesvalor;
        }
        else if($novedadesconcepto==6){
            $viaticos=$viaticos+$novedadescantidad;
            $valorviaticos=$valorviaticos+$novedadesvalor;
        }
        else if($novedadesconcepto==7){
            $nofactorsalarial=$nofactorsalarial+$novedadescantidad;
            $valornofactorsalarial=$valornofactorsalarial+$novedadesvalor;
        }
        else if($novedadesconcepto==50){
            $retencion=$retencion+$novedadescantidad;
            $valorretencion=$valorretencion+$novedadesvalor;
        }
        else if($novedadesconcepto==51){
            $sindicato=$sindicato+$novedadescantidad;
            $valorsindicato=$valorsindicato+$novedadesvalor;
        }
        else if($novedadesconcepto==52){
            $prestamos=$prestamos+$novedadescantidad;
            $valorprestamos=$valorprestamos+$novedadesvalor;
        }
        else if($novedadesconcepto==53){
            $embargos=$embargos+$novedadescantidad;
            $valorembargos=$valorembargos+$novedadesvalor;
        }
        else if($novedadesconcepto==54){
            $otros=$otros+$novedadescantidad;
            $valorotros=$valorotros+$novedadesvalor;
        }

    //estos vienen de la tabla factores nomina
    $factores = Tb_factores_nomina::where('id','=','1')
    ->select('id','extraDiurna','extraNocturna','horaDominical','festivaDiurna','festivaNocturna','recargos','minimolegal','auxilio')->get();

    foreach($factores as $totalg){
        $multextrasdiurnas = $totalg->extraDiurna;
        $multextrasnocturnas = $totalg->extraNocturna;
        $multhorasdominicales = $totalg->horaDominical;
        $multextrasdominicalesdiurnas = $totalg->festivaDiurna;
        $multextrasdominicalesnocturnas = $totalg->festivaNocturna;
        $multrecargos = $totalg->recargos;
        $salariominimo = $totalg->minimolegal;
        $auxilioley = $totalg->auxilio;
    }
    //hasta aca vienen de la tabla factores nomina

    //ojo aca, estos valores están estáticos, pero deberían estar almacenados no se donde
    $multdescuentosalud=0.04;
    $multdescuentopension=0.04;
    //ojo aca, estos valores están estáticos, pero deberían estar almacenados no se donde

    $topesalarios=(25*$salariominimo);
    $diasbase=30; // total dias mes
    $horasbase=(8*$diasbase);
    $sueldobasehora=($sueldobase/$horasbase);
    $sueldocalculado=(($sueldobase/$diasbase)*$diaslabor);

    $totalextrasdiurnas=intval($extrasdiurnas*$multextrasdiurnas*$sueldobasehora); // ejemplo calculado
    $totalextrasnocturnas=intval($extrasnocturnas*$multextrasnocturnas*$sueldobasehora); // ejemplo calculado
    $totalhorasdominicales=intval($horasdominicales*$multhorasdominicales*$sueldobasehora); // ejemplo calculado
    $totalextrasdominicalesdiurnas=intval($extrasdominicalesdiurnas*$multextrasdominicalesdiurnas*$sueldobasehora); // ejemplo calculado
    $totalextrasdominicalesnocturnas=intval($extrasdominicalesnocturnas*$multextrasdominicalesnocturnas*$sueldobasehora); // ejemplo calculado

    $horasextras=$totalextrasdiurnas+$totalextrasnocturnas+$totalhorasdominicales+$totalextrasdominicalesdiurnas+$totalextrasdominicalesnocturnas;
    //suma de todas las tipologias de horas extras ejemplo calculado

    //-------------------------------------------------------------------------------------------------------------------------//
    $total1extrasdiurnas=intval($valorextrasdiurnas); // ejemplo novedad
    $total1extrasnocturnas=intval($valorextrasnocturnas); // ejemplo novedad
    $total1horasdominicales=intval($valorhorasdominicales); // ejemplo novedad
    $total1extrasdominicalesdiurnas=intval($valorextrasdominicalesdiurnas); // ejemplo novedad
    $total1extrasdominicalesnocturnas=intval($valorextrasdominicalesnocturnas); // ejemplo novedad

    $horas1extras=$total1extrasdiurnas+$total1extrasnocturnas+$total1horasdominicales+$total1extrasdominicalesdiurnas+$total1extrasdominicalesnocturnas;
    //suma de todas las tipologias de horas extras ejemplo novedad
    //-------------------------------------------------------------------------------------------------------------------------//

    $recargos=intval($horasrecargos*$multrecargos*$sueldobasehora); //valor de los recargos ejemplo calculado

    //Variante importante, toma en cuenta los valores para los conceptos que no son extras
    $devengados=($sueldocalculado+$horasextras+$recargos+$valorbonificaciones+$valorcomisiones+$valorviaticos+$valornofactorsalarial);
    //suma de conceptos de horas extras y demás menos el auxilio de transporte ejemplo calculado

    //-------------------------------------------------------------------------------------------------------------------------//
    //Variante importante, toma en cuenta los valores para los conceptos que no son extras
    $devengados1=($valordiaslabor+$horas1extras+$recargos+$valorbonificaciones+$valorcomisiones+$valorviaticos+$valornofactorsalarial);
    //suma de conceptos de horas extras y demás menos el auxilio de transporte ejemplo novedad
    //-------------------------------------------------------------------------------------------------------------------------//

    if($sueldobase<=(2*$salariominimo)){
        $auxilio=intval(($auxilioley/$diasbase)*$diaslabor);
    }
    else{
        $auxilio=0;
    }

    $devengadoauxilio=($devengados+$auxilio); // calculado
    $ibcsalario=($devengados-$valornofactorsalarial); // calculado

    $devengado1auxilio=($devengados1+$auxilio); //de novedad
    $ibcsalario1=($devengados1-$valornofactorsalarial); //de novedad

    /*
        Validación ibc e ibc con tope calculado
    */

    if($ibcsalario>0){ //si el ibcsalario tiene valor
        if($ibcsalario>$topesalarios){ // si el ibcsalario tiene valor y es mayor que los 25 salarios
            $ibccontope=$topesalarios;
        }
        else{ // else si el ibcsalario tiene valor y es menor que los 25 salarios

            if($diaslabor<$diasbase){ // si el ibcsalario tiene valor y es menor que los 25 salarios y dias laborados son menos que los del mes

                if($diaslabor==0){
                    $ibcsadi=0;
                }
                else{
                    $ibcsadi=($ibcsalario/$diaslabor);
                }

                if(($ibcsadi)>($salariominimo/$diasbase)){ // si el ibcsalario tiene valor y es menor que los 25 salarios y dias laborados son menos que los del mes y el ibcsalario es mas que el minimo
                    $ibccontope=$ibcsalario;
                }
                else{ // else si el ibcsalario tiene valor y es menor que los 25 salarios y dias laborados son menos que los del mes y el ibcsalario es menos que el minimo
                    $ibccontope=(($salariominimo/$diasbase)*$diaslabor);
                }
            }
            else{ // else si el ibcsalario tiene valor y es menor que los 25 salarios y dias laborados son los del mes
                if($ibcsalario<$salariominimo){
                    $ibccontope=$salariominimo;
                }
                else{
                    $ibccontope=$ibcsalario;
                }
            }
        }
    }
    else{ //else si el ibcsalario no tiene valor
        $ibccontope=0;
    }

    /*
        Fin Validación ibc e ibc con tope calculado
    */

    //-----------------------------------------------------------------------------------------------//
    /*
        Validación ibc e ibc con tope novedades
    */

    if($ibcsalario1>0){ //si el ibcsalario tiene valor
        if($ibcsalario1>$topesalarios){ // si el ibcsalario tiene valor y es mayor que los 25 salarios
            $ibccontope1=$topesalarios;
        }
        else{ // else si el ibcsalario tiene valor y es menor que los 25 salarios

            if($diaslabor<$diasbase){ // si el ibcsalario tiene valor y es menor que los 25 salarios y dias laborados son menos que los del mes

                if($diaslabor==0){
                    $ibcsadi=0;
                }
                else{
                    $ibcsadi=($ibcsalario1/$diaslabor);
                }

                if(($ibcsadi)>($salariominimo/$diasbase)){ // si el ibcsalario tiene valor y es menor que los 25 salarios y dias laborados son menos que los del mes y el ibcsalario es mas que el minimo
                    $ibccontope1=$ibcsalario1;
                }
                else{ // else si el ibcsalario tiene valor y es menor que los 25 salarios y dias laborados son menos que los del mes y el ibcsalario es menos que el minimo
                    $ibccontope1=(($salariominimo/$diasbase)*$diaslabor);
                }
            }
            else{ // else si el ibcsalario tiene valor y es menor que los 25 salarios y dias laborados son los del mes
                if($ibcsalario1<$salariominimo){
                    $ibccontope1=$salariominimo;
                }
                else{
                    $ibccontope1=$ibcsalario1;
                }
            }
        }
    }
    else{ //else si el ibcsalario no tiene valor
        $ibccontope1=0;
    }

    /*
        Fin Validación ibc e ibc con tope novedades
    */
    //-----------------------------------------------------------------------------------------------//

    //-------------------------------------------------------------------------------------------------------------------------//
    //---------------------------------------------------------DEDUCIDOS-------------------------------------------------------//
    //-------------------------------------------------------------------------------------------------------------------------//

    //ojo aca, estos valores están estáticos, pero deberían estar almacenados no se donde
    $multdescuentosalud=0.04;
    $multdescuentopension=0.04;
    //ojo aca, estos valores están estáticos, pero deberían estar almacenados no se donde

    $riesgosadicional = Tb_riesgo_adicional::select('rangoSalarioMin','rangoSalarioMax','porcentajeAdicional')->get();
    foreach($riesgosadicional as $riesgoadicional){
        $rangoSalarioMin = $riesgoadicional->rangoSalarioMin;
        $rangoSalarioMax = $riesgoadicional->rangoSalarioMax;

        $salarioMin=$rangoSalarioMin*$salariominimo;
        $salarioMax=$rangoSalarioMax*$salariominimo;

        if(($ibccontope>=$salarioMin) && ($ibccontope<=$salarioMax)){
            $porcentajeAdicional = $riesgoadicional->porcentajeAdicional;
        }
    }

    $fondoSolidaridad=$ibccontope*$porcentajeAdicional;

    $descuentosalud=$ibccontope*$multdescuentosalud;
    $descuentopension=$ibccontope*$multdescuentopension;

    $descuentosalud1=$ibccontope1*$multdescuentosalud;
    $descuentopension1=$ibccontope1*$multdescuentopension;

    $deducidos=$valorsindicato+$valorprestamos+$valorembargos+$valorotros+$descuentosalud+$descuentopension+$fondoSolidaridad+$valorretencion; // ejemplo calculado

    $deducidos1=$valorsindicato+$valorprestamos+$valorembargos+$valorotros+$descuentosalud1+$descuentopension1+$fondoSolidaridad+$valorretencion; // ejemplo novedad

    //-------------------------------------------------------------------------------------------------------------------------//

    //-----------------------------------------------------TOTAL A PAGAR-------------------------------------------------------//
    //-------------------------------------------------------------------------------------------------------------------------//

    $totalapagar=$devengadoauxilio-$deducidos; // ejemplo calculado

    $totalapagar1=$devengado1auxilio-$deducidos1; // ejemplo novedad

    //-------------------------------------------------------------------------------------------------------------------------//

    //-----------------------------------------------------PARAFISCALES--------------------------------------------------------//
    //-------------------------------------------------------------------------------------------------------------------------//

    if($ibccontope>(10*$salariominimo)){ // si el ibc con tope es mayor que los 10 salarios
        $aportesalud=($ibccontope*0.085);
    }
    else{
        $aportesalud=0;
    }

    $aportepension=($ibccontope*0.12);

    $riesgos = Tb_niveles_riesgo::where('id','=',$vinculacionesidNivelArl)
    ->select('id','nivelArl','porcentajeNivelArl')->get();
    foreach($riesgos as $riesgo){
        $porcentajeNivelArl = $riesgo->porcentajeNivelArl;
        }

    $aportearl=($ibccontope*$porcentajeNivelArl); //valor riesgo arl segun vinculacion

    if($ibccontope>(10*$salariominimo)){ // si el ibc con tope es mayor que los 10 salarios hay que preguntar si esta bien
        $aporteicbf=($ibccontope*0.03);
        $aportesena=($ibccontope*0.02);
    }
    else{
        $aporteicbf=0;
        $aportesena=0;
    }

    $cajacompensacion=($ibccontope*0.04);

    $basecalculo=($ibcsalario+$auxilio);

    $cesantias=($basecalculo*0.0833);

    $interescesantias=($cesantias*0.12);

    $primaservicios=($basecalculo*0.0833);

    $vacaciones=($ibcsalario*0.0417);

    $costototalmensual=$aportesalud+$aportepension+$aportearl+$aportesena+$aporteicbf+$cajacompensacion+$cesantias+$interescesantias+$primaservicios+$vacaciones+$devengadoauxilio;
    //-------------------------------------------------------------------------------------------------------------------------//
        } // cierre calculo por empleado de novedades
/**/
        $tb_resumen_nomina=new Tb_resumen_nomina();
        $tb_resumen_nomina->fechaVinculacion=$vinculacionesfechaInicio;
        $tb_resumen_nomina->tipoContrato=$vinculacionestipoVinculacion;
        $tb_resumen_nomina->diasLaborados=$diaslabor;
        $tb_resumen_nomina->valordiasLaborados=$valordiaslabor;
        $tb_resumen_nomina->extrasDiurnas=$extrasdiurnas;
        $tb_resumen_nomina->valorextrasDiurnas=$valorextrasdiurnas;
        $tb_resumen_nomina->extrasNocturnas=$extrasnocturnas;
        $tb_resumen_nomina->valorextrasNocturnas=$valorextrasnocturnas;
        $tb_resumen_nomina->horasDominicales=$horasdominicales;
        $tb_resumen_nomina->valorhorasDominicales=$valorhorasdominicales;
        $tb_resumen_nomina->extrasDominicalesDiurnas=$extrasdominicalesdiurnas;
        $tb_resumen_nomina->valorextrasDominicalesDiurnas=$valorextrasdominicalesdiurnas;
        $tb_resumen_nomina->extrasDominicalesNocturnas=$extrasdominicalesnocturnas;
        $tb_resumen_nomina->valorextrasDominicalesNocturnas=$valorextrasdominicalesnocturnas;
        $tb_resumen_nomina->recargos=$horasrecargos;
        $tb_resumen_nomina->valorrecargos=$recargos;
        $tb_resumen_nomina->totalhorasExtras=$horasextras;
        $tb_resumen_nomina->totalrecargos=$valorhorasrecargos;
        $tb_resumen_nomina->totalExtrasyRecargos=$valorhorasrecargos+$horasextras;
        $tb_resumen_nomina->primaExtralegal=$valorprimaextralegal;
        $tb_resumen_nomina->bonificaciones=$valorbonificaciones;
        $tb_resumen_nomina->comisiones=$valorcomisiones;
        $tb_resumen_nomina->viaticos=$valorviaticos;
        $tb_resumen_nomina->noFactorSalarial=$valornofactorsalarial;
        $tb_resumen_nomina->devengadoSinAuxilio=$devengados;
        $tb_resumen_nomina->auxilio=$auxilio;
        $tb_resumen_nomina->devengadoConAuxilio=$devengadoauxilio;
        $tb_resumen_nomina->ibcSalario=$ibcsalario;
        $tb_resumen_nomina->ibcConTope=$ibccontope;
        $tb_resumen_nomina->descuentoSalud=$descuentosalud;
        $tb_resumen_nomina->descuentoPension=$descuentopension;

        $tb_resumen_nomina->fondoSolidaridad=$fondoSolidaridad;
        $tb_resumen_nomina->retencion=$valorretencion;

        $tb_resumen_nomina->sindicato=$valorsindicato;
        $tb_resumen_nomina->prestamos=$valorprestamos;
        $tb_resumen_nomina->otros=$valorotros;
        $tb_resumen_nomina->totalDeducido=$deducidos;
        $tb_resumen_nomina->totalPagar=$totalapagar;
        $tb_resumen_nomina->aporteSalud=$aportesalud;
        $tb_resumen_nomina->aportePension=$aportepension;
        $tb_resumen_nomina->aporteArl=$aportearl;
        $tb_resumen_nomina->aporteIcbf=$aporteicbf;
        $tb_resumen_nomina->aporteSena=$aportesena;
        $tb_resumen_nomina->aporteCaja=$cajacompensacion;
        $tb_resumen_nomina->cesantias=$cesantias;
        $tb_resumen_nomina->interesesCesantias=$interescesantias;
        $tb_resumen_nomina->primaServicios=$primaservicios;
        $tb_resumen_nomina->vacaciones=$vacaciones;
        $tb_resumen_nomina->costoTotalMensual=$costototalmensual;
        $tb_resumen_nomina->sueldoBasicoMensual=$sueldobase;
        $tb_resumen_nomina->idEmpleado=$empleadonovedadid;
        $tb_resumen_nomina->idNomina=$nominaid;
        $tb_resumen_nomina->save();

    } //cierre foreach vinculaciones

// muestra temporal de salida
echo "El sueldo basico es ".$sueldobase."<br>";
echo "<hr><br>";
echo "Cantidad extras diurnas es ".$extrasdiurnas."<br>";
echo "Cantidad extras nocturnas es ".$extrasnocturnas."<br>";
echo "Cantidad horas dominicales es ".$horasdominicales."<br>";
echo "Cantidad extras diurnas dominicales es ".$extrasdominicalesdiurnas."<br>";
echo "Cantidad extras nocturnas dominicales es ".$extrasdominicalesnocturnas."<br>";
echo "<hr><br>";
echo "Valor extras diurnas es ".$totalextrasdiurnas."<br>";
echo "Valor extras nocturnas es ".$totalextrasnocturnas."<br>";
echo "Valor horas dominicales es ".$totalhorasdominicales."<br>";
echo "Valor extras diurnas dominicales es ".$totalextrasdominicalesdiurnas."<br>";
echo "Valor extras nocturnas dominicales es ".$totalextrasdominicalesnocturnas."<br>";
echo "<hr><br>";
echo "Valor extras es ".$horasextras."<br>";
echo "<hr><br>";
echo "Valor recargos es ".$recargos."<br>";
echo "Valor bonificaciones es ".$valorbonificaciones."<br>";
echo "Valor comisiones es ".$valorcomisiones."<br>";
echo "Valor viaticos es ".$valorviaticos."<br>";
echo "<hr><br>";
echo "Valor retencion es ".$valorretencion."<br>";
echo "<hr><br>";
echo "Valor fondo solidaridad es ".$fondoSolidaridad."<br>";
echo "<hr><br>";
echo "Valor descuentosalud es ".$descuentosalud."<br>";
echo "<hr><br>";
echo "Valor descuentopension es ".$descuentopension."<br>";
echo "<hr><br>";
echo "Valor devengados es ".$devengados."<br>";
echo "<hr><br>";
echo "Valor no factor salarial es ".$valornofactorsalarial."<br>";
echo "<hr><br>";
echo "Valor devengado con auxilio es ".$devengadoauxilio."<br>";
echo "<hr><br>";
echo "Valor deducidos es ".$deducidos."<br>";
echo "<hr><br>";
echo "Valor ibc salario ".$ibcsalario."<br>";
echo "Valor ibc tope ".$ibccontope."<br>";
echo "<hr><br>";
echo "Valor a pagar ".$totalapagar."<br>";
echo "<hr><br>";
echo "Aporte salud ".$aportesalud."<br>";
echo "<hr><br>";
echo "Aporte pension ".$aportepension."<br>";
echo "<hr><br>";
echo "Aporte arl ".$aportearl."<br>";
echo "<hr><br>";
echo "Aporte sena ".$aportesena."<br>";
echo "<hr><br>";
echo "Aporte icbf ".$aporteicbf."<br>";
echo "<hr><br>";
echo "Aporte caja compensacion ".$cajacompensacion."<br>";
echo "<hr><br>";
echo "Aporte cesantias ".$cesantias."<br>";
echo "<hr><br>";
echo "Intereses cesantias ".$interescesantias."<br>";
echo "<hr><br>";
echo "Prima servicios ".$primaservicios."<br>";
echo "<hr><br>";
echo "Vacaciones ".$vacaciones."<br>";
echo "<hr><br>";
echo "Valor total costos ".$costototalmensual."<br>";
echo "<hr><br>";

    $tb_cierre_nomina=Tb_nomina::findOrFail($nominaid);
    $tb_cierre_nomina->estado=0;
    $tb_cierre_nomina->save();

} //cierre función cálculo

//---------------------------------------------------------------------------------------------------//
// Cálculo de nómina destajo vieja
//---------------------------------------------------------------------------------------------------//
public function pruebadestajo(Request $request)
{ //abre función cálculo
    // recibo el id de nómina a calcular y saco los datos; de aca tomaré las fechas para revisar las novedades
            $nominaid = $request->id;

            $nomina = Tb_nomina::where('id','=',$request->id)
            ->select('id','fechaInicio','fechaFin','tipo','estado')->get();

            foreach($nomina as $guianomina){ //apertura foreach nomina
                $nominaid = $guianomina->id;
                $nominafechaInicio = $guianomina->fechaInicio;
                $nominafechaFin = $guianomina->fechaFin;
                $nominatipo = $guianomina->tipo;
                $nominaestado = $guianomina->estado;
                }

                //estos vienen de la tabla factores nomina
                $factores = Tb_factores_nomina::where('id','=','1')
                ->select('id','extraDiurna','extraNocturna','horaDominical','festivaDiurna','festivaNocturna','recargos','minimolegal','auxilio')->get();

                foreach($factores as $totalg){
                    $salariominimo = $totalg->minimolegal;
                    $auxilioley = $totalg->auxilio;
                }
                //hasta aca vienen de la tabla factores nomina

                //ojo aca, estos valores están estáticos, pero deberían estar almacenados no se donde
                $multdescuentosalud=0.04;
                $multdescuentopension=0.04;
                //ojo aca, estos valores están estáticos, pero deberían estar almacenados no se donde

                $topesalarios=(25*$salariominimo);
                $diasbase=30; // total dias mes
                $horasbase=(8*$diasbase);

            //Distinct de novedades para sacar los empleados involucrados

            $empleadonovedades = Tb_novedades::where('idNomina','=',$nominaid)->groupby('idEmpleado')->distinct()->select('idEmpleado')->get();

             //dentro de este foreach voy a sacar los datos de los empleados que hacen parte de la nómina para buscar sus novedades
            foreach($empleadonovedades as $empleadonovedad){ //abre foreach novedades

                $empleadonovedadid = $empleadonovedad->idEmpleado;

                // basado en el rango de fechas entre inicio y fin verifico cuales vinculaciones están activas para trabajar con los empleados
                $vinculaciones = Tb_vinculaciones::where('idEmpleado','=',$empleadonovedadid)
                ->whereNull('fechaFin')
                ->orWhere([
                    ['tb_vinculaciones.fechaInicio', '>', $nominafechaInicio],
                    ['tb_vinculaciones.fechaFin', '<', $nominafechaFin],
                ])
                ->orWhere([
                    ['tb_vinculaciones.fechaInicio', '<', $nominafechaInicio],
                    ['tb_vinculaciones.fechaFin', '<', $nominafechaFin],
                ])
                ->select('id','tipoContrato','tipoSalario','salarioBasicoMensual','fechaInicio','tiempoContrato','fechaFin','idEmpleado','idNivelArl','estado')->get();

                 //dentro de este foreach voy a sacar los datos de los empleados que hacen parte de la nómina para buscar sus novedades
                foreach($vinculaciones as $guiavinculaciones){ //abre foreach vinculaciones

                    $vinculacionesid = $guiavinculaciones->id;
                    $vinculacionestipoVinculacion = $guiavinculaciones->tipoContrato;
                    $vinculacionessalarioBasicoMensual = $guiavinculaciones->salarioBasicoMensual;
                    $vinculacionesfechaInicio = $guiavinculaciones->fechaInicio;
                    $vinculacionestiempoContrato = $guiavinculaciones->tiempoContrato;
                    $vinculacionesfechaFin = $guiavinculaciones->fechaFin;
                    $vinculacionesidEmpleado = $guiavinculaciones->idEmpleado;
                    $vinculacionesidNivelArl = $guiavinculaciones->idNivelArl;
                    $vinculacionesidEps = $guiavinculaciones->idEps;
                    $vinculacionesidPensiones = $guiavinculaciones->idPensiones;
                    $vinculacionesestado = $guiavinculaciones->estado;

                    $sueldobase=$vinculacionessalarioBasicoMensual; //valor del empleado
                }

                // seccion descuentos
                $valortareas=0;
                $cantidadtareas=0;
                $valorvacaciones=0;
                $valorprima=0;
                $valorcesantias=0;
                $valorintereses=0;
                $valorsalud=0;
                $valorpension=0;
                $valorarl=0;
                $valorcaja=0;
                $valoreps=0;
                $valorpension=0;
                $acumuladovaloreps=0;
                $acumuladovalorpension=0;
                $prestamos=0; // valor prestamos
                $valorprestamos=0;
                $otros=0; // valor otros
                $valorotros=0;
                $descuentosalud=0;
                $descuentopension=0;
                $fondoSolidaridad=0;
                $retencion=0;
                $valorretencion=0;
                //hasta aca vienen de la tabla novedades

                //estos vienen de la tabla novedades
                $sueldobasico=0;

                $novedadesbase = Tb_novedades::where('tb_novedades.idEmpleado','=',$empleadonovedadid)
                ->where('tb_novedades.idNomina','=',$nominaid)
                ->where('tb_novedades.concepto','=','1')->get(); //De aca me voy a traer los de la nomina sin liquidar, ojo tengo que ir cambiando el valor de ella

                foreach($novedadesbase as $guianovedadesbase){ //apertura calculo por empleado de novedades
                    $novedadesvalorbase = $guianovedadesbase->valor;
                    $sueldobasico=$sueldobasico+$novedadesvalorbase;
                }


            $novedades = Tb_novedades::where('tb_novedades.idEmpleado','=',$empleadonovedadid)
            ->where('tb_novedades.idNomina','=',$nominaid)->get(); //De aca me voy a traer los de la nomina sin liquidar, ojo tengo que ir cambiando el valor de ella

            foreach($novedades as $guianovedades){ //apertura calculo por empleado de novedades
                $novedadesid = $guianovedades->id;
                $novedadesfechaNovedad = $guianovedades->fechaNovedad;
                $novedadesconcepto = $guianovedades->concepto;
                $novedadescantidad = $guianovedades->cantidad;
                $novedadesunitario = $guianovedades->unitario;
                $novedadesvalor = $guianovedades->valor;
                $novedadesobservacion = $guianovedades->observacion;
                $novedadestipologia = $guianovedades->tipologia;
                $novedadesidEmpleado = $guianovedades->idEmpleado;
                $novedadesidNomina = $guianovedades->idNomina;


            //ojo aca, estos valores están estáticos, pero deberían estar almacenados no se donde
            $multdescuentosalud=0.04;
            $multdescuentopension=0.04;
            //ojo aca, estos valores están estáticos, pero deberían estar almacenados no se donde

    //para destajo tengo en cuenta tipologias de entradas: 3- sin provision 4- solo liquidacion 5- solo parafiscales 6-con todo
    //para destajo tengo en cuenta tipologias de salidas: 2- salida

                if($novedadesconcepto==1 && $novedadestipologia==3){
                    $valoreps=0;
                    $valorpension=0;

                    $valortareas=$valortareas+$novedadesvalor;
                    $cantidadtareas=$cantidadtareas+$novedadescantidad;
                    $acumuladovaloreps=$acumuladovaloreps+$valoreps;
                    $acumuladovalorpension=$acumuladovalorpension+$valorpension;
                }
                else if($novedadesconcepto==1 && $novedadestipologia==4){

                    $valoreps=$novedadesvalor*$multdescuentosalud;
                    $valorpension=$novedadesvalor*$multdescuentopension;

                    $valortareas=$valortareas+$novedadesvalor;
                    $cantidadtareas=$cantidadtareas+$novedadescantidad;
                    $acumuladovaloreps=$acumuladovaloreps+$valoreps;
                    $acumuladovalorpension=$acumuladovalorpension+$valorpension;
                }
                else if($novedadesconcepto==50){
                    $retencion=$retencion+$novedadescantidad;
                    $valorretencion=$valorretencion+$novedadesvalor;
                }
                else if($novedadesconcepto==52){
                    $prestamos=$prestamos+$novedadescantidad;
                    $valorprestamos=$valorprestamos+$novedadesvalor;
                }
                else if($novedadesconcepto==54){
                    $otros=$otros+$novedadescantidad;
                    $valorotros=$valorotros+$novedadesvalor;
                }

            //-------------------------------------------------------------------------------------------------------------------------//
            $devengados=($sueldobasico);
            //-------------------------------------------------------------------------------------------------------------------------//

            if(($sueldobasico<=(2*$salariominimo)) && ($sueldobasico>=$salariominimo)){
                $auxilio=intval($auxilioley);
            }
            else{
                $auxilio=0;
            }

            $devengadoauxilio=($devengados+$auxilio); // calculado
            $ibcsalario=($devengados); // calculado
            /*
                Validación ibc e ibc con tope calculado
            */

            if($ibcsalario>0){ //si el ibcsalario tiene valor
                if($ibcsalario>$topesalarios){ // si el ibcsalario tiene valor y es mayor que los 25 salarios
                    $ibccontope=$topesalarios;
                }
                else{ // else si el ibcsalario tiene valor y es menor que los 25 salarios
                        if($ibcsalario<$salariominimo){
                            $ibccontope=$salariominimo;
                        }
                        else{
                            $ibccontope=$ibcsalario;
                        }
                }
            }
            else{ //else si el ibcsalario no tiene valor
                $ibccontope=0;
            }

            /*
                Fin Validación ibc e ibc con tope calculado
            */

            //-------------------------------------------------------------------------------------------------------------------------//
            //---------------------------------------------------------DEDUCIDOS-------------------------------------------------------//
            //-------------------------------------------------------------------------------------------------------------------------//

            //ojo aca, estos valores están estáticos, pero deberían estar almacenados no se donde

            $riesgosadicional = Tb_riesgo_adicional::select('rangoSalarioMin','rangoSalarioMax','porcentajeAdicional')->get();
            foreach($riesgosadicional as $riesgoadicional){
                $rangoSalarioMin = $riesgoadicional->rangoSalarioMin;
                $rangoSalarioMax = $riesgoadicional->rangoSalarioMax;

                $salarioMin=$rangoSalarioMin*$salariominimo;
                $salarioMax=$rangoSalarioMax*$salariominimo;

                if(($ibccontope>=$salarioMin) && ($ibccontope<=$salarioMax)){
                    $porcentajeAdicional = $riesgoadicional->porcentajeAdicional;
                }
            }

            $fondoSolidaridad=$ibccontope*$porcentajeAdicional;

            //$descuentosalud=$ibccontope*$multdescuentosalud;
            //$descuentopension=$ibccontope*$multdescuentopension;

            $descuentosalud=$acumuladovaloreps;
            $descuentopension=$acumuladovalorpension;

            $deducidos=$valorprestamos+$valorotros+$descuentosalud+$descuentopension; // ejemplo calculado
            //-------------------------------------------------------------------------------------------------------------------------//

            //-----------------------------------------------------TOTAL A PAGAR-------------------------------------------------------//
            //-------------------------------------------------------------------------------------------------------------------------//

            $totalapagar=$devengadoauxilio-$deducidos; // ejemplo calculado
            //-------------------------------------------------------------------------------------------------------------------------//

            //-----------------------------------------------------PARAFISCALES--------------------------------------------------------//
            //-------------------------------------------------------------------------------------------------------------------------//

            if($ibccontope>(10*$salariominimo)){ // si el ibc con tope es mayor que los 10 salarios
                $aportesalud=($ibccontope*8.5);
            }
            else{
                $aportesalud=0;
            }

            $aportepension=($ibccontope*0.12);

            $riesgos = Tb_niveles_riesgo::where('id','=',$vinculacionesidNivelArl)
            ->select('id','nivelArl','porcentajeNivelArl')->get();
            foreach($riesgos as $riesgo){
                $porcentajeNivelArl = $riesgo->porcentajeNivelArl;
                }

            $aportearl=($ibccontope*$porcentajeNivelArl); //valor riesgo arl segun vinculacion

            if($ibccontope>(10*$salariominimo)){ // si el ibc con tope es mayor que los 10 salarios hay que preguntar si esta bien
                $aporteicbf=($ibccontope*0.03);
                $aportesena=($ibcsalario*0.02);
            }
            else{
                $aporteicbf=0;
                $aportesena=0;
            }

            $cajacompensacion=($ibccontope*0.04);

            $basecalculo=($ibcsalario+$auxilio);

            $cesantias=($basecalculo*0.0833);

            $interescesantias=($cesantias*0.12);

            $primaservicios=($basecalculo*0.0833);

            $vacaciones=($ibcsalario*0.0417);

            $costototalmensual=$aportesalud+$aportepension+$aportearl+$aportesena+$aporteicbf+$cajacompensacion+$cesantias+$interescesantias+$primaservicios+$vacaciones+$devengadoauxilio;
            //-------------------------------------------------------------------------------------------------------------------------//
                } // cierre calculo por empleado de novedades
    /**/
                $tb_resumen_nomina=new Tb_resumen_nomina();
                $tb_resumen_nomina->fechaVinculacion=$vinculacionesfechaInicio;
                $tb_resumen_nomina->tipoContrato=$vinculacionestipoVinculacion;
                $tb_resumen_nomina->diasLaborados=0;
                $tb_resumen_nomina->valordiasLaborados=$devengados;
                $tb_resumen_nomina->extrasDiurnas=0;
                $tb_resumen_nomina->valorextrasDiurnas=0;
                $tb_resumen_nomina->extrasNocturnas=0;
                $tb_resumen_nomina->valorextrasNocturnas=0;
                $tb_resumen_nomina->horasDominicales=0;
                $tb_resumen_nomina->valorhorasDominicales=0;
                $tb_resumen_nomina->extrasDominicalesDiurnas=0;
                $tb_resumen_nomina->valorextrasDominicalesDiurnas=0;
                $tb_resumen_nomina->extrasDominicalesNocturnas=0;
                $tb_resumen_nomina->valorextrasDominicalesNocturnas=0;
                $tb_resumen_nomina->recargos=0;
                $tb_resumen_nomina->valorrecargos=0;
                $tb_resumen_nomina->totalhorasExtras=0;
                $tb_resumen_nomina->totalrecargos=0;
                $tb_resumen_nomina->totalExtrasyRecargos=0;
                $tb_resumen_nomina->primaExtralegal=0;
                $tb_resumen_nomina->bonificaciones=0;
                $tb_resumen_nomina->comisiones=0;
                $tb_resumen_nomina->viaticos=0;
                $tb_resumen_nomina->noFactorSalarial=0;
                $tb_resumen_nomina->devengadoSinAuxilio=$devengados;
                $tb_resumen_nomina->auxilio=$auxilio;
                $tb_resumen_nomina->devengadoConAuxilio=$devengadoauxilio;
                $tb_resumen_nomina->ibcSalario=$ibcsalario;
                $tb_resumen_nomina->ibcConTope=$ibccontope;
                $tb_resumen_nomina->descuentoSalud=$descuentosalud;
                $tb_resumen_nomina->descuentoPension=$descuentopension;

                $tb_resumen_nomina->fondoSolidaridad=$fondoSolidaridad;
                $tb_resumen_nomina->retencion=$valorretencion;

                $tb_resumen_nomina->sindicato=0;
                $tb_resumen_nomina->prestamos=$valorprestamos;
                $tb_resumen_nomina->otros=$valorotros;
                $tb_resumen_nomina->totalDeducido=$deducidos;
                $tb_resumen_nomina->totalPagar=$totalapagar;
                $tb_resumen_nomina->aporteSalud=$aportesalud;
                $tb_resumen_nomina->aportePension=$aportepension;
                $tb_resumen_nomina->aporteArl=$aportearl;
                $tb_resumen_nomina->aporteSena=$aportesena;
                $tb_resumen_nomina->aporteCaja=$cajacompensacion;
                $tb_resumen_nomina->cesantias=$cesantias;
                $tb_resumen_nomina->interesesCesantias=$interescesantias;
                $tb_resumen_nomina->primaServicios=$primaservicios;
                $tb_resumen_nomina->vacaciones=$vacaciones;
                $tb_resumen_nomina->costoTotalMensual=$costototalmensual;
                $tb_resumen_nomina->sueldoBasicoMensual=$sueldobasico;
                $tb_resumen_nomina->idEmpleado=$empleadonovedadid;
                $tb_resumen_nomina->idNomina=$nominaid;
                $tb_resumen_nomina->save();

            } //cierre foreach novedades

// muestra temporal de salida
echo "El sueldo basico es ".$sueldobase."<br>";
echo "<hr><br>";
echo "Valor retencion es ".$valorretencion."<br>";
echo "<hr><br>";
echo "Valor fondo solidaridad es ".$fondoSolidaridad."<br>";
echo "<hr><br>";
echo "Valor descuentosalud es ".$descuentosalud."<br>";
echo "<hr><br>";
echo "Valor descuentopension es ".$descuentopension."<br>";
echo "<hr><br>";
echo "Valor devengados es ".$devengados."<br>";
echo "<hr><br>";
echo "Valor devengado con auxilio es ".$devengadoauxilio."<br>";
echo "<hr><br>";
echo "Valor deducidos es ".$deducidos."<br>";
echo "<hr><br>";
echo "Valor ibc salario ".$ibcsalario."<br>";
echo "Valor ibc tope ".$ibccontope."<br>";
echo "<hr><br>";
echo "Valor a pagar ".$totalapagar."<br>";
echo "<hr><br>";
echo "Aporte salud ".$aportesalud."<br>";
echo "<hr><br>";
echo "Aporte pension ".$aportepension."<br>";
echo "<hr><br>";
echo "Aporte arl ".$aportearl."<br>";
echo "<hr><br>";
echo "Aporte sena ".$aportesena."<br>";
echo "<hr><br>";
echo "Aporte icbf ".$aporteicbf."<br>";
echo "<hr><br>";
echo "Aporte caja compensacion ".$cajacompensacion."<br>";
echo "<hr><br>";
echo "Aporte cesantias ".$cesantias."<br>";
echo "<hr><br>";
echo "Intereses cesantias ".$interescesantias."<br>";
echo "<hr><br>";
echo "Prima servicios ".$primaservicios."<br>";
echo "<hr><br>";
echo "Vacaciones ".$vacaciones."<br>";
echo "<hr><br>";
echo "Valor total costos ".$costototalmensual."<br>";
echo "<hr><br>";

            $tb_cierre_nomina=Tb_nomina::findOrFail($nominaid);
            $tb_cierre_nomina->estado=0;
            $tb_cierre_nomina->save();

} //cierre función cálculo

    public function prueba()
    {
    $ibccontope=22713150;
    $salariominimo=908600;
    $porcentajeAdicional=0;
    $riesgosadicional = Tb_riesgo_adicional::select('rangoSalarioMin','rangoSalarioMax','porcentajeAdicional')->get();
    foreach($riesgosadicional as $riesgoadicional){
        $rangoSalarioMin = $riesgoadicional->rangoSalarioMin;
        $rangoSalarioMax = $riesgoadicional->rangoSalarioMax;

        $salarioMin=$rangoSalarioMin*$salariominimo;
        $salarioMax=$rangoSalarioMax*$salariominimo;

        if(($ibccontope>=$salarioMin) && ($ibccontope<=$salarioMax)){
            $porcentajeAdicional = $riesgoadicional->porcentajeAdicional;
        }
    }

    $fondoSolidaridad=$ibccontope*$porcentajeAdicional;
    }

}
