<?php

namespace App\Http\Livewire\ModuloDocente\Inicio;

use App\Models\Admision;
use App\Models\CursoProgramaProceso;
use App\Models\Docente;
use App\Models\DocenteCurso;
use App\Models\Programa;
use App\Models\ProgramaProcesoGrupo;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $filtro_estado; // variable para el filtro de estado
    public $data_filtro_estado; // variable para el filtro de estado
    public $filtro_proceso; // variable para el filtro de proceso
    public $data_filtro_proceso; // variable para el filtro de proceso

    public $grupos; // variable para los grupos

    protected $queryString = [
        'search' => ['except' => ''],
        'filtro_estado' => ['except' => '', 'as' => 'estado'],
        'data_filtro_estado' => ['except' => '', 'as' => 'dest'],
        'filtro_proceso' => ['except' => '', 'as' => 'proceso'],
        'data_filtro_proceso' => ['except' => '', 'as' => 'dpro'],
    ];

    public function mount()
    {
        $this->grupos = collect();
    }

    public function resetear_filtro()
    {
        $this->reset([
            'filtro_estado',
            'data_filtro_estado',
            'filtro_proceso',
            'data_filtro_proceso',
        ]);
    }

    public function aplicar_filtro()
    {
        $this->data_filtro_estado = $this->filtro_estado;
        $this->data_filtro_proceso = $this->filtro_proceso;
    }

    public function ingresar(DocenteCurso $docente_curso)
    {
        if ($docente_curso->docente_curso_estado == 0) {
            // emitimos alerta de acceso denegado por curso inhabilitado
            $this->dispatchBrowserEvent('alerta-basica', [
                'title' => '¡Error!',
                'text' => 'El curso se encuentra inhabilitado.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        return redirect()->route('docente.matriculados', ['id_docente_curso' => $docente_curso->id_docente_curso]);
    }

    public function render()
    {
        $usuario = auth('usuario')->user(); // obtenemos el usuario autenticado
        $trabajador_tipo_trabajador = $usuario->trabajador_tipo_trabajador; // obtenemos el trabajador_tipo_trabajador del usuario autenticado
        $trabajador = $trabajador_tipo_trabajador->trabajador; // obtenemos el trabajador del trabajador_tipo_trabajador del usuario autenticado
        $docente = Docente::where('id_trabajador', $trabajador->id_trabajador)->first(); // obtenemos el docente del trabajador del usuario autenticado

        $cursos_docente = collect(); // creamos una coleccion de cursos

        $procesos = DocenteCurso::join('admision', 'docente_curso.id_admision', 'admision.id_admision')
            ->where('docente_curso.id_docente', $docente->id_docente)
            ->where('docente_curso.id_admision', $this->data_filtro_proceso == null ? '!=' : '=', $this->data_filtro_proceso)
            ->select('docente_curso.id_admision')
            ->distinct()
            ->orderBy('admision.admision', 'desc')
            ->get();

        foreach ($procesos as $proceso) {
            $proceso_model = Admision::find($proceso->id_admision);

            $programas = DocenteCurso::join('curso_programa_plan', 'docente_curso.id_curso_programa_plan', 'curso_programa_plan.id_curso_programa_plan')
                ->join('programa_plan', 'curso_programa_plan.id_programa_plan', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->where('docente_curso.id_admision', $proceso->id_admision)
                ->where('docente_curso.id_docente', $docente->id_docente)
                ->select('programa.id_programa as id_programa')
                ->distinct()
                ->get();

            $programaData = $programas->map(function ($programa) use ($docente, $proceso) {
                $programa_model = Programa::find($programa->id_programa);
                $cursos = DocenteCurso::join('curso_programa_plan', 'docente_curso.id_curso_programa_plan', 'curso_programa_plan.id_curso_programa_plan')
                    ->join('programa_plan', 'curso_programa_plan.id_programa_plan', 'programa_plan.id_programa_plan')
                    ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                    ->join('curso', 'curso_programa_plan.id_curso', 'curso.id_curso')
                    ->where('docente_curso.id_docente', $docente->id_docente)
                    ->where('docente_curso.id_admision', $proceso->id_admision)
                    ->where('programa.id_programa', $programa->id_programa)
                    ->where(function ($query) {
                        $query->where('curso.curso_nombre', 'like', '%' . $this->search . '%')
                            ->orWhere('curso.curso_codigo', 'like', '%' . $this->search . '%');
                    })
                    ->where('docente_curso_estado', $this->data_filtro_estado == null ? '!=' : '=', $this->data_filtro_estado)
                    ->orderBy('docente_curso.docente_curso_estado', 'asc')
                    ->orderBy('curso.curso_codigo', 'asc')
                    ->get();

                $colores = [
                    'primary' => 'bg-light-primary',
                    'dark' => 'bg-light-dark',
                    'success' => 'bg-light-success',
                    'danger' => 'bg-light-danger',
                    'warning' => 'bg-light-warning',
                    'info' => 'bg-light-info',
                ];

                $numero_aleatorio = rand(1, 6);
                $color = array_keys($colores)[$numero_aleatorio - 1];
                $colorlight = $colores[$color];

                return [
                    'programa' => $programa_model,
                    'color' => $color,
                    'colorlight' => $colorlight,
                    'cursos' => $cursos,
                ];
            });

            $cursos_docente->push([
                'proceso' => $proceso_model,
                'programa' => $programaData,
            ]);
        }

        $admisiones = collect();

        foreach ($procesos as $proceso) {
            $admision = Admision::find($proceso->id_admision);
            $admisiones->push($admision);
        }

        return view('livewire.modulo-docente.inicio.index', [
            'docente' => $docente, // pasamos el docente del usuario autenticado a la vista 'modulo-docente.inicio.index
            'cursos_docente' => $cursos_docente,
            'procesos' => $admisiones
        ]);
    }
}
