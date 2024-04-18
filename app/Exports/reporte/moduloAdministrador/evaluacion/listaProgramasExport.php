<?php

namespace App\Exports\reporte\moduloAdministrador\evaluacion;

use App\Models\Inscripcion;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class listaProgramasExport implements WithMultipleSheets
{

    use Exportable;


    public function sheets(): array
    {
        $sheets = [];

        $programas = Inscripcion::join('programa_proceso','inscripcion.id_programa_proceso','=','programa_proceso.id_programa_proceso')
            ->join('programa_plan','programa_proceso.id_programa_plan','=','programa_plan.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->where('programa.programa_estado',1)
            ->where('programa.id_modalidad',2)
            ->where('inscripcion.inscripcion_estado',1)
            ->where('inscripcion.retiro_inscripcion',0)
            ->where('inscripcion.verificar_expedientes',1)
            ->groupBy('inscripcion.id_programa_proceso')
            ->select('programa.id_programa','programa.programa','programa.subprograma','programa.mencion')
            ->get();

        foreach ($programas as $programa) {
            $sheets[] = new listaEvaluacionesExport($programa->id_programa);
        }

        return $sheets;
    }
}
