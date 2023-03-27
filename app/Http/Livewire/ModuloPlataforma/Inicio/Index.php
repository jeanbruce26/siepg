<?php

namespace App\Http\Livewire\ModuloPlataforma\Inicio;
use App\Models\Admision;
use App\Models\Admitidos;
use App\Models\Encuesta;
use App\Models\EncuestaDetalle;
use App\Models\Inscripcion;
use App\Models\Persona;
use App\Models\ProgramaProceso;
use Carbon\Carbon;

use Livewire\Component;

class Index extends Component
{
    public $encuesta = []; // array de encuestas
    public $encuesta_otro = null; // campo de otros
    public $mostra_otros = false; // mostrar campo de otros

    public function open_modal_encuesta()
    {
        $documento = auth('plataforma')->user()->usuario_estudiante; // documento del usuario logueado

        $encuesta = EncuestaDetalle::where('documento', $documento)->get(); // buscamos si el usuario ya realizo la encuesta
        if($encuesta->count() == 0){
            $this->dispatchBrowserEvent('modal_encuesta', [
                'action' => 'show'
            ]);
        }
    }

    public function updatedEncuesta($value)
    {
        $contador = 0;
        foreach ($this->encuesta as $key => $value) {
            if($value == 8){
                $contador++;
            }
        }
        if($contador > 0){
            $this->mostra_otros = true;
        }else{
            $this->mostra_otros = false;
        }
    }

    public function guardar_encuesta()
    {
        // validamos los campos del formulario
        if($this->encuesta == null)
        {
            $this->dispatchBrowserEvent('alerta-encuesta', [
                'title' => 'Error',
                'text' => 'Debe seleccionar al menos una opción.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }
        // validamos el campo otros
        if($this->mostra_otros == true)
        {
            if($this->encuesta_otro == null || $this->encuesta_otro == '')
            {
                $this->dispatchBrowserEvent('alerta-encuesta', [
                    'title' => 'Error',
                    'text' => 'Debe ingresar el campo "Otros".',
                    'icon' => 'error',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'danger'
                ]);
                return;
            }
        }

        // guardamos la encuesta
        foreach ($this->encuesta as $key => $value)
        {
            $encuesta = new EncuestaDetalle();
            $encuesta->documento = auth('plataforma')->user()->usuario_estudiante;
            $encuesta->id_encuesta = $value;
            if($value == 8)
            {
                $encuesta->otros = $this->encuesta_otro;
            }
            else
            {
                $encuesta->otros = null;
            }
            $encuesta->encuesta_detalle_estado = 1;
            $encuesta->encuesta_detalle_creacion = now();
            $encuesta->save();
        }

        // mostrar alerta de registro de pago con exito
        $this->dispatchBrowserEvent('alerta-encuesta', [
            'title' => 'Exito',
            'text' => 'Encuesta registrada con exito.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // resetear el formulario
        $this->reset('encuesta', 'encuesta_otro', 'mostra_otros');

        // aqui cerra el modal de encuesta
        $this->dispatchBrowserEvent('modal_encuesta', [
            'action' => 'hide'
        ]);
    }

    public function render()
    {
        $admision = Admision::where('admision_estado',1)->first(); // admision activa
        $admision_fecha_admitidos = Carbon::parse(Admision::where('admision_estado',1)->first()->admision_fecha_resultados); //fecha de admision de admitidos
        $admision_fecha_admitidos->locale('es'); // seteamos el idioma
        $admision_fecha_admitidos = $admision_fecha_admitidos->isoFormat('LL'); // formateamos la fecha

        $documento = auth('plataforma')->user()->usuario_estudiante; // documento del usuario logueado
        $persona = Persona::where('numero_documento', $documento)->first(); // persona logueada
        $inscripcion_ultima = Inscripcion::where('id_persona', $persona->id_persona)->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        $inscripcion_admision = ProgramaProceso::where('id_programa_proceso', $inscripcion_ultima->id_programa_proceso)->first(); // admision de la inscripcion del usuario logueado
        $evaluacion = $inscripcion_ultima->evaluacion; // evaluacion de la inscripcion del usuario logueado
        if($evaluacion)
        {
            $admitido = $persona->admitido->where('id_evaluacion', $evaluacion->id_evaluacion)->first(); // admitido de la inscripcion del usuario logueado
        }
        else
        {
            $admitido = null;
        }
        $encuestas = Encuesta::where('encuesta_estado', 1)->get(); // obtenemos las encuestas activas

        return view('livewire.modulo-plataforma.inicio.index', [
            'admision' => $admision,
            'admision_fecha_admitidos' => $admision_fecha_admitidos,
            'inscripcion_ultima' => $inscripcion_ultima,
            'inscripcion_admision' => $inscripcion_admision,
            'evaluacion' => $evaluacion,
            'admitido' => $admitido,
            'encuestas' => $encuestas,
        ]);
    }
}
