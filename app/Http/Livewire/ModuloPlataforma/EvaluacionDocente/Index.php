<?php

namespace App\Http\Livewire\ModuloPlataforma\EvaluacionDocente;

use App\Models\Admitido;
use App\Models\CursoProgramaPlan;
use App\Models\Docente;
use App\Models\EvaluacionDocente;
use App\Models\EvaluacionDocenteDetalle;
use App\Models\EvaluacionDocentePregunta;
use App\Models\Matricula;
use App\Models\NotaMatriculaCurso;
use App\Models\Persona;
use Livewire\Component;

class Index extends Component
{
    public $nombre_docente;
    public $nombre_curso;
    public $evaluacion_docente_preguntas = [];
    public $count_preguntas = 0;
    public $respuestas = [];
    public $data = [];

    public function mount()
    {
        $this->evaluacion_docente_preguntas = collect();
    }

    public function cargar($item)
    {
        $curso = CursoProgramaPlan::query()
            ->join('curso', 'curso_programa_plan.id_curso', 'curso.id_curso')
            ->where('curso_programa_plan.id_curso_programa_plan', $item['id_curso_programa_plan'])
            ->first();
        $this->nombre_curso = $curso->curso_nombre;
        $docente = Docente::find($item['id_docente']);
        $this->nombre_docente = $docente ? ($docente->trabajador->grado_academico->grado_academico_prefijo . ' ' . $docente->trabajador->trabajador_nombre_completo) : null;
        // $evaluacion_docente = EvaluacionDocente::query()
        //     ->where('id_nota_matricula_curso', $item->id_nota_matricula_curso)
        //     ->where('id_docente', $item->id_docente)
        //     ->where('id_admitido', $item->id_admitido)
        //     ->first();
        $this->evaluacion_docente_preguntas = EvaluacionDocentePregunta::query()
            ->where('evaluacion_docente_pregunta_estado', 1)
            ->get();
        $this->count_preguntas = $this->evaluacion_docente_preguntas->count();
        $this->data = $item;
    }

    public function guardar_encuesta()
    {
        if (count($this->respuestas) == 0) {
            $this->dispatchBrowserEvent('alerta', [
                'title' => 'Error',
                'text' => 'Debe responder todas las preguntas',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        // validamos que no haya respuestas vacias
        if ($this->count_preguntas != count($this->respuestas)) {
            $this->dispatchBrowserEvent('alerta', [
                'title' => 'Error',
                'text' => 'Debe responder todas las preguntas',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }
        $evaluacion_docente = new EvaluacionDocente();
        $evaluacion_docente->id_nota_matricula_curso = $this->data['id_nota_matricula_curso'];
        $evaluacion_docente->id_docente = $this->data['id_docente'];
        $evaluacion_docente->id_admitido = $this->data['id_admitido'];
        $evaluacion_docente->evaluacion_docente_estado = 1;
        $evaluacion_docente->save();

        foreach ($this->respuestas as $key => $value) {
            $evaluacion_docente_detalle = new EvaluacionDocenteDetalle();
            $evaluacion_docente_detalle->id_evaluacion_docente = $evaluacion_docente->id_evaluacion_docente;
            $evaluacion_docente_detalle->id_evaluacion_docente_pregunta = $key;
            $evaluacion_docente_detalle->respuesta = $value;
            $evaluacion_docente_detalle->save();
        }

        $this->dispatchBrowserEvent('alerta', [
            'title' => 'Éxito',
            'text' => 'Muchas gracias por su evaluación, se ha registrado correctamente',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        $this->respuestas = [];
        $this->dispatchBrowserEvent('modal', [
            'modal' => '#modal-encuesta',
            'action' => 'hide'
        ]);
    }

    public function render()
    {
        $persona = Persona::find(auth('plataforma')->user()->id_persona);
        $admitido = Admitido::where('id_persona', $persona->id_persona)->orderBy('id_admitido', 'desc')->first();
        $ultima_matricula = Matricula::where('id_admitido', $admitido->id_admitido)->orderBy('id_matricula', 'desc')->first();
        $cursos_activos_matricula = NotaMatriculaCurso::query()
            ->join('matricula_curso', 'nota_matricula_curso.id_matricula_curso', '=', 'matricula_curso.id_matricula_curso')
            ->join('matricula', 'matricula_curso.id_matricula', '=', 'matricula.id_matricula')
            ->where('matricula_curso.id_matricula', $ultima_matricula->id_matricula)
            ->where('matricula_curso.matricula_curso_estado', 2)
            ->where('matricula_curso.matricula_curso_activo', 1)
            ->groupBy('nota_matricula_curso.id_matricula_curso')
            ->paginate(10);
        return view('livewire.modulo-plataforma.evaluacion-docente.index', [
            'cursos_activos_matricula' => $cursos_activos_matricula
        ]);
    }
}
