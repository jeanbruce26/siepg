<?php

namespace App\Http\Livewire\ModuloInscripcion\RegistroDocente;

use Livewire\Component;
use App\Models\Trabajador;

class Gracias extends Component
{
    public $id_trabajador;

    //Redirigir a la vista de login
    public function login()
    {
        return redirect()->route('login');
    }
    public function render()
    {
        $trabajador = Trabajador::find($this->id_trabajador);
        $trabajador_tipo_trabajador = $trabajador->trabajador_tipo_trabajador;
        // verificar si el trabajador es docente
        if ($trabajador_tipo_trabajador->id_tipo_trabajador != 1) {
            abort(403, 'No tienes permisos para acceder a esta página');
        }
        $usuario = $trabajador_tipo_trabajador->usuario;

        return view('livewire.modulo-inscripcion.registro-docente.gracias', [
            'trabajador' => $trabajador,
            'usuario' => $usuario
        ]);
    }
}
