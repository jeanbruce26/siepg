<?php

namespace App\Http\Livewire\ModuloCoordinador\Sidebar;

use Livewire\Component;

class Index extends Component
{
    public $pago;
    public $route;

    public $usuario; // variable que almacena el usuario logueado
    public $trabajador_tipo_trabajador; // variable que almacena el trabajador_tipo_trabajador del usuario logueado
    public $tipo_trabajador; // variable que almacena el tipo_trabajador del usuario logueado
    public $trabajador; // variable que almacena el trabajador del usuario logueado
    public $coordinador; // variable que almacena el coordinador del usuario logueado

    protected $listeners = [
        'actualizar_notificacion_pagos' => 'render'
    ];

    public function mount()
    {
        $this->route = request()->route()->getName();

        $this->usuario = auth('usuario')->user(); // asignamos el usuario logueado a la variable usuario
        $this->trabajador_tipo_trabajador = $this->usuario->trabajador_tipo_trabajador; // asignamos el trabajador_tipo_trabajador del usuario logueado a la variable trabajador_tipo_trabajador
        $this->tipo_trabajador = $this->trabajador_tipo_trabajador->tipo_trabajador; // asignamos el tipo_trabajador del usuario logueado a la variable tipo_trabajador
        $this->trabajador = $this->trabajador_tipo_trabajador->trabajador; // asignamos el trabajador del usuario logueado a la variable trabajador
        $this->coordinador = $this->trabajador->coordinador; // asignamos el coordinador del usuario logueado a la variable coordinador
    }

    public function cerrar_sesion()
    {
        auth('usuario')->logout(); // cerramos la sesion del usuario en la plataforma
        return redirect()->route('login'); // redireccionamos al usuario a la pagina de login de la plataforma
    }

    public function render()
    {
        return view('livewire.modulo-coordinador.sidebar.index');
    }
}
