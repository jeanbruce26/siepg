<?php

namespace App\Http\Livewire\ModuloInscripcion\RegistroAlumnos;

use App\Models\ProgramaProceso;
use Livewire\Component;

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

    public function updated($propertyName)
    {
        if($this->paso == 1){
            $this->validateOnly($propertyName, [
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
            }
            if($this->ubigeo_nacimiento){
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
            }
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
            }
        }
    }

            // else if($this->ubigeo_nacimiento == 1893 && $this->ubigeo_direccion == 1893)
            // {
            //     $this->validate([
            //         'paterno' => 'required|max:50',
            //         'materno' => 'required|max:50',
            //         'nombres' => 'required|max:50',
            //         'fecha_nacimiento' => 'required|date',
            //         'genero' => 'required|numeric',
            //         'estado_civil' => 'required|numeric',
            //         'grado_academico' => 'required|numeric',
            //         'especialidad_carrera' => 'required|max:50',
            //         'discapacidad' => 'required|numeric',
            //         'direccion' => 'required|max:100',
            //         'celular' => 'required|max:9',
            //         'celular_opcional' => 'nullable|max:9',
            //         'año_egreso' => 'required|numeric',
            //         'email' => 'required|email|max:50',
            //         'email_opcional' => 'nullable|email|max:50',
            //         'universidad' => 'required|numeric',
            //         'centro_trabajo' => 'required|max:50',
            //         'pais_direccion' => 'required|max:50',
            //         'ubigeo_direccion' => 'required|numeric',
            //         'pais_nacimiento' => 'required|max:50',
            //         'ubigeo_nacimiento' => 'required|numeric',
            //     ]);
            // }
            // else
            // {
            //     $this->validate([
            //         'paterno' => 'required|max:50',
            //         'materno' => 'required|max:50',
            //         'nombres' => 'required|max:50',
            //         'fecha_nacimiento' => 'required|date',
            //         'genero' => 'required|numeric',
            //         'estado_civil' => 'required|numeric',
            //         'grado_academico' => 'required|numeric',
            //         'especialidad_carrera' => 'required|max:50',
            //         'discapacidad' => 'required|numeric',
            //         'direccion' => 'required|max:100',
            //         'celular' => 'required|max:9',
            //         'celular_opcional' => 'nullable|max:9',
            //         'año_egreso' => 'required|numeric',
            //         'email' => 'required|email|max:50',
            //         'email_opcional' => 'nullable|email|max:50',
            //         'universidad' => 'required|numeric',
            //         'centro_trabajo' => 'required|max:50',
            //         'pais_direccion' => 'nullable|max:50',
            //         'ubigeo_direccion' => 'required|numeric',
            //         'pais_nacimiento' => 'nullable|max:50',
            //         'ubigeo_nacimiento' => 'required|numeric',
            //     ]);
            // }

    public function updatedModalidad($modalidad)
    {
        $this->programas_model = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', 'programa_proceso.id_programa_plan')
                                        ->join('programa', 'programa.id_programa', 'programa_plan.id_programa')
                                        ->where('programa.id_modalidad', $modalidad)
                                        ->where('programa_proceso.id_admision',$this->admision)
                                        ->where('programa_plan.programa_plan_estado', 1)
                                        ->get();
    }

    public function updatedAdmision($admision){
        $this->programas_model = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', 'programa_proceso.id_programa_plan')
                                        ->join('programa', 'programa.id_programa', 'programa_plan.id_programa')
                                        ->where('programa.id_modalidad', $this->modalidad)
                                        ->where('programa_proceso.id_admision',$admision)
                                        ->where('programa_plan.programa_plan_estado', 1)
                                        ->get();
    }

    public function render()
    {


        return view('livewire.modulo-inscripcion.registro-alumnos.index', [
            'admision_model' => \App\Models\Admision::all(),
        ]);
    }
}
