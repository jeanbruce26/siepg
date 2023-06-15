<?php

namespace App\Http\Livewire\ModuloDocente\Inicio;

use App\Models\Admision;
use App\Models\Docente;
use App\Models\DocenteCurso;
use Livewire\Component;

class Index extends Component
{
    public $search = '';

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function render()
    {
        $usuario = auth('usuario')->user(); // obtenemos el usuario autenticado
        $trabajador_tipo_trabajador = $usuario->trabajador_tipo_trabajador; // obtenemos el trabajador_tipo_trabajador del usuario autenticado
        $trabajador = $trabajador_tipo_trabajador->trabajador; // obtenemos el trabajador del trabajador_tipo_trabajador del usuario autenticado
        $docente = Docente::where('id_trabajador', $trabajador->id_trabajador)->first(); // obtenemos el docente del trabajador del usuario autenticado

        $programas = DocenteCurso::join('curso_programa_proceso', 'docente_curso.id_curso_programa_proceso', 'curso_programa_proceso.id_curso_programa_proceso')
                    ->join('programa_proceso', 'curso_programa_proceso.id_programa_proceso', 'programa_proceso.id_programa_proceso')
                    ->join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
                    ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                    ->where('docente_curso.id_docente', $docente->id_docente)
                    ->select('programa.id_programa as id_programa')
                    ->distinct()
                    ->get();

        $admisiones = Admision::all();

        return view('livewire.modulo-docente.inicio.index', [
            'docente' => $docente, // pasamos el docente del usuario autenticado a la vista 'modulo-docente.inicio.index
            'programas' => $programas,
            'admisiones' => $admisiones
        ]);
    }
}
