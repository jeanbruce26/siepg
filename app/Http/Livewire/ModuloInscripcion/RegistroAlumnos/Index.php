<?php

namespace App\Http\Livewire\ModuloInscripcion\RegistroAlumnos;

use App\Models\Persona;
use App\Models\ProgramaProceso;
use Livewire\Component;
use Psy\Command\WhereamiCommand;

class Index extends Component
{
    public $paso = 1;

    //Variables para el paso 1
    public $admision;
    public $programa;
    public $modalidad;

    //Variables modelo para el paso 1
    public $programas_model;//Programas de la modalidad para el select
    public $modalidad_model;//Modalidades para el select


    //Variables para el paso 2
    public $id_persona;
    public $numero_documento;
    public $apellido_paterno;
    public $apellido_materno;
    public $nombre;
    public $genero;
    public $fecha_nacimiento;
    public $direccion;
    public $celular;
    public $celular_opcional;
    public $correo;
    public $correo_opcional;
    public $año_egreso;
    public $especialidad;
    public $centro_trabajo;
    public $discapacidad;
    public $estado_civil;
    public $grado_academico;
    public $universidad;
    public $ubigeo_direccion;
    public $ubigeo_nacimiento;
    public $pais_direccion;
    public $pais_nacimiento;
    public $pais_direccion_estado = false;
    public $pais_nacimiento_estado = false;

    public $admitido_codigo;

    //Valiables para modal de selecionar codigo de admitido
    public $numero_alumnos = 0;
    public $search = '';
    public $estudiantes_codigo_model;

    public function updated($propertyName)
    {
        if($this->paso == 1){
            $this->validateOnly($propertyName, [
                'admision' => 'required',
                'programa' => 'required',
                'modalidad' => 'required',
            ]);
        }else{
            $this->validateOnly($propertyName, [
                'numero_documento' => 'required',
                'apellido_paterno' => 'required',
                'apellido_materno' => 'required',
                'nombre' => 'required',
                'genero' => 'required',
                'fecha_nacimiento' => 'required',
                'direccion' => 'required',
                'celular' => 'required',
                'celular_opcional' => 'nullable',
                'correo' => 'required',
                'correo_opcional' => 'nullable',
                'año_egreso' => 'required',
                'especialidad' => 'required',
                'centro_trabajo' => 'required',
                'discapacidad' => 'required',
                'estado_civil' => 'required',
                'grado_academico' => 'required',
                'universidad' => 'required',
                'ubigeo_direccion' => 'required',
                'ubigeo_nacimiento' => 'required',
                'pais_direccion' => 'required',
                'pais_nacimiento' => 'required',
            ]);
        }
    }

    public function mount()
    {
        $this->modalidad_model = \App\Models\Modalidad::where('modalidad_estado', 1)->get();
        $this->programas_model = collect();
        $this->estudiantes_codigo_model = collect();
    }

    public function paso_1()
    {
        $this->paso = 1;
    }

    public function paso_2()
    {
        $this->validar_registro();
        $this->paso = 2;
    }

    public function guardarRegistro()
    {
        $this->validar_registro();
        $this->paso = 2;
    }

    public function validar_registro()
    {
        if($this->paso === 1)
        {
            $this->resetErrorBag();
            $this->resetValidation();
            $this->validate([
                'admision' => 'required|numeric',
                'modalidad' => 'required|numeric',
                'programa' => 'required|numeric',
            ]);
        }
        else if($this->paso === 2)
        {
            $this->resetErrorBag();
            $this->resetValidation();

            if($this->ubigeo_direccion && $this->ubigeo_nacimiento){
                $ubi_direccion = \App\Models\Ubigeo::find($this->ubigeo_direccion)->ubigeo;
                $ubi_nacimiento = \App\Models\Ubigeo::find($this->ubigeo_nacimiento)->ubigeo;

                if($ubi_direccion == 000000 && $ubi_nacimiento == 000000){
                    $this->validate([
                        'numero_documento' => 'required|max:8|unique:personas,numero_documento',
                        'apellido_paterno' => 'required|max:50',
                        'apellido_materno' => 'required|max:50',
                        'nombre' => 'required|max:50',
                        'genero' => 'required|numeric',
                        'fecha_nacimiento' => 'required|date',
                        'direccion' => 'required|max:100',
                        'celular' => 'required|max:9',
                        'celular_opcional' => 'nullable|max:9',
                        'correo' => 'required|email|max:50',
                        'correo_opcional' => 'nullable|email|max:50',
                        'año_egreso' => 'required|numeric',
                        'especialidad' => 'required|max:50',
                        'centro_trabajo' => 'required|max:50',
                        'discapacidad' => 'required|numeric',
                        'estado_civil' => 'required|numeric',
                        'grado_academico' => 'required|numeric',
                        'universidad' => 'required|max:50',
                        'ubigeo_direccion' => 'required|numeric',
                        'ubigeo_nacimiento' => 'required|numeric',
                        'pais_direccion' => 'required|max:50',
                        'pais_nacimiento' => 'required|max:50',
                    ]);
                }
            }else{

                if($this->ubigeo_direccion){
                    $ubi_direccion = \App\Models\Ubigeo::find($this->ubigeo_direccion)->ubigeo;
                    if($ubi_direccion == 000000){
                        $this->validate([
                            'numero_documento' => 'required|max:8|unique:personas,numero_documento',
                            'apellido_paterno' => 'required|max:50',
                            'apellido_materno' => 'required|max:50',
                            'nombre' => 'required|max:50',
                            'genero' => 'required|numeric',
                            'fecha_nacimiento' => 'required|date',
                            'direccion' => 'required|max:100',
                            'celular' => 'required|max:9',
                            'celular_opcional' => 'nullable|max:9',
                            'correo' => 'required|email|max:50',
                            'correo_opcional' => 'nullable|email|max:50',
                            'año_egreso' => 'required|numeric',
                            'especialidad' => 'required|max:50',
                            'centro_trabajo' => 'required|max:50',
                            'discapacidad' => 'required|numeric',
                            'estado_civil' => 'required|numeric',
                            'grado_academico' => 'required|numeric',
                            'universidad' => 'required|max:50',
                            'ubigeo_direccion' => 'required|numeric',
                            'ubigeo_nacimiento' => 'required|numeric',
                            'pais_direccion' => 'required|max:50',
                            'pais_nacimiento' => 'nullable|max:50',
                        ]);
                    }
                }else if($this->ubigeo_nacimiento){
                    $ubi_nacimiento = \App\Models\Ubigeo::find($this->ubigeo_nacimiento)->ubigeo;
                    if($ubi_nacimiento == 000000){
                        $this->validate([
                            'numero_documento' => 'required|max:8|unique:personas,numero_documento',
                            'apellido_paterno' => 'required|max:50',
                            'apellido_materno' => 'required|max:50',
                            'nombre' => 'required|max:50',
                            'genero' => 'required|numeric',
                            'fecha_nacimiento' => 'required|date',
                            'direccion' => 'required|max:100',
                            'celular' => 'required|max:9',
                            'celular_opcional' => 'nullable|max:9',
                            'correo' => 'required|email|max:50',
                            'correo_opcional' => 'nullable|email|max:50',
                            'año_egreso' => 'required|numeric',
                            'especialidad' => 'required|max:50',
                            'centro_trabajo' => 'required|max:50',
                            'discapacidad' => 'required|numeric',
                            'estado_civil' => 'required|numeric',
                            'grado_academico' => 'required|numeric',
                            'universidad' => 'required|max:50',
                            'ubigeo_direccion' => 'required|numeric',
                            'ubigeo_nacimiento' => 'required|numeric',
                            'pais_direccion' => 'nullable|max:50',
                            'pais_nacimiento' => 'required|max:50',
                        ]);
                    }
                }else{
                    $this->validate([
                        'numero_documento' => 'required|max:8|unique:personas,numero_documento',
                        'apellido_paterno' => 'required|max:50',
                        'apellido_materno' => 'required|max:50',
                        'nombre' => 'required|max:50',
                        'genero' => 'required|numeric',
                        'fecha_nacimiento' => 'required|date',
                        'direccion' => 'required|max:100',
                        'celular' => 'required|max:9',
                        'celular_opcional' => 'nullable|max:9',
                        'correo' => 'required|email|max:50',
                        'correo_opcional' => 'nullable|email|max:50',
                        'año_egreso' => 'required|numeric',
                        'especialidad' => 'required|max:50',
                        'centro_trabajo' => 'required|max:50',
                        'discapacidad' => 'required|numeric',
                        'estado_civil' => 'required|numeric',
                        'grado_academico' => 'required|numeric',
                        'universidad' => 'required|max:50',
                        'ubigeo_direccion' => 'required|numeric',
                        'ubigeo_nacimiento' => 'required|numeric',
                        'pais_direccion' => 'nullable|max:50',
                        'pais_nacimiento' => 'nullable|max:50',
                    ]);
                }
            }
        }
    }

    public function updatedModalidad($modalidad)
    {
        $this->programas_model = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', 'programa_proceso.id_programa_plan')
                                        ->join('programa', 'programa.id_programa', 'programa_plan.id_programa')
                                        ->where('programa.id_modalidad', $modalidad)
                                        ->where('programa_proceso.id_admision',$this->admision)
                                        ->where('programa_plan.programa_plan_estado', 1)
                                        ->get();
    }

    public function updatedAdmision($admision)
    {
        $this->programas_model = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', 'programa_proceso.id_programa_plan')
                                        ->join('programa', 'programa.id_programa', 'programa_plan.id_programa')
                                        ->where('programa.id_modalidad', $this->modalidad)
                                        ->where('programa_proceso.id_admision',$admision)
                                        ->where('programa_plan.programa_plan_estado', 1)
                                        ->get();
    }

    public function updatedUbigeoDireccion($ubigeo_direccion)
    {
        $ubi = \App\Models\Ubigeo::find($ubigeo_direccion);
        if($ubi->ubigeo == 000000){
            $this->pais_direccion_estado = true;
        }else{
            $this->pais_direccion_estado = false;
            $this->reset('pais_direccion');
            $this->resetErrorBag('pais_direccion');
        }
    }

    public function updatedUbigeoNacimiento($ubigeo_nacimiento)
    {
        $ubi = \App\Models\Ubigeo::find($ubigeo_nacimiento);
        if($ubi->ubigeo == 000000){
            $this->pais_nacimiento_estado = true;
        }else{
            $this->pais_nacimiento_estado = false;
            $this->reset('pais_nacimiento');
            $this->resetErrorBag('pais_nacimiento');
        }
    }

    public function updatedSearch($search)
    {
        if($search || $search != ''){
            $this->estudiantes_codigo_model = Persona::where('numero_documento', 'like', '%'.$search.'%')
                                                    ->orWhere('apellido_paterno', 'like', '%'.$search.'%')
                                                    ->orWhere('apellido_materno', 'like', '%'.$search.'%')
                                                    ->orWhere('nombre', 'like', '%'.$search.'%')
                                                    ->get();
        }else{
            $this->estudiantes_codigo_model = collect();
        }
    }

    //Limpiamos las variables del modal
    public function limpiar(){
        $this->reset('search');
        $this->estudiantes_codigo_model = collect();
    }

    public function render()
    {

        return view('livewire.modulo-inscripcion.registro-alumnos.index', [
            'admision_model' => \App\Models\Admision::all(),
            'ubigeo_model' => \App\Models\Ubigeo::all(),
            'genero_model' => \App\Models\Genero::all(),
            'grado_academico_model' => \App\Models\GradoAcademico::all(),
            'estado_civil_model' => \App\Models\EstadoCivil::all(),
            'discapacidad_model' => \App\Models\Discapacidad::all(),
            'universidad_model' => \App\Models\Universidad::all(),
        ]);
    }
}
