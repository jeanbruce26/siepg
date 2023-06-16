<?php

namespace App\Http\Livewire\ModuloDocente\Matriculados;

use App\Models\CursoProgramaProceso;
use App\Models\Docente;
use App\Models\DocenteCurso;
use App\Models\MatriculaCurso;
use App\Models\NotaMatriculaCurso;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // trait para paginacion en livewire
    // bootstrap livewire pagination views
    protected $paginationTheme = 'bootstrap';

    public $id_docente_curso;
    public $id_programa_proceso_grupo;
    public $curso_programa_proceso;
    public $id_curso_programa_proceso;
    public $curso;
    public $grupo;

    public $id_matricula_curso;
    public $matricula_curso;
    public $nota_matricula_curso;
    public $nota;
    public $modo_nota = 0;

    public $search = '';

    protected $listeners = [
        'asignar_nsp' => 'asignar_nsp',
    ];

    protected $queryString = [
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $usuario = auth('usuario')->user(); // obtenemos el usuario autenticado
        $trabajador_tipo_trabajador = $usuario->trabajador_tipo_trabajador; // obtenemos el trabajador_tipo_trabajador del usuario autenticado
        $trabajador = $trabajador_tipo_trabajador->trabajador; // obtenemos el trabajador del trabajador_tipo_trabajador del usuario autenticado
        $docente = Docente::where('id_trabajador', $trabajador->id_trabajador)->first(); // obtenemos el docente del trabajador del usuario autenticado

        $docente_curso = DocenteCurso::find($this->id_docente_curso);
        if ($docente_curso) {
            if ($docente_curso->id_docente != $docente->id_docente) {
                abort(403);
            }
        }
        else {
            abort(403);
        }

        $this->curso_programa_proceso = CursoProgramaProceso::find($docente_curso->id_curso_programa_proceso);
        $this->id_curso_programa_proceso = $this->curso_programa_proceso->id_curso_programa_proceso;
        $this->id_programa_proceso_grupo = $docente_curso->id_programa_proceso_grupo;
        $this->curso = $this->curso_programa_proceso->curso;
        $this->grupo = $docente_curso->programa_proceso_grupo;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'nota' => 'required|numeric|min:0|max:20',
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function limpiar_modal()
    {
        $this->reset([
            'nota',
        ]);
    }

    public function cargar_curso(MatriculaCurso $matricula_curso, $modo)
    {
        $this->matricula_curso = $matricula_curso;
        $this->id_matricula_curso = $matricula_curso->id_matricula_curso;
        $this->modo_nota = $modo;
        $this->nota_matricula_curso = NotaMatriculaCurso::where('id_matricula_curso', $matricula_curso->id_matricula_curso)->first();

        if($this->modo_nota == 1)
        {
            if($this->nota_matricula_curso)
            {
                $this->nota = $this->nota_matricula_curso->nota_evaluacion_permanente;
            }
            else
            {
                $nota_matricula_curso = new NotaMatriculaCurso();
                $nota_matricula_curso->id_matricula_curso = $matricula_curso->id_matricula_curso;
                $nota_matricula_curso->nota_matricula_curso_fecha_creacion = date('Y-m-d H:i:s');
                $nota_matricula_curso->nota_matricula_curso_estado = 1;
                $nota_matricula_curso->id_estado_cursos = null;
                $nota_matricula_curso->save();
            }
            $this->dispatchBrowserEvent('modal_nota', ['action' => 'show']);
        }
        else if($this->modo_nota == 2)
        {
            if($this->nota_matricula_curso)
            {
                if($this->nota_matricula_curso->nota_evaluacion_permanente == null)
                {
                    $this->dispatchBrowserEvent('modal_nota', ['action' => 'hide']);
                    // emitir alerta para mostrar mensaje de error
                    $this->dispatchBrowserEvent('alerta_matriculados', [
                        'title' => '¡Error!',
                        'text' => 'No se puede registrar la nota de evaluación medio curso, porque no se ha registrado la nota de evaluación permanente.',
                        'icon' => 'error',
                        'confirmButtonText' => 'Aceptar',
                        'color' => 'danger'
                    ]);
                    return;
                }
                $this->nota = $this->nota_matricula_curso->nota_evaluacion_medio_curso;
            }
            else
            {
                $this->dispatchBrowserEvent('modal_nota', ['action' => 'hide']);
                // emitir alerta para mostrar mensaje de error
                $this->dispatchBrowserEvent('alerta_matriculados', [
                    'title' => '¡Error!',
                    'text' => 'No se puede registrar la nota de evaluación medio curso, porque no se ha registrado la nota de evaluación permanente.',
                    'icon' => 'error',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'danger'
                ]);
                return;
            }
            $this->dispatchBrowserEvent('modal_nota', ['action' => 'show']);
        }
        else if($this->modo_nota == 3)
        {
            if($this->nota_matricula_curso)
            {
                if($this->nota_matricula_curso->nota_evaluacion_medio_curso == null && $this->nota_matricula_curso->nota_evaluacion_permanente == null)
                {
                    $this->dispatchBrowserEvent('modal_nota', ['action' => 'hide']);
                    // emitir alerta para mostrar mensaje de error
                    $this->dispatchBrowserEvent('alerta_matriculados', [
                        'title' => '¡Error!',
                        'text' => 'No se puede registrar la nota de evaluación final, porque no se ha registrado la nota de evaluación permanente y de medio curso.',
                        'icon' => 'error',
                        'confirmButtonText' => 'Aceptar',
                        'color' => 'danger'
                    ]);
                    return;
                }
                if($this->nota_matricula_curso->nota_evaluacion_medio_curso == null)
                {
                    $this->dispatchBrowserEvent('modal_nota', ['action' => 'hide']);
                    // emitir alerta para mostrar mensaje de error
                    $this->dispatchBrowserEvent('alerta_matriculados', [
                        'title' => '¡Error!',
                        'text' => 'No se puede registrar la nota de evaluación final, porque no se ha registrado la nota de medio curso.',
                        'icon' => 'error',
                        'confirmButtonText' => 'Aceptar',
                        'color' => 'danger'
                    ]);
                    return;
                }
                $this->nota = $this->nota_matricula_curso->nota_evaluacion_final;
            }
            else
            {
                $this->dispatchBrowserEvent('modal_nota', ['action' => 'hide']);
                // emitir alerta para mostrar mensaje de error
                $this->dispatchBrowserEvent('alerta_matriculados', [
                    'title' => '¡Error!',
                    'text' => 'No se puede registrar la nota de evaluación final, porque no se ha registrado la nota de evaluación permanente y la de medio curso.',
                    'icon' => 'error',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'danger'
                ]);
                return;
            }
            $this->dispatchBrowserEvent('modal_nota', ['action' => 'show']);
        }
    }

    public function agregar_nota()
    {
        $this->validate([
            'nota' => 'required|numeric|min:0|max:20'
        ]);

        if($this->modo_nota == 1)
        {
            $this->nota_matricula_curso->nota_evaluacion_permanente = $this->nota;
        }
        else if($this->modo_nota == 2)
        {
            $this->nota_matricula_curso->nota_evaluacion_medio_curso = $this->nota;
        }
        else if($this->modo_nota == 3)
        {
            $this->nota_matricula_curso->nota_evaluacion_final = $this->nota;
            $promedio_final = ($this->nota_matricula_curso->nota_evaluacion_permanente + $this->nota_matricula_curso->nota_evaluacion_medio_curso + $this->nota_matricula_curso->nota_evaluacion_final) / 3;
            $promedio_final = round($promedio_final);
            if ( $promedio_final >= 14 )
            {
                $this->nota_matricula_curso->id_estado_cursos = 1;
            }
            else if ( $promedio_final >= 10 && $promedio_final < 14 )
            {
                $this->nota_matricula_curso->id_estado_cursos = 2;
            }
            else if ( $promedio_final < 10 )
            {
                $this->nota_matricula_curso->id_estado_cursos = 3;
            }
            $this->nota_matricula_curso->nota_promedio_final = $promedio_final;
        }
        $this->nota_matricula_curso->save();

        // cambiamos el estado de la matricula_curso a finalizado
        $this->matricula_curso->matricula_curso_estado = 2; // 2 = curso finalizado
        $this->matricula_curso->save();

        // emitimos evento para mostrar mensaje de éxito
        $this->dispatchBrowserEvent('alerta_matriculados', [
            'title' => '¡Éxito!',
            'text' => 'Se registró la nota correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // emitimos evento para ocultar modal
        $this->dispatchBrowserEvent('modal_nota', ['action' => 'hide']);
    }

    public function alerta_asignar_nsp(MatriculaCurso $matricula_curso)
    {
        $this->matricula_curso = $matricula_curso;
        $this->id_matricula_curso = $matricula_curso->id_matricula_curso;
        $this->nota_matricula_curso = NotaMatriculaCurso::where('id_matricula_curso', $matricula_curso->id_matricula_curso)->first();

        if ( $this->nota_matricula_curso )
        {
            if ( $this->nota_matricula_curso->id_estado_cursos == 4 )
            {
                // emitimos la alerta para mostrar mensaje sobre que el alumno ya fue asignado su nsp
                $this->dispatchBrowserEvent('alerta_matriculados', [
                    'title' => '¡Atención!',
                    'text' => 'El estudiante ya fue asignado su NSP.',
                    'icon' => 'warning',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'warning'
                ]);
                return;
            }
        }

        // emitir alerta para asignar nsp
        $this->dispatchBrowserEvent('alerta_matriculados_opciones', [
            'title' => '¡Atención!',
            'text' => '¿Está seguro que desea asignar NSP a este estudiante?',
            'icon' => 'warning',
            'confirmButtonText' => 'Si',
            'cancelButtonText' => 'Cancelar',
            'color' => 'warning',
            'confirmButtonColor' => 'warning',
            'cancelButtonColor' => 'danger',
        ]);
    }

    public function asignar_nsp()
    {
        if ($this->nota_matricula_curso)
        {
            $this->nota_matricula_curso->nota_evaluacion_permanente = 0;
            $this->nota_matricula_curso->nota_evaluacion_medio_curso = 0;
            $this->nota_matricula_curso->nota_evaluacion_final = 0;
            $this->nota_matricula_curso->nota_promedio_final = 0;
            $this->nota_matricula_curso->id_estado_cursos = 4;
            $this->nota_matricula_curso->save();
        }
        else
        {
            $this->nota_matricula_curso = new NotaMatriculaCurso();
            $this->nota_matricula_curso->id_matricula_curso = $this->id_matricula_curso;
            $this->nota_matricula_curso->nota_evaluacion_permanente = 0;
            $this->nota_matricula_curso->nota_evaluacion_medio_curso = 0;
            $this->nota_matricula_curso->nota_evaluacion_final = 0;
            $this->nota_matricula_curso->nota_promedio_final = 0;
            $this->nota_matricula_curso->nota_matricula_curso_fecha_creacion = date('Y-m-d H:i:s');
            $this->nota_matricula_curso->nota_matricula_curso_estado = 1;
            $this->nota_matricula_curso->id_estado_cursos = 4;
            $this->nota_matricula_curso->save();
        }

        // cambiamos el estado de la matricula_curso a finalizado
        $this->matricula_curso->matricula_curso_estado = 2; // 2 = curso finalizado
        $this->matricula_curso->save();

        // emitimos alerta para mostrar mensaje de éxito
        $this->dispatchBrowserEvent('alerta_matriculados', [
            'title' => '¡Éxito!',
            'text' => 'Se asignó el NSP correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function render()
    {
        $matriculados = MatriculaCurso::join('matricula', 'matricula_curso.id_matricula', 'matricula.id_matricula')
                        ->join('admitido', 'matricula.id_admitido', 'admitido.id_admitido')
                        ->join('persona', 'admitido.id_persona', 'persona.id_persona')
                        ->where('matricula_curso.id_curso_programa_proceso', $this->id_curso_programa_proceso)
                        ->where('matricula.id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
                        ->where(function ($query) {
                            $query->where('persona.nombre_completo', 'like', '%'.$this->search.'%')
                                ->orWhere('admitido.admitido_codigo', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('persona.nombre_completo', 'asc')
                        ->paginate(50);

        $matriculados_count = MatriculaCurso::join('matricula', 'matricula_curso.id_matricula', 'matricula.id_matricula')
                        ->join('admitido', 'matricula.id_admitido', 'admitido.id_admitido')
                        ->join('persona', 'admitido.id_persona', 'persona.id_persona')
                        ->where('matricula_curso.id_curso_programa_proceso', $this->id_curso_programa_proceso)
                        ->where('matricula.id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
                        ->count();

        $matriculados_finalizados_count = MatriculaCurso::join('matricula', 'matricula_curso.id_matricula', 'matricula.id_matricula')
                        ->join('admitido', 'matricula.id_admitido', 'admitido.id_admitido')
                        ->join('persona', 'admitido.id_persona', 'persona.id_persona')
                        ->where('matricula_curso.id_curso_programa_proceso', $this->id_curso_programa_proceso)
                        ->where('matricula.id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
                        ->where('matricula_curso.matricula_curso_estado', 2)
                        ->count();

        return view('livewire.modulo-docente.matriculados.index', [
            'matriculados' => $matriculados,
            'matriculados_count' => $matriculados_count,
            'matriculados_finalizados_count' => $matriculados_finalizados_count,
        ]);
    }
}
