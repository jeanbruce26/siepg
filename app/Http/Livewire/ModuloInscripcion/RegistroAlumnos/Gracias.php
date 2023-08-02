<?php

namespace App\Http\Livewire\ModuloInscripcion\RegistroAlumnos;

use App\Models\Admitido;
use App\Models\Persona;
use App\Models\UsuarioEstudiante;
use Livewire\Component;

class Gracias extends Component
{

    //recuperamos el id_persona que se mando desde la vista de registro
    public $id_persona;

    //Redirigir a la vista de plataforma login
    public function plataforma()
    {
        return redirect()->route('plataforma.login');
    }

    public function render()
    {

        $personaModel = Persona::find($this->id_persona);
        $admitidoModel = Admitido::where('id_persona', $this->id_persona)->first();
        $usuarioModel = UsuarioEstudiante::where('usuario_estudiante', $personaModel->correo)->first();
        dd($usuarioModel);

        return view('livewire.modulo-inscripcion.registro-alumnos.gracias', [
            'personaModel' => $personaModel,
            'admitidoModel' => $admitidoModel,
            'usuarioModel' => $usuarioModel
        ]);
    }
}
