<?php

namespace App\Http\Livewire\ModuloPlataforma\Expedientes;

use App\Jobs\ProcessUpdateFichaInscripcion;
use App\Models\Admision;
use App\Models\Expediente;
use App\Models\ExpedienteInscripcion;
use App\Models\Inscripcion;
use App\Models\Persona;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads; // para subir archivos

    public $filtro_proceso; // variable para el filtro de proceso
    public $admision; // variable para la admision activa
    public $admisiones; // variable para todas las admisiones
    public $expedientes; // variable para los expedientes
    public $expedientes_model; // variable para los expedientes
    public $inscripcion; // variable para la inscripcion
    public $id_inscripcion; // variable para el id de la inscripcion

    // variables para el modal
    public $titulo_modal = 'Nuevo expediente'; // titulo del modal
    public $expediente_id; // variable para el id del expediente
    public $expediente_inscripcion_id; // variable para el id del expediente de la inscripcion
    public $expediente; // variable para el expediente
    public $expediente_nombre = ''; // variable para el nombre del expediente
    public $boton_modal = 'Agregar'; // texto del boton del modal
    public $modo = 'crear'; // variable para el modo del modal
    public $iteration = 0; // variable para la iteracion del modal

    protected $listeners = [
        'expediente_registrado' => 'mount',
    ];

    public function mount()
    {
        $this->admision = Admision::where('estado', 1)->first(); // obtenemos la admision activa
        $this->filtro_proceso = $this->admision->cod_admi; // asignamos el valor de la admision activa a la variable filtro_proceso
        // $this->admisiones = Admision::orderBy('cod_admi', 'desc')->get(); // obtenemos todas las admisiones
        $persona = Persona::where('num_doc', auth('plataforma')->user()->usuario_estudiante)->first(); // obtenemos la persona
        $this->inscripcion = Inscripcion::where('persona_idpersona', $persona->idpersona)->orderBy('id_inscripcion', 'desc')->first(); // obtenemos la inscripcion
        $this->admisiones = Inscripcion::where('persona_idpersona', $persona->idpersona)->orderBy('id_inscripcion', 'desc')->get(); // obtenemos todas las inscripciones de la persona
        $this->id_inscripcion = $this->inscripcion->id_inscripcion; // asignamos el valor de la inscripcion a la variable id_inscripcion
        $this->expedientes = $this->inscripcion->expediente_inscripcion; // obtenemos los expedientes de la inscripcion
        $this->expedientes_model = Expediente::where('expediente_tipo', 0)->orWhere('expediente_tipo', $this->inscripcion->tipo_programa)->get(); // obtenemos los expedientes segun tipo de programa
    }

    public function aplicar_filtro()
    {
        $persona = Persona::where('num_doc', auth('plataforma')->user()->usuario_estudiante)->first(); // obtenemos la persona
        $this->inscripcion = Inscripcion::join('persona', 'persona.idpersona', '=', 'inscripcion.persona_idpersona')
                            ->where('persona.num_doc', $persona->num_doc)->where('admision_cod_admi', $this->filtro_proceso)->first(); // obtenemos la inscripcion
        $this->expedientes_model = Expediente::where('expediente_tipo', 0)->orWhere('expediente_tipo', $this->inscripcion->tipo_programa)->get(); // obtenemos los expedientes segun tipo de programa

        // if ($inscripcion == null) { // si la inscripcion existe
        //     // alerta de error
        //     $this->dispatchBrowserEvent('alerta-expedientes', [
        //         'title' => '¡Error!',
        //         'text' => 'No se encontró la inscripción en el proceso seleccionado.',
        //         'icon' => 'error',
        //         'confirmButtonText' => 'Aceptar',
        //         'color' => 'danger'
        //     ]);
        //     return back();
        // }
        $this->expedientes = ExpedienteInscripcion::where('id_inscripcion', $this->inscripcion->id_inscripcion)->get(); // obtenemos los expedientes de la inscripcion
    }

    public function resetear_filtro()
    {
        $this->mount();
    }

    public function cargar_expediente_inscripcion($id)
    {
        $expediente_inscripcion = ExpedienteInscripcion::find($id); // obtenemos el expediente de la inscripcion
        $this->expediente_id = $expediente_inscripcion->expediente->cod_exp; // asignamos el valor del id del expediente a la variable expediente_id
        $this->expediente_nombre = $expediente_inscripcion->expediente->tipo_doc; // asignamos el valor del nombre del expediente a la variable expediente_nombre
        $this->boton_modal = 'Actualizar'; // asignamos el valor del texto del boton del modal a la variable boton_modal
        $this->titulo_modal = 'Actualizar expediente'; // asignamos el valor del titulo del modal a la variable titulo_modal
        $this->modo = 'editar'; // asignamos el valor del modo del modal a la variable modo
    }

    public function cargar_expediente($id)
    {
        $expediente = Expediente::find($id); // obtenemos el expediente
        $this->expediente_id = $expediente->cod_exp; // asignamos el valor del id del expediente a la variable expediente_id
        $this->expediente_nombre = $expediente->tipo_doc; // asignamos el valor del nombre del expediente a la variable expediente_nombre
        $this->boton_modal = 'Agregar'; // asignamos el valor del texto del boton del modal a la variable boton_modal
        $this->titulo_modal = 'Nuevo expediente'; // asignamos el valor del titulo del modal a la variable titulo_modal
        $this->modo = 'crear'; // asignamos el valor del modo del modal a la variable modo
    }

    public function limpiar_expediente()
    {
        $this->expediente_id = ''; // asignamos el valor del id del expediente a la variable expediente_id
        $this->expediente_nombre = ''; // asignamos el valor del nombre del expediente a la variable expediente_nombre
        $this->boton_modal = 'Agregar'; // asignamos el valor del texto del boton del modal a la variable boton_modal
        $this->titulo_modal = 'Nuevo expediente'; // asignamos el valor del titulo del modal a la variable titulo_modal
        $this->modo = 'crear'; // asignamos el valor del modo del modal a la variable modo
        $this->iteration++; // incrementamos la iteracion del modal
    }

    public function registrar_expediente()
    {
        $this->validate([
            'expediente' => 'required|file|max:10240|mimetypes:application/octet-stream,application/pdf,application/x-pdf,application/x-download,application/force-download', // validamos el expediente
        ]); // validamos los campos

        if($this->modo == 'crear')
        {
            if($this->expediente != null)
            {
                $path = 'Posgrado/' . $this->admision->admision . '/' . $this->inscripcion->persona->num_doc . '/' . 'Expedientes/'; // asignamos el valor del path a la variable path
                $nombre = $this->expediente_nombre . '.pdf'; // asignamos el valor del nombre del expediente a la variable nombre
                $nombreDB = $path . $nombre; // asignamos el valor del nombre del expediente para la base de datos a la variable nombreDB
                $this->expediente->storeAs($path, $nombre, 'files_publico'); // almacenamos el expediente en el servidor

                $expediente_inscripcion = new ExpedienteInscripcion(); // creamos una nueva instancia de expediente_inscripcion
                $expediente_inscripcion->nom_exped = $nombreDB; // asignamos el valor del nombre del expediente para la base de datos a la variable nom_exped
                $expediente_inscripcion->estado = 'enviado'; // asignamos el valor del estado del expediente a la variable estado
                $expediente_inscripcion->expediente_cod_exp = $this->expediente_id; // asignamos el valor del id del expediente a la variable expediente_cod_exp
                $expediente_inscripcion->id_inscripcion = $this->id_inscripcion; // asignamos el valor del id de la inscripcion a la variable id_inscripcion
                $expediente_inscripcion->save(); // guardamos el expediente de la inscripcion

                // alerta de exito
                $this->dispatchBrowserEvent('alerta-expedientes', [
                    'title' => '¡Exito!',
                    'text' => 'El expediente se registró correctamente.',
                    'icon' => 'success',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'success'
                ]);
            }
            else
            {
                // alerta de error
                $this->dispatchBrowserEvent('alerta-expedientes', [
                    'title' => '¡Error!',
                    'text' => 'No se encontró el expediente.',
                    'icon' => 'error',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'danger'
                ]);
            }
        }
        else if($this->modo == 'editar')
        {
            if($this->expediente != null)
            {
                $path = 'Posgrado/' . $this->admision->admision . '/' . $this->inscripcion->persona->num_doc . '/' . 'Expedientes/'; // asignamos el valor del path a la variable path
                $nombre = $this->expediente_nombre . '.pdf'; // asignamos el valor del nombre del expediente a la variable nombre
                $nombreDB = $path . $nombre; // asignamos el valor del nombre del expediente para la base de datos a la variable nombreDB
                $this->expediente->storeAs($path, $nombre, 'files_publico'); // almacenamos el expediente en el servidor

                $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $this->id_inscripcion)->where('expediente_cod_exp', $this->expediente_id)->first(); // obtenemos el expediente de la inscripcion
                $expediente_inscripcion->nom_exped = $nombreDB; // asignamos el valor del nombre del expediente para la base de datos a la variable nom_exped
                $expediente_inscripcion->estado = 'enviado'; // asignamos el valor del estado del expediente a la variable estado
                $expediente_inscripcion->fecha_entre = now(); // asignamos el valor de la fecha de entrega del expediente a la variable fecha_entre
                $expediente_inscripcion->save(); // guardamos el expediente de la inscripcion

                // alerta de exito
                $this->dispatchBrowserEvent('alerta-expedientes', [
                    'title' => '¡Exito!',
                    'text' => 'El expediente se actualizó correctamente.',
                    'icon' => 'success',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'success'
                ]);
            }
            else
            {
                // alerta de error
                $this->dispatchBrowserEvent('alerta-expedientes', [
                    'title' => '¡Error!',
                    'text' => 'No se encontró el expediente.',
                    'icon' => 'error',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'danger'
                ]);
            }
        }

        $this->limpiar_expediente(); // limpiamos los campos del expediente
        $this->emit('expediente_registrado'); // emitimos el evento expedienteRegistrado
        $this->dispatchBrowserEvent('modal_expediente', ['action' => 'hide']); // ocultamos el modal

        ProcessUpdateFichaInscripcion::dispatch($this->inscripcion); // despachamos el proceso de actualizacion de la ficha de inscripcion
    }

    public function render()
    {
        $fecha_fin_admision = Carbon::parse($this->admision->fecha_fin); // convertimos la fecha de fin de admision a formato carbon
        $fecha_fin_admision = $fecha_fin_admision->locale('es')->isoFormat('D [de] MMMM [de] YYYY'); // formateamos la fecha de fin de admision a español
        return view('livewire.modulo-plataforma.expedientes.index', [
            'fecha_fin_admision' => $fecha_fin_admision
        ]);
    }
}
