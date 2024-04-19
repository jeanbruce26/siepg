<?php

namespace App\Http\Livewire\ModuloEvaluacion\Auth;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';

    public function ingresar()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('usuario_correo', $this->email)->first();

        if (!$usuario) {
            $this->dispatchBrowserEvent('alerta', [
                'title' => 'Error',
                'text' => 'Creedenciales incorrectas',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        if (!Hash::check($this->password, $usuario->usuario_password)) {
            $this->dispatchBrowserEvent('alerta', [
                'title' => 'Error',
                'text' => 'Creedenciales incorrectas',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        // verificamos si el usuairo tiene evaluaciones asigmnadas
        $usuairo_evaluaciones = $usuario->usuario_evaluaciones;

        if ($usuairo_evaluaciones->count() == 0) {
            $this->dispatchBrowserEvent('alerta', [
                'title' => 'Error',
                'text' => 'No tiene evaluaciones asignadas, contacte con el administrador.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        auth('evaluacion')->login($usuario);
        return redirect()->route('evaluacion.home');
    }

    public function render()
    {
        return view('livewire.modulo-evaluacion.auth.login')
            ->layout('layouts.modulo-evaluaciones.auth');
    }
}
