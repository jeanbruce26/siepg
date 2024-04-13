<?php

namespace App\Http\Livewire\ModuloCoordinador\Inicio\Evaluaciones;

use App\Models\Admision;
use App\Models\Evaluacion;
use App\Models\ExpedienteAdmision;
use App\Models\ExpedienteInscripcion;
use App\Models\Inscripcion;
use App\Models\Persona;
use App\Models\Programa;
use App\Models\Puntaje;
use App\Models\TrabajadorTipoTrabajador;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class Inscripciones extends Component
{
    use WithPagination; // para la paginacion del componente de livewire

    public $id_programa; // es el id del programa que se esta consultando
    public $programa; // es el programa que se esta consultando
    public $id_admision; // es el id de la admision que se esta consultando
    public $admision; // es la admision que se esta consultando
    public $puntaje_model; // es el modelo de puntaje que se esta consultando
    public $puntaje_alerta; // es el puntaje de alerta que se esta consultando

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

    public $nombre_desde;
    public $filtro_nombre_desde;
    public $nombre_hasta;
    public $filtro_nombre_hasta;

    protected $queryString = [
        'search' => ['except' => ''], // para la busqueda
        'filtro_nombre_desde' => ['except' => ''], // para la busqueda
        'filtro_nombre_hasta' => ['except' => ''], // para la busqueda
    ];

    public function mount()
    {
        $usuario = auth('usuario')->user();
        $trabajador_tipo_trabajador = TrabajadorTipoTrabajador::where('id_trabajador_tipo_trabajador', $usuario->id_trabajador_tipo_trabajador)->first();
        $trabajador = $trabajador_tipo_trabajador->trabajador;
        $coordinador = $trabajador->coordinador;
        $this->programa = Programa::find($this->id_programa);
        if (!$this->programa) {
            abort(404);
        } elseif ($this->programa->id_facultad != $coordinador->id_facultad) {
            abort(404);
        }
        $this->admision = Admision::find($this->id_admision);
        if (!$this->admision) {
            abort(404);
        }
        $this->puntaje_model = Puntaje::where('puntaje_estado', 1)->first();
    }

    // public function alerta_evaluacion($tipo_evaluacion)
    // {
    //     dd($tipo_evaluacion);
    // }

    public function evaluacion_expediente(Inscripcion $inscripcion)
    {
        if ($this->admision->admision_fecha_inicio_expediente <= date('Y-m-d') && $this->admision->admision_fecha_fin_expediente >= date('Y-m-d')) {
            $evaluacion = Evaluacion::where('id_inscripcion', $inscripcion->id_inscripcion)->first();
            if ($evaluacion) {
                return redirect()->route('coordinador.evaluacion-expediente', [
                    'id' => $this->id_programa,
                    'id_admision' => $this->id_admision,
                    'id_evaluacion' => $evaluacion->id_evaluacion
                ]);
            } else {
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
        } else if ($this->admision->admision_fecha_inicio_expediente > date('Y-m-d')) {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación de expedientes comienza el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_inicio_expediente)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        } else if ($this->admision->admision_fecha_fin_expediente < date('Y-m-d')) {
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
        if ($this->admision->admision_fecha_inicio_entrevista <= date('Y-m-d') && $this->admision->admision_fecha_fin_entrevista >= date('Y-m-d')) {
            $evaluacion = Evaluacion::where('id_inscripcion', $inscripcion->id_inscripcion)->first();
            if ($inscripcion->inscripcion_tipo_programa = 2) {
                if ($evaluacion) {
                    if ($evaluacion->puntaje_expediente) {
                        return redirect()->route('coordinador.evaluacion-investigacion', [
                            'id' => $this->id_programa,
                            'id_admision' => $this->id_admision,
                            'id_evaluacion' => $evaluacion->id_evaluacion
                        ]);
                    } else {
                        $this->dispatchBrowserEvent('alerta_evaluacion', [
                            'title' => '¡Error!',
                            'text' => 'No se puede realizar la evaluación del tema de tesis, debe realizar la evaluación de expediente.',
                            'icon' => 'error',
                            'confirmButtonText' => 'Aceptar',
                            'color' => 'danger'
                        ]);
                    }
                } else {
                    $this->dispatchBrowserEvent('alerta_evaluacion', [
                        'title' => '¡Error!',
                        'text' => 'No se puede realizar la evaluación del tema de tesis, debe realizar la evaluación de expediente.',
                        'icon' => 'error',
                        'confirmButtonText' => 'Aceptar',
                        'color' => 'danger'
                    ]);
                }
            }
        } else if ($this->admision->admision_fecha_inicio_entrevista > date('Y-m-d')) {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación del tema de tesis comienza el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_inicio_entrevista)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        } else if ($this->admision->admision_fecha_fin_entrevista < date('Y-m-d')) {
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
        if ($this->admision->admision_fecha_inicio_entrevista <= date('Y-m-d') && $this->admision->admision_fecha_fin_entrevista >= date('Y-m-d')) {
            $evaluacion = Evaluacion::where('id_inscripcion', $inscripcion->id_inscripcion)->first();
            if ($inscripcion->inscripcion_tipo_programa = 1) {
                if ($evaluacion) {
                    if ($evaluacion->puntaje_expediente) {
                        return redirect()->route('coordinador.evaluacion-entrevista', [
                            'id' => $this->id_programa,
                            'id_admision' => $this->id_admision,
                            'id_evaluacion' => $evaluacion->id_evaluacion
                        ]);
                    } else {
                        $this->dispatchBrowserEvent('alerta_evaluacion', [
                            'title' => '¡Error!',
                            'text' => 'No se puede realizar la evaluación de entrevista, debe realizar la evaluación de expediente.',
                            'icon' => 'error',
                            'confirmButtonText' => 'Aceptar',
                            'color' => 'danger'
                        ]);
                    }
                } else {
                    $this->dispatchBrowserEvent('alerta_evaluacion', [
                        'title' => '¡Error!',
                        'text' => 'No se puede realizar la evaluación de entrevista, debe realizar la evaluación de expediente.',
                        'icon' => 'error',
                        'confirmButtonText' => 'Aceptar',
                        'color' => 'danger'
                    ]);
                }
            } else {
                if ($evaluacion) {
                    if ($evaluacion->puntaje_investigacion) {
                        return redirect()->route('coordinador.evaluacion-entrevista', [
                            'id' => $this->id_programa,
                            'id_admision' => $this->id_admision,
                            'id_evaluacion' => $evaluacion->id_evaluacion
                        ]);
                    } else {
                        $this->dispatchBrowserEvent('alerta_evaluacion', [
                            'title' => '¡Error!',
                            'text' => 'No se puede realizar la evaluación de entrevista, debe realizar la evaluación del tema de tesis.',
                            'icon' => 'error',
                            'confirmButtonText' => 'Aceptar',
                            'color' => 'danger'
                        ]);
                    }
                } else {
                    $this->dispatchBrowserEvent('alerta_evaluacion', [
                        'title' => '¡Error!',
                        'text' => 'No se puede realizar la evaluación de entrevista, debe realizar la evaluación del tema de tesis.',
                        'icon' => 'error',
                        'confirmButtonText' => 'Aceptar',
                        'color' => 'danger'
                    ]);
                }
            }
        } else if ($this->admision->admision_fecha_inicio_entrevista > date('Y-m-d')) {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación de entrevista comienza el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_inicio_entrevista)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        } else if ($this->admision->admision_fecha_fin_entrevista < date('Y-m-d')) {
            $this->dispatchBrowserEvent('alerta_evaluacion', [
                'title' => '¡Error!',
                'text' => 'No se puede realizar las evaluaciones, la fecha de evaluación de entrevista termino el ' . date('d/m/Y', strtotime($this->admision->admision_fecha_fin_entrevista)) . '.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        }
    }

    public function detalle_evaluacion(Inscripcion $inscripcion)
    {
        $persona = Persona::where('id_persona', $inscripcion->id_persona)->first();
        $expedientes_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $inscripcion->id_inscripcion)->orderBy('id_expediente_admision', 'asc')->get();
        $expediente_admision = ExpedienteAdmision::join('expediente', 'expediente.id_expediente', '=', 'expediente_admision.id_expediente')
            ->where('expediente_admision.id_admision', $this->id_admision)
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

    public function filtrar_nombre()
    {
        $this->filtro_nombre_desde = $this->nombre_desde;
        $this->filtro_nombre_hasta = $this->nombre_hasta;
    }

    public function limpiar_filtro_nombre()
    {
        $this->nombre_desde = null;
        $this->filtro_nombre_desde = null;
        $this->nombre_hasta = null;
        $this->filtro_nombre_hasta = null;
    }

    public function render()
    {
        $inscripcionesQuery = Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
            ->where('programa.id_programa', $this->id_programa)
            ->where('programa_proceso.id_admision', $this->id_admision)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('inscripcion.inscripcion_estado', 1)
            ->where(function ($query) {
                $query->where('persona.nombre_completo', 'like', '%' . $this->search . '%')
                    ->orWhere('persona.numero_documento', 'like', '%' . $this->search . '%');
            })
            ->orderBy('persona.nombre_completo', 'asc');
        if ($this->filtro_nombre_desde && $this->filtro_nombre_hasta) {
            $inscripcionesQuery->whereBetween('persona.nombre_completo', [$this->filtro_nombre_desde, $this->filtro_nombre_hasta]);
        }
        $inscripciones = $inscripcionesQuery->paginate(100);

        $evaluaciones = Evaluacion::join('inscripcion', 'inscripcion.id_inscripcion', '=', 'evaluacion.id_inscripcion')
            ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
            ->where('programa.id_programa', $this->id_programa)
            ->where('programa_proceso.id_admision', $this->id_admision)
            ->where('inscripcion.retiro_inscripcion', 0)
            ->where('inscripcion.inscripcion_estado', 1)
            ->where(function ($query) {
                $query->where('evaluacion.evaluacion_estado', 2)
                    ->orWhere('evaluacion.evaluacion_estado', 3);
            })
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();
        return view('livewire.modulo-coordinador.inicio.evaluaciones.inscripciones', [
            'inscripciones' => $inscripciones,
            'evaluaciones' => $evaluaciones
        ]);
    }
}
