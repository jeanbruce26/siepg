<?php

namespace App\Http\Livewire\ModuloPlataforma\Sidebar;

use App\Models\ConstanciaIngreso;
use App\Models\Inscripcion;
use App\Models\Persona;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'actualizar_sidebar' => 'render',
    ];

    public function render()
    {
        $usuario = auth('plataforma')->user(); // obtenemos el usuario autenticado en la plataforma
        $persona = Persona::where('numero_documento', $usuario->usuario_estudiante)->first(); // obtenemos la persona del usuario autenticado en la plataforma
        $inscripcion_ultima = Inscripcion::where('id_persona', $persona->id_persona)->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        $evaluacion = $inscripcion_ultima->evaluacion; // evaluacion de la inscripcion del usuario logueado
        $constancia = null;
        if($evaluacion)
        {
            $admitido = $persona->admitido->where('id_evaluacion', $evaluacion->id_evaluacion)->first(); // admitido de la inscripcion del usuario logueado
            if($admitido)
            {
                $constancia = ConstanciaIngreso::where('id_admitido', $admitido->id_admitido)->orderBy('id_constancia_ingreso', 'desc')->first(); // constancia de ingreso del usuario logueado

            }
        }
        else
        {
            $admitido = null;
        }
        return view('livewire.modulo-plataforma.sidebar.index', [
            'usuario' => $usuario,
            'persona' => $persona,
            'inscripcion_ultima' => $inscripcion_ultima,
            'evaluacion' => $evaluacion,
            'admitido' => $admitido,
            'constancia' => $constancia,
        ]);
    }
}
