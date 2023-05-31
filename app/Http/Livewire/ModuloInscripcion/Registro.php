<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Jobs\ProcessRegistroFichaInscripcion;
use App\Mail\EmailFichaInscripcion;
use App\Models\Admision;
use App\Models\Discapacidad;
use App\Models\EstadoCivil;
use App\Models\ExpedienteAdmision;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\ExpedienteTipoSeguimiento;
use App\Models\Genero;
use App\Models\GradoAcademico;
use App\Models\Inscripcion;
use App\Models\Modalidad;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\ProgramaProceso;
use App\Models\TipoSeguimiento;
use App\Models\Ubigeo;
use App\Models\Universidad;
use App\Models\UsuarioEstudiante;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;

class Registro extends Component
{
    use WithFileUploads; // sirve para subir archivos

    public $paso = 0; // variable para el paso de la vista
    public $total_pasos = 3; // variable para el total de pasos de la vista

    public $admision; // variable para el nombre de la admision

    public $id_inscripcion; // variable para el id de la inscripcion
    public $id_persona, $documento, $paterno, $materno, $nombres, $fecha_nacimiento, $genero, $estado_civil, $grado_academico, $especialidad_carrera, $discapacidad, $direccion, $celular, $celular_opcional, $año_egreso, $email, $email_opcional, $universidad, $centro_trabajo; // variables para el formulario de registro de información personal
    public $programa_array, $programa; // variables para el formulario de registro de información de programa
    public $modalidad_array, $modalidad; // variables para el formulario de registro de información de modalidad
    public $ubigeo_direccion_array, $ubigeo_direccion, $pais_direccion; // variables para el formulario de registro de información de dirección
    public $ubigeo_nacimiento_array, $ubigeo_nacimiento, $pais_nacimiento; // variables para el formulario de registro de información de nacimiento

    public $expediente, $expediente_array, $expediente_nombre, $id_expediente; // variable para el formulario de registro de expediente
    public $mostrar_tipo_expediente; // sirve para mostrar los expedientes segun el programa que elija el usuario
    public $iteration; // sirve para actualizar el componente de expediente
    public $modo = 'create'; // sirve para cargar informacion del registro en los formularios
    public $modo_expediente = 'create'; // sirve para cargar informacion del registro en los formularios
    public $check_expediente = false;   // sirve para hacer el seguimiento de los expedientes de grado academico
                                        // true = acepta la declaracion jurada, false = no acepta la declaracion jurada
    public $declaracion_jurada = false; // sirve para aceptar la declaracion jurada al finalizar el registro de inscripcion y es obligatorio
                                        // true = acepta la declaracion jurada, false = no acepta la declaracion jurada

    public function mount()
    {
        $this->paso = 1;
        $this->documento = auth('inscripcion')->user()->pago_documento;
        $persona = Persona::where('numero_documento', $this->documento)->first();
        $this->modalidad_array = Modalidad::where('modalidad_estado', 1)->get();
        $this->programa_array = Collect();
        $this->admision = Admision::where('admision_estado', 1)->first();
        $this->ubigeo_direccion_array = Ubigeo::all();
        $this->ubigeo_nacimiento_array = Ubigeo::all();
        if($persona)
        {
            $this->id_persona = $persona->id_persona;
            $this->paterno = $persona->apellido_paterno;
            $this->materno = $persona->apellido_materno;
            $this->nombres = $persona->nombre;
            $this->fecha_nacimiento = $persona->fecha_nacimiento;
            $this->genero = $persona->id_genero;
            $this->estado_civil = $persona->id_estado_civil;
            $this->grado_academico = $persona->id_grado_academico;
            $this->especialidad_carrera = $persona->especialidad_carrera;
            $this->discapacidad = $persona->id_discapacidad;
            $this->direccion = $persona->direccion;
            $this->celular = $persona->celular;
            $this->celular_opcional = $persona->celular_opcional;
            $this->año_egreso = $persona->año_egreso;
            $this->email = $persona->correo;
            $this->email_opcional = $persona->correo_opcional;
            $this->universidad = $persona->id_universidad;
            $this->centro_trabajo = $persona->centro_trabajo;
            $this->pais_direccion = $persona->pais_direccion;
            $this->pais_nacimiento = $persona->pais_nacimiento;
            $this->ubigeo_direccion = $persona->ubigeo_direccion;
            $this->ubigeo_nacimiento = $persona->ubigeo_nacimiento;
            $this->modo = 'update';
        }
    }

    public function updated($propertyName)
    {
        if($this->paso == 1)
        {
            $this->validateOnly($propertyName, [
                'modalidad' => 'required|numeric',
                'programa' => 'required|numeric',
            ]);
        }
        elseif($this->paso == 2)
        {
            $this->validateOnly($propertyName, [
                'paterno' => 'required|max:50',
                'materno' => 'required|max:50',
                'nombres' => 'required|max:50',
                'fecha_nacimiento' => 'required|date',
                'genero' => 'required|numeric',
                'estado_civil' => 'required|numeric',
                'grado_academico' => 'required|numeric',
                'especialidad_carrera' => 'required|max:50',
                'discapacidad' => 'required|numeric',
                'direccion' => 'required|max:100',
                'celular' => 'required|max:9',
                'celular_opcional' => 'nullable|max:9',
                'año_egreso' => 'required|numeric',
                'email' => 'required|email|max:50',
                'email_opcional' => 'nullable|email|max:50',
                'universidad' => 'required|numeric',
                'centro_trabajo' => 'required|max:50',
                'pais_direccion' => 'nullable|max:50',
                'ubigeo_direccion' => 'required|numeric',
                'pais_nacimiento' => 'nullable|max:50',
                'ubigeo_nacimiento' => 'required|numeric',
            ]);
        }
    }

    public function paso_1()
    {
        $this->paso = 1;
    }

    public function paso_2()
    {
        $this->validacion();
        $this->paso = 2;
    }

    public function paso_3()
    {
        $this->validacion();
        $this->paso = 3;
    }

    public function validacion()
    {
        if($this->paso === 1)
        {
            $this->resetErrorBag();
            $this->resetValidation();
            $this->validate([
                'modalidad' => 'required|numeric',
                'programa' => 'required|numeric',
            ]);
        }
        elseif($this->paso === 2)
        {
            $this->resetErrorBag();
            $this->resetValidation();
            if($this->ubigeo_direccion == 1893)
            {
                $this->validate([
                    'paterno' => 'required|max:50',
                    'materno' => 'required|max:50',
                    'nombres' => 'required|max:50',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|numeric',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|max:50',
                    'discapacidad' => 'required|numeric',
                    'direccion' => 'required|max:100',
                    'celular' => 'required|max:9',
                    'celular_opcional' => 'nullable|max:9',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email|max:50',
                    'email_opcional' => 'nullable|email|max:50',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|max:50',
                    'pais_direccion' => 'required|max:50',
                    'ubigeo_direccion' => 'required|numeric',
                    'pais_nacimiento' => 'nullable|max:50',
                    'ubigeo_nacimiento' => 'required|numeric',
                ]);
            }
            else if($this->ubigeo_nacimiento == 1893)
            {
                $this->validate([
                    'paterno' => 'required|max:50',
                    'materno' => 'required|max:50',
                    'nombres' => 'required|max:50',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|numeric',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|max:50',
                    'discapacidad' => 'required|numeric',
                    'direccion' => 'required|max:100',
                    'celular' => 'required|max:9',
                    'celular_opcional' => 'nullable|max:9',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email|max:50',
                    'email_opcional' => 'nullable|email|max:50',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|max:50',
                    'pais_direccion' => 'nullable|max:50',
                    'ubigeo_direccion' => 'required|numeric',
                    'pais_nacimiento' => 'required|max:50',
                    'ubigeo_nacimiento' => 'required|numeric',
                ]);
            }
            else if($this->ubigeo_nacimiento == 1893 && $this->ubigeo_direccion == 1893)
            {
                $this->validate([
                    'paterno' => 'required|max:50',
                    'materno' => 'required|max:50',
                    'nombres' => 'required|max:50',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|numeric',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|max:50',
                    'discapacidad' => 'required|numeric',
                    'direccion' => 'required|max:100',
                    'celular' => 'required|max:9',
                    'celular_opcional' => 'nullable|max:9',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email|max:50',
                    'email_opcional' => 'nullable|email|max:50',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|max:50',
                    'pais_direccion' => 'required|max:50',
                    'ubigeo_direccion' => 'required|numeric',
                    'pais_nacimiento' => 'required|max:50',
                    'ubigeo_nacimiento' => 'required|numeric',
                ]);
            }
            else
            {
                $this->validate([
                    'paterno' => 'required|max:50',
                    'materno' => 'required|max:50',
                    'nombres' => 'required|max:50',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|numeric',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|max:50',
                    'discapacidad' => 'required|numeric',
                    'direccion' => 'required|max:100',
                    'celular' => 'required|max:9',
                    'celular_opcional' => 'nullable|max:9',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email|max:50',
                    'email_opcional' => 'nullable|email|max:50',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|max:50',
                    'pais_direccion' => 'nullable|max:50',
                    'ubigeo_direccion' => 'required|numeric',
                    'pais_nacimiento' => 'nullable|max:50',
                    'ubigeo_nacimiento' => 'required|numeric',
                ]);
            }
        }
    }

    public function updatedModalidad($modalidad)
    {
        $this->programa_array = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                        ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                                        ->join('sede', 'sede.id_sede', '=', 'programa.id_sede')
                                        ->where('programa.id_modalidad',$modalidad)
                                        ->where('programa_proceso.id_admision',$this->admision->id_admision)
                                        ->where('programa_proceso.programa_proceso_estado',1)
                                        ->where('programa_plan.programa_plan_estado',1)
                                        ->get();
    }

    public function updatedPrograma($programa_proceso)
    {
        $programa = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                    ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                                    ->join('sede', 'sede.id_sede', '=', 'programa.id_sede')
                                    ->where('programa_proceso.id_programa_proceso',$programa_proceso)
                                    ->where('programa_proceso.id_admision',$this->admision->id_admision)
                                    ->first();
        // dd($this->programa_array);
        if($programa){
            $programa_tipo = $programa->programa_tipo;
            if($programa_tipo == 1){
                $this->mostrar_tipo_expediente = 1;
            }else if($programa_tipo == 2){
                $this->mostrar_tipo_expediente = 2;
            }
            $this->expediente_array = ExpedienteAdmision::join('expediente', 'expediente.id_expediente', '=', 'expediente_admision.id_expediente')
                            ->where('expediente_admision.expediente_admision_estado', 1)
                            ->where('expediente.expediente_estado', 1)
                            ->where(function($query){
                                $query->where('expediente.expediente_tipo', 0)
                                    ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                            })
                            ->get();
        }else{
            $this->expediente_array = null;
        }
    }

    public function limpiar_modal_expediente()
    {
        $this->reset([
            'expediente',
        ]);
        $this->iteration++;
    }

    public function cargar_modal_expediente(ExpedienteAdmision $expediente)
    {
        $this->limpiar_modal_expediente();
        $this->expediente_nombre = $expediente->expediente->expediente;
        $this->id_expediente = $expediente->id_expediente_admision;
        $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $this->id_inscripcion)->where('id_expediente_admision', $expediente->id_expediente_admision)->first();
        // dd($expediente_inscripcion, $this->id_inscripcion, $expediente->expediente->id_expediente, $this->id_expediente, $this->expediente_nombre);
        if($expediente_inscripcion){
            $this->modo_expediente = 'edit';
        }else{
            $this->modo_expediente = 'create';
        }
    }

    public function registrar_expediente()
    {
        $expediente_model = ExpedienteAdmision::where('expediente_admision_estado', 1)->where('id_expediente_admision', $this->id_expediente)->first();

        // Validar si el expediente es requerido
        if($expediente_model->expediente->expediente_requerido == 1 && $this->modo_expediente == 'create')
        {
            $this->validate([
                'expediente' => 'required|file|max:10240|mimetypes:application/octet-stream,application/pdf,application/x-pdf,application/x-download,application/force-download',
            ]);
        }
        else if($expediente_model->expediente->expediente_requerido == 1 && $this->modo_expediente == 'edit')
        {
            $this->validate([
                'expediente' => 'nullable|file|max:10240|mimetypes:application/octet-stream,application/pdf,application/x-pdf,application/x-download,application/force-download',
            ]);
        }
        else if($expediente_model->expediente->expediente_requerido != 1 && $this->modo_expediente == 'edit')
        {
            $this->validate([
                'expediente' => 'nullable|file|max:10240|mimetypes:application/octet-stream,application/pdf,application/x-pdf,application/x-download,application/force-download',
            ]);
        }

        // numero de documento
        $numero_documento = auth('inscripcion')->user()->pago_documento;

        // obtenemos el año de admision
        $admision = Admision::where('admision_estado',1)->first()->admision;

        if($this->expediente != null)
        {
            $path = 'Posgrado/' . $admision. '/' . $numero_documento . '/' . 'Expedientes' . '/';
            $filename = $expediente_model->expediente->expediente_nombre_file . ".pdf";
            $nombreDB = $path.$filename;
            $this->expediente->storeAs($path, $filename, 'files_publico');

            if($this->modo_expediente == 'create')
            {
                $expediente_inscripcion = new ExpedienteInscripcion();
                $expediente_inscripcion->expediente_inscripcion_url = $nombreDB;
                $expediente_inscripcion->expediente_inscripcion_estado = 1;
                $expediente_inscripcion->expediente_inscripcion_fecha = now();
                $expediente_inscripcion->id_expediente_admision = $this->id_expediente;
                $expediente_inscripcion->id_inscripcion = $this->id_inscripcion;
                $expediente_inscripcion->save();
            }
            else if($this->modo_expediente == 'edit')
            {
                $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $this->id_inscripcion)->where('id_expediente_admision', $this->id_expediente)->first();
                $expediente_inscripcion->expediente_inscripcion_url = $nombreDB;
                $expediente_inscripcion->expediente_inscripcion_estado = 1;
                $expediente_inscripcion->expediente_inscripcion_fecha = now();
                $expediente_inscripcion->save();
            }

            // emitir evento para mostrar mensaje de alerta de registro exitoso de expediente
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'Se ha registrado el expediente correctamente',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }else{
            // emitir evento para mostrar mensaje de alerta de registro erroneo de expediente
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'No se ha registrado el expediente',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
        }

        // emitir evento para ocultar modal de registro de expediente
        $this->dispatchBrowserEvent('modal_registro_expediente', [
            'action' => 'hide'
        ]);

        // limpiar modal de registro de expediente
        $this->limpiar_modal_expediente();
    }

    public function updatedDeclaracionJurada($declaracion_jurada)
    {
        $this->validate([
            'declaracion_jurada' => 'accepted',
        ]);
    }

    public function registrar_inscripcion()
    {
        // obtenemos el id de admision
        $admision = Admision::where('admision_estado', 1)->first();
        // validamos si se subieron los expedientes completos
        // buscamos los expedientes que pertenecen al tipo de expediente que se esta mostrando
        $expediente = ExpedienteAdmision::join('expediente', 'expediente.id_expediente', 'expediente_admision.id_expediente')
                            ->where('expediente.expediente_estado', 1)
                            ->where('expediente_admision.id_admision', $admision->id_admision)
                            ->where(function($query){
                                $query->where('expediente.expediente_tipo', 0)
                                    ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                            })->count();

        // buscamos los expedientes que se han subido
        $inscripcion_expedeinte = ExpedienteInscripcion::where('id_inscripcion', $this->id_inscripcion)->count();

        if($expediente > $inscripcion_expedeinte)
        {
            // emitir evento para mostrar mensaje de alerta de expedientes incompletos
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'No se han subido todos los expedientes requeridos',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return redirect()->back();
        }

        // validar declaracion jurada
        if($this->declaracion_jurada == false)
        {
            // emitir evento para mostrar mensaje de alerta de declaracion jurada
            $this->dispatchBrowserEvent('registro_inscripcion', [
                'title' => '',
                'text' => 'Debe aceptar la declaración jurada para continuar con el proceso de inscripción',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);

            // validar el campo de declaracion jurada para que no se envie el formulario
            $this->validate([
                'declaracion_jurada' => 'accepted',
            ]);

            // redireccionar a la misma pagina
            return redirect()->back();
        }

        // verificamos el pais de procedencia de la direccion
        if ($this->ubigeo_direccion == 1893)
        {
            $this->pais_direccion = $this->pais_direccion;
            $this->pais_direccion = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->pais_direccion);
        }
        else
        {
            $this->pais_direccion = 'PERU';
        }

        // verificamos el pais de procedencia de nacimiento
        if ($this->ubigeo_nacimiento == 1893)
        {
            $this->pais_nacimiento = $this->pais_nacimiento;
            $this->pais_nacimiento = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->pais_nacimiento);
        }
        else
        {
            $this->pais_nacimiento = 'PERU';
        }

        // reemplazar tildes por letras sin tildes en los campos de apellido paterno, apellido materno y nombres
        $this->nombres = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->nombres);
        $this->paterno = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->paterno);
        $this->materno = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->materno);
        $this->direccion = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->direccion);
        $this->centro_trabajo = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->centro_trabajo);

        // registro de formulario de inscripcion
        $this->documento = auth('inscripcion')->user()->pago_documento;
        $persona = Persona::where('numero_documento', $this->documento)->first();
        if($persona)
        {
            // actualizar datos de persona
            $persona->apellido_paterno = strtoupper($this->paterno);
            $persona->apellido_materno = strtoupper($this->materno);
            $persona->nombre = strtoupper($this->nombres);
            $persona->nombre_completo = strtoupper($this->paterno.' '.$this->materno.' '.$this->nombres);
            $persona->id_genero = $this->genero;
            $persona->fecha_nacimiento = $this->fecha_nacimiento;
            $persona->direccion = strtoupper($this->direccion);
            $persona->celular = $this->celular;
            if($this->celular_opcional != null)
            {
                $persona->celular_opcional = $this->celular_opcional;
            }
            else
            {
                $persona->celular_opcional = null;
            }
            $persona->correo = $this->email;
            if($this->email_opcional != null)
            {
                $persona->correo_opcional = $this->email_opcional;
            }
            else
            {
                $persona->correo_opcional = null;
            }
            $persona->año_egreso = $this->año_egreso;
            $persona->especialidad_carrera = strtoupper($this->especialidad_carrera);
            $persona->centro_trabajo = strtoupper($this->centro_trabajo);
            if(strlen($this->documento) == 8)
            {
                $persona->id_tipo_documento = 1;
            }
            else if(strlen($this->documento) == 9)
            {
                $persona->id_tipo_documento = 2;
            }
            $persona->id_discapacidad = $this->discapacidad;
            $persona->id_estado_civil = $this->estado_civil;
            $persona->id_grado_academico = $this->grado_academico;
            $persona->id_universidad = $this->universidad;
            $persona->ubigeo_direccion = $this->ubigeo_direccion;
            $persona->pais_direccion = $this->pais_direccion;
            $persona->ubigeo_nacimiento = $this->ubigeo_nacimiento;
            $persona->pais_nacimiento = $this->pais_nacimiento;
            $persona->save();

            // asignar id de persona
            $this->id_persona = $persona->id_persona;
        }
        else
        {
            // registrar datos de persona
            $persona = new Persona();
            $persona->numero_documento = $this->documento;
            $persona->apellido_paterno = strtoupper($this->paterno);
            $persona->apellido_materno = strtoupper($this->materno);
            $persona->nombre = strtoupper($this->nombres);
            $persona->nombre_completo = strtoupper($this->paterno.' '.$this->materno.' '.$this->nombres);
            $persona->id_genero = $this->genero;
            $persona->fecha_nacimiento = $this->fecha_nacimiento;
            $persona->direccion = strtoupper($this->direccion);
            $persona->celular = $this->celular;
            if($this->celular_opcional != null)
            {
                $persona->celular_opcional = $this->celular_opcional;
            }
            else
            {
                $persona->celular_opcional = null;
            }
            $persona->correo = $this->email;
            if($this->email_opcional != null)
            {
                $persona->correo_opcional = $this->email_opcional;
            }
            else
            {
                $persona->correo_opcional = null;
            }
            $persona->año_egreso = $this->año_egreso;
            $persona->especialidad_carrera = strtoupper($this->especialidad_carrera);
            $persona->centro_trabajo = strtoupper($this->centro_trabajo);
            if(strlen($this->documento) == 8)
            {
                $persona->id_tipo_documento = 1;
            }
            else if(strlen($this->documento) == 9)
            {
                $persona->id_tipo_documento = 2;
            }
            $persona->id_discapacidad = $this->discapacidad;
            $persona->id_estado_civil = $this->estado_civil;
            $persona->id_grado_academico = $this->grado_academico;
            $persona->id_universidad = $this->universidad;
            $persona->ubigeo_direccion = $this->ubigeo_direccion;
            $persona->pais_direccion = $this->pais_direccion;
            $persona->ubigeo_nacimiento = $this->ubigeo_nacimiento;
            $persona->pais_nacimiento = $this->pais_nacimiento;
            $persona->save();

            // asignar id de persona
            $this->id_persona = $persona->id_persona;
        }

        // Actulaizacion de datos de la inscripcion
        $inscripcion = Inscripcion::find($this->id_inscripcion);
        $inscripcion->inscripcion_fecha = now();
        $inscripcion->id_persona = $this->id_persona;
        $inscripcion->id_programa_proceso = $this->programa;
        $inscripcion->inscripcion_tipo_programa = $this->mostrar_tipo_expediente;
        $inscripcion->save();

        // Sirve para asignar el seguimiento del expediente
        if($this->check_expediente == true)
        {
            $exp_ins = ExpedienteInscripcion::join('expediente_admision','expediente_inscripcion.id_expediente_admsiion','=','expediente_admision.id_expediente_admsiion')
                                                ->join('expediente','expediente_admision.id_expediente','=','expediente.id_expediente')
                                                ->where('expediente_inscripcion.id_inscripcion',$this->id_inscripcion)
                                                ->where(function($query){
                                                    $query->where('expediente.expediente_tipo', 0)
                                                        ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                                                })
                                                ->get();
            $exp_seg = ExpedienteTipoSeguimiento::join('expediente','expediente_tipo_seguimiento.id_expediente','=','expediente.id_expediente')
                                                ->where('expediente_tipo_seguimiento.expediente_tipo_seguimiento_estado', 1)
                                                ->where('expediente_tipo_seguimiento.tipo_seguimiento', 1)
                                                ->where(function($query){
                                                    $query->where('expediente.expediente_tipo', 0)
                                                        ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                                                })
                                                ->get();
            $array_seguimiento = [];
            foreach ($exp_ins as $exp)
            {
                foreach ($exp_seg as $seg)
                {
                    if($exp->id_expediente == $seg->id_expediente)
                    {
                        array_push($array_seguimiento, $exp->id_expediente_inscripcion);
                    }
                }
            }
            // Registrar datos del seguimiento del expediente de inscripcion
            foreach ($array_seguimiento as $item)
            {
                $seguimiento_exp_ins = new ExpedienteInscripcionSeguimiento();
                $seguimiento_exp_ins->id_expediente_inscripcion = $item;
                $seguimiento_exp_ins->tipo_seguimiento = 1;
                $seguimiento_exp_ins->expediente_inscripcion_seguimiento_estado = 1;
                $seguimiento_exp_ins->save();
            }
        }

        // Eliminar expedientes de la inscripcion que no son del programa elegido
        $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion',$this->id_inscripcion)->get();
        // delete storage file and database
        foreach($expediente_inscripcion as $exp)
        {
            $expediente = ExpedienteAdmision::join('expediente','expediente_admision.id_expediente','=','expediente.id_expediente')
                                        ->where('expediente_admision.id_expediente_admision', $exp->id_expediente_admision)
                                        ->where(function($query){
                                            $query->where('expediente.expediente_tipo', 0)
                                            ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                                        })
                                        ->first();
            if($expediente === null)
            {
                $exp->delete();
                File::delete($exp->expediente_inscripcion_url);
            }
        }

        // Actualizar eestado del pago
        $pago = Pago::find(auth('inscripcion')->user()->id_pago);
        $pago->pago_estado = 2;
        $pago->save();

        // Registrar datos de usuario del estudiante
        $usuario_estudiante = UsuarioEstudiante::where('usuario_estudiante',$this->documento)->first();
        if($usuario_estudiante === null)
        {
            $usuario_estudiante = new UsuarioEstudiante();
            $usuario_estudiante->usuario_estudiante = $this->documento;
            $usuario_estudiante->usuario_estudiante_password = $inscripcion->inscripcion_codigo;
            $usuario_estudiante->usuario_estudiante_creacion = now();
            $usuario_estudiante->usuario_estudiante_estado = 1;
            $usuario_estudiante->save();
        }
        else
        {
            // falta mostrar mensaje de que el usuario ya existe y de que se le actualizo la contraseña
            $usuario_estudiante->usuario_estudiante_password = $inscripcion->inscripcion_codigo;
            $usuario_estudiante->save();
        }

        // alerta de cuenta regresiva
        $this->dispatchBrowserEvent('alerta_final_registro');

        // redireccionar a la pagina final
        return redirect()->route('inscripcion.pdf-email', ['id' => $this->id_inscripcion]);
    }

    public function render()
    {
        $estado_civil_array = EstadoCivil::where('estado_civil_estado', 1)->get();
        $tipo_discapacidad_array = Discapacidad::where('discapacidad_estado', 1)->get();
        $universidad_array = Universidad::where('universidad_estado', 1)->get();
        $grado_academico_array = GradoAcademico::where('grado_academico_estado', 1)->get();
        $genero_array = Genero::where('genero_estado', 1)->get();
        $tipo_seguimiento_constancia_sunedu = TipoSeguimiento::where('id_tipo_seguimiento', 1)->first();
        return view('livewire.modulo-inscripcion.registro', [
            'estado_civil_array' => $estado_civil_array,
            'tipo_discapacidad_array' => $tipo_discapacidad_array,
            'universidad_array' => $universidad_array,
            'grado_academico_array' => $grado_academico_array,
            'genero_array' => $genero_array,
            'tipo_seguimiento_constancia_sunedu' => $tipo_seguimiento_constancia_sunedu
        ]);
    }
}
