<?php

namespace App\Http\Livewire\ModuloDocente\Sidebar;

use Livewire\Component;

class Index extends Component
{
    public $usuario; // variable que almacena el usuario logueado
    public $trabajador_tipo_trabajador; // variable que almacena el trabajador_tipo_trabajador del usuario logueado
    public $tipo_trabajador; // variable que almacena el tipo_trabajador del usuario logueado
    public $trabajador; // variable que almacena el trabajador del usuario logueado
    public $docente; // variable que almacena el docente del usuario logueado
    
    public function mount()
    {
        $this->route = request()->route()->getName();

        $this->usuario = auth('usuario')->user(); // asignamos el usuario logueado a la variable usuario
        $this->trabajador_tipo_trabajador = $this->usuario->trabajador_tipo_trabajador; // asignamos el trabajador_tipo_trabajador del usuario logueado a la variable trabajador_tipo_trabajador
        $this->tipo_trabajador = $this->trabajador_tipo_trabajador->tipo_trabajador; // asignamos el tipo_trabajador del usuario logueado a la variable tipo_trabajador
        $this->trabajador = $this->trabajador_tipo_trabajador->trabajador; // asignamos el trabajador del usuario logueado a la variable trabajador
        $this->docente = $this->trabajador->docente; // asignamos el docente del usuario logueado a la variable docente
    }

    public function cerrar_sesion()
    {
        auth('usuario')->logout(); // cerramos la sesion del usuario en la plataforma
        return redirect()->route('login'); // redireccionamos al usuario a la pagina de login de la plataforma
    }

    public function render()
    {
        return view('livewire.modulo-docente.sidebar.index');
    }
}
