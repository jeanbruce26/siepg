<?php

namespace App\Http\Livewire\ModuloCoordinador\Inicio\Evaluaciones;

use App\Models\Admision;
use App\Models\Evaluacion;
use App\Models\EvaluacionEntrevista as ModelsEvaluacionEntrevista;
use App\Models\EvaluacionEntrevistaItem;
use App\Models\EvaluacionExpediente;
use App\Models\EvaluacionObservacion;
use App\Models\Programa;
use App\Models\Puntaje;
use App\Models\TrabajadorTipoTrabajador;
use Livewire\Component;

class EvaluacionEntrevista extends Component
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
    public $id_evaluacion_entrevista_item; // es el id de la evaluacion expediente titulo que se esta consultando
    public $observacion; // es la observacion que se esta consultando

    protected $listeners = [
        'evaluar_entrevista_paso_2' => 'evaluar_entrevista',
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
        if($this->evaluacion->puntaje_entrevista)
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
        $evaluacion_entrevista_item = EvaluacionEntrevistaItem::where('id_tipo_evaluacion',$this->evaluacion->id_tipo_evaluacion)->get();
        foreach($evaluacion_entrevista_item as $item)
        {
            if($item->id_evaluacion_entrevista_item == $this->id_evaluacion_entrevista_item)
            {
                $puntaje = number_format($item->evaluacion_entrevista_item_puntaje, 0);
                $this->validateOnly($propertyName, [
                    'puntaje'=> 'required|numeric|min:0|max:'.$puntaje,
                ]);
            }
        }

        $this->contar_total();
    }

    public function contar_total()
    {
        $evaluacion = ModelsEvaluacionEntrevista::where('id_evaluacion',$this->id_evaluacion)->get();
        $this->puntaje_total = 0;
        foreach($evaluacion as $item)
        {
            $this->puntaje_total = $this->puntaje_total + $item->evaluacion_entrevista_puntaje;
        }
    }

    public function limpiar_modal()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset(['puntaje']);
    }

    public function cargar_evaluacion_entrevista_item(EvaluacionEntrevistaItem $evaluacion_entrevista_item)
    {
        $this->limpiar_modal();

        $this->id_evaluacion_entrevista_item = $evaluacion_entrevista_item->id_evaluacion_entrevista_item;

        $evaluacion_entrevista = ModelsEvaluacionEntrevista::where('id_evaluacion_entrevista_item',$this->id_evaluacion_entrevista_item)->where('id_evaluacion',$this->id_evaluacion)->first();
        if($evaluacion_entrevista)
        {
            $this->puntaje = number_format($evaluacion_entrevista->evaluacion_entrevista_puntaje, 0);
        }
    }

    public function agregar_puntaje()
    {
        $evaluacion_entrevista_item = EvaluacionEntrevistaItem::where('id_tipo_evaluacion',$this->evaluacion->id_tipo_evaluacion)->get();
        foreach($evaluacion_entrevista_item as $item)
        {
            if($item->id_evaluacion_entrevista_item == $this->id_evaluacion_entrevista_item)
            {
                $puntaje = number_format($item->evaluacion_entrevista_item_puntaje, 0);
                $this->validate([
                    'puntaje'=> 'required|numeric|min:0|max:'.$puntaje,
                ]);
            }
        }

        $evaluacion_entrevista = ModelsEvaluacionEntrevista::where('id_evaluacion_entrevista_item',$this->id_evaluacion_entrevista_item)->where('id_evaluacion',$this->id_evaluacion)->first();

        if($evaluacion_entrevista){
            $evaluacion_entrevista->evaluacion_entrevista_puntaje = $this->puntaje;
            $evaluacion_entrevista->save();
            $this->dispatchBrowserEvent('alerta_evaluacion_entrevista', [
                'title' => '¡Actualizado!',
                'text' => 'Puntaje actualizado satisfactoriamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }else{
            $evaluacion_entrevista = new ModelsEvaluacionEntrevista();
            $evaluacion_entrevista->evaluacion_entrevista_puntaje = $this->puntaje;
            $evaluacion_entrevista->id_evaluacion_entrevista_item = $this->id_evaluacion_entrevista_item;
            $evaluacion_entrevista->id_evaluacion = $this->id_evaluacion;
            $evaluacion_entrevista->save();

            $this->dispatchBrowserEvent('alerta_evaluacion_entrevista', [
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

    public function evaluar_entrevista_paso_1()
    {
        // validamos observacion
        $this->validate([
            'observacion' => 'nullable|string',
        ]);

        // validamos si todos los puntajes fueron agregados
        $evaluacion_entrevista = ModelsEvaluacionEntrevista::where('id_evaluacion',$this->id_evaluacion)->get()->count();
        $evaluacion_entrevista_titulo = EvaluacionEntrevistaItem::where('id_tipo_evaluacion',$this->evaluacion->id_tipo_evaluacion)->get()->count();

        // verificamos si los puntajes fueron agregados
        if($evaluacion_entrevista != $evaluacion_entrevista_titulo)
        {
            $this->dispatchBrowserEvent('alerta_evaluacion_entrevista', [
                'title' => '¡Error!',
                'text' => 'Debe agregar todos los puntajes.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return redirect()->back();
        }

        // emitimos alerta para confirmar la evaluacion
        $this->dispatchBrowserEvent('alerta_evaluacion_entrevista_2', [
            'title' => '¿Está seguro?',
            'text' => 'Una vez realizada la evaluación no podrá modificar los puntajes.',
            'icon' => 'question',
            'confirmButtonText' => 'Evaluar',
            'cancelButtonText' => 'Cancelar',
            'colorConfirmButton' => 'primary',
            'colorCancelButton' => 'danger',
        ]);
    }

    public function evaluar_entrevista()
    {
        $this->evaluacion->puntaje_entrevista = $this->puntaje_total;
        if($this->evaluacion->id_tipo_evaluacion == 1)
        {
            $puntaje_final = $this->evaluacion->puntaje_expediente + $this->evaluacion->puntaje_entrevista;
            if($this->puntaje_model->puntaje_maestria <= $puntaje_final)
            {
                $this->evaluacion->evaluacion_observacion = null;
                $this->evaluacion->evaluacion_estado = 2; // 1 = Pendiente // 2 = Aprobado // 3 = Rechazado
            }
            else
            {
                $this->evaluacion->evaluacion_observacion = 'El puntaje total no supera el puntaje mínimo.';
                $this->evaluacion->evaluacion_estado = 3; // 1 = Pendiente // 2 = Aprobado // 3 = Rechazado
            }
        }
        else if($this->evaluacion->id_tipo_evaluacion == 2)
        {
            $puntaje_final = $this->evaluacion->puntaje_expediente + $this->evaluacion->puntaje_investigacion + $this->evaluacion->puntaje_entrevista;
            if($this->puntaje_model->puntaje_doctorado <= $puntaje_final)
            {
                $this->evaluacion->evaluacion_observacion = null;
                $this->evaluacion->evaluacion_estado = 2; // 1 = Pendiente // 2 = Aprobado // 3 = Rechazado
            }
            else
            {
                $this->evaluacion->evaluacion_observacion = 'El puntaje total no supera el puntaje mínimo.';
                $this->evaluacion->evaluacion_estado = 3; // 1 = Pendiente // 2 = Aprobado // 3 = Rechazado
            }
        }
        $this->evaluacion->fecha_entrevista = now();
        $this->evaluacion->puntaje_final = $puntaje_final;
        $this->evaluacion->save();

        if($this->observacion){
            $observacion = new EvaluacionObservacion();
            $observacion->evaluacion_observacion = $this->observacion;
            $observacion->id_tipo_evaluacion = 3; // 1 = Expediente 2 = Tesis 3 = Entrevista
            $observacion->evaluacion_observacion_fecha = now();
            $observacion->id_evaluacion = $this->id_evaluacion;
            $observacion->save();
        }

        if($this->puntaje_total == 0){
            $this->evaluacion->evaluacion_observacion = 'No se presentó a la evaluación de entrevista.';
            $this->evaluacion->save();
        }

        // redireccionamos a la vista de evaluaciones
        return redirect()->route('coordinador.evaluaciones', [
            'id' => $this->id_programa,
            'id_admision' => $this->id_admision
        ]);
    }

    public function render()
    {
        // obtenemos los datos de la evaluacion del expediente
        $evaluacion_entrevista = EvaluacionEntrevistaItem::where('id_tipo_evaluacion', $this->evaluacion->id_tipo_evaluacion)->get();
        return view('livewire.modulo-coordinador.inicio.evaluaciones.evaluacion-entrevista', [
            'evaluacion_entrevista' => $evaluacion_entrevista
        ]);
    }
}
