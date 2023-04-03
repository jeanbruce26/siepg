<?php

namespace App\Http\Livewire\ModuloPlataforma\Sidebar;

use App\Models\Inscripcion;
use App\Models\Persona;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $persona = Persona::where('numero_documento', auth('plataforma')->user()->usuario_estudiante)->first(); // persona del usuario logueado
        $inscripcion_ultima = Inscripcion::where('id_persona', $persona->id_persona)->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        $evaluacion = $inscripcion_ultima->evaluacion; // evaluacion de la inscripcion del usuario logueado
        if($evaluacion)
        {
            $admitido = $persona->admitido->where('id_evaluacion', $evaluacion->id_evaluacion)->first(); // admitido de la inscripcion del usuario logueado
        }
        else
        {
            $admitido = null;
        }
        return view('livewire.modulo-plataforma.sidebar.index', [
            'inscripcion_ultima' => $inscripcion_ultima,
            'evaluacion' => $evaluacion,
            'admitido' => $admitido,
        ]);
    }
}