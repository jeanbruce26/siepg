<?php

namespace App\Http\Livewire\ModuloAdministrador\Perfil;

use App\Models\GradoAcademico;
use App\Models\Trabajador;
use App\Models\TrabajadorTipoTrabajador;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads; // importamos la libreria para subir archivos

    public $id_tipo_trabajador; // variable que almacena el id_tipo_trabajador del usuario logueado
    public $usuario, $trabajador_tipo_trabajador, $trabajador, $tipo_trabajador; // variables para almacenar los datos del usuario logueado
    public $usuario_nombre, $usuario_correo; // variables para almacenar los datos del usuario logueado
    public $modo = "show"; // variable que almacena el modo de la vista
    public $apellido, $nombre, $documento_identidad, $correo_electronico, $direccion, $grado_academico, $grado_academico_input, $perfil, $iteration = 0; // variables para almacenar los datos del trabajador

    public function mount()
    {
        $this->usuario = auth('usuario')->user();
        $this->usuario_nombre = $this->usuario->usuario_nombre;
        $this->usuario_correo = $this->usuario->usuario_correo;
        $this->trabajador_tipo_trabajador = TrabajadorTipoTrabajador::where('id_trabajador_tipo_trabajador', $this->usuario->id_trabajador_tipo_trabajador)->where('id_tipo_trabajador', $this->id_tipo_trabajador)->first();
        $this->tipo_trabajador = $this->trabajador_tipo_trabajador->tipo_trabajador;
        $this->trabajador = $this->trabajador_tipo_trabajador->trabajador;
        $this->apellido = $this->trabajador->trabajador_apellido;
        $this->nombre = $this->trabajador->trabajador_nombre;
        $this->documento_identidad = $this->trabajador->trabajador_numero_documento;
        $this->correo_electronico = $this->trabajador->trabajador_correo;
        $this->direccion = $this->trabajador->trabajador_direccion;
        $this->grado_academico_input = $this->trabajador->grado_academico->grado_academico;
        $this->grado_academico = $this->trabajador->grado_academico->id_grado_academico;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'apellido' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'documento_identidad' => 'required|numeric|digits_between:8,8|unique:trabajador,trabajador_numero_documento,' . $this->trabajador->id_trabajador . ',id_trabajador',
            'correo_electronico' => 'required|string|email|max:255|unique:trabajador,trabajador_correo,' . $this->trabajador->id_trabajador . ',id_trabajador',
            'direccion' => 'required|string|max:255',
            'grado_academico' => 'required|numeric',
            'perfil' => 'nullable|image|max:2048'
        ]);
    }

    public function editar_perfil()
    {
        $this->modo = "edit";
    }

    public function cancelar()
    {
        $this->modo = "show";
        $this->resetErrorBag();
        $this->resetValidation();
        $this->mount();
        $this->reset('iteration', 'perfil');
        $this->iteration++;
    }

    public function guardar_perfil()
    {
        // validacion de los datos
        $this->validate([
            'apellido' => 'required|string|max:255',
            'nombre' => 'required|string|max:255',
            'documento_identidad' => 'required|numeric|digits_between:8,8|unique:trabajador,trabajador_numero_documento,' . $this->trabajador->id_trabajador . ',id_trabajador',
            'correo_electronico' => 'required|string|email|max:255|unique:trabajador,trabajador_correo,' . $this->trabajador->id_trabajador . ',id_trabajador',
            'direccion' => 'required|string|max:255',
            'grado_academico' => 'required|numeric',
            'perfil' => 'nullable|image|max:2048'
        ]);

        // actualizacion de los datos del trabajador
        $trabajador = Trabajador::find($this->trabajador->id_trabajador);
        $trabajador->trabajador_apellido = $this->apellido;
        $trabajador->trabajador_nombre = $this->nombre;
        $trabajador->trabajador_numero_documento = $this->documento_identidad;
        $trabajador->trabajador_correo = $this->correo_electronico;
        $trabajador->trabajador_direccion = $this->direccion;
        $trabajador->id_grado_academico = $this->grado_academico;
        if ($this->perfil) {
            if (file_exists($trabajador->trabajador_perfil_url)) {
                unlink($trabajador->trabajador_perfil_url);
            }
            $path = 'Posgrado/Usuarios/' . $this->trabajador->id_trabajador . '/Perfil' . '/';
            $filename = 'foto-perfil-' . date('HisdmY') . '.' . $this->perfil->getClientOriginalExtension();
            $nombre_db = $path.$filename;
            $this->perfil->storeAs($path, $filename, 'files_publico');
            $trabajador->trabajador_perfil_url = $nombre_db;
        }
        $trabajador->save();

        // emitir evento para actualizar el perfil del usuario logueado en la barra de navegacion
        $this->emit('actualizar_perfil');

        // emitir alerta de exito
        $this->dispatchBrowserEvent('alerta_perfil', [
            'title' => '¡Éxito!',
            'text' => 'El perfil se ha actualizado correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // volver al modo show
        $this->cancelar();
    }

    public function render()
    {
        $administrativo = $this->trabajador->administrativo;
        $coordinador = $this->trabajador->coordinador;
        $docente = $this->trabajador->docente;
        $grado_academico_array = GradoAcademico::where('grado_academico_estado', 1)->get(); // consulta para obtener los grados academicos
        return view('livewire.modulo-administrador.perfil.index', [
            'administrativo' => $administrativo,
            'coordinador' => $coordinador,
            'docente' => $docente,
            'grado_academico_array' => $grado_academico_array,
        ]);
    }
}
