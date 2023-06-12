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
    public $coordinador; // variable que almacena el administrativo del usuario logueado
    public $area_administrativa; // variable que almacena el area_administrativa del usuario logueado
    public $docente; // variable que almacena el docente del usuario logueado

    protected $listeners = [
        'actualizar_perfil' => 'mount'
    ];

    public function mount()
    {
        $this->usuario = auth('usuario')->user(); // asignamos el usuario logueado a la variable usuario
        $this->trabajador_tipo_trabajador = $this->usuario->trabajador_tipo_trabajador; // asignamos el trabajador_tipo_trabajador del usuario logueado a la variable trabajador_tipo_trabajador
        $this->tipo_trabajador = $this->trabajador_tipo_trabajador->tipo_trabajador; // asignamos el tipo_trabajador del usuario logueado a la variable tipo_trabajador
        $this->trabajador = $this->trabajador_tipo_trabajador->trabajador; // asignamos el trabajador del usuario logueado a la variable trabajador
        $this->administrativo = $this->trabajador->administrativo; // asignamos el administrativo del usuario logueado a la variable administrativo
        if($this->administrativo)
        {
            $this->area_administrativa = $this->administrativo->area_administrativo; // asignamos el area_administrativa del usuario logueado a la variable area_administrativa
        }
        $this->coordinador = $this->trabajador->coordinador; // asignamos el coordinador del usuario logueado a la variable coordinador
        $this->docente = $this->trabajador->docente; // asignamos el docente del usuario logueado a la variable docente
    }

    public function cerrar_sesion()
    {
        auth('usuario')->logout(); // cerramos la sesion del usuario en la plataforma
        return redirect()->route('login'); // redireccionamos al usuario a la pagina de login de la plataforma
    }

    public function perfil()
    {
        $administrativo = $this->trabajador->administrativo;
        $coordinador = $this->trabajador->coordinador;
        $docente = $this->trabajador->docente;

        if ($administrativo && $this->tipo_trabajador->id_tipo_trabajador == 3)
        {
            $area_administrativa = $this->administrativo->area_administrativo;
            if($area_administrativa->id_area_administrativo == 1)
            {
                // redireccionamos al usuario a la pagina de perfil del area administrativa de la plataforma contable
                return redirect()->route('contable.perfil');
            }
            else if($area_administrativa->id_area_administrativo == 2)
            {
                // redireccionamos al usuario a la pagina de perfil del area administrativa de emision de actas
                dd('area administrativa de emision de actas');
            }
            else if($area_administrativa->id_area_administrativo == 3)
            {
                // redireccionamos al usuario a la pagina de perfil del area administrativa de soporte informatico
                return redirect()->route('administrador.perfil');
            }
            else{
                dd('area administrativa');
            }
            // return redirect()->route('administrador.perfil');
        }
        elseif ($coordinador && $this->tipo_trabajador->id_tipo_trabajador == 2)
        {
            return redirect()->route('coordinador.perfil');
        }
        elseif ($docente && $this->tipo_trabajador->id_tipo_trabajador == 1)
        {
            return redirect()->route('docente.perfil');
        }
    }

    public function render()
    {
        return view('livewire.modulo-administrador.navbar.index');
    }
}
