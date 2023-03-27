<?php

namespace App\Http\Livewire\ModuloPlataforma\Perfil;

use App\Models\Admision;
use App\Models\Persona;
use App\Models\UsuarioEstudiante;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads; // trait para subir archivos
    public $perfil; // variable para el perfil del usuario logueado
    public $password, $confirm_password; // variables para el cambio de contraseña
    public $iteration = 0; // variable para la iteracion de la imagen
    public $usuario; // variable para el usuario logueado

    public function mount()
    {
        $this->usuario = UsuarioEstudiante::find(auth('plataforma')->user()->id_usuario_estudiante);
    }

    public function refresh()
    {
        $this->usuario = UsuarioEstudiante::find(auth('plataforma')->user()->id_usuario_estudiante);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'perfil' => 'nullable|image|max:2048', // validacion para la imagen
            'password' => 'nullable|min:8|max:20', // validacion para la contraseña
            'confirm_password' => 'nullable|same:password', // validacion para la confirmacion de la contraseña
        ]);
    }

    public function limpiar_perfil()
    {
        $this->reset([
            'perfil',
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
            'password' => 'nullable|min:8|max:20', // validacion para la contraseña
            'confirm_password' => 'nullable|same:password', // validacion para la confirmacion de la contraseña
        ]);

        // buscar usuario logueado para actualizar el perfil
        $usuario = UsuarioEstudiante::find(auth('plataforma')->user()->id_usuario_estudiante);

        if($this->perfil)
        {
            $persona = Persona::where('numero_documento', auth('plataforma')->user()->usuario_estudiante)->first();
            $inscripcion = $persona->inscripcion()->orderBy('id_inscripcion', 'desc')->first();
            $admision = $inscripcion->programa_proceso->first()->admision->admision;
            $path = 'Posgrado/' . $admision . '/' . auth('plataforma')->user()->usuario_estudiante . '/' . 'Perfil/';
            $filename = 'foto-perfil.' . $this->perfil->getClientOriginalExtension();
            $nombre_db = $path.$filename;
            $this->perfil->storeAs($path, $filename, 'files_publico');
            $usuario->usuario_estudiante_perfil_url = $nombre_db;
        }
        if($this->password)
        {
            $usuario->usuario_estudiante_password = $this->password;
        }
        $usuario->save();

        // cerrar modal
        $this->dispatchBrowserEvent('modal_perfil', [
            'action' => 'hide'
        ]);

        // emitir alerta de exito
        if($this->perfil || $this->password)
        {
            $this->dispatchBrowserEvent('update_perfil', [
                'title' => '¡Éxito!',
                'text' => 'Se ha actualizado el perfil del usuario correctamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }
        else
        {
            $this->dispatchBrowserEvent('update_perfil', [
                'title' => '¡Advertencia!',
                'text' => 'No se ingresaron datos para actualizar el perfil del usuario.',
                'icon' => 'warning',
                'confirmButtonText' => 'Aceptar',
                'color' => 'warning'
            ]);
        }

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
        $documento = auth('plataforma')->user()->usuario_estudiante; // documento del usuario logueado
        $persona = Persona::where('numero_documento', $documento)->first(); // persona logueada
        if(!$persona)
        {
            abort(404);
        }
        $inscripcion = $persona->inscripcion()->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        if(!$inscripcion)
        {
            abort(404);
        }
        return view('livewire.modulo-plataforma.perfil.index', [
            'persona' => $persona,
            'inscripcion' => $inscripcion,
        ]);
    }
}
