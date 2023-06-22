<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionAdmision\Inscripcion;

use App\Models\Admision;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Inscripcion;
use App\Models\Modalidad;
use App\Models\Programa;
use App\Models\TipoSeguimiento;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';//paginacion de bootstrap

    protected $queryString = [
        'search' => ['except' => ''],
        'modalidadFiltro' => ['except' => ''],
        'procesoFiltro' => ['except' => ''],
        'programaFiltro' => ['except' => ''],
        'seguimientoFiltro' => ['except' => ''],
    ];

    public $search = '';

    //Variables para el filtro de Inscripión
    public $procesoFiltro;//Para la búsqueda de inscripciones por proceso
    public $proceso_filtro;//Para el filtro de inscripciones por proceso
    public $modalidadFiltro;//Para la búsqueda de inscripciones por modalidad
    public $modalidad_filtro;//Para el filtro de inscripciones por modalidad
    public $programaFiltro;//Para la búsqueda de inscripciones por programa
    public $programa_filtro;//Para el filtro de inscripciones por programa
    public $seguimientoFiltro;//Para la búsqueda de inscripciones por seguimiento
    public $seguimiento_filtro;//Para el filtro de inscripciones por seguimiento

    //variables
    public $id_inscripcion;

    protected $listeners = [
        'render', 'cambiarEstado', 'cambiarSeguimiento', 'reservarPago'
    ];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'id_inscripcion' => 'required',
        ]);
    }

    public function limpiar()
    {
        $this->reset('id_inscripcion');
    }
    
    //Limpiamos los filtros
    public function resetear_filtro()
    {
        $this->reset('procesoFiltro', 'programaFiltro', 'seguimientoFiltro', 'modalidadFiltro', 'proceso_filtro', 'programa_filtro', 'seguimiento_filtro', 'modalidad_filtro');
    }

    //Asignamos los filtros
    public function filtrar()
    {
        $this->procesoFiltro = $this->proceso_filtro;
        $this->modalidadFiltro = $this->modalidad_filtro;
        $this->programaFiltro = $this->programa_filtro;
        $this->seguimientoFiltro = $this->seguimiento_filtro;
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
    public function alertaPrograma($title, $text, $icon, $confirmButtonText, $color)
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
        $this->alertaConfirmacion('¿Estás seguro?','¿Desea cambiar el estado de la inscripción de '.$inscripcion->persona->nombre_completo.'?','question','Modificar','Cancelar','primary','danger','cambiarEstado',$inscripcion->id_inscripcion);
    }

    //Cambiar el estado de la inscripción
    public function cambiarEstado($id)
    {
        $inscripcion = Inscripcion::find($id);
        if($inscripcion->inscripcion_estado == 1){//Si el estado es activo(1), se cambia a inactivo(0)
            $inscripcion->inscripcion_estado = 0;
        }else{//Si el estado es inactivo(0), se cambia a activo(1)
            $inscripcion->inscripcion_estado = 1;
        }

        $inscripcion->save();
        $this->alertaPrograma('¡Exito!','El estado de la inscripción de '.$inscripcion->persona->nombre_completo.' ha sido actualizado satisfactoriamente','success','Aceptar','success');
    }

    public function render()
    {
        if($this->seguimientoFiltro){//Si existe el seguimientoFiltro, se cambia de consulta, con el fin de mostrar las inscripciones que tienen un seguimiento
            $inscripcionModel = ExpedienteInscripcionSeguimiento::Join('expediente_inscripcion', 'expediente_inscripcion_seguimiento.id_expediente_inscripcion', '=', 'expediente_inscripcion.id_expediente_inscripcion')
                                                                    ->Join('inscripcion', 'expediente_inscripcion.id_inscripcion', '=', 'inscripcion.id_inscripcion')
                                                                    ->Join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
                                                                    ->Join('admision', 'programa_proceso.id_admision', '=', 'admision.id_admision')
                                                                    ->Join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                                                                    ->Join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
                                                                    ->Join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
                                                                    ->where(function ($query){
                                                                        $query->where('programa.programa', 'like', '%'.$this->search.'%')
                                                                        ->orWhere('programa.subprograma', 'like', '%'.$this->search.'%')
                                                                        ->orWhere('programa.mencion', 'like', '%'.$this->search.'%')
                                                                        ->orWhere('persona.nombre', 'like', '%'.$this->search.'%')
                                                                        ->orWhere('persona.apellido_paterno', 'like', '%'.$this->search.'%')
                                                                        ->orWhere('persona.apellido_materno', 'like', '%'.$this->search.'%')
                                                                        ->orWhere('persona.numero_documento', 'like', '%'.$this->search.'%');
                                                                    })
                                                                    ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', $this->seguimientoFiltro)
                                                                    ->where('programa.id_modalidad', $this->modalidadFiltro == null ? '!=' : '=', $this->modalidadFiltro)
                                                                    ->where('programa_plan.id_programa', $this->programaFiltro == null ? '!=' : '=', $this->programaFiltro)
                                                                    ->where('programa_proceso.id_admision', $this->procesoFiltro == null ? '!=' : '=', $this->procesoFiltro)
                                                                    ->orderBy('id_inscripcion', 'desc')
                                                                    ->paginate(10);
        }else{//Si no existe el seguimientoFiltro, se muestra la consulta normal
            $inscripcionModel = Inscripcion::Join('programa_proceso', 'inscripcion.id_programa_proceso', '=', 'programa_proceso.id_programa_proceso')
                                                ->Join('admision', 'programa_proceso.id_admision', '=', 'admision.id_admision')
                                                ->Join('programa_plan', 'programa_proceso.id_programa_plan', '=', 'programa_plan.id_programa_plan')
                                                ->Join('programa', 'programa_plan.id_programa', '=', 'programa.id_programa')
                                                ->Join('modalidad', 'programa.id_modalidad', '=', 'modalidad.id_modalidad')
                                                ->Join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
                                                ->where(function ($query){
                                                    $query->where('programa.programa', 'like', '%'.$this->search.'%')
                                                    ->orWhere('programa.subprograma', 'like', '%'.$this->search.'%')
                                                    ->orWhere('programa.mencion', 'like', '%'.$this->search.'%')
                                                    ->orWhere('persona.nombre', 'like', '%'.$this->search.'%')
                                                    ->orWhere('persona.apellido_paterno', 'like', '%'.$this->search.'%')
                                                    ->orWhere('persona.apellido_materno', 'like', '%'.$this->search.'%')
                                                    ->orWhere('persona.numero_documento', 'like', '%'.$this->search.'%')
                                                    ->orWhere('modalidad.modalidad', 'like', '%'.$this->search.'%');
                                                })
                                                ->where('programa.id_modalidad', $this->modalidadFiltro == null ? '!=' : '=', $this->modalidadFiltro)
                                                ->where('programa_plan.id_programa', $this->programaFiltro == null ? '!=' : '=', $this->programaFiltro)
                                                ->where('programa_proceso.id_admision', $this->procesoFiltro == null ? '!=' : '=', $this->procesoFiltro)
                                                ->orderBy('id_inscripcion', 'desc')
                                                ->paginate(10);
        }

        return view('livewire.modulo-administrador.gestion-admision.inscripcion.index', [
            'inscripcionModel' => $inscripcionModel,
            'procesos' => Admision::all(),
            'seguimientos' => TipoSeguimiento::all(),
            'modalidades' => Modalidad::all(),
        ]);
    }
}
