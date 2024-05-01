<?php

namespace App\Exports\reporte\moduloAdministrador\matriculados;

use App\Models\Inscripcion;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\reporte\moduloAdministrador\matriculados\listaMatriculadosExport;
use App\Models\ProgramaProcesoGrupo;

class listaGruposExport implements WithMultipleSheets
{

    use Exportable;

    protected $id_programa_proceso;

    public function __construct($id_programa_proceso)
    {
        $this->id_programa_proceso = $id_programa_proceso;
    }


    public function sheets(): array
    {
        $sheets = [];

        $grupos = ProgramaProcesoGrupo::where('id_programa_proceso', $this->id_programa_proceso)
            ->where('programa_proceso_grupo_estado', 1)
            ->get();

        foreach ($grupos as $grupo) {
            $sheets[] = new listaMatriculadosExport($grupo->id_programa_proceso, $grupo->grupo_detalle);
        }

        return $sheets;
    }
}
