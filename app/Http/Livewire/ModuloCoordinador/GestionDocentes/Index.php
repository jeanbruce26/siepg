<?php

namespace App\Http\Livewire\ModuloCoordinador\GestionDocentes;

use App\Jobs\ProcessEnvioCredencialesDocentes;
use App\Models\Docente;
use App\Models\GradoAcademico;
use App\Models\TipoDocente;
use App\Models\Trabajador;
use App\Models\TrabajadorTipoTrabajador;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // trait para paginacion
    protected $paginationTheme = 'bootstrap'; // tema de paginacion
    use WithFileUploads; // Trait de carga de archivos

    public $search = ''; // Variable para almacenar la búsqueda
    public $modo = 'create'; // Variable para almacenar el modo (create, edit)

    // variables para el modal
    public $title_modal = 'Agregar Docente'; // Título del modal
    public $iteration = 0; // Variable para limpiar el modal
    public $iteration2 = 0; // Variable para limpiar el modal
    public $documento_identidad; // Variable para almacenar el número de documento
    public $nombre; // Variable para almacenar el nombre
    public $apellido_paterno; // Variable para almacenar el apellido paterno
    public $apellido_materno; // Variable para almacenar el apellido materno
    public $correo_electronico; // Variable para almacenar el correo electrónico
    public $direccion; // Variable para almacenar la dirección
    public $grado_academico; // Variable para almacenar el grado académico
    public $tipo_docente; // Variable para almacenar el tipo de docente
    public $id_docente; // Variable para almacenar el id del docente
    public $curriculum_vitae; // Variable para almacenar el curriculum vitae
    public $foto_perfil; // Variable para almacenar la foto de perfil
    public $trabajador; // Variable para almacenar el id del trabajador
    public $mostrar_curriculum = false; // Variable para almacenar el curriculum vitae

    // variables para los filtros
    public $filtro_grado_academico; // Variable para almacenar el grado académico
    public $grado_academico_data; // Variable para almacenar el grado académico
    public $filtro_tipo_docente; // Variable para almacenar el tipo de docente
    public $tipo_docente_data; // Variable para almacenar el tipo de docente

    protected $queryString = [ // Variables de búsqueda
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'filtro_grado_academico' => ['except' => ''],
        'filtro_tipo_docente' => ['except' => ''],
    ];

    protected $listeners = [ // Listado de eventos
        'cambiar_estado_docente' => 'cambiar_estado_docente',
    ];

    public function updated($propertyName) // Función que se ejecuta cuando se modifica una propiedad
    {
        if($this->modo == 'create')
        {
            $this->validateOnly($propertyName, [
                // Validación de los campos
                'documento_identidad' => 'required|numeric|digits_between:8,9',
                'nombre' => 'required|regex:/^[\pL\s-]+$/u|max:100',
                'apellido_paterno' => 'required|regex:/^[\pL\s-]+$/u|max:50',
                'apellido_materno' => 'required|regex:/^[\pL\s-]+$/u|max:50',
                'correo_electronico' => 'required|email|max:50',
                'direccion' => 'required|max:100',
                'grado_academico' => 'required',
                'tipo_docente' => 'required',
                'curriculum_vitae' => $this->mostrar_curriculum == true ? 'required|mimes:pdf|max:10240' : 'nullable|mimes:pdf|max:10240',
                'foto_perfil' => 'required|image|max:2048'
            ]);
        }
        else
        {
            $this->validateOnly($propertyName, [
                // Validación de los campos
                'documento_identidad' => 'required|numeric|digits_between:8,9',
                'nombre' => 'required|regex:/^[\pL\s-]+$/u|max:100',
                'apellido_paterno' => 'required|regex:/^[\pL\s-]+$/u|max:50',
                'apellido_materno' => 'required|regex:/^[\pL\s-]+$/u|max:50',
                'correo_electronico' => 'required|email|max:50',
                'direccion' => 'required|max:100',
                'grado_academico' => 'required',
                'tipo_docente' => 'required',
                'curriculum_vitae' => 'nullable|mimes:pdf|max:10240',
                'foto_perfil' => 'nullable|image|max:2048'
            ]);
        }
    }

    public function aplicar_filtro()
    {
        $this->grado_academico_data = $this->filtro_grado_academico; // Se almacena el grado académico
        $this->tipo_docente_data = $this->filtro_tipo_docente; // Se almacena el tipo de docente
    }

    public function resetear_filtro()
    {
        $this->reset([
            'filtro_grado_academico',
            'grado_academico_data',
            'filtro_tipo_docente',
            'tipo_docente_data'
        ]);
    }

    public function modo()
    {
        $this->limpiar_modal();
        $this->modo = 'create';
        $this->title_modal = 'Agregar Docente';
    }

    public function limpiar_modal()
    {
        $this->reset([
            'documento_identidad',
            'nombre',
            'apellido_paterno',
            'apellido_materno',
            'correo_electronico',
            'direccion',
            'grado_academico',
            'tipo_docente',
            'id_docente',
            'curriculum_vitae',
            'foto_perfil',
            'trabajador',
            'mostrar_curriculum'
        ]);
        $this->resetErrorBag();
        $this->iteration++;
        $this->iteration2++;
        $this->resetValidation();
    }

    // Función para resetear la paginación cuando se realiza una búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function cargar_docente(Docente $docente, $tipo)
    {
        if($tipo == 'edit')
        {
            $this->modo = 'edit';
            $this->title_modal = 'Editar Docente';
        }
        else if ($tipo == 'show')
        {
            $this->modo = 'show';
            $this->title_modal = 'Información del Docente';
        }
        $this->id_docente = $docente->id_docente;
        $this->trabajador = $docente->trabajador;
        $this->documento_identidad = $this->trabajador->trabajador_numero_documento;
        $this->nombre = $this->trabajador->trabajador_nombre;
        $apellidos = explode(' ', $this->trabajador->trabajador_apellido);
        $this->apellido_paterno = $apellidos[0];
        if (count($apellidos) > 1) {
            $this->apellido_materno = implode(' ', array_slice($apellidos, 1));
        } else {
            $this->apellido_materno = '';
        }
        $this->correo_electronico = $this->trabajador->trabajador_correo;
        $this->direccion = $this->trabajador->trabajador_direccion;
        $this->grado_academico = $tipo == 'edit' ? $this->trabajador->id_grado_academico : ($tipo == 'show' ? $this->trabajador->grado_academico->grado_academico : '');
        $this->tipo_docente = $tipo == 'edit' ? $docente->id_tipo_docente : ($tipo == 'show' ? $docente->tipo_docente->tipo_docente : '');
    }

    public function guardar_docente()
    {
        // verificar si el docente ya se encuentra registrado
        if($this->modo == 'create')
        {
            $docente = Trabajador::where('trabajador_numero_documento', $this->documento_identidad)->orderBy('id_trabajador', 'desc')->first();
            if ($docente)
            {
                // emitir alerta indicando que el docente ya se encuentra registrado
                $this->dispatchBrowserEvent('alerta_docente', [
                    'title' => '¡Alerta!',
                    'text' => 'El docente con el número de documento de identidad ' . $this->documento_identidad . ' ya se encuentra registrado en el sistema.',
                    'icon' => 'warning',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'warning'
                ]);

                return redirect()->back();
            }
        }

        // Validación de campos del formulario
        if($this->modo == 'create')
        {
            $this->validate([
                // Validación de los campos
                'documento_identidad' => 'required|numeric|digits_between:8,9',
                'nombre' => 'required|regex:/^[\pL\s-]+$/u|max:100',
                'apellido_paterno' => 'required|regex:/^[\pL\s-]+$/u|max:50',
                'apellido_materno' => 'required|regex:/^[\pL\s-]+$/u|max:50',
                'correo_electronico' => 'required|email|max:50',
                'direccion' => 'required|max:100',
                'grado_academico' => 'required',
                'tipo_docente' => 'required',
                'curriculum_vitae' => $this->mostrar_curriculum == true ? 'required|mimes:pdf|max:10240' : 'nullable|mimes:pdf|max:10240',
                'foto_perfil' => 'required|image|max:2048'
            ]);
        }
        else
        {
            $this->validate([
                // Validación de los campos
                'documento_identidad' => 'required|numeric|digits_between:8,9',
                'nombre' => 'required|regex:/^[\pL\s-]+$/u|max:100',
                'apellido_paterno' => 'required|regex:/^[\pL\s-]+$/u|max:50',
                'apellido_materno' => 'required|regex:/^[\pL\s-]+$/u|max:50',
                'correo_electronico' => 'required|email|max:50',
                'direccion' => 'required|max:100',
                'grado_academico' => 'required',
                'tipo_docente' => 'required',
                'curriculum_vitae' => 'nullable|mimes:pdf|max:10240',
                'foto_perfil' => 'nullable|image|max:2048'
            ]);
        }

        if ($this->modo == 'create')
        {
            // Creación de un nuevo trabajador
            $trabajador = new Trabajador();
            $trabajador->trabajador_apellido = ucwords($this->apellido_paterno . ' ' . $this->apellido_materno);
            $trabajador->trabajador_nombre = ucwords($this->nombre);
            $trabajador->trabajador_nombre_completo = ucwords($this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
            $trabajador->trabajador_numero_documento = $this->documento_identidad;
            $trabajador->trabajador_correo = $this->correo_electronico;
            $trabajador->trabajador_direccion = $this->direccion;
            $trabajador->id_grado_academico = $this->grado_academico;
            $trabajador->trabajador_estado = 1;
            $trabajador->save();

            $this->trabajador = Trabajador::find($trabajador->id_trabajador);
            // Asignamos su foto de perfil al trabajador en caso de que se haya subido una foto
            if($this->foto_perfil)
            {
                if (file_exists($this->trabajador->trabajador_perfil_url)) {
                    unlink($this->trabajador->trabajador_perfil_url);
                }
                $path = 'Posgrado/Usuarios/' . $this->trabajador->id_trabajador . '/Perfil' . '/';
                $filename = 'foto-perfil-' . date('HisdmY') . '.' . $this->foto_perfil->getClientOriginalExtension();
                $nombre_db = $path.$filename;
                $this->foto_perfil->storeAs($path, $filename, 'files_publico');
                $this->trabajador->trabajador_perfil_url = $nombre_db;
                $this->trabajador->save();
            }

            // Creación de un nuevo docente
            $docente = new Docente();
            if($this->tipo_docente == 1)
            {
                $docente->docente_cv_url = null;
            }
            else
            {
                $path = 'Posgrado/Usuarios/' . $this->trabajador->id_trabajador . '/Curriculum' . '/';
                $filename = 'curriculum-vitae-' . date('HisdmY') . '.pdf';
                $nombre_db = $path.$filename;
                $this->curriculum_vitae->storeAs($path, $filename, 'files_publico');
                $docente->docente_cv_url = $nombre_db;
            }
            $docente->id_tipo_docente = $this->tipo_docente;
            $docente->docente_estado = 1;
            $docente->id_trabajador = $this->trabajador->id_trabajador;
            $docente->save();

            // Asignamosal trabajador tipo de trabajador a docente
            $trabajador_tipo_trabajador = new TrabajadorTipoTrabajador();
            $trabajador_tipo_trabajador->id_tipo_trabajador = 1; // 1 = Docente | 2 = Coordinador de Unidad | 3 = Administrativo
            $trabajador_tipo_trabajador->id_trabajador = $this->trabajador->id_trabajador;
            $trabajador_tipo_trabajador->trabajador_tipo_trabajador_estado = 1;
            $trabajador_tipo_trabajador->save();

            // Creamos su usuario y le asignamos su trabajador_tipo_trabajador
            $usuario = new Usuario();
            $usuario->usuario_nombre = 'DOCENTE - ' . $this->trabajador->trabajador_nombre_completo;
            $nombre = explode(' ', $this->trabajador->trabajador_nombre)[0];
            $usuario->usuario_correo = strtolower($nombre) . '_' . strtolower(explode(' ', $this->apellido_paterno)[0]) . '@unu.edu.pe';
            $usuario->usuario_password = Hash::make($this->documento_identidad);
            $usuario->id_trabajador_tipo_trabajador = $trabajador_tipo_trabajador->id_trabajador_tipo_trabajador;
            $usuario->usuario_estado = 2; // 0 = Inactivo | 1 = Activo | 2 = Asignado
            $usuario->save();

            // ejecutamos el proceso en segundo plano de envio de correo electrónico al usuario sus credenciales
            ProcessEnvioCredencialesDocentes::dispatch($docente->id_docente, 'create');

            // emitir alerta para mostrar mensaje de éxito
            $this->dispatchBrowserEvent('alerta_docente', [
                'title' => '¡Éxito!',
                'text' => 'Docente registrado correctamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }
        else if ($this->modo == 'edit')
        {
            // editamos los datos del trabajador
            $trabajador = Trabajador::find($this->trabajador->id_trabajador);
            $trabajador->trabajador_apellido = ucwords($this->apellido_paterno . ' ' . $this->apellido_materno);
            $trabajador->trabajador_nombre = ucwords($this->nombre);
            $trabajador->trabajador_nombre_completo = ucwords($this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno);
            $trabajador->trabajador_numero_documento = $this->documento_identidad;
            $trabajador->trabajador_correo = $this->correo_electronico;
            $trabajador->trabajador_direccion = $this->direccion;
            $trabajador->id_grado_academico = $this->grado_academico;
            if($this->foto_perfil)
            {
                if (file_exists($trabajador->trabajador_perfil_url)) {
                    unlink($trabajador->trabajador_perfil_url);
                }
                $path = 'Posgrado/Usuarios/' . $trabajador->id_trabajador . '/Perfil' . '/';
                $filename = 'foto-perfil-' . date('HisdmY') . '.' . $this->foto_perfil->getClientOriginalExtension();
                $nombre_db = $path.$filename;
                $this->foto_perfil->storeAs($path, $filename, 'files_publico');
                $trabajador->trabajador_perfil_url = $nombre_db;
            }
            $trabajador->save();

            // editamos los datos del docente
            $docente = Docente::find($this->id_docente);
            if($this->tipo_docente == 1)
            {
                $docente->docente_cv_url = null;
            }
            else
            {
                if (file_exists($docente->docente_cv_url)) {
                    unlink($docente->docente_cv_url);
                }
                $path = 'Posgrado/Usuarios/' . $this->trabajador->id_trabajador . '/Curriculum' . '/';
                $filename = 'curriculum-vitae-' . date('HisdmY') . '.pdf';
                $nombre_db = $path.$filename;
                $this->curriculum_vitae->storeAs($path, $filename, 'files_publico');
                $docente->docente_cv_url = $nombre_db;
            }
            $docente->id_tipo_docente = $this->tipo_docente;
            $docente->id_trabajador = $this->trabajador->id_trabajador;
            $docente->save();

            // buscamos al trabajador tipo tabajador
            $trabajador_tipo_trabajador = TrabajadorTipoTrabajador::where('id_trabajador', $this->trabajador->id_trabajador)->orderBy('id_trabajador_tipo_trabajador', 'desc')->first();

            // editamos el usuario
            $usuario = Usuario::where('id_trabajador_tipo_trabajador', $trabajador_tipo_trabajador->id_trabajador_tipo_trabajador)->first();
            $usuario->usuario_nombre = 'DOCENTE - ' . $trabajador->trabajador_nombre_completo;
            $nombre = explode(' ', $this->trabajador->trabajador_nombre)[0];
            $usuario->usuario_correo = strtolower($nombre) . '_' . strtolower(explode(' ', $this->apellido_paterno)[0]) . '@unu.edu.pe';
            $usuario->usuario_password = Hash::make($this->documento_identidad);
            $usuario->save();

            // ejecutamos el proceso en segundo plano de envio de correo electrónico al usuario sus credenciales
            ProcessEnvioCredencialesDocentes::dispatch($docente->id_docente, 'edit');

            // emitir alerta para mostrar mensaje de éxito
            $this->dispatchBrowserEvent('alerta_docente', [
                'title' => '¡Éxito!',
                'text' => 'Docente actualizado correctamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }

        //emitir evento para cerrar el modal
        $this->dispatchBrowserEvent('modal_docente', ['action' => 'hide']);

        // Limpiamos las variables
        $this->limpiar_modal();
    }

    public function alerta_cambiar_estado(Docente $docente)
    {
        $this->id_docente = $docente->id_docente;
        // emitir alerta para poder modificar el estado del docente
        $this->dispatchBrowserEvent('alerta_cambiar_estado_docente', [
            'title' => '¡Advertencia!',
            'text' => '¿Está seguro que desea cambiar el estado del docente?',
            'icon' => 'question',
            'showCancelButton' => true,
            'confirmButtonText' => 'Aceptar',
            'cancelButtonText' => 'Cancelar',
            'confirmButtonColor' => 'primary',
            'cancelButtonColor' => 'danger',
        ]);
    }

    public function cambiar_estado_docente()
    {
        $docente = Docente::find($this->id_docente);

        if ($docente->docente_estado == 1)
        {
            $docente->docente_estado = 0;
            $docente->save();

            // cambiar el estado del trabajador
            $trabajador = Trabajador::find($docente->id_trabajador);
            $trabajador->trabajador_estado = 0;
            $trabajador->save();

            // cambiamos el estado del trabajador tipo de trabajador
            $trabajador_tipo_trabajador = TrabajadorTipoTrabajador::where('id_trabajador', $trabajador->id_trabajador)->orderBy('id_trabajador_tipo_trabajador', 'desc')->first();
            $trabajador_tipo_trabajador->trabajador_tipo_trabajador_estado = 0;
            $trabajador_tipo_trabajador->save();

            // dasctivar el usuario
            $usuario = Usuario::where('id_trabajador_tipo_trabajador', $trabajador_tipo_trabajador->id_trabajador_tipo_trabajador)->first();
            $usuario->usuario_estado = 0;
            $usuario->save();

            // emitir alerta para mostrar mensaje de éxito
            $this->dispatchBrowserEvent('alerta_docente', [
                'title' => '¡Éxito!',
                'text' => 'Docente desactivado correctamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }
        else if ($docente->docente_estado == 0)
        {
            $docente->docente_estado = 1;
            $docente->save();

            // cambiar el estado del trabajador
            $trabajador = Trabajador::find($docente->id_trabajador);
            $trabajador->trabajador_estado = 1;
            $trabajador->save();

            // cambiamos el estado del trabajador tipo de trabajador
            $trabajador_tipo_trabajador = TrabajadorTipoTrabajador::where('id_trabajador', $trabajador->id_trabajador)->orderBy('id_trabajador_tipo_trabajador', 'desc')->first();
            $trabajador_tipo_trabajador->trabajador_tipo_trabajador_estado = 1;
            $trabajador_tipo_trabajador->save();

            // activar el usuario
            $usuario = Usuario::where('id_trabajador_tipo_trabajador', $trabajador_tipo_trabajador->id_trabajador_tipo_trabajador)->first();
            $usuario->usuario_estado = 1;
            $usuario->save();

            // emitir alerta para mostrar mensaje de éxito
            $this->dispatchBrowserEvent('alerta_docente', [
                'title' => '¡Éxito!',
                'text' => 'Docente activado correctamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }
    }

    public function render()
    {
        $docentes = Docente::join('trabajador', 'docente.id_trabajador', '=', 'trabajador.id_trabajador')
                                ->join('grado_academico', 'trabajador.id_grado_academico', '=', 'grado_academico.id_grado_academico')
                                ->where(function ($query) {
                                    $query->where('trabajador.trabajador_nombre_completo', 'like', '%' . $this->search . '%')
                                        ->orWhere('trabajador.trabajador_numero_documento', 'like', '%' . $this->search . '%')
                                        ->orWhere('trabajador.trabajador_correo', 'like', '%' . $this->search . '%')
                                        ->orWhere('grado_academico.grado_academico', 'like', '%' . $this->search . '%');
                                })
                                ->where('trabajador.id_grado_academico', $this->grado_academico_data == null ? '!=' : '=', $this->grado_academico_data)
                                ->where('docente.id_tipo_docente', $this->tipo_docente_data == null ? '!=' : '=', $this->tipo_docente_data)
                                ->paginate(10);

        $grados_academicos = GradoAcademico::where('grado_academico_estado', 1)->get();
        $tipos_docentes = TipoDocente::where('tipo_docente_estado', 1)->get();

        return view('livewire.modulo-coordinador.gestion-docentes.index', [
            'docentes' => $docentes,
            'grados_academicos' => $grados_academicos,
            'tipos_docentes' => $tipos_docentes
        ]);
    }
}
