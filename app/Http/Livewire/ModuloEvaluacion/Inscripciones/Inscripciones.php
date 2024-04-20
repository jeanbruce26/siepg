<?php

namespace App\Http\Livewire\ModuloEvaluacion\Inscripciones;

use App\Models\Persona;
use App\Models\Puntaje;
use Livewire\Component;
use App\Models\Admision;
use App\Models\Programa;
use App\Models\Evaluacion;
use App\Models\Inscripcion;
use App\Models\ProgramaProceso;
use App\Models\ExpedienteAdmision;
use Illuminate\Support\Collection;
use App\Models\EvaluacionEntrevista;
use App\Models\EvaluacionExpediente;
use App\Models\EvaluacionObservacion;
use App\Models\ExpedienteInscripcion;
use App\Models\EvaluacionInvestigacion;
use App\Models\EvaluacionEntrevistaItem;
use App\Models\EvaluacionExpedienteTitulo;
use App\Models\EvaluacionInvestigacionItem;

class Inscripciones extends Component
{
    public $usuario;
    public $usuario_evaluacion;
    public $evaluaciones;
    public $programa_proceso;

    public $programa; // es el programa que se esta consultando
    public $admision; // es la admision que se esta consultando
    public $puntaje; // es el modelo de puntaje que se esta consultando

    // variables del modal
    public $title_modal = 'Información del Postulante'; // titulo del modal
    public $nombre_completo; // nombre completo del postulante
    public $documento; // dni del postulante
    public $correo; // correo del postulante
    public $celular; // telefono del postulante
    public $especialidad; // especialidad del postulante
    public $grado_academico; // grado academico del postulante
    public $expedientes; // listado de expedientes del postulante
    public $expedientes_model; // modelo de la tabla expediente

    public $search = ''; // Variable para la busqueda

    protected $queryString = [
        'search' => ['except' => ''], // para la busqueda
    ];

    public $variable = 'expediente';
    public $expediente_tipo_evaluacion = 1;
    public $es_doctorado = false;
    public $inscripcion;
    public $evaluacion;

    public Collection $evaluacion_expediente;
    public Collection $evaluacion_investigacion;
    public Collection $evaluacion_entrevista;
    public $puntajes_expediente = [];
    public $puntajes_investigacion = [];
    public $puntajes_entrevista = [];
    public $puntaje_total = 0;
    public $observacion;

    public Collection $expedientes_inscripcion;

    public function mount($id_programa_proceso)
    {
        $this->usuario = auth('evaluacion')->user();
        $this->usuario_evaluacion = $this->usuario->usuario_evaluaciones->where('id_programa_proceso', $id_programa_proceso)->first();
        $this->evaluaciones = $this->usuario->usuario_evaluaciones;
        $this->programa_proceso = ProgramaProceso::find($id_programa_proceso);
        $esatdo = false;
        foreach ($this->evaluaciones as $evaluacion) {
            if ($evaluacion->id_programa_proceso == $id_programa_proceso) {
                $esatdo = true;
                break;
            }
        }
        if (!$esatdo) {
            abort(403, 'No tiene permisos para acceder a esta evaluación');
        }

        $this->programa = Programa::find($this->programa_proceso->programa_plan->programa->id_programa);
        if (!$this->programa) {
            abort(404);
        }
        $this->evaluacion_expediente = collect();
        $this->evaluacion_investigacion = collect();
        $this->evaluacion_entrevista = collect();
        $this->puntaje = Puntaje::where('puntaje_estado', 1)->first();

        $this->expedientes_inscripcion = collect();
    }

    public function detalle_evaluacion(Inscripcion $inscripcion)
    {
        $persona = Persona::where('id_persona', $inscripcion->id_persona)->first();
        $expedientes_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $inscripcion->id_inscripcion)->orderBy('id_expediente_admision', 'asc')->get();
        $expediente_admision = ExpedienteAdmision::join('expediente', 'expediente.id_expediente', '=', 'expediente_admision.id_expediente')
            ->where('expediente_admision.id_admision', $this->programa_proceso->id_admision)
            ->where(function ($query) use ($inscripcion) {
                $query->where('expediente.expediente_tipo', 0)
                    ->orWhere('expediente.expediente_tipo', $inscripcion->inscripcion_tipo_programa);
            })
            ->get();

        // Utilizamos el método map() para recorrer la colección $expediente_admision y realizar la comparación con cada $expediente_inscripcion.
        // Si se encuentra una coincidencia, se agrega la columna "estado" con el valor "enviado" al objeto $expediente.
        // De lo contrario, se agrega la columna "estado" con el valor "no enviado".
        // Finalmente, se devuelve la colección $expedientes_comparados con la nueva columna agregada.
        $this->expedientes = $expediente_admision->map(
            function ($expediente) use ($expedientes_inscripcion) {
                $expediente_inscripcion = $expedientes_inscripcion->firstWhere('id_expediente_admision', $expediente->id_expediente_admision);

                if ($expediente_inscripcion) {
                    $expediente->estado = 1;
                    $expediente->expediente_inscripcion_url = $expediente_inscripcion->expediente_inscripcion_url;
                } else {
                    $expediente->estado = 0;
                    $expediente->expediente_inscripcion_url = null;
                }

                return $expediente;
            }
        );

        // Asignamos los valores a las variables que se mostrarán en la vista.
        $this->nombre_completo = $persona->nombre_completo;
        $this->documento = $persona->numero_documento;
        $this->correo = $persona->correo;
        $this->celular = $persona->celular;
        $this->especialidad = $persona->especialidad_carrera;
        $this->grado_academico = $persona->grado_academico->grado_academico;
    }

    public function alerta($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    public function change_title($value)
    {
        $this->variable = $value;
        $this->cargar_puntaje();
    }

    public function limpiar()
    {
        $this->reset([
            'variable',
            'search',
            'inscripcion',
            'evaluacion',
            'expediente_tipo_evaluacion',
            'puntajes_expediente',
            'puntajes_investigacion',
            'puntajes_entrevista',
            'puntaje_total',
            'observacion'
        ]);
        $this->evaluacion_expediente = collect();
        $this->evaluacion_investigacion = collect();
        $this->evaluacion_entrevista = collect();
        $this->expedientes_inscripcion = collect();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cargar_evaluacion($id_inscripcion, $value)
    {
        $this->limpiar();
        $this->change_title($value);
        if ($this->variable === 'expediente') {
            $this->expediente_tipo_evaluacion = 1;
        }
        if ($this->variable === 'investigacion') {
            $this->expediente_tipo_evaluacion = 2;
        }
        if ($this->variable === 'entrevista') {
            $this->expediente_tipo_evaluacion = 3;
        }
        $this->inscripcion = Inscripcion::find($id_inscripcion);
        $this->expedientes_inscripcion = ExpedienteInscripcion::join('expediente_admision', 'expediente_inscripcion.id_expediente_admision', 'expediente_admision.id_expediente_admision')
            ->join('expediente', 'expediente_admision.id_expediente', 'expediente.id_expediente')
            ->where('expediente_inscripcion.id_inscripcion',$this->inscripcion->id_inscripcion)
            ->where(function($query){
                $query->where('expediente.expediente_tipo', 0)
                    ->orWhere('expediente.expediente_tipo', $this->inscripcion->inscripcion_tipo_programa);
            })
            ->orderBy('expediente.id_expediente', 'asc')
            ->get();
        $this->es_doctorado = $this->inscripcion->inscripcion_tipo_programa == 2 ? true : false;
        // validamos las fechas de las evaluciones
        $admision = Admision::find($this->programa_proceso->id_admision);
        if ($this->variable === 'expediente') {
            if ($admision->admision_fecha_inicio_expediente > date('Y-m-d') || $admision->admision_fecha_fin_expediente < date('Y-m-d')) {
                $this->alerta(
                    'Error',
                    'No se puede realizar las evaluaciones, las fechas de evaluación de expediente son del ' . date('d/m/Y', strtotime($admision->admision_fecha_inicio_expediente)) . ' al ' . date('d/m/Y', strtotime($admision->admision_fecha_fin_expediente)),
                    'error',
                    'Ok',
                    'danger');
                return;
            }
        }
        if ($this->variable === 'investigacion') {
            if ($admision->admision_fecha_inicio_investigacion > date('Y-m-d') || $admision->admision_fecha_fin_investigacion < date('Y-m-d')) {
                $this->alerta(
                    'Error',
                    'No se puede realizar las evaluaciones, las fechas de evaluación de investigación son del ' . date('d/m/Y', strtotime($admision->admision_fecha_inicio_investigacion)) . ' al ' . date('d/m/Y', strtotime($admision->admision_fecha_fin_investigacion)),
                    'error',
                    'Ok',
                    'danger');
                return;
            }
        }
        if ($this->variable === 'entrevista') {
            if ($admision->admision_fecha_inicio_entrevista > date('Y-m-d') || $admision->admision_fecha_fin_entrevista < date('Y-m-d')) {
                $this->alerta(
                    'Error',
                    'No se puede realizar las evaluaciones, las fechas de evaluación de entrevista son del ' . date('d/m/Y', strtotime($admision->admision_fecha_inicio_entrevista)) . ' al ' . date('d/m/Y', strtotime($admision->admision_fecha_fin_entrevista)),
                    'error',
                    'Ok',
                    'danger');
                return;
            }
        }
        // verificamos si tiene evaluacion
        $evaluacion = Evaluacion::where('id_inscripcion', $id_inscripcion)->first();
        if (!$evaluacion) {
            $evaluacion = new Evaluacion(); // se crea una nueva evaluacion
            $evaluacion->evaluacion_estado = 1;
            $evaluacion->evaluacion_estado_admitido = 0;
            $evaluacion->id_inscripcion = $this->inscripcion->id_inscripcion;
            $evaluacion->id_tipo_evaluacion = $this->inscripcion->inscripcion_tipo_programa;
            $evaluacion->save();
        }
        // validamos si todavia no respondio la evaluaciones anteriores
        if ($this->variable === 'investigacion' || $this->variable === 'entrevista') {
            if (!$evaluacion->puntaje_expediente) {
                $this->alerta(
                    'Error',
                    'No se puede realizar la evaluación del tema de tesis, debe realizar la evaluación de expediente.',
                    'error',
                    'Ok',
                    'danger');
                return;
            }
        }
        if ($this->variable === 'entrevista') {
            if ($this->es_doctorado) {
                if (!$evaluacion->puntaje_investigacion) {
                    $this->alerta(
                        'Error',
                        'No se puede realizar la evaluación de entrevista, debe realizar la evaluación del tema de tesis.',
                        'error',
                        'Ok',
                        'danger');
                    return;
                }
            }
        }
        $this->evaluacion = $evaluacion;
        // cargamos las evaluaciones de expediente
        $this->evaluacion_expediente = EvaluacionExpedienteTitulo::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        $this->evaluacion_investigacion = EvaluacionInvestigacionItem::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        $this->evaluacion_entrevista = EvaluacionEntrevistaItem::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        $this->cargar_puntaje();
        // abrimos el modal
        $this->dispatchBrowserEvent('modal', [
            'id' => '#modal-evaluacion',
            'action' => 'show'
        ]);
    }

    public function cargar_puntaje()
    {
        $this->puntaje_total = 0;
        $this->puntajes_expediente = [];
        $this->puntajes_investigacion = [];
        $this->puntajes_entrevista = [];
        if ($this->variable == 'expediente') {
            $this->expediente_tipo_evaluacion = 1;
            foreach ($this->evaluacion_expediente as $evaluacion) {
                $evaluacion_expediente = EvaluacionExpediente::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                    ->where('id_evaluacion_expediente_titulo', $evaluacion->id_evaluacion_expediente_titulo)
                    ->first();
                $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo] = $evaluacion_expediente ? $evaluacion_expediente->evaluacion_expediente_puntaje : 0;
                $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo] = number_format($this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo], 0);
                $this->puntaje_total += $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo];
            }
        } elseif ($this->variable == 'investigacion') {
            $this->expediente_tipo_evaluacion = 2;
            foreach ($this->evaluacion_investigacion as $evaluacion) {
                $evaluacion_investigacion = EvaluacionInvestigacion::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                    ->where('id_evaluacion_investigacion_item', $evaluacion->id_evaluacion_investigacion_item)
                    ->first();
                $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item] = $evaluacion_investigacion ? $evaluacion_investigacion->evaluacion_investigacion_puntaje : 0;
                $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item] = number_format($this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item], 0);
                $this->puntaje_total += $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item];
            }
        } elseif ($this->variable == 'entrevista') {
            $this->expediente_tipo_evaluacion = 3;
            foreach ($this->evaluacion_entrevista as $evaluacion) {
                $evaluacion_entrevista = EvaluacionEntrevista::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                    ->where('id_evaluacion_entrevista_item', $evaluacion->id_evaluacion_entrevista_item)
                    ->first();
                $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item] = $evaluacion_entrevista ? $evaluacion_entrevista->evaluacion_entrevista_puntaje : 0;
                $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item] = number_format($this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item], 0);
                $this->puntaje_total += $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item];
            }
        }
    }

    public function updatedPuntajesExpediente()
    {
        $this->puntaje_total = 0;
        foreach ($this->puntajes_expediente as $puntaje) {
            if ($puntaje < 0) {
                $this->alerta('Error', 'El puntaje no puede ser negativo', 'error', 'Ok', 'danger');
                $this->puntajes_expediente = [];
                $this->cargar_puntaje();
                return;
            }
            $this->puntaje_total += $puntaje;
        }
    }

    public function updatedPuntajesInvestigacion()
    {
        $this->puntaje_total = 0;
        foreach ($this->puntajes_investigacion as $puntaje) {
            if ($puntaje < 0) {
                $this->alerta('Error', 'El puntaje no puede ser negativo', 'error', 'Ok', 'danger');
                $this->puntajes_investigacion = [];
                $this->cargar_puntaje();
                return;
            }
            $this->puntaje_total += $puntaje;
        }
    }

    public function updatedPuntajesEntrevista()
    {
        $this->puntaje_total = 0;
        foreach ($this->puntajes_entrevista as $puntaje) {
            if ($puntaje < 0) {
                $this->alerta('Error', 'El puntaje no puede ser negativo', 'error', 'Ok', 'danger');
                $this->puntajes_entrevista = [];
                $this->cargar_puntaje();
                return;
            }
            $this->puntaje_total += $puntaje;
        }
    }

    public function evaluar_expediente()
    {
        if ($this->puntaje_total == 0) {
            $this->alerta('¡Error!', 'Debe ingresar al menos un puntaje.', 'error', 'Aceptar', 'danger');
            return;
        }

        // validar si no hay puntajes vacios
        foreach ($this->puntajes_expediente as $puntaje) {
            if ($puntaje == 0) {
                $this->alerta('¡Error!', 'Hay puntajes por ingresar', 'error', 'Aceptar', 'danger');
                return;
            }
        }

        // ingresamos los puntajes
        foreach ($this->evaluacion_expediente as $evaluacion) {
            $evaluacion_expediente = EvaluacionExpediente::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                ->where('id_evaluacion_expediente_titulo', $evaluacion->id_evaluacion_expediente_titulo)
                ->first();
            if (!$evaluacion_expediente) {
                $evaluacion_expediente = new EvaluacionExpediente();
                $evaluacion_expediente->evaluacion_expediente_puntaje = $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo];
                $evaluacion_expediente->id_evaluacion_expediente_titulo = $evaluacion->id_evaluacion_expediente_titulo;
                $evaluacion_expediente->id_evaluacion = $this->evaluacion->id_evaluacion;
            } else {
                $evaluacion_expediente->evaluacion_expediente_puntaje = $this->puntajes_expediente[$evaluacion->id_evaluacion_expediente_titulo];
            }
            $evaluacion_expediente->save();
        }

        $this->evaluacion->puntaje_expediente = $this->puntaje_total;
        $this->evaluacion->fecha_expediente = date('Y-m-d H:i:s'); // fecha actual
        $this->evaluacion->save();

        if($this->observacion){
            $observacion = new EvaluacionObservacion();
            $observacion->evaluacion_observacion = $this->observacion;
            $observacion->id_tipo_evaluacion = 1; // 1 = Expediente 2 = Tesis 3 = Entrevista
            $observacion->evaluacion_observacion_fecha = now();
            $observacion->id_evaluacion = $this->id_evaluacion;
            $observacion->save();
        }

        finalizar_evaluacion($this->evaluacion, $this->puntaje);

        $this->alerta('¡Exito!', 'Se ha registrado el puntaje de su evaluación correctamente.', 'success', 'Aceptar', 'success');

        $this->dispatchBrowserEvent('modal', [
            'id' => '#modal-evaluacion',
            'action' => 'hide'
        ]);

        $this->limpiar();
    }

    public function evaluar_investigacion()
    {
        if ($this->puntaje_total == 0) {
            $this->alerta('¡Error!', 'Debe ingresar al menos un puntaje.', 'error', 'Aceptar', 'danger');
            return;
        }

        // validar si no hay puntajes vacios
        foreach ($this->puntajes_investigacion as $puntaje) {
            if ($puntaje == 0) {
                $this->alerta('¡Error!', 'Hay puntajes por ingresar', 'error', 'Aceptar', 'danger');
                return;
            }
        }

        // ingresamos los puntajes
        foreach ($this->evaluacion_investigacion as $evaluacion) {
            $evaluacion_investigacion = EvaluacionInvestigacion::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                ->where('id_evaluacion_investigacion_item', $evaluacion->id_evaluacion_investigacion_item)
                ->first();
            if (!$evaluacion_investigacion) {
                $evaluacion_investigacion = new EvaluacionInvestigacion();
                $evaluacion_investigacion->evaluacion_investigacion_puntaje = $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item];
                $evaluacion_investigacion->id_evaluacion_investigacion_item = $evaluacion->id_evaluacion_investigacion_item;
                $evaluacion_investigacion->id_evaluacion = $this->evaluacion->id_evaluacion;
            } else {
                $evaluacion_investigacion->evaluacion_investigacion_puntaje = $this->puntajes_investigacion[$evaluacion->id_evaluacion_investigacion_item];
            }
            $evaluacion_investigacion->save();
        }

        $this->evaluacion->puntaje_investigacion = $this->puntaje_total;
        $this->evaluacion->fecha_investigacion = date('Y-m-d H:i:s'); // fecha actual
        $this->evaluacion->save();

        if($this->observacion){
            $observacion = new EvaluacionObservacion();
            $observacion->evaluacion_observacion = $this->observacion;
            $observacion->id_tipo_evaluacion = 2; // 1 = Expediente 2 = Tesis 3 = Entrevista
            $observacion->evaluacion_observacion_fecha = now();
            $observacion->id_evaluacion = $this->id_evaluacion;
            $observacion->save();
        }

        finalizar_evaluacion($this->evaluacion, $this->puntaje);

        $this->alerta('¡Exito!', 'Se ha registrado el puntaje de su evaluación correctamente.', 'success', 'Aceptar', 'success');

        $this->dispatchBrowserEvent('modal', [
            'id' => '#modal-evaluacion',
            'action' => 'hide'
        ]);

        $this->limpiar();
    }

    public function evaluar_entrevista()
    {
        if ($this->puntaje_total == 0) {
            $this->alerta('¡Error!', 'Debe ingresar al menos un puntaje.', 'error', 'Aceptar', 'danger');
            return;
        }

        // validar si no hay puntajes vacios
        foreach ($this->puntajes_entrevista as $puntaje) {
            if ($puntaje == 0) {
                $this->alerta('¡Error!', 'Hay puntajes por ingresar', 'error', 'Aceptar', 'danger');
                return;
            }
        }

        // ingresamos los puntajes
        foreach ($this->evaluacion_entrevista as $evaluacion) {
            $evaluacion_entrevista = EvaluacionEntrevista::where('id_evaluacion', $this->evaluacion->id_evaluacion)
                ->where('id_evaluacion_entrevista_item', $evaluacion->id_evaluacion_entrevista_item)
                ->first();
            if (!$evaluacion_entrevista) {
                $evaluacion_entrevista = new EvaluacionEntrevista();
                $evaluacion_entrevista->evaluacion_entrevista_puntaje = $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item];
                $evaluacion_entrevista->id_evaluacion_entrevista_item = $evaluacion->id_evaluacion_entrevista_item;
                $evaluacion_entrevista->id_evaluacion = $this->evaluacion->id_evaluacion;
            } else {
                $evaluacion_entrevista->evaluacion_entrevista_puntaje = $this->puntajes_entrevista[$evaluacion->id_evaluacion_entrevista_item];
            }
            $evaluacion_entrevista->save();
        }

        $this->evaluacion->puntaje_entrevista = $this->puntaje_total;
        $this->evaluacion->fecha_entrevista = date('Y-m-d H:i:s'); // fecha actual
        $this->evaluacion->save();

        if($this->observacion){
            $observacion = new EvaluacionObservacion();
            $observacion->evaluacion_observacion = $this->observacion;
            $observacion->id_tipo_evaluacion = 3; // 1 = Expediente 2 = Tesis 3 = Entrevista
            $observacion->evaluacion_observacion_fecha = now();
            $observacion->id_evaluacion = $this->id_evaluacion;
            $observacion->save();
        }

        finalizar_evaluacion($this->evaluacion, $this->puntaje);

        $this->alerta('¡Exito!', 'Se ha registrado el puntaje de su evaluación correctamente.', 'success', 'Aceptar', 'success');

        $this->dispatchBrowserEvent('modal', [
            'id' => '#modal-evaluacion',
            'action' => 'hide'
        ]);

        $this->limpiar();
    }

    public function render()
    {
        $inscripciones = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
            ->where('programa_proceso.id_programa_proceso', $this->programa_proceso->id_programa_proceso)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('inscripcion.inscripcion_estado', 1)
            ->whereBetween('inscripcion.numero', [$this->usuario_evaluacion->numero_inicio, $this->usuario_evaluacion->numero_fin])
            ->where(function ($query) {
                $query->where('persona.nombre_completo', 'like', '%' . $this->search . '%')
                    ->orWhere('persona.numero_documento', 'like', '%' . $this->search . '%');
            })
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();

        return view('livewire.modulo-evaluacion.inscripciones.inscripciones', [
            'inscripciones' => $inscripciones
        ])->layout('layouts.modulo-evaluaciones.app', ['title' => 'Listado | Evaluaciones | Escuela de Posgrado UNU']);
    }
}
