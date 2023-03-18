<?php

namespace App\Http\Livewire\ModuloAdministrador\Navbar;

use Livewire\Component;

class Index extends Component
{
    public $usuario; // variable que almacena el usuario logueado
    public $trabajador_tipo_trabajador; // variable que almacena el trabajador_tipo_trabajador del usuario logueado
    public $tipo_trabajador; // variable que almacena el tipo_trabajador del usuario logueado
    public $trabajador; // variable que almacena el trabajador del usuario logueado
    public $administrativo; // variable que almacena el administrativo del usuario logueado
    public $area_administrativa; // variable que almacena el area_administrativa del usuario logueado

    public function mount()
    {
        $this->usuario = auth('usuario')->user(); // asignamos el usuario logueado a la variable usuario
        $this->trabajador_tipo_trabajador = $this->usuario->trabajador_tipo_trabajador; // asignamos el trabajador_tipo_trabajador del usuario logueado a la variable trabajador_tipo_trabajador
        $this->tipo_trabajador = $this->trabajador_tipo_trabajador->tipo_trabajador; // asignamos el tipo_trabajador del usuario logueado a la variable tipo_trabajador
        $this->trabajador = $this->trabajador_tipo_trabajador->trabajador; // asignamos el trabajador del usuario logueado a la variable trabajador
        $this->administrativo = $this->trabajador->administrativo; // asignamos el administrativo del usuario logueado a la variable administrativo
        $this->area_administrativa = $this->administrativo->area_administrativo; // asignamos el area_administrativa del usuario logueado a la variable area_administrativa
    }

    public function cerrar_sesion()
    {
        auth('usuario')->logout(); // cerramos la sesion del usuario en la plataforma
        return redirect()->route('login'); // redireccionamos al usuario a la pagina de login de la plataforma
    }

    public function render()
    {
        return view('livewire.modulo-administrador.navbar.index');
    }
}
