<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Programa;

use App\Models\Programa;
use App\Models\ProgramaPlan;
use App\Models\ProgramaProceso;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class GestionPlanProceso extends Component
{
    use WithPagination;//Paginacion
    protected $paginationTheme = 'bootstrap';//paginacion de bootstrap
    
    public $search;//Variable de busqueda
    public $titulo = "Agregar Plan al Programa";//titulo del modal
    public $modo = 1;//Variable que cambia entre agregar(1), editar(2) o mostrar(3) un registro de Plan

    public $id_programa;//id del programa al que se le asignara el plan y proceso, se recibe desde la vista de programa
    //Variables para la gestion de Plan del programa
    public $id_programa_plan;//id del programa al que se le asignara el programa plan
    public $programa_codigo;
    public $programa;//id del programa
    public $plan;//id del plan
    public $programa_plan_creacion;//fecha de creacion del programa plan
    public $programa_plan_estado;//estado del programa plan (1: activo, 0: inactivo)

    //Variables para la gestion de Proceso del programa
    public $id_programa_proceso;//id del programa al que se le asignara el programa proceso
    public $admision;//id de la admision
    public $programa_proceso_estado;//estado del programa proceso (1: activo, 0: inactivo)

    protected $queryString = [//Variables de busqueda amigables
        'search' => ['except' => ''],
    ];

    protected $listeners = ['render', 'cambiarEstado'];//Escuchar evento para que se actualice el componente

    //Validaciones en tiempo real para los campos del formulario de expediente
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'programa' => 'required|numeric',
            'plan' => 'required|numeric',
            'admision' => 'required|numeric',
        ]);
    }

    //Limpiar los campos del formulario y resetear el modo
    //Limpiar los campos del formulario y resetear el modo
    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
    }

    //Limpiar los campos del formulario
    public function limpiar()
    {
        $this->resetErrorBag();
        // $this->reset('tipo_seguimiento');
        $this->modo = 1;
        $this->titulo = 'Agregar Plan al Programa';
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
        $this->dispatchBrowserEvent('alerta-gestion-plan-proceso', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Mostar modal de confirmacion para cambiar el estado del programa
    public function cargarAlertaEstado(ProgramaPlan $programaPlan)
    {
        $programaPlan->programa->mencion ? $mencion = ' CON MECION EN '.$programaPlan->programa->mencion : $mencion = '';

        $this->alertaConfirmacion('¿Estás seguro?', '¿Desea cambiar el estado del plan en el programa de '.$programaPlan->programa->programa.' EN '.$programaPlan->programa->subprograma.''.$mencion.'?', 'question', 'Modificar', 'Cancelar', 'primary', 'danger', 'cambiarEstado', $programaPlan->id_programa_plan);
    }

    //Cambiar el estado del programa
    public function cambiarEstado(ProgramaPlan $programaPlan)
    {
        if ($programaPlan->programa_plan_estado == 1) {//Si el estado es 1 (activo), cambiar a 0 (inactivo)
            $programaPlan->programa_plan_estado = 0;
        } else {//Si el estado es 2 (inactivo), cambiar a 1 (activo)
            $programaPlan->programa_plan_estado = 1;
        }

        $programaPlan->save();//Actualizar el estado del programa

        $programaPlan->programa->mencion ? $mencion = ' CON MECION EN '.$programaPlan->programa->mencion : $mencion = '';//Si el programa tiene mencion, mostrarla en la alerta
        //Mostrar alerta de confirmacion de cambio de estado
        $this->alertaPrograma('¡Éxito!', 'El estado del plan en el programa de '.$programaPlan->programa->programa.' EN '.$programaPlan->programa->subprograma.''.$mencion.' ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
    }
    
    public function render()
    {
        $buscar = $this->search;
        $programaModel = Programa::find($this->id_programa);
        $programaProcesoModel = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                                ->join('admision', 'admision.id_admision', '=', 'programa_proceso.id_admision')
                                                ->where('programa_plan.id_programa', '=', $this->id_programa)
                                                ->orderBy('programa_proceso.id_programa_proceso', 'asc')->get();

        $programaPlanModel = ProgramaPlan::join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                                            ->join('plan', 'plan.id_plan', '=', 'programa_plan.id_plan')
                                            ->where('programa_plan.id_programa', '=', $this->id_programa)
                                            ->where(function($query) use ($buscar){
                                                $query->Where('plan.plan','like','%'.$buscar.'%')
                                                ->orWhere('programa_plan.programa_codigo','like','%'.$buscar.'%');
                                            })
                                            ->orderBy('programa_plan.id_programa_plan', 'asc')
                                            ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.programa.gestion-plan-proceso', [
            'programaModel' => $programaModel,
            'programaPlanModel' => $programaPlanModel,
            'programaProcesoModel' => $programaProcesoModel,
        ]);
    }
}
