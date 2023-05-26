<?php

namespace App\Http\Livewire\ModuloCoordinador\GestionCursos;

use App\Models\Admision;
use App\Models\Ciclo;
use App\Models\Coordinador;
use App\Models\Curso;
use App\Models\CursoProgramaProceso;
use App\Models\Docente;
use App\Models\DocenteCurso;
use App\Models\Plan;
use App\Models\Programa;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // Trait de paginación

    public $search = ''; // Variable para almacenar la búsqueda
    public $modo = 'show'; // Variable para almacenar el modo (select, show)

    // variables del coordinador
    public $usuario; // Variable para almacenar el usuario
    public $trabajador; // Variable para almacenar el trabajador
    public $coordinador; // Variable para almacenar el coordinador

    // variables para los modales
    public $title_modal = 'Asignar Docente al Curso Seleccionado'; // Variable para almacenar el título del modal
    public $curso; // Variable para almacenar el curso
    public $id_curso; // Variable para almacenar el id del curso
    public $curso_programa_proceso; // Variable para almacenar el curso_programa_proceso
    public $id_curso_programa_proceso; // Variable para almacenar el id del curso_programa_proceso
    public $docentes; // Variable para almacenar el docente
    public $docente; // Variable para almacenar el docente del formulario del modal

    // variables para los filtros
    public $filtro_proceso; // Variable para almacenar el proceso
    public $proceso_data; // Variable para almacenar el proceso
    public $filtro_plan; // Variable para almacenar el plan de estudios
    public $plan_data; // Variable para almacenar el plan de estudios
    public $filtro_programa; // Variable para almacenar el programa
    public $programa_data; // Variable para almacenar el programa
    public $filtro_ciclo; // Variable para almacenar el ciclo
    public $ciclo_data; // Variable para almacenar el ciclo

    protected $queryString = [ // Variables de búsqueda
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'filtro_proceso' => ['except' => ''],
        'proceso_data' => ['except' => ''],
        'filtro_plan' => ['except' => ''],
        'plan_data' => ['except' => ''],
        'filtro_programa' => ['except' => ''],
        'programa_data' => ['except' => ''],
        'filtro_ciclo' => ['except' => ''],
        'ciclo_data' => ['except' => ''],
    ];

    public function updated($propertyName) // Método que se ejecuta al modificar una variable
    {
        if($this->modo == 'select')
        {
            $this->validateOnly($propertyName, [ // Validación de los campos
                'docente' => 'required|integer|exists:docente,id_docente',
            ]);
        }
    }

    public function mount()
    {
        $this->usuario = auth('usuario')->user();
        $this->trabajador = $this->usuario->trabajador_tipo_trabajador->trabajador;
        $this->coordinador = Coordinador::where('id_trabajador', $this->trabajador->id_trabajador)->first();
    }

    public function aplicar_filtro()
    {
        $this->proceso_data = $this->filtro_proceso; // Se almacena el proceso
        $this->plan_data = $this->filtro_plan; // Se almacena el plan de estudios
        $this->programa_data = $this->filtro_programa; // Se almacena el programa
        $this->ciclo_data = $this->filtro_ciclo; // Se almacena el ciclo
    }

    public function resetear_filtro()
    {
        $this->reset([
            'filtro_proceso',
            'proceso_data',
            'filtro_plan',
            'plan_data',
            'filtro_programa',
            'programa_data',
            'filtro_ciclo',
            'ciclo_data',
        ]);
    }

    public function limpiar_modal()
    {
        $this->reset([
            'title_modal',
            'curso',
            'id_curso',
            'curso_programa_proceso',
            'id_curso_programa_proceso',
            'docentes',
            'docente',
        ]);
    }

    public function cancelar_modal()
    {
        $this->reset([
            'docente',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cargar_datos(CursoProgramaProceso $curso_programa_proceso, $modo)
    {
        $this->modo = $modo; // Se almacena el modo
        if($this->modo == 'select')
        {
            $this->title_modal = 'Asignar Docentes al Curso Seleccionado'; // Se almacena el título del modal
        }
        else if ($this->modo == 'show')
        {
            $this->title_modal = 'Detalle del Cursos y Docentes Asignados'; // Se almacena el título del modal
        }
        $this->curso_programa_proceso = $curso_programa_proceso; // Se almacena el curso_programa_proceso
        $this->id_curso_programa_proceso = $curso_programa_proceso->id_curso_programa_proceso; // Se almacena el id del curso_programa_proceso
        $this->curso = $curso_programa_proceso->curso; // Se almacena el curso
        $this->id_curso = $curso_programa_proceso->curso->id_curso; // Se almacena el id del curso
        $this->docentes = DocenteCurso::where('id_curso_programa_proceso', $this->id_curso_programa_proceso)->where('docente_curso_estado', 1)->get(); // Se almacena el docente
    }

    public function asignar_docente()
    {
        $this->validate([ // Validación de los campos
            'docente' => 'required|integer|exists:docente,id_docente',
        ]);

        // Se verifica si el docente ya está asignado al curso
        $docente_curso = DocenteCurso::where('id_curso_programa_proceso', $this->id_curso_programa_proceso)->where('id_docente', $this->docente)->first();
        if($docente_curso)
        {
            // emitir alerta para mostrar mensaje de error
            $this->dispatchBrowserEvent('alerta_curso', [
                'title' => '¡Error!',
                'text' => 'El docente ya está asignado al curso.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        // registrar docente
        $docente_curso = new DocenteCurso();
        $docente_curso->id_docente = $this->docente;
        $docente_curso->id_curso_programa_proceso = $this->id_curso_programa_proceso;
        $docente_curso->docente_curso_fecha_creacion = date('Y-m-d H:i:s');
        $docente_curso->docente_curso_estado = 1;
        $docente_curso->save();

        // emitir alerta para mostrar mensaje de éxito
        $this->dispatchBrowserEvent('alerta_curso', [
            'title' => '¡Éxito!',
            'text' => 'El docente se asignó correctamente al curso.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // limpiamos el combo docente
        $this->reset([
            'docente',
        ]);

        // Se actualiza la lista de docentes
        $this->docentes = DocenteCurso::where('id_curso_programa_proceso', $this->id_curso_programa_proceso)->where('docente_curso_estado', 1)->get(); // Se almacena el docente
    }

    public function render()
    {
        $cursos = CursoProgramaProceso::join('curso', 'curso_programa_proceso.id_curso', '=', 'curso.id_curso')
                    ->join('ciclo', 'curso.id_ciclo', '=', 'ciclo.id_ciclo')
                    ->join('programa_proceso', 'curso_programa_proceso.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
                    ->join('admision', 'programa_proceso.id_admision', '=', 'admision.id_admision')
                    ->join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                    ->join('plan', 'programa_plan.id_plan', '=', 'plan.id_plan')
                    ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
                    ->where(function ($query) {
                        $query->where('curso.curso_codigo', 'like', '%' . $this->search . '%')
                            ->orWhere('curso.curso_nombre', 'like', '%' . $this->search . '%');
                    })
                    ->where('programa.id_facultad', $this->coordinador->id_facultad)
                    ->where('programa_proceso.id_admision', $this->proceso_data == null ? '!=' : '=', $this->proceso_data)
                    ->where('programa_plan.id_plan', $this->plan_data == null ? '!=' : '=', $this->plan_data)
                    ->where('programa.id_programa', $this->programa_data == null ? '!=' : '=', $this->programa_data)
                    ->where('curso.id_ciclo', $this->ciclo_data == null ? '!=' : '=', $this->ciclo_data)
                    ->orderBy('ciclo.id_ciclo', 'asc')
                    ->paginate(10);

        $planes = Plan::where('plan_estado', 1)->get();
        $procesos = Admision::all();
        $programas = Programa::where('id_facultad', $this->coordinador->id_facultad)->where('programa_estado', 1)->get();
        $ciclos = Ciclo::where('ciclo_estado', 1)->get();
        $docentes_model = Docente::join('trabajador', 'docente.id_trabajador', '=', 'trabajador.id_trabajador')
                                ->where('docente.docente_estado', 1)
                                ->get();

        return view('livewire.modulo-coordinador.gestion-cursos.index', [
            'cursos' => $cursos,
            'planes' => $planes,
            'procesos' => $procesos,
            'programas' => $programas,
            'ciclos' => $ciclos,
            'docentes_model' => $docentes_model,
        ]);
    }
}
