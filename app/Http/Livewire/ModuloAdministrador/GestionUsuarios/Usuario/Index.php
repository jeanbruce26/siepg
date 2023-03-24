<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionUsuarios\Usuario;

use App\Models\HistorialAdministrativo;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';
    public $titulo = 'Crear Usuario';
    public $usuario_id;

    public $modo = 1;

    public $username;
    public $correo;
    public $password;

    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'username' => 'required',
            'correo' => 'required|email',
            'password' => 'nullable'
        ]);
    }

    public function modo()
    {
        $this->modo = 1;
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('username', 'correo', 'password');
        $this->modo = 1;
    }

    public function cargarAlerta($id)
    {
        $this->dispatchBrowserEvent('alertaConfirmacionUsuario', ['id' => $id]);
    }

    public function cambiarEstado(Usuario $usuario)
    {
        if ($usuario->usuario_estado == 1 || $usuario->usuario_estado == 2) {
            $usuario->usuario_estado = 0;
        } else if ($usuario->usuario_estado == 0) {
            if ($usuario->trabajador_tipo_trabajador_id) {
                $usuario->usuario_estado = 2;
            } else {
                $usuario->usuario_estado = 1;
            }
        }

        $usuario->save();

        $this->dispatchBrowserEvent('alerta-usuario', [
            'title' => '¡Estado del Usuario actualizado satisfactoriamente!',
            'text' => '',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        $this->subirHistorial($usuario->usuario_id, 'Actualizacion de estado usuario', 'usuario');
    }

    public function cargarUsuario(Usuario $usuario)
    {
        $this->modo = 2;
        $this->titulo = 'ACTUALIZAR USUARIO - CORREO: '  . $usuario->correo;
        $this->usuario_id = $usuario->usuario_id;

        $this->username = $usuario->usuario_nombre;
        $this->correo = $usuario->usuario_correo;
    }

    public function guardarUsuario()
    {
        if ($this->modo == 1) {
            $this->validate([
                'username' => 'required|unique:usuario,usuario_nombre',
                'correo' => 'required|email|unique:usuario,usuario_correo',
                'password' => 'required'
            ]);

            $usuario = Usuario::create([
                "usuario_nombre" => $this->username,
                "usuario_correo" => $this->correo,
                "usuario_contraseña" => Hash::make($this->password),
                "usuario_estado" => 1,
            ]);

            $this->subirHistorial($usuario->usuario_id, 'Creacion de usuario', 'usuario');

            $this->dispatchBrowserEvent('alerta-usuario', [
                'title' => '¡Usuario agregado satisfactoriamente!',
                'text' => '',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        } else {
            $this->validate([
                'username' => "required|unique:usuario,usuario_nombre,{$this->usuario_id},usuario_id",
                'correo' => "required|email|unique:usuario,usuario_correo,{$this->usuario_id},usuario_id",
                'password' => 'nullable'
            ]);

            $usuario = Usuario::find($this->usuario_id);
            $usuario->usuario_nombre = $this->username;
            $usuario->usuario_correo = $this->correo;
            if ($this->password) {
                $usuario->usuario_contraseña = Hash::make($this->password);
            }
            $usuario->save();

            $this->subirHistorial($usuario->usuario_id, 'Actualizacion de usuario', 'usuario');

            $this->dispatchBrowserEvent('alerta-usuario', [
                'title' => '¡Usuario <strong>' . $this->username . '</strong>  actualizado satisfactoriamente!',
                'text' => '',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }

        $this->dispatchBrowserEvent('modalUsuario');

        $this->limpiar();
    }

    //Registrar cambios del sistema administrativo
    public function subirHistorial($usuario_id, $descripcion, $tabla)
    {
        HistorialAdministrativo::create([
            "usuario_id" => auth('usuario')->user()->usuario_id,
            "trabajador_id" => auth('usuario')->user()->trabajador_tipo_trabajador->trabajador->trabajador_id,
            "historial_descripcion" => $descripcion,
            "historial_tabla" => $tabla,
            "historial_usuario_id" => $usuario_id,
            "historial_fecha" => now()
        ]);
    }


    public function render()
    {
        $usuarios = Usuario::where('usuario_nombre', 'LIKE', "%{$this->search}%")
            ->orWhere('usuario_correo', 'LIKE', "%{$this->search}%")
            ->orWhere('usuario_id', 'LIKE', "%{$this->search}%")
            ->orderBy('usuario_id', 'DESC')
            ->paginate(50);

        return view('livewire.modulo-administrador.gestion-usuarios.usuario.index', [
            'usuarios' => $usuarios
        ]);
    }
}
