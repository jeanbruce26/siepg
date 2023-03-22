<?php

namespace App\Http\Livewire\ModuloPlataforma\Navbar;

use App\Models\Persona;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'update_avatar' => '$refresh',
    ];

    public function cerrar_sesion()
    {
        auth('plataforma')->logout(); // cerramos la sesion del usuario en la plataforma
        return redirect()->route('plataforma.login'); // redireccionamos al usuario a la pagina de login de la plataforma
    }

    public function render()
    {
        $usuario = auth('plataforma')->user(); // obtenemos el usuario autenticado en la plataforma
        $persona = Persona::where('num_doc', $usuario->usuario_estudiante)->first(); // obtenemos la persona del usuario autenticado en la plataforma
        $inscripcion_persona = $persona->inscripcion()->orderBy('id_inscripcion', 'desc')->first(); // obtenemos la inscripcion de la persona del usuario autenticado en la plataforma
        $admision = $inscripcion_persona->admision()->first(); // obtenemos la admision de la inscripcion de la persona del usuario autenticado en la plataforma
        return view('livewire.modulo-plataforma.navbar.index', [
            'usuario' => $usuario,
            'persona' => $persona,
            'admision' => $admision,
        ]);
    }
}
