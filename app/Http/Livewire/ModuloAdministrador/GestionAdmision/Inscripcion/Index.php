<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionAdmision\Inscripcion;

use App\Jobs\ObservarInscripcionJob;
use App\Models\Admision;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Inscripcion;
use App\Models\Modalidad;
use App\Models\Programa;
use App\Models\ProgramaProceso;
use App\Models\TipoSeguimiento;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; //paginacion de bootstrap

    protected $queryString = [
        'search' => ['except' => ''],
        'modalidadFiltro' => ['except' => ''],
        'procesoFiltro' => ['except' => ''],
        'programaFiltro' => ['except' => ''],
        'seguimientoFiltro' => ['except' => ''],
    ];

    public $search = '';

    //Variables para el filtro de Inscripión
    public $procesoFiltro; //Para la búsqueda de inscripciones por proceso
    public $proceso_filtro; //Para el filtro de inscripciones por proceso
    public $modalidadFiltro; //Para la búsqueda de inscripciones por modalidad
    public $modalidad_filtro; //Para el filtro de inscripciones por modalidad
    public $programaFiltro; //Para la búsqueda de inscripciones por programa
    public $programa_filtro; //Para el filtro de inscripciones por programa
    public $seguimientoFiltro; //Para la búsqueda de inscripciones por seguimiento
    public $seguimiento_filtro; //Para el filtro de inscripciones por seguimiento
    public $mesFiltro;
    public $mes_filtro;
    //variables
    public $id_inscripcion;
    public $modalidad;
    public $programa;
    public $programasModal; //Para mostrar los programas en el modal

    // expeidentes de la inscripcion
    public $expedientes = [];

    // estado de la inscripcion
    public $estado;
    public $observacion_inscripcion;

    //Para mapear el mes al filtrar
    public $meses = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Setiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];

    protected $listeners = [
        'render', 'cambiarEstado', 'cambiarSeguimiento', 'reservarPago'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'id_inscripcion' => 'required',
            'modalidad' => 'required',
            'programa' => 'required',
        ]);
    }

    public function limpiar()
    {
        $this->reset(
            'id_inscripcion',
            'estado',
        );
    }

    //Limpiamos los filtros
    public function resetear_filtro()
    {
        $this->reset(
            'procesoFiltro',
            'programaFiltro',
            'seguimientoFiltro',
            'modalidadFiltro',
            'mesFiltro',
            'proceso_filtro',
            'programa_filtro',
            'seguimiento_filtro',
            'modalidad_filtro',
            'mes_filtro'
        );
    }

    //Asignamos los filtros
    public function filtrar()
    {
        $this->procesoFiltro = $this->proceso_filtro;
        $this->modalidadFiltro = $this->modalidad_filtro;
        $this->programaFiltro = $this->programa_filtro;
        $this->seguimientoFiltro = $this->seguimiento_filtro;
        $this->mesFiltro = $this->mes_filtro;
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
    public function alertaInscripcion($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-inscripcion', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Mostar modal de confirmacion para cambiar el estado del programa
    public function cargarAlertaEstado(Inscripcion $inscripcion)
    {
        $this->alertaConfirmacion('¿Estás seguro?', '¿Desea cambiar el estado de la inscripción de ' . $inscripcion->persona->nombre_completo . '?', 'question', 'Modificar', 'Cancelar', 'primary', 'danger', 'cambiarEstado', $inscripcion->id_inscripcion);
    }

    //Cambiar el estado de la inscripción
    public function cambiarEstado($id)
    {
        $inscripcion = Inscripcion::find($id);
        if ($inscripcion->inscripcion_estado == 1) { //Si el estado es activo(1), se cambia a inactivo(0)
            $inscripcion->inscripcion_estado = 0;
        } else { //Si el estado es inactivo(0), se cambia a activo(1)
            $inscripcion->inscripcion_estado = 1;
        }

        $inscripcion->save();
        $this->alertaInscripcion('¡Exito!', 'El estado de la inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido actualizado satisfactoriamente', 'success', 'Aceptar', 'success');
    }

    //Cargamos los datos de la inscripción para mostrarlos en el modal
    public function cargarInscripcion(Inscripcion $inscripcion)
    {
        $admision = Admision::find($inscripcion->programa_proceso->id_admision);
        $this->id_inscripcion = $inscripcion->id_inscripcion;
        $this->modalidad = $inscripcion->programa_proceso->programa_plan->programa->modalidad->id_modalidad;
        $this->programa = $inscripcion->programa_proceso->programa_plan->programa->id_programa;
        $this->programasModal = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', 'programa_plan.id_programa')
            ->where('programa.id_modalidad', $this->modalidad)
            ->where('programa_plan.programa_plan_estado', 1)
            ->where('programa_proceso.id_admision', $admision->id_admision)
            ->where('programa_proceso.programa_proceso_estado', 1)
            ->where('programa_plan.programa_plan_estado', 1)
            ->get();
    }

    //Actualizar el programa de la inscripción
    public function actualizarInscripcion()
    {
        //Validar que los campos no esten vacios
        $this->validate([
            'id_inscripcion' => 'required',
            'modalidad' => 'required',
            'programa' => 'required',
        ]);

        $inscripcion = Inscripcion::find($this->id_inscripcion);

        $id_programa_proceso_actualizado = Programa::join('programa_plan', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('programa_proceso', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->where('programa.id_modalidad', $this->modalidad)->where('programa.id_programa', $this->programa)->first()->id_programa_proceso;
        // dd($id_programa_proceso_actualizado);
        //Validar que no hayan cambios
        if ($inscripcion->id_programa_proceso == $id_programa_proceso_actualizado) {
            $this->alertaInscripcion('¡Información!', 'No se han realizado cambios en el programa de la inscripción', 'info', 'Aceptar', 'info');
            //Cerramos el modal
            $this->dispatchBrowserEvent('modal', [
                'titleModal' => '#ModalInscripcionEditar',
            ]);
            return;
        }

        $inscripcion->id_programa_proceso = $id_programa_proceso_actualizado;
        $inscripcion->save();
        $this->alertaInscripcion('¡Exito!', 'El programa de la inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido actualizado satisfactoriamente', 'success', 'Aceptar', 'success');
        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#ModalInscripcionEditar',
        ]);
    }

    public function cargar_expedientes($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        $this->expedientes = ExpedienteInscripcion::where('id_inscripcion', $id_inscripcion)->get();
    }

    public function verificar_expediente($id_expediente_inscripcion)
    {
        $expediente = ExpedienteInscripcion::find($id_expediente_inscripcion);
        $expediente->expediente_inscripcion_verificacion = 1; //verificado
        $expediente->save();
        // mostrar alerta
        $this->alertaInscripcion(
            '¡Exito!',
            'El expediente de ' . $expediente->inscripcion->persona->nombre_completo . ' ha sido verificado satisfactoriamente',
            'success',
            'Aceptar',
            'success'
        );
        // cargar expedientes
        $this->cargar_expedientes($expediente->id_inscripcion);
    }

    public function rechazar_expediente($id_expediente_inscripcion)
    {
        $expediente = ExpedienteInscripcion::find($id_expediente_inscripcion);
        $expediente->expediente_inscripcion_verificacion = 2; //rechazado
        $expediente->save();
        // mostrar alerta
        $this->alertaInscripcion(
            '¡Exito!',
            'El expediente de ' . $expediente->inscripcion->persona->nombre_completo . ' ha sido rechazado satisfactoriamente',
            'success',
            'Aceptar',
            'success'
        );
        // ejecutamos el job para enviar el correo de rechazo de expediente
        ObservarInscripcionJob::dispatch($expediente->id_inscripcion, 'observar-expediente');
        // cargar expedientes
        $this->cargar_expedientes($expediente->id_inscripcion);
    }

    public function cargar_inscripcion($id_inscripcion)
    {
        $this->id_inscripcion = $id_inscripcion;
        $this->estado = Inscripcion::find($id_inscripcion)->inscripcion_estado;
    }

    public function editar_estado()
    {
        $inscripcion = Inscripcion::find($this->id_inscripcion);
        $inscripcion->inscripcion_estado = $this->estado;
        if ($inscripcion->inscripcion_estado == 2) {
            $inscripcion->inscripcion_observacion = $this->observacion_inscripcion;
        } else {
            $inscripcion->inscripcion_observacion = null;
        }
        $inscripcion->save();
        // mostrar alerta
        $this->alertaInscripcion(
            '¡Exito!',
            'El estado de la inscripción de ' . $inscripcion->persona->nombre_completo . ' ha sido actualizado satisfactoriamente',
            'success',
            'Aceptar',
            'success'
        );
        // cerrar modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modal-estado-inscripcion',
        ]);
        // ejecutamos el job para enviar el correo de observacion o verificacion de expediente
        if ($inscripcion->inscripcion_estado == 2) {
            ObservarInscripcionJob::dispatch($inscripcion->id_inscripcion, 'observar-inscripcion');
        } else if ($inscripcion->inscripcion_estado == 1) {
            ObservarInscripcionJob::dispatch($inscripcion->id_inscripcion, 'verificar-inscripcion');
        }
    }

    public function render()
    {
        if ($this->seguimientoFiltro) { //Si existe el seguimientoFiltro, se cambia de consulta, con el fin de mostrar las inscripciones que tienen un seguimiento
            $inscripcionModel = ExpedienteInscripcionSeguimiento::Join('expediente_inscripcion', 'expediente_inscripcion_seguimiento.id_expediente_inscripcion', '=', 'expediente_inscripcion.id_expediente_inscripcion')
                ->Join('inscripcion', 'expediente_inscripcion.id_inscripcion', '=', 'inscripcion.id_inscripcion')
                ->Join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
                ->Join('admision', 'programa_proceso.id_admision', '=', 'admision.id_admision')
                ->Join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                ->Join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
                ->Join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
                ->where(function ($query) {
                    $query->where('programa.programa', 'like', '%' . $this->search . '%')
                        ->orWhere('programa.subprograma', 'like', '%' . $this->search . '%')
                        ->orWhere('programa.mencion', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.apellido_paterno', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.apellido_materno', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.numero_documento', 'like', '%' . $this->search . '%');
                })
                ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', $this->seguimientoFiltro)
                ->where('programa.id_modalidad', $this->modalidadFiltro == null ? '!=' : '=', $this->modalidadFiltro)
                ->where('programa_plan.id_programa', $this->programaFiltro == null ? '!=' : '=', $this->programaFiltro)
                ->where('programa_proceso.id_admision', $this->procesoFiltro == null ? '!=' : '=', $this->procesoFiltro)
                ->orderBy('id_inscripcion', 'desc')
                ->paginate(10);
        } else { //Si no existe el seguimientoFiltro, se muestra la consulta normal
            $inscripcionModel = Inscripcion::Join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
                ->Join('admision', 'programa_proceso.id_admision', '=', 'admision.id_admision')
                ->Join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                ->Join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
                ->Join('modalidad', 'programa.id_modalidad', '=', 'modalidad.id_modalidad')
                ->Join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
                ->where(function ($query) {
                    $query->where('programa.programa', 'like', '%' . $this->search . '%')
                        ->orWhere('programa.subprograma', 'like', '%' . $this->search . '%')
                        ->orWhere('programa.mencion', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.apellido_paterno', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.apellido_materno', 'like', '%' . $this->search . '%')
                        ->orWhere('persona.numero_documento', 'like', '%' . $this->search . '%')
                        ->orWhere('modalidad.modalidad', 'like', '%' . $this->search . '%');
                })
                ->where('programa.id_modalidad', $this->modalidadFiltro == null ? '!=' : '=', $this->modalidadFiltro)
                ->where('programa_plan.id_programa', $this->programaFiltro == null ? '!=' : '=', $this->programaFiltro)
                ->where('programa_proceso.id_admision', $this->procesoFiltro == null ? '!=' : '=', $this->procesoFiltro)
                ->when($this->mesFiltro, function ($query, $mesFiltro) {
                    return $query->whereMonth('inscripcion_fecha', $mesFiltro);
                })
                ->orderBy('id_inscripcion', 'desc')
                ->paginate(10);
        }

        //Obtenemos los meses únicos de las inscripciones
        $mesesUnicos = Inscripcion::selectRaw('MONTH(inscripcion_fecha) as mes, YEAR(inscripcion_fecha) as anio')
            ->groupBy('mes', 'anio')
            ->get();

        //validar que existan las modalidades en programa Proceso
        $modalidadesModal = ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
            ->join('modalidad', 'programa.id_modalidad', '=', 'modalidad.id_modalidad')
            ->selectRaw('programa.id_modalidad as id_modalidad')
            ->groupBy('id_modalidad')
            ->pluck('id_modalidad');

        $this->programasModal = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', 'programa_plan.id_programa')
            ->where('programa.id_modalidad', $this->modalidad)
            ->where('programa_plan.programa_plan_estado', 1)
            ->where('programa_proceso.programa_proceso_estado', 1)
            ->get();

        return view('livewire.modulo-administrador.gestion-admision.inscripcion.index', [
            'inscripcionModel' => $inscripcionModel,
            'procesos' => Admision::all(),
            'seguimientos' => TipoSeguimiento::all(),
            'modalidades' => Modalidad::all(),
            'mesesUnicos' => $mesesUnicos,
            'modalidadesModal' => $modalidadesModal,
        ]);
    }
}
