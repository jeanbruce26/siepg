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
    public $id_docente_curso;
    public $id_programa_proceso_grupo;
    public $curso_programa_proceso;
    public $id_curso_programa_proceso;
    public $curso;
    public $grupo;

    public $id_matricula_curso;
    public $matricula_curso;
    public $nota_matricula_curso;
    public $modo_nota = 0;

    public $matriculados;
    public $notas = [];
    public $modo = 'hide';

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'modo' => ['except' => 'hide'],
    ];

    protected $listeners = [
        'asignar_nsp' => 'asignar_nsp',
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
        // Limpiar los mensajes de error cuando se modifiquen los campos
        $this->resetErrorBag($propertyName);
    }

    public function modo_ingresar_notas()
    {
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

        // emitir alerta de que todas las notas ya fueron ingresadas
        if ( $matriculados_count == $matriculados_finalizados_count )
        {
            $this->dispatchBrowserEvent('alerta_matriculados', [
                'title' => '¡Alerta!',
                'text' => 'Todas las notas ya fueron ingresadas.',
                'icon' => 'warning',
                'confirmButtonText' => 'Aceptar',
                'color' => 'warning'
            ]);
        }

        $this->modo = 'show';
    }

    public function modo_cancelar()
    {
        $this->modo = 'hide';
    }

    public function esNotaValida($idMatricula, $campo)
    {
        // Verificar si la nota es válida
        if (isset($this->notas[$idMatricula][$campo]))
        {
            $nota = $this->notas[$idMatricula][$campo];

            // Realiza las validaciones necesarias
            // Por ejemplo, verifica si la nota está dentro de un rango específico
            // Si cumple las condiciones, retorna true, de lo contrario, retorna false
            return ($nota >= 0 && $nota <= 20);
        }

        return false;
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

    public function guardar_notas(MatriculaCurso $matricula_curso)
    {
        // Validar los campos
        $this->validate([
            "notas.$matricula_curso->id_matricula_curso.nota1" => 'required|numeric|between:0,20',
            "notas.$matricula_curso->id_matricula_curso.nota2" => 'required|numeric|between:0,20',
            "notas.$matricula_curso->id_matricula_curso.nota3" => 'required|numeric|between:0,20',
        ]);

        $nota_matricula_curso = NotaMatriculaCurso::where('id_matricula_curso', $matricula_curso->id_matricula_curso)->first();
        if($nota_matricula_curso == null)
        {
            $nota_matricula_curso = new NotaMatriculaCurso();
            $nota_matricula_curso->id_matricula_curso = $matricula_curso->id_matricula_curso;
            $nota_matricula_curso->nota_evaluacion_permanente = $this->notas[$matricula_curso->id_matricula_curso]['nota1'];
            $nota_matricula_curso->nota_evaluacion_medio_curso = $this->notas[$matricula_curso->id_matricula_curso]['nota2'];
            $nota_matricula_curso->nota_evaluacion_final = $this->notas[$matricula_curso->id_matricula_curso]['nota3'];
            $promedio_final = ($this->notas[$matricula_curso->id_matricula_curso]['nota1'] + $this->notas[$matricula_curso->id_matricula_curso]['nota2'] + $this->notas[$matricula_curso->id_matricula_curso]['nota3']) / 3;
            $nota_matricula_curso->nota_promedio_final = $promedio_final;
            $nota_matricula_curso->nota_matricula_curso_fecha_creacion = date('Y-m-d H:i:s');
            $nota_matricula_curso->nota_matricula_curso_estado = 1;
            if ( $promedio_final >= 14 )
            {
                $nota_matricula_curso->id_estado_cursos = 1;
            }
            else if ( $promedio_final >= 10 && $promedio_final < 14)
            {
                $nota_matricula_curso->id_estado_cursos = 2;
            }
            else
            {
                $nota_matricula_curso->id_estado_cursos = 3;
            }
            $nota_matricula_curso->save();
        }
        else
        {
            $nota_matricula_curso->nota_evaluacion_permanente = $this->notas[$matricula_curso->id_matricula_curso]['nota1'];
            $nota_matricula_curso->nota_evaluacion_medio_curso = $this->notas[$matricula_curso->id_matricula_curso]['nota2'];
            $nota_matricula_curso->nota_evaluacion_final = $this->notas[$matricula_curso->id_matricula_curso]['nota3'];
            $promedio_final = ($this->notas[$matricula_curso->id_matricula_curso]['nota1'] + $this->notas[$matricula_curso->id_matricula_curso]['nota2'] + $this->notas[$matricula_curso->id_matricula_curso]['nota3']) / 3;
            $nota_matricula_curso->nota_promedio_final = $promedio_final;
            $nota_matricula_curso->nota_matricula_curso_fecha_creacion = date('Y-m-d H:i:s');
            $nota_matricula_curso->nota_matricula_curso_estado = 1;
            if ( $promedio_final >= 14 )
            {
                $nota_matricula_curso->id_estado_cursos = 1;
            }
            else if ( $promedio_final >= 10 && $promedio_final < 14)
            {
                $nota_matricula_curso->id_estado_cursos = 2;
            }
            else
            {
                $nota_matricula_curso->id_estado_cursos = 3;
            }
            $nota_matricula_curso->save();
        }

        // cambiamos el estado de la matricula_curso a finalizado
        $matricula_curso->matricula_curso_estado = 2; // 2 = curso finalizado
        $matricula_curso->save();

        // emitir alerta de notas agregadas correctamente
        $this->dispatchBrowserEvent('alerta_matriculados', [
            'title' => '¡Éxito!',
            'text' => 'Se guardaron las notas correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // emitir evento para renderizar la tabla
        $this->emit('render');
    }

    public function render()
    {
        $this->matriculados = MatriculaCurso::join('matricula', 'matricula_curso.id_matricula', 'matricula.id_matricula')
                        ->join('admitido', 'matricula.id_admitido', 'admitido.id_admitido')
                        ->join('persona', 'admitido.id_persona', 'persona.id_persona')
                        ->where('matricula_curso.id_curso_programa_proceso', $this->id_curso_programa_proceso)
                        ->where('matricula.id_programa_proceso_grupo', $this->id_programa_proceso_grupo)
                        ->where(function ($query) {
                            $query->where('persona.nombre_completo', 'like', '%'.$this->search.'%')
                                ->orWhere('admitido.admitido_codigo', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('persona.nombre_completo', 'asc')
                        // ->paginate(50);
                        ->get();

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

        // verificar si ya se agregaron todas las notas
        if ( $matriculados_count == $matriculados_finalizados_count )
        {
            $this->modo = 'hide';
        }

        return view('livewire.modulo-docente.matriculados.index', [
            // 'matriculados' => $matriculados,
            'matriculados_count' => $matriculados_count,
            'matriculados_finalizados_count' => $matriculados_finalizados_count,
        ]);
    }
}
