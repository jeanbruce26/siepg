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
    protected $paginationTheme = 'bootstrap';//paginacion de bootstrap

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';
    public $titulo = 'Crear Usuario';
    public $id_usuario;

    public $modo = 1;

    public $username;
    public $correo;
    public $password;
    public $password_confirmation;
    public $showPassword = false;
    public $showPassword_confirmation = false;

    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'username' => 'required',
            'correo' => 'required|email',
            'password' => 'nullable',
            'password_confirmation' => 'nullable|same:password'
        ]);
    }

    public function updatedPassword($password)
    {
        if($password != null && $this->password_confirmation != null)
        {
            $this->validate([
                'password' => 'required',
                'password_confirmation' => 'required|same:password'
            ]);
        }
    }

    public function modo()
    {
        $this->modo = 1;
        $this->titulo = 'Crear Usuario';
        $this->limpiar();
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('username', 'correo', 'password');
        $this->modo = 1;
    }

    public function toggleShowPassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function toggleShowPasswordConfirmation()
    {
        $this->showPassword_confirmation = !$this->showPassword_confirmation;
    }

    public function cargarAlerta($id)
    {
        $this->dispatchBrowserEvent('alertaConfirmacionUsuario', [
            'title' => '¿Estás seguro?',
            'text' => '¿Deseas modificar el estado del usuario?',
            'icon' => 'question',
            'confirmButtonText' => 'Modificar',
            'cancelButtonText' => 'Cancelar',
            'confirmButtonColor' => 'primary',
            'cancelButtonColor' => 'danger',
            'id' => $id,
        ]);
    }

    public function cambiarEstado(Usuario $usuario)
    {
        if ($usuario->usuario_estado == 1 || $usuario->usuario_estado == 2) {
            $usuario->usuario_estado = 0;
        } else if ($usuario->usuario_estado == 0) {
            if ($usuario->id_trabajador_tipo_trabajador) {
                $usuario->usuario_estado = 2;
            } else {
                $usuario->usuario_estado = 1;
            }
        }

        $usuario->save();

        $this->dispatchBrowserEvent('alerta-usuario', [
            'title' => '¡Éxito!',
            'text' => 'Estado del Usuario '.$usuario->usuario_nombre.' ha sido actualizado satisfactoriamente',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

    }

    public function cargarUsuario(Usuario $usuario)
    {
        $this->modo = 2;
        $this->titulo = 'ACTUALIZAR USUARIO - CORREO: '  . $usuario->usuario_correo;
        $this->id_usuario = $usuario->id_usuario;

        $this->username = $usuario->usuario_nombre;
        $this->correo = $usuario->usuario_correo;
    }

    public function guardarUsuario()
    {
        if ($this->modo == 1) {//crear
            $this->validate([
                'username' => 'required|unique:usuario,usuario_nombre',
                'correo' => 'required|email|unique:usuario,usuario_correo',
                'password' => 'required',
                'password_confirmation' => 'required|same:password'
            ]);

            $usuario = Usuario::create([
                "usuario_nombre" => $this->username,
                "usuario_correo" => $this->correo,
                "usuario_password" => Hash::make($this->password),
                "usuario_estado" => 1,
            ]);

            $this->dispatchBrowserEvent('alerta-usuario', [
                'title' => '¡Éxito!',
                'text' => 'Usuario agregado satisfactoriamente',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        } else {//actualizar
            if($this->password)
            {
                $this->validate([
                    'username' => "required|unique:usuario,usuario_nombre,{$this->id_usuario},id_usuario",
                    'correo' => "required|email|unique:usuario,usuario_correo,{$this->id_usuario},id_usuario",
                    'password' => 'required',
                    'password_confirmation' => 'required|same:password'
                ]);
            }
            else
            {
                $this->validate([
                    'username' => "required|unique:usuario,usuario_nombre,{$this->id_usuario},id_usuario",
                    'correo' => "required|email|unique:usuario,usuario_correo,{$this->id_usuario},id_usuario",
                    'password' => 'nullable',
                    'password_confirmation' => 'nullable|same:password'
                ]);
            }

            $usuario = Usuario::find($this->id_usuario);
            //Validar si se realizo algun cambio
            $usuarioValidar = Usuario::where('id_usuario', $this->id_usuario)
                            ->where('usuario_nombre', $this->username)
                            ->where('usuario_correo', $this->correo)
                            ->first();

            if($usuarioValidar != null && $this->password == null){
                $this->dispatchBrowserEvent('alerta-usuario', [
                    'title' => '¡Información!',
                    'text' => 'No se realizaron cambios en los datos del Usuario.',
                    'icon' => 'info',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'info'
                ]);
            }else{
                $usuario->usuario_nombre = $this->username;
                $usuario->usuario_correo = $this->correo;
                if ($this->password) {
                    $usuario->usuario_password = Hash::make($this->password);
                }
                $usuario->save();
    
                $this->dispatchBrowserEvent('alerta-usuario', [
                    'title' => '¡Éxito!',
                    'text' => 'Usuario ' . $this->username . ' ha sido actualizado satisfactoriamente',
                    'icon' => 'success',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'success'
                ]);
            }
        }

        $this->dispatchBrowserEvent('modalUsuario', [
            'titleModal' => '#modalUsuario',
        ]);

        $this->limpiar();
    }

    public function render()
    {
        $usuarios = Usuario::where('usuario_nombre', 'LIKE', "%{$this->search}%")
            ->orWhere('usuario_correo', 'LIKE', "%{$this->search}%")
            ->orWhere('id_usuario', 'LIKE', "%{$this->search}%")
            ->orderBy('id_usuario', 'DESC')
            ->paginate(50);

        return view('livewire.modulo-administrador.gestion-usuarios.usuario.index', [
            'usuarios' => $usuarios
        ]);
    }
}
