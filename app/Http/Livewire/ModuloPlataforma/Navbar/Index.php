<?php

namespace App\Http\Livewire\ModuloPlataforma\Navbar;

use App\Models\Admitido;
use App\Models\Evaluacion;
use App\Models\Pago;
use App\Models\Persona;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'update_avatar' => 'render',
    ];

    public function cerrar_sesion()
    {
        auth('plataforma')->logout(); // cerramos la sesion del usuario en la plataforma
        return redirect()->route('plataforma.login'); // redireccionamos al usuario a la pagina de login de la plataforma
    }

    public function render()
    {
        $usuario = auth('plataforma')->user(); // obtenemos el usuario autenticado en la plataforma
        $persona = Persona::where('id_persona', $usuario->id_persona)->first(); // obtenemos la persona del usuario autenticado en la plataforma
        $inscripcion_persona = $persona->inscripcion()->orderBy('id_inscripcion', 'desc')->first(); // obtenemos la inscripcion de la persona del usuario autenticado en la plataforma
        $admitido = Admitido::where('id_persona', $persona->id_persona)->orderBy('id_admitido', 'desc')->first(); // obtenemos el admitido de la inscripcion de la persona del usuario autenticado en la plataforma
        $evaluacion = $admitido ? Evaluacion::where('id_evaluacion', $admitido->id_evaluacion)->first() : null; // obtenemos la evaluacion de la inscripcion de la persona del usuario autenticado en la plataforma
        $admision = $inscripcion_persona->programa_proceso()->first()->admision; // obtenemos la admision de la inscripcion de la persona del usuario autenticado en la plataforma
        return view('livewire.modulo-plataforma.navbar.index', [
            'usuario' => $usuario,
            'persona' => $persona,
            'admision' => $admision,
            'evaluacion' => $evaluacion,
            'admitido' => $admitido,
        ]);
    }
}
