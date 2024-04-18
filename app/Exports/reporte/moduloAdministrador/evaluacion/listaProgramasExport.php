<?php

namespace App\Exports\reporte\moduloAdministrador\evaluacion;

use App\Models\Inscripcion;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class listaProgramasExport implements WithMultipleSheets
{

    use Exportable;


    public function sheets(): array
    {
        $sheets = [];

        $programas = Inscripcion::join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
            ->where('programa.programa_estado', 1)
            ->where('programa.id_modalidad', 2)
            ->where('inscripcion.inscripcion_estado', 1)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('inscripcion.verificar_expedientes', 1)
            ->distinct()
            ->select('programa.id_programa', 'programa.programa', 'programa.subprograma', 'programa.mencion', DB::raw('count(inscripcion.id_programa_proceso) as total'))
            ->get();

        foreach ($programas as $programa) {
            if ($programa->total != 0) {
                $sheets[] = new listaEvaluacionesExport($programa->id_programa);
            }
        }

        return $sheets;
    }
}
