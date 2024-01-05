<?php

namespace App\Http\Livewire\ModuloPlataforma\Inicio;

use App\Models\Admision;
use App\Models\Encuesta;
use App\Models\EncuestaDetalle;
use Livewire\Component;

class Index extends Component
{
    public $encuesta = []; // array de encuestas
    public $encuesta_otro = null; // campo de otros
    public $mostra_otros = false; // mostrar campo de otros

    public function open_modal_encuesta()
    {
        $id_persona = auth('plataforma')->user()->id_persona;

        $encuesta = EncuestaDetalle::where('id_persona', $id_persona)->get(); // buscamos si el usuario ya realizo la encuesta
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
            $encuesta->id_persona = auth('plataforma')->user()->id_persona;
            $encuesta->id_admision = Admision::where('admision_estado', 1)->first()->id_admision;
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
        $encuestas = Encuesta::where('encuesta_estado', 1)->get(); // obtenemos las encuestas activas
        $usuario = auth('plataforma')->user();
        $persona = $usuario->persona;
        $inscripcion = $persona->inscripcion()->orderBy('id_inscripcion', 'desc')->first();
        return view('livewire.modulo-plataforma.inicio.index', [
            'encuestas' => $encuestas,
            'inscripcion' => $inscripcion
        ]);
    }
}
