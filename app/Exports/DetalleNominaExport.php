<?php

namespace App\Exports;

use App\Tb_resumen_nomina;
use App\Tb_vinculaciones;
use App\Tb_niveles_riesgo;
use App\Empleado;
use App\perfil;
use App\proceso;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;


class DetalleNominaExport implements FromCollection,WithHeadings,WithEvents,ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Codigo',
            'Documento',
            'Nombres y Apellidos',
            'Cargo',
            'Nivel Arl',
            'Porcentaje Arl',
            'Fecha Vinculación',
            'Tipo Contrato',
            'Sueldo Basico Mensual',
            'Dias Laborados',
            'Valor Dias Laborados',
            'Hora Extra Diurna',
            'Valor Extra Diurna',
            'Hora Extra Nocturna',
            'Valor Extra Nocturna',
            'Hora Dominical',
            'Valor Hora Dominical',
            'Hora Extra Dominical Diurna',
            'Valor Extra Dominical Diurno',
            'Hora Extra Dominical Nocturno',
            'Valor Extra Dominical Nocturno',
            'Recargos',
            'Valor Recargos',
            'Total Horas Extras',
            'Total Recargos',
            'Total Extra y Recargos',
            'Prima Extra Legal',
            'Bonificaciones',
            'Comisiones',
            'Viaticos',
            'No Factor Salarial',
            'Devengado Sin Auxilio',
            'Auxilio',
            'Devengado Con Auxilio',
            'Ibc Salario',
            'Ibc Con Tope',
            'Descuento Salud',
            'Descuento Pensión',
            'Sindicato',
            'Prestamos',
            'Otros',
            'Total Deducido',
            'Total a Pagar',
            'Aporte Salud',
            'Aporte Pensión',
            'Aporte Arl',
            'Aporte Sena',
            'Aporte Caja',
            'Cesantias',
            'Intereses Cesantias',
            'Prima Servicios',
            'Vacaciones',
            'Costo Total Mensual',

        ];
    }
    public function collection()
    {
       $detalles = Tb_resumen_nomina::join('tb_empleado','tb_resumen_nomina.idEmpleado','=','tb_empleado.id')
         ->join('tb_perfil','tb_empleado.idPerfil','=','tb_perfil.id')
         ->join("tb_vinculaciones","tb_empleado.id","=","tb_vinculaciones.idEmpleado")
         ->join("tb_niveles_riesgo","tb_vinculaciones.idNivelArl",'=',"tb_niveles_riesgo.id")
         ->select('tb_resumen_nomina.id','tb_empleado.documento','tb_empleado.nombre as nombreEmpleado','tb_perfil.perfil','tb_niveles_riesgo.nivelArl','tb_niveles_riesgo.porcentajeNivelArl',
         'tb_resumen_nomina.fechaVinculacion','tb_resumen_nomina.tipoContrato','tb_resumen_nomina.sueldoBasicoMensual','tb_resumen_nomina.diasLaborados','tb_resumen_nomina.valorDiasLaborados',
         'tb_resumen_nomina.extrasDiurnas','tb_resumen_nomina.valorextrasDiurnas','tb_resumen_nomina.extrasNocturnas','tb_resumen_nomina.valorextrasNocturnas','tb_resumen_nomina.horasDominicales',
         'tb_resumen_nomina.valorhorasDominicales','tb_resumen_nomina.extrasDominicalesDiurnas','tb_resumen_nomina.valorextrasDominicalesDiurnas','tb_resumen_nomina.extrasDominicalesNocturnas',
         'tb_resumen_nomina.valorextrasDominicalesNocturnas','tb_resumen_nomina.recargos','tb_resumen_nomina.valorrecargos','tb_resumen_nomina.totalhorasExtras','tb_resumen_nomina.totalrecargos',
         'tb_resumen_nomina.totalExtrasyRecargos','tb_resumen_nomina.primaExtralegal','tb_resumen_nomina.bonificaciones','tb_resumen_nomina.comisiones','tb_resumen_nomina.viaticos','tb_resumen_nomina.noFactorSalarial',
         'tb_resumen_nomina.devengadoSinAuxilio','tb_resumen_nomina.auxilio','tb_resumen_nomina.devengadoConAuxilio','tb_resumen_nomina.ibcSalario','tb_resumen_nomina.ibcConTope','tb_resumen_nomina.descuentoSalud',
         'tb_resumen_nomina.descuentoPension','tb_resumen_nomina.sindicato','tb_resumen_nomina.prestamos','tb_resumen_nomina.otros','tb_resumen_nomina.totalDeducido','tb_resumen_nomina.totalPagar','tb_resumen_nomina.aporteSalud',
         'tb_resumen_nomina.aportePension','tb_resumen_nomina.aporteArl','tb_resumen_nomina.aporteSena','tb_resumen_nomina.aporteCaja','tb_resumen_nomina.cesantias','tb_resumen_nomina.interesesCesantias','tb_resumen_nomina.primaServicios',
         'tb_resumen_nomina.vacaciones','tb_resumen_nomina.costoTotalMensual',
         DB::raw('CONCAT(tb_empleado.nombre," ",tb_empleado.apellido) as nombreEmpleado'))
         ->orderBy('id','asc')
         ->get();
         return $detalles;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:BA1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(11);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}