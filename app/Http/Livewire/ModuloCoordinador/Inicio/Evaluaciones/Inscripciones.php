<?php

namespace App\Http\Livewire\ModuloCoordinador\Inicio\Evaluaciones;

use App\Models\Admision;
use App\Models\Evaluacion;
use App\Models\Inscripcion;
use App\Models\Programa;
use App\Models\Puntaje;
use App\Models\TrabajadorTipoTrabajador;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Inscripciones extends Component
{
    public $id_programa; // es el id del programa que se esta consultando
    public $programa; // es el programa que se esta consultando
    public $id_admision; // es el id de la admision que se esta consultando
    public $admision; // es la admision que se esta consultando
    public $inscripciones; // es el listado de inscripciones del programa
    public $puntaje_model; // es el modelo de puntaje que se esta consultando
    public $puntaje_alerta; // es el puntaje de alerta que se esta consultando

    public $search = ''; // Variable para la busqueda

    public function mount()
    {
        $usuario = auth('usuario')->user();
        $trabajador_tipo_trabajador = TrabajadorTipoTrabajador::where('id_trabajador_tipo_trabajador', $usuario->id_trabajador_tipo_trabajador)->first();
        $trabajador = $trabajador_tipo_trabajador->trabajador;
        $coordinador = $trabajador->coordinador;
        $this->programa = Programa::find($this->id_programa);
        if(!$this->programa)
        {
            abort(404);
        }
        elseif($this->programa->id_facultad != $coordinador->id_facultad)
        {
            abort(404);
        }
        $this->admision = Admision::find($this->id_admision);
        if(!$this->admision)
        {
            abort(404);
        }
        $this->puntaje_model = Puntaje::where('puntaje_estado', 1)->first();
    }

    public function alerta_evaluacion($tipo_evaluacion)
    {
        dd($tipo_evaluacion);
    }

    public function evaluacion_expediente(Inscripcion $inscripcion)
    {
        if($this->admision->admision_fecha_inicio_expediente <= date('Y-m-d') && $this->admision->admision_fecha_fin_expediente >= date('Y-m-d'))
        {
            $evaluacion = Evaluacion::where('id_inscripcion', $inscripcion->id_inscripcion)->first();
            if($evaluacion)
            {
                return redirect()->route('coordinador.evaluacion-expediente', [
                    'id' => $this->id_programa,
                    'id_admision' => $this->id_admision,
                    'id_evaluacion' => $evaluacion->id_evaluacion
                ]);
            }
            else
            {
                $evaluacion = new Evaluacion(); // se crea una nueva evaluacion
                $evaluacion->evaluacion_estado = 1;
                $evaluacion->evaluacion_estado_admitido = 0;
                $evaluacion->id_inscripcion = $inscripcion->id_inscripcion;
                $evaluacion->id_tipo_evaluacion = $inscripcion->inscripcion_tipo_programa;
                $evaluacion->save();

                // retorna a la vista de evaluacion de expediente
                return redirect()->route('coordinador.evaluacion-expediente', [
                    'id' => $this->id_programa,
                    'id_admision' => $this->id_admision,
                    'id_evaluacion' => $evaluacion->id_evaluacion
                ]);
            }
        }
        else if($this->admision->admision_fecha_inicio_expediente > date('Y-m-d'))
        {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación de expedientes comienza el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_inicio_expediente)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        }
        else if($this->admision->admision_fecha_fin_expediente < date('Y-m-d'))
        {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación de expediente termino el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_fin_expediente)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        }
    }

    public function evaluacion_investigacion(Inscripcion $inscripcion)
    {
        if($this->admision->admision_fecha_inicio_entrevista <= date('Y-m-d') && $this->admision->admision_fecha_fin_entrevista >= date('Y-m-d'))
        {
            $evaluacion = Evaluacion::where('id_inscripcion', $inscripcion->id_inscripcion)->first();
            if($inscripcion->inscripcion_tipo_programa = 2)
            {
                if($evaluacion)
                {
                    if($evaluacion->puntaje_expediente)
                    {
                        return redirect()->route('coordinador.evaluacion-investigacion', [
                            'id' => $this->id_programa,
                            'id_admision' => $this->id_admision,
                            'id_evaluacion' => $evaluacion->id_evaluacion
                        ]);
                    }
                    else
                    {
                        $this->dispatchBrowserEvent('alerta_evaluacion', [
                            'title' => '¡Error!',
                            'text' => 'No se puede realizar la evaluación del tema de tesis, debe realizar la evaluación de expediente.',
                            'icon' => 'error',
                            'confirmButtonText' => 'Aceptar',
                            'color' => 'danger'
                        ]);
                    }
                }
                else
                {
                    $this->dispatchBrowserEvent('alerta_evaluacion', [
                        'title' => '¡Error!',
                        'text' => 'No se puede realizar la evaluación del tema de tesis, debe realizar la evaluación de expediente.',
                        'icon' => 'error',
                        'confirmButtonText' => 'Aceptar',
                        'color' => 'danger'
                    ]);
                }
            }
        }
        else if($this->admision->admision_fecha_inicio_entrevista > date('Y-m-d'))
        {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación del tema de tesis comienza el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_inicio_entrevista)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        }
        else if($this->admision->admision_fecha_fin_entrevista < date('Y-m-d'))
        {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación del tema de tesis termino el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_fin_entrevista)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        }
    }

    public function evaluacion_entrevista(Inscripcion $inscripcion)
    {
        if($this->admision->admision_fecha_inicio_entrevista <= date('Y-m-d') && $this->admision->admision_fecha_fin_entrevista >= date('Y-m-d'))
        {
            $evaluacion = Evaluacion::where('id_inscripcion', $inscripcion->id_inscripcion)->first();
            if($inscripcion->inscripcion_tipo_programa = 1)
            {
                if($evaluacion)
                {
                    if($evaluacion->puntaje_expediente)
                    {
                        return redirect()->route('coordinador.evaluacion-entrevista', [
                            'id' => $this->id_programa,
                            'id_admision' => $this->id_admision,
                            'id_evaluacion' => $evaluacion->id_evaluacion
                        ]);
                    }
                    else
                    {
                        $this->dispatchBrowserEvent('alerta_evaluacion', [
                            'title' => '¡Error!',
                            'text' => 'No se puede realizar la evaluación de entrevista, debe realizar la evaluación de expediente.',
                            'icon' => 'error',
                            'confirmButtonText' => 'Aceptar',
                            'color' => 'danger'
                        ]);
                    }
                }
                else
                {
                    $this->dispatchBrowserEvent('alerta_evaluacion', [
                        'title' => '¡Error!',
                        'text' => 'No se puede realizar la evaluación de entrevista, debe realizar la evaluación de expediente.',
                        'icon' => 'error',
                        'confirmButtonText' => 'Aceptar',
                        'color' => 'danger'
                    ]);
                }
            }
            else
            {
                if($evaluacion)
                {
                    if($evaluacion->puntaje_investigacion)
                    {
                        return redirect()->route('coordinador.evaluacion-entrevista', [
                            'id' => $this->id_programa,
                            'id_admision' => $this->id_admision,
                            'id_evaluacion' => $evaluacion->id_evaluacion
                        ]);
                    }
                    else
                    {
                        $this->dispatchBrowserEvent('alerta_evaluacion', [
                            'title' => '¡Error!',
                            'text' => 'No se puede realizar la evaluación de entrevista, debe realizar la evaluación del tema de tesis.',
                            'icon' => 'error',
                            'confirmButtonText' => 'Aceptar',
                            'color' => 'danger'
                        ]);
                    }
                }
                else
                {
                    $this->dispatchBrowserEvent('alerta_evaluacion', [
                        'title' => '¡Error!',
                        'text' => 'No se puede realizar la evaluación de entrevista, debe realizar la evaluación del tema de tesis.',
                        'icon' => 'error',
                        'confirmButtonText' => 'Aceptar',
                        'color' => 'danger'
                    ]);
                }
            }
        }
        else if($this->admision->admision_fecha_inicio_entrevista > date('Y-m-d'))
        {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación de entrevista comienza el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_inicio_entrevista)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        }
        else if($this->admision->admision_fecha_fin_entrevista < date('Y-m-d'))
        {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación de entrevista termino el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_fin_entrevista)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        }
    }

    public function render()
    {
        $this->inscripciones = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                        ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                        ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                                        ->join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
                                        ->where('programa.id_programa', $this->id_programa)
                                        ->where('programa_proceso.id_admision', $this->id_admision)
                                        ->where(function($query) {
                                            $query->where('persona.nombre_completo', 'like', '%' . $this->search . '%')
                                                ->orWhere('persona.numero_documento', 'like', '%' . $this->search . '%');
                                        })
                                        ->orderBy('persona.nombre_completo', 'asc')
                                        ->get();
        $evaluaciones = Evaluacion::join('inscripcion', 'inscripcion.id_inscripcion', '=', 'evaluacion.id_inscripcion')
                                    ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                    ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                    ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                                    ->join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
                                    ->where('programa.id_programa', $this->id_programa)
                                    ->where('programa_proceso.id_admision', $this->id_admision)
                                    ->orderBy('persona.nombre_completo', 'asc')
                                    ->get();
        return view('livewire.modulo-coordinador.inicio.evaluaciones.inscripciones', [
            'evaluaciones' => $evaluaciones
        ]);
    }
}
