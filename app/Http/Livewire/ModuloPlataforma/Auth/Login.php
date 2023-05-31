<?php

namespace App\Http\Livewire\ModuloPlataforma\Auth;

use App\Models\UsuarioEstudiante;
use Livewire\Component;

class Login extends Component
{
    public $usuario = ''; // este es el nombre del campo en el formulario
    public $password = ''; // este es el nombre del campo en el formulario

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'usuario' => 'required',
            'password' => 'required',
        ]);
    }

    public function ingresar_plataforma()
    {
        // validamos los campos del formulario
        $this->validate([
            'usuario' => 'required',
            'password' => 'required',
        ]);

        // aqui buscamos el usuario en la base de datos
        $usuario = UsuarioEstudiante::where('usuario_estudiante', $this->usuario)->where('usuario_estudiante_password', $this->password)->first();

        if(!$usuario) // verificamos si no existe el usuario o si sus credenciales son incorrectas
        {
            session()->flash('message', 'Credenciales incorrectas');
            return redirect()->back();
        }
        else
        {
            auth('plataforma')->login($usuario); // autenticamos al usuario en la plataforma
            return redirect()->route('plataforma.inicio'); // redireccionamos al usuario a la pagina de inicio de la plataforma
        }
    }

    public function render()
    {
        return view('livewire.modulo-plataforma.auth.login');
    }
}
