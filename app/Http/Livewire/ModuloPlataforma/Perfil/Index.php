<?php

namespace App\Http\Livewire\ModuloPlataforma\Perfil;

use App\Models\Admision;
use App\Models\Admitido;
use App\Models\Persona;
use App\Models\UsuarioEstudiante;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads; // trait para subir archivos
    public $perfil; // variable para el perfil del usuario logueado
    public $password, $confirm_password; // variables para el cambio de contraseña
    public $nombre;
    public $apellido_paterno;
    public $apellido_materno;
    public $celular;
    public $correo_electronico;
    public $iteration = 0; // variable para la iteracion de la imagen
    public $usuario; // variable para el usuario logueado

    public function mount()
    {
        $this->usuario = UsuarioEstudiante::find(auth('plataforma')->user()->id_usuario_estudiante);
        $this->nombre = $this->usuario->persona->nombre;
        $this->apellido_paterno = $this->usuario->persona->apellido_paterno;
        $this->apellido_materno = $this->usuario->persona->apellido_materno;
        $this->celular = $this->usuario->persona->celular;
        $this->correo_electronico = $this->usuario->persona->correo;
    }

    public function cargar_perfil()
    {
        $this->nombre = $this->usuario->persona->nombre;
        $this->apellido_paterno = $this->usuario->persona->apellido_paterno;
        $this->apellido_materno = $this->usuario->persona->apellido_materno;
        $this->celular = $this->usuario->persona->celular;
        $this->correo_electronico = $this->usuario->persona->correo;
    }

    public function refresh()
    {
        $this->usuario = UsuarioEstudiante::find(auth('plataforma')->user()->id_usuario_estudiante);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'perfil' => 'nullable|image|max:2048', // validacion para la imagen
            'nombre' => 'required|min:3|max:50', // validacion para el nombre
            'apellido_paterno' => 'required|min:3|max:50', // validacion para el apellido paterno
            'apellido_materno' => 'required|min:3|max:50', // validacion para el apellido materno
            'celular' => 'required|numeric|digits:9', // validacion para el celular
            'correo_electronico' => 'required|email|max:100', // validacion para el correo electronico
            'password' => 'nullable|min:8|max:20', // validacion para la contraseña
            'confirm_password' => 'nullable|same:password', // validacion para la confirmacion de la contraseña
        ]);
    }

    public function limpiar_perfil()
    {
        $this->reset([
            'perfil',
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'celular',
            'correo_electronico',
            'password',
            'confirm_password',
        ]);
    }

    public function remove_avatar()
    {
        $this->reset('perfil');
        $this->iteration++;
    }

    public function actualizar_perfil()
    {
        // validamos los campos del formulario
        $this->validate([
            'perfil' => 'nullable|image|max:2048', // validacion para la imagen
            'nombre' => 'required|min:3|max:50', // validacion para el nombre
            'apellido_paterno' => 'required|min:3|max:50', // validacion para el apellido paterno
            'apellido_materno' => 'required|min:3|max:50', // validacion para el apellido materno
            'celular' => 'required|numeric|digits:9', // validacion para el celular
            'correo_electronico' => 'required|email|max:100', // validacion para el correo electronico
            'password' => 'nullable|min:8|max:20', // validacion para la contraseña
            'confirm_password' => 'nullable|same:password', // validacion para la confirmacion de la contraseña
        ]);

        // buscar usuario logueado para actualizar el perfil
        $usuario = UsuarioEstudiante::find(auth('plataforma')->user()->id_usuario_estudiante);

        if ($this->perfil) {
            if (file_exists($usuario->usuario_estudiante_perfil_url)) {
                unlink($usuario->usuario_estudiante_perfil_url);
            }
            $persona = Persona::where('id_persona', auth('plataforma')->user()->id_persona)->first();
            $inscripcion = $persona->inscripcion()->orderBy('id_inscripcion', 'desc')->first();
            $admision = null;
            $admitido = Admitido::where('id_persona', $persona->id_persona)->orderBy('id_admitido', 'desc')->first();
            if ($admitido) {
                if ($admitido->id_programa_proceso_antiguo) {
                    $admision = $admitido->programa_proceso_antiguo->admision->admision;
                } else {
                    $admision = $admitido->programa_proceso->admision->admision;
                }
            } else {
                $admision = $inscripcion->programa_proceso->admision->admision;
            }

            $base_path = 'Posgrado/';
            $folders = [$admision, $persona->numero_documento, 'Perfil'];

            // Asegurar que se creen los directorios con los permisos correctos
            $path = asignarPermisoFolders($base_path, $folders);

            // Nombre del archivo
            $filename = 'foto-perfil-' . date('HisdmY') . '.' . $this->perfil->getClientOriginalExtension();
            $nombre_db = $path . $filename;

            // Guardar el archivo
            $this->perfil->storeAs($path, $filename, 'files_publico');
            $usuario->usuario_estudiante_perfil_url = $nombre_db;

            // Asignar todos los permisos al archivo
            chmod($nombre_db, 0777);
        }
        if ($this->password) {
            $usuario->usuario_estudiante_password = Hash::make($this->password);
        }
        $usuario->usuario_estudiante = mb_strtolower($this->correo_electronico, 'UTF-8');
        $usuario->save();

        // buscar persona para actualizar el perfil
        $persona = Persona::where('id_persona', auth('plataforma')->user()->id_persona)->first();
        $persona->nombre = mb_strtoupper($this->nombre, 'UTF-8');
        $persona->apellido_paterno = mb_strtoupper($this->apellido_paterno, 'UTF-8');
        $persona->apellido_materno = mb_strtoupper($this->apellido_materno, 'UTF-8');
        $persona->celular = $this->celular;
        $persona->correo = mb_strtolower($this->correo_electronico, 'UTF-8');
        $persona->save();

        // cerrar modal
        $this->dispatchBrowserEvent('modal_perfil', [
            'action' => 'hide'
        ]);

        // emitir alerta de exito
        $this->dispatchBrowserEvent('update_perfil', [
            'title' => '¡Éxito!',
            'text' => 'Se ha actualizado el perfil del usuario correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // emitir evento para actualizar la imagen del usuario logueado
        $this->emit('update_avatar');

        // refrescar componente
        $this->refresh();

        // limpiar formulario
        $this->limpiar_perfil();
        $this->remove_avatar();
    }

    public function render()
    {
        $id_persona = auth('plataforma')->user()->id_persona; // documento del usuario logueado
        $persona = Persona::where('id_persona', $id_persona)->first(); // persona logueada
        $admitido = Admitido::where('id_persona', $persona->id_persona)->orderBy('id_admitido', 'desc')->first(); // admitido del usuario logueado')
        if (!$persona) {
            abort(404);
        }
        $inscripcion = $persona->inscripcion()->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        return view('livewire.modulo-plataforma.perfil.index', [
            'persona' => $persona,
            'inscripcion' => $inscripcion,
            'admitido' => $admitido,
        ]);
    }
}
