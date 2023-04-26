<?php

namespace App\Http\Livewire\ModuloCoordinador\Inicio\Evaluaciones;

use App\Models\Admision;
use App\Models\Evaluacion;
use App\Models\EvaluacionEntrevista;
use App\Models\EvaluacionEntrevistaItem;
use App\Models\EvaluacionExpediente as EvaluacionExpedienteModel;
use App\Models\EvaluacionExpedienteTitulo;
use App\Models\EvaluacionInvestigacion;
use App\Models\EvaluacionInvestigacionItem;
use App\Models\EvaluacionObservacion;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Programa;
use App\Models\Puntaje;
use App\Models\TrabajadorTipoTrabajador;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class EvaluacionExpediente extends Component
{
    public $id_programa; // es el id del programa que se esta consultando
    public $programa; // es el programa que se esta consultando
    public $id_admision; // es el id de la admision que se esta consultando
    public $admision; // es la admision que se esta consultando
    public $id_evaluacion; // es el id de la evaluacion que se esta consultando
    public $evaluacion; // es la evaluacion que se esta consultando
    public $puntaje_model; // es el modelo de puntaje que se esta consultando
    public $inscripcion; // es la inscripcion que se esta consultando
    public $persona; // es la persona que se esta consultando

    //variables del modal puntaje
    public $puntaje;
    public $puntaje_total = 0; // es el puntaje total que se esta consultando
    public $id_evaluacion_expediente_titulo; // es el id de la evaluacion expediente titulo que se esta consultando
    public $observacion; // es la observacion que se esta consultando

    protected $listeners = [
        'evaluar_expediente_paso_2' => 'evaluar_expediente',
        'evaluar_expediente_cero_paso_2' => 'evaluar_expediente_cero',
    ];

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
        $this->evaluacion = Evaluacion::find($this->id_evaluacion);
        if(!$this->evaluacion)
        {
            abort(404);
        }
        if($this->evaluacion->puntaje_expediente)
        {
            abort(403);
        }
        $this->inscripcion = $this->evaluacion->inscripcion;
        $this->persona = $this->inscripcion->persona;
        $this->puntaje_model = Puntaje::where('puntaje_estado', 1)->first();
        $this->contar_total();
    }

    public function updated($propertyName)
    {
        $evaluacion_expediente_titulo = EvaluacionExpedienteTitulo::where('id_tipo_evaluacion',$this->evaluacion->id_tipo_evaluacion)->get();
        foreach($evaluacion_expediente_titulo as $item)
        {
            if($item->id_evaluacion_expediente_titulo == $this->id_evaluacion_expediente_titulo)
            {
                $puntaje = number_format($item->evaluacion_expediente_titulo_puntaje, 0);
                $this->validateOnly($propertyName, [
                    'puntaje'=> 'required|numeric|min:0|max:'.$puntaje,
                ]);
            }
        }

        $this->contar_total();
    }

    public function contar_total()
    {
        $evaluacion = EvaluacionExpedienteModel::where('id_evaluacion',$this->id_evaluacion)->get();
        $this->puntaje_total = 0;
        foreach($evaluacion as $item)
        {
            $this->puntaje_total = $this->puntaje_total + $item->evaluacion_expediente_puntaje;
        }
    }

    public function limpiar_modal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['puntaje']);
    }

    public function cargar_evaluacion_expediente_titulo(EvaluacionExpedienteTitulo $evaluacion_expediente_titulo)
    {
        $this->limpiar_modal();

        $this->id_evaluacion_expediente_titulo = $evaluacion_expediente_titulo->id_evaluacion_expediente_titulo;

        $evaluacion_expediente = EvaluacionExpedienteModel::where('id_evaluacion_expediente_titulo',$this->id_evaluacion_expediente_titulo)->where('id_evaluacion',$this->id_evaluacion)->first();
        if($evaluacion_expediente)
        {
            $this->puntaje = number_format($evaluacion_expediente->evaluacion_expediente_puntaje, 0);
        }
    }

    public function agregar_puntaje()
    {
        $evaluacion_expediente_titulo = EvaluacionExpedienteTitulo::where('id_tipo_evaluacion',$this->evaluacion->id_tipo_evaluacion)->get();
        foreach($evaluacion_expediente_titulo as $item)
        {
            if($item->id_evaluacion_expediente_titulo == $this->id_evaluacion_expediente_titulo)
            {
                $puntaje = number_format($item->evaluacion_expediente_titulo_puntaje, 0);
                $this->validate([
                    'puntaje'=> 'required|numeric|min:0|max:'.$puntaje,
                ]);
            }
        }

        $evaluacion_expediente = EvaluacionExpedienteModel::where('id_evaluacion_expediente_titulo',$this->id_evaluacion_expediente_titulo)->where('id_evaluacion',$this->id_evaluacion)->first();

        if($evaluacion_expediente){
            $evaluacion_expediente->evaluacion_expediente_puntaje = $this->puntaje;
            $evaluacion_expediente->save();
            $this->dispatchBrowserEvent('alerta_evaluacion_expediente', [
                'title' => '¡Actualizado!',
                'text' => 'Puntaje actualizado satisfactoriamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }else{
            $evaluacion_expediente = new EvaluacionExpedienteModel();
            $evaluacion_expediente->evaluacion_expediente_puntaje = $this->puntaje;
            $evaluacion_expediente->id_evaluacion_expediente_titulo = $this->id_evaluacion_expediente_titulo;
            $evaluacion_expediente->id_evaluacion = $this->id_evaluacion;
            $evaluacion_expediente->save();

            $this->dispatchBrowserEvent('alerta_evaluacion_expediente', [
                'title' => '¡Agregado!',
                'text' => 'Puntaje agregado satisfactoriamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }

        $this->limpiar_modal();
        $this->contar_total();
        $this->dispatchBrowserEvent('modal_puntaje', ['action' => 'hide']);
    }

    public function evaluar_expediente_paso_1()
    {
        // validamos observacion
        $this->validate([
            'observacion' => 'nullable|string',
        ]);

        // validamos si todos los puntajes fueron agregados
        $evaluacion_expediente = EvaluacionExpedienteModel::where('id_evaluacion',$this->id_evaluacion)->get()->count();
        $evaluacion_expediente_titulo = EvaluacionExpedienteTitulo::where('id_tipo_evaluacion',$this->evaluacion->id_tipo_evaluacion)->get()->count();

        // verificamos si los puntajes fueron agregados
        if($evaluacion_expediente != $evaluacion_expediente_titulo)
        {
            $this->dispatchBrowserEvent('alerta_evaluacion_expediente', [
                'title' => '¡Error!',
                'text' => 'Debe agregar todos los puntajes.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return redirect()->back();
        }

        // emitimos alerta para confirmar la evaluacion
        $this->dispatchBrowserEvent('alerta_evaluacion_expediente_2', [
            'title' => '¿Está seguro?',
            'text' => 'Una vez realizada la evaluación no podrá modificar los puntajes.',
            'icon' => 'question',
            'confirmButtonText' => 'Evaluar',
            'cancelButtonText' => 'Cancelar',
            'colorConfirmButton' => 'primary',
            'colorCancelButton' => 'danger',
        ]);
    }

    public function evaluar_expediente()
    {
        $this->evaluacion->puntaje_expediente = $this->puntaje_total;
        $this->evaluacion->fecha_expediente = now();
        $this->evaluacion->save();

        if($this->observacion){
            $observacion = new EvaluacionObservacion();
            $observacion->evaluacion_observacion = $this->observacion;
            $observacion->id_tipo_evaluacion = 1; // 1 = Expediente 2 = Tesis 3 = Entrevista
            $observacion->evaluacion_observacion_fecha = now();
            $observacion->id_evaluacion = $this->id_evaluacion;
            $observacion->save();
        }

        if($this->puntaje_total == 0){
            $this->evaluar_expediente_cero();
        }

        // redireccionamos a la vista de evaluaciones
        return redirect()->route('coordinador.evaluaciones', [
            'id' => $this->id_programa,
            'id_admision' => $this->id_admision
        ]);
    }

    public function evaluar_expediente_cero_paso_1()
    {
        // emitimos alerta para confirmar la evaluacion
        $this->dispatchBrowserEvent('alerta_evaluacion_expediente_cero', [
            'title' => '¿Está seguro?',
            'text' => 'Una vez realizada la evaluación cero, no podrá modificar los puntajes.',
            'icon' => 'question',
            'confirmButtonText' => 'Evaluar',
            'cancelButtonText' => 'Cancelar',
            'colorConfirmButton' => 'primary',
            'colorCancelButton' => 'danger',
        ]);
    }

    public function evaluar_expediente_cero()
    {
        // asignamos los puntajes 0 en las evaluaciones de expediente, investigacion y entrevista
        // evaluacion de expediente
        $evaluacion_expediente_titulo = EvaluacionExpedienteTitulo::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        foreach ($evaluacion_expediente_titulo as $key => $value) {
            $evaluacion_expediente = EvaluacionExpedienteModel::where('id_evaluacion', $this->id_evaluacion)->where('id_evaluacion_expediente_titulo', $value->id_evaluacion_expediente_titulo)->first();
            if($evaluacion_expediente){
                $evaluacion_expediente->evaluacion_expediente_puntaje = 0;
                $evaluacion_expediente->save();
            }else{
                $evaluacion_expediente = new EvaluacionExpedienteModel();
                $evaluacion_expediente->evaluacion_expediente_puntaje = 0;
                $evaluacion_expediente->id_evaluacion_expediente_titulo = $value->id_evaluacion_expediente_titulo;
                $evaluacion_expediente->id_evaluacion = $this->id_evaluacion;
                $evaluacion_expediente->save();
            }
        }

        // evaluacion de investigacion
        if ($this->evaluacion->id_tipo_evaluacion == 2) {
            $evaluacion_investigacion_item = EvaluacionInvestigacionItem::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
            foreach ($evaluacion_investigacion_item as $key => $value) {
                $evaluacion_investigacion = EvaluacionInvestigacion::where('id_evaluacion', $this->id_evaluacion)->where('id_evaluacion_investigacion_item', $value->id_evaluacion_investigacion_item)->first();
                if($evaluacion_investigacion){
                    $evaluacion_investigacion->evaluacion_investigacion_puntaje = 0;
                    $evaluacion_investigacion->save();
                }else{
                    $evaluacion_investigacion = new EvaluacionInvestigacion();
                    $evaluacion_investigacion->evaluacion_investigacion_puntaje = 0;
                    $evaluacion_investigacion->id_evaluacion_investigacion_item = $value->id_evaluacion_investigacion_item;
                    $evaluacion_investigacion->id_evaluacion = $this->id_evaluacion;
                    $evaluacion_investigacion->save();
                }
            }
        }

        // evaluacion de entrevista
        $evaluacion_entrevista_item = EvaluacionEntrevistaItem::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        foreach ($evaluacion_entrevista_item as $key => $value) {
            $evaluacion_entrevista = EvaluacionEntrevista::where('id_evaluacion', $this->id_evaluacion)->where('id_evaluacion_entrevista_item', $value->id_evaluacion_entrevista_item)->first();
            if($evaluacion_entrevista){
                $evaluacion_entrevista->evaluacion_entrevista_puntaje = 0;
                $evaluacion_entrevista->save();
            }else{
                $evaluacion_entrevista = new EvaluacionEntrevista();
                $evaluacion_entrevista->evaluacion_entrevista_puntaje = 0;
                $evaluacion_entrevista->id_evaluacion_entrevista_item = $value->id_evaluacion_entrevista_item;
                $evaluacion_entrevista->id_evaluacion = $this->id_evaluacion;
                $evaluacion_entrevista->save();
            }
        }

        // asignamos los puntajes a cero de las evaluaciones
        $this->evaluacion->puntaje_expediente = 0;
        $this->evaluacion->fecha_expediente = now();
        if ($this->evaluacion->id_tipo_evaluacion == 2) {
            $this->evaluacion->puntaje_investigacion = 0;
            $this->evaluacion->fecha_investigacion = now();
        }
        $this->evaluacion->puntaje_entrevista = 0;
        $this->evaluacion->fecha_entrevista = now();
        if ($this->evaluacion->id_tipo_evaluacion == 1) {
            $this->evaluacion->evaluacion_observacion = 'No cumple con el Grado Académico del Art. 51.';
        } else {
            $this->evaluacion->evaluacion_observacion = 'No cumple con el Grado Académico del Art. 68.';
        }
        $this->evaluacion->puntaje_final = 0;
        $this->evaluacion->evaluacion_estado = 3;
        $this->evaluacion->save();

        // redireccionamos a la vista de evaluaciones
        return redirect()->route('coordinador.evaluaciones', [
            'id' => $this->id_programa,
            'id_admision' => $this->id_admision
        ]);
    }

    public function render()
    {
        // buscar si el expediente de la inscripcion tiene seguimiento
        $expediente_inscripcion_seguimiento = ExpedienteInscripcionSeguimiento::join('expediente_inscripcion', 'expediente_inscripcion_seguimiento.id_expediente_inscripcion', 'expediente_inscripcion.id_expediente_inscripcion')
                                        ->where('expediente_inscripcion.id_inscripcion', $this->inscripcion->id_inscripcion)
                                        ->where('expediente_inscripcion_seguimiento.expediente_inscripcion_seguimiento_estado', 1)
                                        ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', 1)
                                        ->get();

        // buscamos los expedientes de la inscripcion
        $expedientes = ExpedienteInscripcion::join('expediente_admision', 'expediente_inscripcion.id_expediente_admision', 'expediente_admision.id_expediente_admision')
                        ->join('expediente', 'expediente_admision.id_expediente', 'expediente.id_expediente')
                        ->where('expediente_inscripcion.id_inscripcion',$this->inscripcion->id_inscripcion)
                        ->where(function($query){
                            $query->where('expediente.expediente_tipo', 0)
                                ->orWhere('expediente.expediente_tipo', $this->inscripcion->inscripcion_tipo_programa);
                        })
                        ->get();

        // obtenemos los datos de la evaluacion del expediente
        $evaluacion_expediente = EvaluacionExpedienteTitulo::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        return view('livewire.modulo-coordinador.inicio.evaluaciones.evaluacion-expediente', [
            'expediente_inscripcion_seguimiento' => $expediente_inscripcion_seguimiento,
            'expedientes' => $expedientes,
            'evaluacion_expediente' => $evaluacion_expediente,
        ]);
    }
}
