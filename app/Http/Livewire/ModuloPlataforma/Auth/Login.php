<?php

namespace App\Http\Livewire\ModuloPlataforma\Auth;

use App\Models\UsuarioEstudiante;
use Illuminate\Support\Facades\Hash;
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

        // simulamos un retraso en la autenticacion
        sleep(3);

        // aqui buscamos el usuario en la base de datos
        $usuario = UsuarioEstudiante::where('usuario_estudiante', $this->usuario)->first();

        // verificamos si no existe el usuario o si sus credenciales son incorrectas
        if (!$usuario) {
            session()->flash('message', 'Credenciales incorrectas');
            return redirect()->back();
        } else {
            if (!Hash::check($this->password, $usuario->usuario_estudiante_password)) {
                session()->flash('message', 'Credenciales incorrectas');
                return redirect()->back();
            }
            auth('plataforma')->login($usuario); // autenticamos al usuario en la plataforma
            return redirect()->route('plataforma.inicio'); // redireccionamos al usuario a la pagina de inicio de la plataforma
        }
    }

    public function render()
    {
        return view('livewire.modulo-plataforma.auth.login');
    }
}
