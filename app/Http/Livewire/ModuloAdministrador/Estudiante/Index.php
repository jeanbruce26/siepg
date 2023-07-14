<?php

namespace App\Http\Livewire\ModuloAdministrador\Estudiante;

use App\Models\Admision;
use App\Models\Discapacidad;
use App\Models\EstadoCivil;
use App\Models\Genero;
use App\Models\GradoAcademico;
use App\Models\Inscripcion;
use App\Models\Persona;
use App\Models\Ubigeo;
use App\Models\Universidad;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    //paginacion de bootstrapprocesoFiltro
    protected $paginationTheme = 'bootstrap';

    protected $queryString = [
        'search' => ['except' => ''],
        'procesoFiltro' => ['except' => ''],
    ];

    public $search = '';
    public $titulo = 'Detalle del estudiante';
    public $modo = 3; // 2 = Actualizar, 3 = Detalle

    //Variables para el filtro de Persona
    public $procesoFiltro;
    public $filtro_proceso;

    //Valiables de los modelos de Persona
    public $id_persona;
    public $numero_documento;
    public $apellido_paterno;
    public $apellido_materno;
    public $nombre;
    public $nombre_completo;
    public $genero;
    public $genero_detalle;
    public $fecha_nacimiento;
    public $direccion;
    public $celular;
    public $celular_opcional;
    public $correo;
    public $correo_opcional;
    public $año_egreso;
    public $especialidad;
    public $centro_trabajo;
    // public $tipo_documento;
    public $discapacidad;
    public $discapacidad_detalle;
    public $estado_civil;
    public $estado_civil_detalle;
    public $grado_academico;
    public $grado_academico_detalle;
    public $universidad;
    public $universidad_detalle;
    public $ubigeo_direccion;
    public $ubigeo_direccion_detalle;
    public $ubigeo_nacimiento;
    public $ubigeo_nacimiento_detalle;
    public $pais_direccion;
    public $pais_nacimiento;
    public $pais_direccion_estado = false;
    public $pais_nacimiento_estado = false;

    public $agregar_celular = false;
    public $agregar_correo = false;

    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'numero_documento' => 'required|numeric|digits_between:8,9|unique:persona,numero_documento,' . $this->id_persona . ',id_persona',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'nombre' => 'required',
            'genero' => 'required|numeric',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required',
            'celular' => 'required|numeric|digits:9',
            'celular_opcional' => [
                'nullable',
                'numeric',
                'digits:9',
                function ($attribute, $value, $fail) {
                    if ($this->celular_opcional) {
                        if ($this->celular == $this->celular_opcional) {
                            $fail('El celular opcional no puede ser igual al celular.');
                        }
                    }
                },
            ],
            //Valida el correo que sea unico en el campo correo y correo opcional
            'correo' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $query = Persona::where('id_persona', '<>', $this->id_persona)
                                ->where(function ($query) use ($value) {
                                    $query->where('correo', $value)
                                        ->orWhere('correo_opcional', $value);
                                })
                                ->exists();
            
                    if ($query) {
                        $fail('El correo ya está en uso en el campo correo o correo opcional.');
                    }
                },
            ],
            'correo_opcional' => [
                'nullable',
                'email',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $query = Persona::where('id_persona', '<>', $this->id_persona)
                                ->where(function ($query) use ($value) {
                                    $query->where('correo', $value)
                                        ->orWhere('correo_opcional', $value);
                                })
                                ->exists();
            
                        if ($query) {
                            $fail('El correo opcional ya está en uso en el campo correo o correo opcional.');
                        }
                        if($this->correo_opcional == $this->correo){
                            $fail('El correo opcional no puede ser igual al correo.');
                        }
                    }
                },
            ],
            'año_egreso' => 'required|numeric|digits:4',
            'especialidad' => 'nullable',
            'centro_trabajo' => 'required',
            'discapacidad' => 'required|numeric',
            'estado_civil' => 'required|numeric',
            'grado_academico' => 'required|numeric',
            'universidad' => 'required|numeric',
            'ubigeo_direccion' => 'required|numeric',
            'ubigeo_nacimiento' => 'required|numeric',
            'pais_direccion' => 'required',
            'pais_nacimiento' => 'required',
        ]);

        //Validar si existe el ubigeo de dirección y nacimiento. Si selecciona otro pais, se debe activar el pais de nacimiento y direccion
        if ($this->ubigeo_direccion) 
        {
            //Validar si escoge otro pais con ubigeo 000000
            $ubigeo = Ubigeo::findOrFail($this->ubigeo_direccion);
            if ($ubigeo->ubigeo == '000000') {
                $this->pais_direccion_estado = true;
            }else{
                $this->pais_direccion_estado = false;
                //Reseteamos el pais de direccion
                $this->resetErrorBag('pais_direccion'); //Elimina los mensajes de error de validacion
                $paisDireccion = Persona::findOrFail($this->id_persona)->pais_direccion;
                $this->pais_direccion = $paisDireccion;
            }
        }
        if ($this->ubigeo_nacimiento) 
        {
            //Validar si escoge otro pais con ubigeo 000000
            $ubigeo = Ubigeo::findOrFail($this->ubigeo_nacimiento);
            if ($ubigeo->ubigeo == '000000') {
                $this->pais_nacimiento_estado = true;
            }else{
                $this->pais_nacimiento_estado = false;
                //Reseteamos el pais de nacimiento
                $this->resetErrorBag('pais_nacimiento'); //Elimina los mensajes de error de validacion
                $paisNacimiento = Persona::findOrFail($this->id_persona)->pais_nacimiento;
                $this->pais_nacimiento = $paisNacimiento;
            }
        }
    }

    public function limpiar()
    {
        $this->resetErrorBag(); //Elimina los mensajes de error de validacion
        $this->reset('id_persona', 'numero_documento', 'apellido_paterno', 'apellido_materno', 'nombre', 'nombre_completo', 'genero', 'fecha_nacimiento', 'direccion', 'celular', 'celular_opcional', 'correo', 'correo_opcional', 'año_egreso', 'especialidad', 'centro_trabajo', 'discapacidad', 'estado_civil', 'grado_academico', 'universidad', 'ubigeo_direccion', 'pais_direccion', 'ubigeo_nacimiento', 'pais_nacimiento');
        $this->agregar_celular = false;
        $this->agregar_correo = false;
        $this->pais_direccion_estado = false;
        $this->pais_nacimiento_estado = false;
        $this->modo = 3; // 2 = Actualizar, 3 = Detalle
        $this->titulo = 'Detalle del estudiante';
    }

    //Alerta de confirmacion
    public function alertaConfirmacion($title, $text, $icon, $confirmButtonText, $cancelButtonText, $confimrColor, $cancelColor, $metodo, $id)
    {
        $this->dispatchBrowserEvent('alertaConfirmacion', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'cancelButtonText' => $cancelButtonText,
            'confimrColor' => $confimrColor,
            'cancelColor' => $cancelColor,
            'metodo' => $metodo,
            'id' => $id,
        ]);
    }

    //Alertas de exito o error
    public function alertaEstudiante($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-estudiante', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Cargamos los datos del estudiante
    public function cargarPersona($id, $modo)
    {
        $this->limpiar();
        $this->modo = $modo;
        $this->modo == 2 ? $this->titulo = 'Actualizar estudiante' : $this->titulo = 'Detalle del estudiante';
        $persona = Persona::findOrFail($id);
        $this->id_persona = $persona->id_persona;
        $this->numero_documento = $persona->numero_documento;
        $this->apellido_paterno = $persona->apellido_paterno;
        $this->apellido_materno = $persona->apellido_materno;
        $this->nombre = $persona->nombre;
        $this->nombre_completo = $persona->nombre_completo;
        $this->genero = $persona->id_genero;
        $this->genero_detalle = Genero::findOrFail($persona->id_genero)->genero;
        $this->fecha_nacimiento = $persona->fecha_nacimiento;
        $this->direccion = $persona->direccion;
        $this->celular = $persona->celular;
        $this->celular_opcional = $persona->celular_opcional;
        if ($persona->celular_opcional) {
            $this->agregar_celular = true;
        }
        $this->correo = $persona->correo;
        $this->correo_opcional = $persona->correo_opcional;
        if ($persona->correo_opcional) {
            $this->agregar_correo = true;
        }
        $this->año_egreso = $persona->año_egreso;
        $this->especialidad = $persona->especialidad_carrera;
        $this->centro_trabajo = $persona->centro_trabajo;
        $this->discapacidad = $persona->id_discapacidad;
        $this->discapacidad_detalle = Discapacidad::findOrFail($persona->id_discapacidad)->discapacidad;
        $this->estado_civil = $persona->id_estado_civil;
        $this->estado_civil_detalle = EstadoCivil::findOrFail($persona->id_estado_civil)->estado_civil;
        $this->grado_academico = $persona->id_grado_academico;
        $this->grado_academico_detalle = GradoAcademico::findOrFail($persona->id_grado_academico)->grado_academico;
        $this->universidad = $persona->id_universidad;
        $this->universidad_detalle = Universidad::findOrFail($persona->id_universidad)->universidad;
        $this->ubigeo_direccion = $persona->ubigeo_direccion;
        $this->ubigeo_direccion_detalle = Ubigeo::findOrFail($persona->ubigeo_direccion);
        $this->ubigeo_direccion_detalle = $this->ubigeo_direccion_detalle->ubigeo . ' / ' . $this->ubigeo_direccion_detalle->departamento . ' / ' . $this->ubigeo_direccion_detalle->provincia . ' / ' . $this->ubigeo_direccion_detalle->distrito;
        $this->ubigeo_nacimiento = $persona->ubigeo_nacimiento;
        $this->ubigeo_nacimiento_detalle = Ubigeo::findOrFail($persona->ubigeo_nacimiento);
        $this->ubigeo_nacimiento_detalle = $this->ubigeo_nacimiento_detalle->ubigeo . ' / ' . $this->ubigeo_nacimiento_detalle->departamento . ' / ' . $this->ubigeo_nacimiento_detalle->provincia . ' / ' . $this->ubigeo_nacimiento_detalle->distrito;
        $this->pais_direccion = $persona->pais_direccion;
        $this->pais_nacimiento = $persona->pais_nacimiento;
    }

    //Agregar celular opcional
    public function agregarCelular()
    {
        $this->agregar_celular = true;
    }

    //Quitar celular opcional
    public function quitarCelular()
    {
        $this->agregar_celular = false;
        $this->resetErrorBag('celular_opcional'); //Elimina los mensajes de error de validacion
        $this->reset('celular_opcional'); //Limpiamos el campo celular opcional
    }

    //Agregar correo opcional
    public function agregarCorreo()
    {
        $this->agregar_correo = true;
    }

    //Quitar celular opcional
    public function quitarCorreo()
    {
        $this->agregar_correo = false;
        $this->resetErrorBag('correo_opcional'); //Elimina los mensajes de error de validacion
        $this->reset('correo_opcional'); //Limpiamos el campo celular opcional
    }

    //Limpiamos los filtros
    public function resetear_filtro()
    {
        $admisionActivo = Admision::where('admision_estado', 1)->first();
        $this->procesoFiltro = $admisionActivo->id_admision;
        $this->filtro_proceso = $admisionActivo->id_admision;
    }

    //Asignamos los filtros
    public function filtrar()
    {
        if ($this->procesoFiltro == null || $this->procesoFiltro == "") {
            $admisionActivo = Admision::where('admision_estado', 1)->first();
            $this->procesoFiltro = $admisionActivo->id_admision;
            $this->filtro_proceso = $admisionActivo->id_admision;
        } else {
            $this->procesoFiltro = $this->filtro_proceso;
        }
    }

    //Guardar los datos del estudiante
    public function guardarEstudiante()
    {
        //Validamos los campos
        $this->validate([
            'numero_documento' => 'required|numeric|digits_between:8,9|unique:persona,numero_documento,' . $this->id_persona . ',id_persona',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'nombre' => 'required',
            'genero' => 'required|numeric',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required',
            'celular' => 'required|numeric|digits:9',
            'celular_opcional' => [
                'nullable',
                'numeric',
                'digits:9',
                function ($attribute, $value, $fail) {
                    if ($this->celular_opcional) {
                        if ($this->celular == $this->celular_opcional) {
                            $fail('El celular opcional no puede ser igual al celular.');
                        }
                    }
                },
            ],
            //Valida el correo que sea unico en el campo correo y correo opcional
            'correo' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    $query = Persona::where('id_persona', '<>', $this->id_persona)
                                ->where(function ($query) use ($value) {
                                    $query->where('correo', $value)
                                        ->orWhere('correo_opcional', $value);
                                })
                                ->exists();
            
                    if ($query) {
                        $fail('El correo ya está en uso en el campo correo o correo opcional.');
                    }
                    if($this->correo_opcional == $this->correo){
                        $fail('El correo no puede ser igual al correo opcional.');
                    }
                },
            ],
            'correo_opcional' => [
                'nullable',
                'email',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $query = Persona::where('id_persona', '<>', $this->id_persona)
                                ->where(function ($query) use ($value) {
                                    $query->where('correo', $value)
                                        ->orWhere('correo_opcional', $value);
                                })
                                ->exists();
            
                        if ($query) {
                            $fail('El correo opcional ya está en uso en el campo correo o correo opcional.');
                        }
                        if($this->correo_opcional == $this->correo){
                            $fail('El correo opcional no puede ser igual al correo.');
                        }
                    }
                },
            ],
            'año_egreso' => 'required|numeric|digits:4',
            'especialidad' => 'nullable',
            'centro_trabajo' => 'required',
            'discapacidad' => 'required|numeric',
            'estado_civil' => 'required|numeric',
            'grado_academico' => 'required|numeric',
            'universidad' => 'required|numeric',
            'ubigeo_direccion' => 'required|numeric',
            'ubigeo_nacimiento' => 'required|numeric',
            'pais_direccion' => 'required',
            'pais_nacimiento' => 'required',
        ]);

        //Validar que no se hayan realizado cambios en el modal
        $persona = Persona::findOrFail($this->id_persona);
        if ($persona->numero_documento == $this->numero_documento && $persona->apellido_paterno == $this->apellido_paterno && $persona->apellido_materno == $this->apellido_materno && $persona->nombre == $this->nombre && $persona->id_genero == $this->genero && $persona->fecha_nacimiento == $this->fecha_nacimiento && $persona->direccion == $this->direccion && $persona->celular == $this->celular && $persona->celular_opcional == $this->celular_opcional && $persona->correo == $this->correo && $persona->correo_opcional == $this->correo_opcional && $persona->año_egreso == $this->año_egreso && $persona->especialidad_carrera == $this->especialidad && $persona->centro_trabajo == $this->centro_trabajo && $persona->id_discapacidad == $this->discapacidad && $persona->id_estado_civil == $this->estado_civil && $persona->id_grado_academico == $this->grado_academico && $persona->id_universidad == $this->universidad && $persona->ubigeo_direccion == $this->ubigeo_direccion && $persona->ubigeo_nacimiento == $this->ubigeo_nacimiento && $persona->pais_direccion == $this->pais_direccion && $persona->pais_nacimiento == $this->pais_nacimiento) {
            $this->alertaEstudiante('¡Advertencia!', "No se realizaron cambios en el estudiante.", 'warning', 'Aceptar', 'warning');
            return;
        }

        //Guardamos los datos
        $persona = Persona::findOrFail($this->id_persona);
        $persona->numero_documento = $this->numero_documento;
        $persona->apellido_paterno = $this->apellido_paterno;
        $persona->apellido_materno = $this->apellido_materno;
        $persona->nombre = $this->nombre;
        $persona->nombre_completo = $this->apellido_paterno . ' ' . $this->apellido_materno . ' ' . $this->nombre;
        $persona->id_genero = $this->genero;
        $persona->fecha_nacimiento = $this->fecha_nacimiento;
        $persona->direccion = $this->direccion;
        $persona->celular = $this->celular;
        //Validamos si existe el celular opcional
        if ($this->agregar_celular) {
            $persona->celular_opcional = $this->celular_opcional;
        }
        $persona->correo = $this->correo;
        //Validamos si existe el correo opcional
        if ($this->agregar_correo) {
            $persona->correo_opcional = $this->correo_opcional;
        }
        $persona->año_egreso = $this->año_egreso;
        $persona->especialidad_carrera = $this->especialidad;
        $persona->centro_trabajo = $this->centro_trabajo;
        //Validamos que tipo de documento tiene por la cantidad de digitos, 8 = DNI, 9 = Carnet de extranjeria
        if (strlen($this->numero_documento) == 8) {
            $persona->id_tipo_documento = 1;//DNI
        } else {
            $persona->id_tipo_documento = 2;//Carnet de extranjeria
        }
        $persona->id_discapacidad = $this->discapacidad;
        $persona->id_estado_civil = $this->estado_civil;
        $persona->id_grado_academico = $this->grado_academico;
        $persona->id_universidad = $this->universidad;
        $persona->ubigeo_direccion = $this->ubigeo_direccion;
        $persona->ubigeo_nacimiento = $this->ubigeo_nacimiento;
        //Validamos si existe el pais de direccion
        if ($this->pais_direccion_estado == true) {
            $persona->pais_direccion = $this->pais_direccion;
        }
        //Validamos si existe el pais de nacimiento
        if ($this->pais_nacimiento_estado == true) {
            $persona->pais_nacimiento = $this->pais_nacimiento;
        }
        $persona->save();
        
        $this->alertaEstudiante('¡Éxito!', "El estudiante $persona->nombre_completo se actualizó correctamente.", 'success', 'Aceptar', 'success');

        $this->limpiar();
        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalPersona',
        ]);

    }

    public function render()
    {
        $admisionActivo = Admision::where('admision_estado', 1)->first();
        //Filtrar siempre el proceso activo
        if ($this->procesoFiltro == null || $this->procesoFiltro == "") {
            $this->procesoFiltro = $admisionActivo->id_admision;
            $this->filtro_proceso = $admisionActivo->id_admision;
        }

        $estudiantesModel = Inscripcion::join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
            ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
            ->join('admision', 'admision.id_admision', '=', 'programa_proceso.id_admision')
            ->where(function ($query) {
                $query->where('nombre_completo', 'like', '%' . $this->search . '%')
                    ->orWhere('numero_documento', 'like', '%' . $this->search . '%')
                    ->orWhere('correo', 'like', '%' . $this->search . '%')
                    ->orWhere('celular', 'like', '%' . $this->search . '%');
            })
            ->where('admision.id_admision', 'like', '%' . $this->procesoFiltro . '%')
            ->orderBy('persona.id_persona', 'desc')
            ->paginate(10);


        return view('livewire.modulo-administrador.estudiante.index', [
            "estudiantesModel" => $estudiantesModel,
            "procesos" => Admision::all(),
            "genero_model" => Genero::all(),
            "estado_civil_model" => EstadoCivil::all(),
            "discapacidad_model" => Discapacidad::all(),
            "ubigeo_model" => Ubigeo::all(),
            "grado_academico_model" => GradoAcademico::all(),
            "universidad_model" => Universidad::all(),
        ]);
    }
}
