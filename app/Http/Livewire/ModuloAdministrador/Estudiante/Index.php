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
    public $modo = 3;// 2 = Actualizar, 3 = Detalle

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
    public $pais;
    public $ubigeo_nacimiento;
    public $ubigeo_nacimiento_detalle;
    public $pais_nacimiento;
    
    public $agregar_celular = false;
    public $agregar_correo = false;

    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'numero_documento' => 'required|numeric|digits_between:8,12|unique:persona,numero_documento,'.$this->id_persona.',id_persona',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'nombre' => 'required',
            'genero' => 'required|numeric',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required',
            'celular' => 'required|numeric|digits:9',
            'celular_opcional' => 'nullable|numeric|digits:9',
            'correo' => 'required|email|unique:persona,correo,'.$this->id_persona.',id_persona',
            'correo_opcional' => 'nullable|email|unique:persona,correo_opcional,'.$this->id_persona.',id_persona',
            'año_egreso' => 'required|numeric|digits:4',
            'especialidad' => 'nullable',
            'centro_trabajo' => 'required',
            // 'tipo_documento' => 'required',
            'discapacidad' => 'required|numeric',
            'estado_civil' => 'required|numeric',
            'grado_academico' => 'required|numeric',
            'universidad' => 'required|numeric',
            'ubigeo_direccion' => 'required|numeric',
            'pais' => 'required',
            'ubigeo_nacimiento' => 'required|numeric',
            'pais_nacimiento' => 'required',
        ]);
    }

    public function limpiar()
    {
        $this->resetErrorBag();//Elimina los mensajes de error de validacion
        $this->reset('id_persona', 'numero_documento', 'apellido_paterno', 'apellido_materno', 'nombre', 'nombre_completo', 'genero', 'fecha_nacimiento', 'direccion', 'celular', 'celular_opcional', 'correo', 'correo_opcional', 'año_egreso', 'especialidad', 'centro_trabajo', 'discapacidad', 'estado_civil', 'grado_academico', 'universidad', 'ubigeo_direccion', 'pais', 'ubigeo_nacimiento', 'pais_nacimiento');
        $this->agregar_celular = false;
        $this->agregar_correo = false;
        $this->modo = 3;// 2 = Actualizar, 3 = Detalle
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
        if($persona->celular_opcional){
            $this->agregar_celular = true;
        }
        $this->correo = $persona->correo;
        $this->correo_opcional = $persona->correo_opcional;
        if($persona->correo_opcional){
            $this->agregar_correo = true;
        }
        $this->año_egreso = $persona->año_egreso;
        $this->especialidad = $persona->especialidad_carrera;
        $this->centro_trabajo = $persona->centro_trabajo;
        // $this->tipo_documento = $persona->tipo_documento;
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
        $this->ubigeo_direccion_detalle = $this->ubigeo_direccion_detalle->ubigeo.' / '.$this->ubigeo_direccion_detalle->departamento.' / '.$this->ubigeo_direccion_detalle->provincia.' / '.$this->ubigeo_direccion_detalle->distrito;
        $this->pais = $persona->pais;
        $this->ubigeo_nacimiento = $persona->ubigeo_nacimiento;
        $this->ubigeo_nacimiento_detalle = Ubigeo::findOrFail($persona->ubigeo_nacimiento);
        $this->ubigeo_nacimiento_detalle = $this->ubigeo_nacimiento_detalle->ubigeo.' / '.$this->ubigeo_nacimiento_detalle->departamento.' / '.$this->ubigeo_nacimiento_detalle->provincia.' / '.$this->ubigeo_nacimiento_detalle->distrito;
        $this->pais_nacimiento = $persona->pais_nacimiento;
    }

    //Agregar celular opcional
    public function agregarCelular(){
        $this->agregar_celular = true;
    }

    //Quitar celular opcional
    public function quitarCelular(){
        $this->agregar_celular = false;
        $this->resetErrorBag('celular_opcional');//Elimina los mensajes de error de validacion
        $this->reset('celular_opcional');//Limpiamos el campo celular opcional
    }

    //Agregar correo opcional
    public function agregarCorreo(){
        $this->agregar_correo = true;
    }

    //Quitar celular opcional
    public function quitarCorreo(){
        $this->agregar_correo = false;
        $this->resetErrorBag('correo_opcional');//Elimina los mensajes de error de validacion
        $this->reset('correo_opcional');//Limpiamos el campo celular opcional
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
        if($this->procesoFiltro == null || $this->procesoFiltro == "")
        {
            $admisionActivo = Admision::where('admision_estado', 1)->first();
            $this->procesoFiltro = $admisionActivo->id_admision;
            $this->filtro_proceso = $admisionActivo->id_admision;
        }else{
            $this->procesoFiltro = $this->filtro_proceso;
        }
    }

    public function render()
    {
        $admisionActivo = Admision::where('admision_estado', 1)->first();
        //Filtrar siempre el proceso activo
        if($this->procesoFiltro == null || $this->procesoFiltro == "")
        {
            $this->procesoFiltro = $admisionActivo->id_admision;
            $this->filtro_proceso = $admisionActivo->id_admision;
        }

        $estudiantesModel = Inscripcion::join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
                                    ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                    ->join('admision', 'admision.id_admision', '=', 'programa_proceso.id_admision')
                                    ->where(function ($query){
                                        $query->where('nombre_completo', 'like', '%'.$this->search.'%')
                                        ->orWhere('numero_documento', 'like', '%'.$this->search.'%')
                                        ->orWhere('correo', 'like', '%'.$this->search.'%')
                                        ->orWhere('celular', 'like', '%'.$this->search.'%');
                                    })
                                    ->where('admision.id_admision', 'like', '%'.$this->procesoFiltro.'%')
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
