<?php

namespace App\Http\Livewire\ModuloEvaluacion\Components;

use Livewire\Component;

class Sidebar extends Component
{
    public function cerrar_sesion()
    {
        auth('evaluacion')->logout();
        return redirect()->route('evaluacion.login');
    }

    public function render()
    {
        $usuario = auth('evaluacion')->user();
        $avatar = 'https://ui-avatars.com/api/?name=' . $usuario->usuario_nombre . '&color=fff&background=1166fa&bold=true';
        return view('livewire.modulo-evaluacion.components.sidebar', [
            'usuario' => $usuario,
            'avatar' => $avatar
        ]);
    }
}
