<?php

namespace App\Http\Livewire\ModuloEvaluacion\Inscripciones;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $usuario = auth('evaluacion')->user();

        $evaluaciones = $usuario->usuario_evaluaciones;

        return view('livewire.modulo-evaluacion.inscripciones.index', [
            'usuario' => $usuario,
            'evaluaciones' => $evaluaciones
        ])->layout('layouts.modulo-evaluaciones.app', ['title' => 'Inicio | Evaluaciones | Escuela de Posgrado UNU']);
    }
}
