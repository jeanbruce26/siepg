<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Mail\EmailFichaInscripcion;
use App\Models\Admision;
use App\Models\Departamento;
use App\Models\Discapacidad;
use App\Models\Distrito;
use App\Models\EstadoCivil;
use App\Models\Expediente;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\ExpedienteTipoSeguimiento;
use App\Models\GradoAcademico;
use App\Models\HistorialInscripcion;
use App\Models\Inscripcion;
use App\Models\InscripcionPago;
use App\Models\Mencion;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\Programa;
use App\Models\Provincia;
use App\Models\Sede;
use App\Models\Subprograma;
use App\Models\UbigeoPersona;
use App\Models\Universidad;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class Registro extends Component
{
    use WithFileUploads; // sirve para subir archivos

    public $paso = 0; // variable para el paso de la vista
    public $total_pasos = 3; // variable para el total de pasos de la vista

    public $id_inscripcion; // variable para el id de la inscripcion
    public $id_persona, $documento, $paterno, $materno, $nombres, $fecha_nacimiento, $genero, $estado_civil, $grado_academico, $especialidad_carrera, $discapacidad, $direccion, $celular, $celular_opcional, $año_egreso, $email, $email_opcional, $universidad, $centro_trabajo, $pais; // variables para el formulario de registro de información personal
    public $sede, $sede_array, $programa, $programa_nombre, $programa_array, $subprograma, $subprograma_array, $mencion, $mencion_mostrar, $mencion_array; // variables para el formulario de registro de información de programa
    public $departamento_direccion, $departamento_direccion_array, $provincia_direccion, $provincia_direccion_array, $distrito_direccion, $distrito_direccion_array; // variables para el formulario de registro de información de dirección
    public $departamento_nacimiento, $departamento_nacimiento_array, $provincia_nacimiento, $provincia_nacimiento_array, $distrito_nacimiento, $distrito_nacimiento_array; // variables para el formulario de registro de información de nacimiento

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
        $this->documento = auth('inscripcion')->user()->dni;
        $persona = Persona::where('num_doc', $this->documento)->first();
        $this->sede_array = Sede::where('sede_estado', 1)->get();
        $this->programa_array = Collect();
        $this->subprograma_array = Collect();
        $this->mencion_array = Collect();
        if($persona)
        {
            $this->id_persona = $persona->idpersona;
            $this->paterno = $persona->apell_pater;
            $this->materno = $persona->apell_mater;
            $this->nombres = $persona->nombres;
            $this->fecha_nacimiento = $persona->fecha_naci;
            $this->genero = $persona->sexo;
            $this->estado_civil = $persona->est_civil_cod_est;
            $this->grado_academico = $persona->id_grado_academico;
            $this->especialidad_carrera = $persona->especialidad;
            $this->discapacidad = $persona->discapacidad_cod_disc;
            $this->direccion = $persona->direccion;
            $this->celular = $persona->celular1;
            $this->celular_opcional = $persona->celular2;
            $this->año_egreso = $persona->año_egreso;
            $this->email = $persona->email;
            $this->email_opcional = $persona->email2;
            $this->universidad = $persona->univer_cod_uni;
            $this->centro_trabajo = $persona->centro_trab;
            $this->pais = $persona->pais_extra;
            $ubi_dire = UbigeoPersona::where('persona_idpersona',$persona->idpersona)->where('tipo_ubigeo_cod_tipo',1)->first();
            $id_distrito_dire = $ubi_dire->id_distrito;
            $pro = Distrito::where('id',$id_distrito_dire)->first();
            $id_provincia_dire = $pro->id_provincia;
            $dep = Provincia::where('id',$id_provincia_dire)->first();
            $id_departamento_dire = $dep->id_departamento;
            $ubi_naci = UbigeoPersona::where('persona_idpersona',$persona->idpersona)->where('tipo_ubigeo_cod_tipo',2)->first();
            $id_distrito_naci = $ubi_naci->id_distrito;
            $pro_naci = Distrito::where('id',$id_distrito_naci)->first();
            $id_provincia_naci = $pro_naci->id_provincia;
            $dep_naci = Provincia::where('id',$id_provincia_naci)->first();
            $id_departamento_naci = $dep_naci->id_departamento;
            $this->departamento_direccion_array = Departamento::all();
            $this->departamento_direccion = $id_departamento_dire;
            $this->provincia_direccion_array = collect();
            $this->distrito_direccion_array = collect();
            $this->provincia_direccion = $id_provincia_dire;
            $this->provincia_direccion_array = Provincia::where('id_departamento', $id_departamento_dire)->get();
            $this->distrito_direccion = $id_distrito_dire;
            $this->distrito_direccion_array = Distrito::where('id_provincia', $id_provincia_dire)->get();
            $this->departamento_nacimiento_array = Departamento::all();
            $this->departamento_nacimiento = $id_departamento_naci;
            $this->provincia_nacimiento_array = collect();
            $this->distrito_nacimiento_array = collect();
            $this->provincia_nacimiento = $id_provincia_naci;
            $this->provincia_nacimiento_array = Provincia::where('id_departamento', $id_departamento_naci)->get();
            $this->distrito_nacimiento = $id_distrito_naci;
            $this->distrito_nacimiento_array = Distrito::where('id_provincia', $id_provincia_naci)->get();
            $this->modo = 'update';
        }else
        {
            $this->departamento_direccion_array = Departamento::all();
            $this->provincia_direccion_array = collect();
            $this->distrito_direccion_array = collect();
            $this->departamento_nacimiento_array = Departamento::all();
            $this->provincia_nacimiento_array = collect();
            $this->distrito_nacimiento_array = collect();
        }
    }

    public function updated($propertyName)
    {
        if($this->paso == 1)
        {
            $this->validateOnly($propertyName, [
                'sede' => 'required|numeric',
                'programa' => 'required|numeric',
                'subprograma' => 'required|numeric',
                'mencion' => 'required|numeric',
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
                'pais' => 'nullable|max:50',
                'departamento_direccion' => 'required|numeric',
                'provincia_direccion' => 'required|numeric',
                'distrito_direccion' => 'required|numeric',
                'departamento_nacimiento' => 'required|numeric',
                'provincia_nacimiento' => 'required|numeric',
                'distrito_nacimiento' => 'required|numeric',
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
                'sede' => 'required|numeric',
                'programa' => 'required|numeric',
                'subprograma' => 'required|numeric',
                'mencion' => 'required|numeric',
            ]);
        }
        elseif($this->paso === 2)
        {
            $this->resetErrorBag();
            $this->resetValidation();
            if($this->distrito_nacimiento == 1893)
            {
                $this->validate([
                    'nombres' => 'required|string|max:50',
                    'paterno' => 'required|string|max:50',
                    'materno' => 'required|string|max:50',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|string',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|string|max:50',
                    'discapacidad' => 'nullable|numeric',
                    'direccion' => 'required|string|max:50',
                    'celular' => 'required|numeric',
                    'celular_opcional' => 'nullable|numeric',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email',
                    'email_opcional' => 'nullable|email',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|string|max:50',
                    'pais' => 'required',
                    'departamento_direccion' => 'required|numeric',
                    'provincia_direccion' => 'required|numeric',
                    'distrito_direccion' => 'required|numeric',
                    'departamento_nacimiento' => 'required|numeric',
                    'provincia_nacimiento' => 'required|numeric',
                    'distrito_nacimiento' => 'required|numeric',
                ]);
            }
            else
            {
                $this->validate([
                    'nombres' => 'required|string|max:255',
                    'paterno' => 'required|string|max:255',
                    'materno' => 'required|string|max:255',
                    'fecha_nacimiento' => 'required|date',
                    'genero' => 'required|string',
                    'estado_civil' => 'required|numeric',
                    'grado_academico' => 'required|numeric',
                    'especialidad_carrera' => 'required|string',
                    'discapacidad' => 'nullable|numeric',
                    'direccion' => 'required|string|max:255',
                    'celular' => 'required|numeric',
                    'celular_opcional' => 'nullable|numeric',
                    'año_egreso' => 'required|numeric',
                    'email' => 'required|email',
                    'email_opcional' => 'nullable|email',
                    'universidad' => 'required|numeric',
                    'centro_trabajo' => 'required|string|max:255',
                    'pais' => 'nullable',
                    'departamento_direccion' => 'required|numeric',
                    'provincia_direccion' => 'required|numeric',
                    'distrito_direccion' => 'required|numeric',
                    'departamento_nacimiento' => 'required|numeric',
                    'provincia_nacimiento' => 'required|numeric',
                    'distrito_nacimiento' => 'required|numeric',
                ]);
            }
        }
    }

    public function updatedSede($sede)
    {
        $this->programa_array = Programa::where('id_sede',$sede)->get();
        $this->subprograma_array = collect();
        $this->mencion_array = collect();
        $this->programa = null;
        $this->subprograma = null;
        $this->mencion = null;
        $this->expediente_array = null;
    }

    public function updatedPrograma($programa)
    {
        $this->subprograma_array = Subprograma::where('id_programa',$programa)->where('estado',1)->get();
        $this->programa_nombre = Programa::where('id_programa',$programa)->first();
        if($this->programa_nombre){
            $this->programa_nombre = ucfirst(strtolower($this->programa_nombre->descripcion_programa));
        }else{
            $this->programa_nombre = null;
        }
        $this->mencion_array = collect();
        $this->subprograma = null;
        $this->mencion = null;
        $programa = Programa::where('id_programa',$programa)->first();
        if($programa){
            $programa = $programa->descripcion_programa;
            if($programa == 'MAESTRIA'){
                $this->mostrar_tipo_expediente = 1;
            }else if($programa == 'DOCTORADO'){
                $this->mostrar_tipo_expediente = 2;
            }
            $this->expediente_array = Expediente::where('estado', 1)
                            ->where(function($query){
                                $query->where('expediente_tipo', 0)
                                    ->orWhere('expediente_tipo', $this->mostrar_tipo_expediente);
                            })
                            ->get();
        }else{
            $this->expediente_array = null;
        }
    }

    public function updatedSubPrograma($subprograma)
    {
        $this->mencion_array = Mencion::where('id_subprograma',$subprograma)->where('mencion_estado',1)->get();
        if($this->mencion_array->count() == 1){
            $mencion = Mencion::where('id_subprograma',$subprograma)->first();
            if($mencion->mencion == null){
                $this->mencion = $mencion->id_mencion;
                $this->mencion_mostrar = 1;
            }else{
                $this->mencion_mostrar = 0;
            }
        }
    }

    public function updatedDepartamentoDireccion($departamento_direccion)
    {
        $this->provincia_direccion_array = Provincia::where('id_departamento',$departamento_direccion)->get();
        $this->distrito_direccion = null;
        $this->distrito_direccion_array = collect();
    }

    public function updatedProvinciaDireccion($provincia_direccion)
    {
        $this->distrito_direccion_array = Distrito::where('id_provincia',$provincia_direccion)->get();
    }

    public function updatedDepartamentoNacimiento($departamento_nacimiento)
    {
        $this->provincia_nacimiento_array = Provincia::where('id_departamento',$departamento_nacimiento)->get();
        $this->distrito_nacimiento = null;
        $this->distrito_nacimiento_array = collect();
    }

    public function updatedProvinciaNacimiento($provincia_nacimiento)
    {
        $this->distrito_nacimiento_array = Distrito::where('id_provincia',$provincia_nacimiento)->get();
    }

    public function limpiar_modal_expediente()
    {
        $this->reset([
            'expediente',
        ]);
        $this->iteration++;
    }

    public function cargar_modal_expediente(Expediente $expediente)
    {
        $this->limpiar_modal_expediente();
        $this->expediente_nombre = $expediente->tipo_doc;
        $this->id_expediente = $expediente->cod_exp;
        $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $this->id_inscripcion)->where('expediente_cod_exp', $expediente->cod_exp)->first();
        if($expediente_inscripcion){
            $this->modo_expediente = 'edit';
        }else{
            $this->modo_expediente = 'create';
        }
    }

    public function registrar_expediente()
    {
        $expediente_model = Expediente::where('estado', 1)->where('cod_exp', $this->id_expediente)->first();

        // Validar si el expediente es requerido
        if($expediente_model->requerido == 1 && $this->modo_expediente == 'create')
        {
            $this->validate([
                'expediente' => 'required|file|max:10240|mimetypes:application/octet-stream,application/pdf,application/x-pdf,application/x-download,application/force-download',
            ]);
        }
        else if($expediente_model->requerido == 1 && $this->modo_expediente == 'edit')
        {
            $this->validate([
                'expediente' => 'nullable|file|max:10240|mimetypes:application/octet-stream,application/pdf,application/x-pdf,application/x-download,application/force-download',
            ]);
        }
        else if($expediente_model->requerido != 1 && $this->modo_expediente == 'edit')
        {
            $this->validate([
                'expediente' => 'nullable|file|max:10240|mimetypes:application/octet-stream,application/pdf,application/x-pdf,application/x-download,application/force-download',
            ]);
        }

        // numero de documento
        $numero_documento = auth('inscripcion')->user()->dni;

        // obtenemos el año de admision
        $admision_year = Admision::where('estado',1)->first()->admision_year;

        if($this->expediente != null)
        {
            $path = 'files/' . $numero_documento . '/' . $admision_year. '/' . 'expedientes' . '/';
            $filename = $expediente_model->exp_nombre.".pdf";
            $nombreDB = $path.$filename;
            $this->expediente->storeAs($path, $filename, 'files_publico');

            if($this->modo_expediente == 'create')
            {
                $expediente_inscripcion = new ExpedienteInscripcion();
                $expediente_inscripcion->nom_exped = $nombreDB;
                $expediente_inscripcion->estado = 'enviado';
                $expediente_inscripcion->expediente_cod_exp = $this->id_expediente;
                $expediente_inscripcion->id_inscripcion = $this->id_inscripcion;
                $expediente_inscripcion->save();
            }
            else if($this->modo_expediente == 'edit')
            {
                $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $this->id_inscripcion)->where('expediente_cod_exp', $this->id_expediente)->first();
                $expediente_inscripcion->nom_exped = $nombreDB;
                $expediente_inscripcion->estado = 'enviado';
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

    public function declaracion_jurada()
    {
        if($this->declaracion_jurada == false)
        {
            $this->declaracion_jurada = true;
        }
        else
        {
            $this->declaracion_jurada = false;
        }
    }

    public function updatedDeclaracionJurada($declaracion_jurada)
    {
        $this->validate([
            'declaracion_jurada' => 'accepted',
        ]);
    }

    public function registrar_inscripcion()
    {
        // validamos si se subieron los expedientes completos
        // buscamos los expedientes que pertenecen al tipo de expediente que se esta mostrando
        $expediente = Expediente::where('estado', 1)
                            ->where(function($query){
                                $query->where('expediente_tipo', 0)
                                    ->orWhere('expediente_tipo', $this->mostrar_tipo_expediente);
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

        // verificamos el pais de procedencia
        if ($this->distrito_nacimiento){
            if ($this->distrito_nacimiento == 1893){
                $this->pais = $this->pais;
            }else{
                $this->pais = 'Perú';
            }
        }

        // reemplazar tildes por letras sin tildes en los campos de apellido paterno, apellido materno y nombres
        $this->nombres = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->nombres);
        $this->paterno = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->paterno);
        $this->materno = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->materno);
        $this->direccion = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->direccion);
        $this->centro_trabajo = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $this->centro_trabajo);

        // registro de formulario de inscripcion
        $this->documento = auth('inscripcion')->user()->dni;
        $persona = Persona::where('num_doc', $this->documento)->first();
        if($persona)
        {
            // actualizar datos de persona
            $persona->apell_pater = strtoupper($this->paterno);
            $persona->apell_mater = strtoupper($this->materno);
            $persona->nombres = strtoupper($this->nombres);
            $persona->nombre_completo = strtoupper($this->paterno.' '.$this->materno.' '.$this->nombres);
            $persona->direccion = strtoupper($this->direccion);
            $persona->celular1 = $this->celular;
            $persona->celular2 = $this->celular_opcional;
            $persona->sexo = $this->genero;
            $persona->fecha_naci = $this->fecha_nacimiento;
            $persona->email = $this->email;
            $persona->email2 = $this->email_opcional;
            $persona->año_egreso = $this->año_egreso;
            $persona->centro_trab = strtoupper($this->centro_trabajo);
            $persona->discapacidad_cod_disc = $this->discapacidad;
            $persona->est_civil_cod_est = $this->estado_civil;
            $persona->univer_cod_uni = $this->universidad;
            $persona->id_grado_academico = $this->grado_academico;
            $persona->especialidad = strtoupper($this->especialidad_carrera);
            $persona->pais_extra = strtoupper($this->pais);
            $persona->save();

            // asignar id de persona
            $this->id_persona = $persona->idpersona;

            // actualizar datos de ubigeo de direccion
            $ubigeo_distrito = Distrito::select('ubigeo')->where('id',$this->distrito_direccion)->first();
            $ubigeo_direccion = UbigeoPersona::where('tipo_ubigeo_cod_tipo',1)->where('persona_idpersona',$this->id_persona)->first();
            $ubigeo_direccion->id_distrito = $this->distrito_direccion;
            $ubigeo_direccion->ubigeo = $ubigeo_distrito->ubigeo;
            $ubigeo_direccion->save();

            // actualizar datos de ubigeo de nacimiento
            $ubigeo_nacimiento = Distrito::select('ubigeo')->where('id',$this->distrito_nacimiento)->first();
            $ubigeo_nacimiento_persona = UbigeoPersona::where('tipo_ubigeo_cod_tipo',2)->where('persona_idpersona',$this->id_persona)->first();
            $ubigeo_nacimiento_persona->id_distrito = $this->distrito_nacimiento;
            $ubigeo_nacimiento_persona->ubigeo = $ubigeo_nacimiento->ubigeo;
            $ubigeo_nacimiento_persona->save();
        }
        else
        {
            // registrar datos de persona
            $persona = new Persona();
            $persona->num_doc = $this->documento;
            $persona->apell_pater = strtoupper($this->paterno);
            $persona->apell_mater = strtoupper($this->materno);
            $persona->nombres = strtoupper($this->nombres);
            $persona->nombre_completo = strtoupper($this->paterno.' '.$this->materno.' '.$this->nombres);
            $persona->direccion = strtoupper($this->direccion);
            $persona->celular1 = $this->celular;
            $persona->celular2 = $this->celular_opcional;
            $persona->sexo = $this->genero;
            $persona->fecha_naci = $this->fecha_nacimiento;
            $persona->email = $this->email;
            $persona->email2 = $this->email_opcional;
            $persona->año_egreso = $this->año_egreso;
            $persona->centro_trab = strtoupper($this->centro_trabajo);
            $persona->discapacidad_cod_disc = $this->discapacidad;
            $persona->est_civil_cod_est = $this->estado_civil;
            $persona->univer_cod_uni = $this->universidad;
            $persona->id_grado_academico = $this->grado_academico;
            $persona->especialidad = strtoupper($this->especialidad_carrera);
            $persona->pais_extra = strtoupper($this->pais);
            $persona->save();

            // asignar id de persona
            $this->id_persona = $persona->idpersona;

            // registrar datos de ubigeo de direccion
            $ubigeo_distrito = Distrito::select('ubigeo')->where('id',$this->distrito_direccion)->first();
            $ubigeo_persona_distrito = new UbigeoPersona();
            $ubigeo_persona_distrito->id_distrito = $this->distrito_direccion;
            $ubigeo_persona_distrito->tipo_ubigeo_cod_tipo = 1;
            $ubigeo_persona_distrito->persona_idpersona = $this->id_persona;
            $ubigeo_persona_distrito->ubigeo = $ubigeo_distrito->ubigeo;
            $ubigeo_persona_distrito->save();

            // registrar datos de ubigeo de nacimiento
            $ubigeo_nacimiento = Distrito::select('ubigeo')->where('id',$this->distrito_nacimiento)->first();
            $ubigeo_persona_nacimiento = new UbigeoPersona();
            $ubigeo_persona_nacimiento->id_distrito = $this->distrito_nacimiento;
            $ubigeo_persona_nacimiento->tipo_ubigeo_cod_tipo = 2;
            $ubigeo_persona_nacimiento->persona_idpersona = $this->id_persona;
            $ubigeo_persona_nacimiento->ubigeo = $ubigeo_nacimiento->ubigeo;
            $ubigeo_persona_nacimiento->save();
        }

        // Actulaizacion de datos de la inscripcion
        $inscripcion = Inscripcion::find($this->id_inscripcion);
        $inscripcion->persona_idpersona = $this->id_persona;
        $inscripcion->id_mencion = $this->mencion;
        $inscripcion->fecha_inscripcion = now();
        $inscripcion->tipo_programa = $this->mostrar_tipo_expediente;
        $inscripcion->save();

        // Registro del historial de inscripcion
        $historial_inscripcion = new HistorialInscripcion();
        $historial_inscripcion->persona_documento = $this->documento;
        $historial_inscripcion->id_inscripcion = $this->id_inscripcion;
        $historial_inscripcion->admision = Admision::where('estado',1)->first()->admision;
        $historial_inscripcion->programa = $this->mencion;
        $historial_inscripcion->historial_inscripcion_fecha = now();
        $historial_inscripcion->admitido = 0;
        $historial_inscripcion->save();

        // Sirve para asignar el seguimiento del expediente
        if($this->check_expediente == true)
        {
            $exp_ins = ExpedienteInscripcion::join('expediente','ex_insc.expediente_cod_exp','=','expediente.cod_exp')
                                                ->where('ex_insc.id_inscripcion',$this->id_inscripcion)
                                                ->where(function($query){
                                                    $query->where('expediente.expediente_tipo', 0)
                                                        ->orWhere('expediente.expediente_tipo', $this->mostrar_tipo_expediente);
                                                })
                                                ->get();
            $exp_seg = ExpedienteTipoSeguimiento::join('expediente','expediente_tipo_seguimiento.cod_exp','=','expediente.cod_exp')
                                                ->where('expediente_tipo_seguimiento_estado', 1)
                                                ->where('tipo_seguimiento', 1)
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
                    if($exp->expediente_cod_exp == $seg->cod_exp)
                    {
                        array_push($array_seguimiento, $exp->cod_ex_insc);
                    }
                }
            }
            // Registrar datos del seguimiento del expediente de inscripcion
            foreach ($array_seguimiento as $item)
            {
                $seguimiento_exp_ins = new ExpedienteInscripcionSeguimiento();
                $seguimiento_exp_ins->cod_ex_insc = $item;
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
            $expediente = Expediente::where('cod_exp', $exp->expediente_cod_exp)
                                        ->where(function($query){
                                            $query->where('expediente_tipo', 0)
                                                ->orWhere('expediente_tipo', $this->mostrar_tipo_expediente);
                                        })
                                        ->first();
            if($expediente === null)
            {
                $exp->delete();
                File::delete($exp->nom_exped);
            }
        }

        // Actualizar eestado del pago
        $pago = Pago::find(auth('inscripcion')->user()->pago_id);
        $pago->estado = 2;
        $pago->save();

        // alerta de cuenta regresiva
        $this->dispatchBrowserEvent('alerta_final_registro');

        // cerrar sesion
        auth('inscripcion')->logout();

        // redireccionar a la pagina final
        return redirect()->route('inscripcion.pdf-email', ['id' => $this->id_inscripcion]);

        // // Registrar datos del pago de la inscripcion en el pdf y enviar al correo
        // $this->ficha_inscripcion_email($this->id_inscripcion);
    }

    // public function ficha_inscripcion_email($id)
    // {
    //     $inscripcion = Inscripcion::where('id_inscripcion',$id)->first(); // Datos de la inscripcion

    //     $inscripcion_pago = InscripcionPago::where('inscripcion_id',$id)->first();
    //     $monto_pago = $inscripcion_pago->pago->monto; // Monto del pago

    //     $admision = Admision::where('estado',1)->first()->admision; // Proceso de admision actual
    //     $admision_year = Admision::where('estado',1)->first()->admision_year; // Año del proceso de admision actual

    //     $fecha_actual = date('h:i:s a d/m/Y', strtotime($inscripcion->fecha_inscripcion)); // Fecha de inscripcion
    //     $fecha_actual2 = date('d-m-Y', strtotime($inscripcion->fecha_inscripcion)); // Fecha de inscripcion
    //     $mencion = Mencion::where('id_mencion',$inscripcion->id_mencion)->first(); // Mencion de la inscripcion
    //     $inscripcion_codigo = Inscripcion::where('id_inscripcion',$id)->first()->inscripcion_codigo;
    //     $tiempo = 6;
    //     $valor = '+ '.intval($tiempo).' month';
    //     setlocale( LC_ALL,"es_ES@euro","es_ES","esp" );
    //     $final = strftime('%d de %B del %Y', strtotime($fecha_actual2.$valor));
    //     $persona = Persona::where('idpersona', $inscripcion->persona_idpersona)->first();
    //     $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion',$id)->get();
    //     $expediente = Expediente::where('estado', 1)
    //                 ->where(function($query) use ($inscripcion){
    //                     $query->where('expediente_tipo', 0)
    //                         ->orWhere('expediente_tipo', $inscripcion->tipo_programa);
    //                 })
    //                 ->get();

    //     // verificamos si tiene expediente en seguimientos
    //     $seguimiento_count = ExpedienteInscripcionSeguimiento::join('ex_insc', 'ex_insc.cod_ex_insc', '=', 'expediente_inscripcion_seguimiento.cod_ex_insc')
    //                                                     ->where('ex_insc.id_inscripcion', $id)
    //                                                     ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', 1)
    //                                                     ->where('expediente_inscripcion_seguimiento.expediente_inscripcion_seguimiento_estado', 1)
    //                                                     ->count();

    //     $data = [
    //         'persona' => $persona,
    //         'fecha_actual' => $fecha_actual,
    //         'mencion' => $mencion,
    //         'admision' => $admision,
    //         'inscripcion_pago' => $inscripcion_pago,
    //         'inscripcion' => $inscripcion,
    //         'inscripcion_codigo' => $inscripcion_codigo,
    //         'monto_pago' => $monto_pago,
    //         'final' => $final,
    //         'expediente_inscripcion' => $expediente_inscripcion,
    //         'expediente' => $expediente,
    //         'seguimiento_count' => $seguimiento_count
    //     ];

    //     $nombre_pdf = 'ficha-inscripcion-' . Str::slug($persona->nombre_completo, '-') . '.pdf';
    //     $path = 'files/' . $this->documento . '/' . $admision_year . '/' . 'expedientes/';
    //     $pdf = PDF::loadView('modulo-inscripcion.ficha-inscripcion', $data)->save(public_path($path.$nombre_pdf));
    //     $pdf2 = PDF::loadView('modulo-inscripcion.ficha-inscripcion', $data);
    //     $pdf_email = $pdf2->output();

    //     $inscripcion = Inscripcion::find($id);
    //     $inscripcion->inscripcion = $path . $nombre_pdf;
    //     $inscripcion->save();

    //     // enviar ficha de inscripcion por correo
    //     $detalle = [
    //         'nombre' => $persona->nombre_completo,
    //         'admision' => $admision,
    //         'email' => $persona->email,
    //     ];

    //     Mail::send('modulo-inscripcion.email', $detalle, function ($message) use ($detalle, $pdf_email, $nombre_pdf) {
    //         $message->to($detalle['email'])
    //                 ->subject('Ficha de Inscripción')
    //                 ->attachData($pdf_email, $nombre_pdf, ['mime' => 'application/pdf']);
    //     });

    //     // cerrar sesion
    //     auth('inscripcion')->logout();

    //     // redireccionar a la pagina final
    //     return redirect()->route('inscripcion.gracias', ['id' => $id]);
    // }

    public function render()
    {
        $estado_civil_array = EstadoCivil::all();
        $tipo_discapacidad_array = Discapacidad::all();
        $universidad_array = Universidad::all();
        $grado_academico_array = GradoAcademico::all();
        return view('livewire.modulo-inscripcion.registro', [
            'estado_civil_array' => $estado_civil_array,
            'tipo_discapacidad_array' => $tipo_discapacidad_array,
            'universidad_array' => $universidad_array,
            'grado_academico_array' => $grado_academico_array,
        ]);
    }
}
