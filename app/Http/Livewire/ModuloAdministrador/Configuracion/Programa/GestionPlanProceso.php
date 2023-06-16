<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Programa;

use App\Models\Plan;
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
    public $plan = null;//id del plan
    public $plan_nombre;//nombre del plan
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
            'programa_codigo' => 'required|string',
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
        $this->reset('programa_codigo', 'plan');
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
    public function alertaProgramaPlan($title, $text, $icon, $confirmButtonText, $color)
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
        $this->alertaProgramaPlan('¡Éxito!', 'El estado del plan en el programa de '.$programaPlan->programa->programa.' EN '.$programaPlan->programa->subprograma.''.$mencion.' ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
    }
    
    //Cargar datos en el modal para detalle y editar
    public function cargarProgramaPlan(ProgramaPlan $programaPlan, $modo)
    {
        //limpiamos
        $this->limpiar();
        //Asignamos los valores del modo y el titulo
        $this->modo = $modo;//1: agregar, 2: editar, 3: detalle
        $this->modo == 2 ? $this->titulo = 'Actualizar Plan del Programa' : $this->titulo = 'Detalle del Plan del Programa';

        //Cargar los datos del programa plan
        $this->id_programa_plan = $programaPlan->id_programa_plan;
        $this->programa_codigo = $programaPlan->programa_codigo;
        $this->plan = $programaPlan->id_plan;
        $this->plan_nombre = $programaPlan->plan->plan;
        $this->programa_plan_creacion = $programaPlan->programa_plan_creacion;
    }

    //Guardar o actualizar el programa plan
    public function guardarProgramaPlan()
    {
        //Validar los campos
        $this->validate([
            'programa_codigo' => 'required | string',
            'plan' => 'required | numeric',
        ]);

        //Si el modo es 1 (agregar), guardar el programa plan
        if ($this->modo == 1) {
            //Validar que el programa plan no exista
            $programaPlan = ProgramaPlan::where('id_programa', '=', $this->id_programa)
                                        ->where('id_plan', '=', $this->plan)
                                        ->first();
            if ($programaPlan) {//Si el programa plan existe, mostrar alerta
                $this->alertaProgramaPlan('¡Error!', 'El plan ya se encuentra registrado en el programa.', 'error', 'Aceptar', 'danger');
                //Limpia los campos del formulario
                $this->limpiar();
                //Cerramos el modal
                $this->dispatchBrowserEvent('modal', [
                    'titleModal' => '#modalPlanProceso',
                ]);
            } else {//Si el programa plan no existe, guardarlo
                $programaPlan = new ProgramaPlan();
                $programaPlan->programa_codigo = $this->programa_codigo;
                $programaPlan->id_programa = $this->id_programa;
                $programaPlan->id_plan = $this->plan;
                $programaPlan->programa_plan_creacion = date('Y-m-d H:i:s');
                $programaPlan->programa_plan_estado = 1;
                $programaPlan->save();

                //Mostrar alerta de confirmacion
                $this->alertaProgramaPlan('¡Éxito!', 'El plan '.$programaPlan->plan->plan.' ha sido registrado satisfactoriamente en el programa.', 'success', 'Aceptar', 'success');
                //Limpiar los campos del formulario
                $this->limpiar();
                //Cerramos el modal
                $this->dispatchBrowserEvent('modal', [
                    'titleModal' => '#modalPlanProceso',
                ]);
            }
        } else {//Si el modo es 2 (editar), actualizar el programa plan
            //Validar que el programa plan no exista
            $programaPlan = ProgramaPlan::where('id_programa', '=', $this->id_programa)
                                        ->where('id_plan', '=', $this->plan)
                                        ->where('id_programa_plan', '!=', $this->id_programa_plan)
                                        ->first();
            if ($programaPlan) {//Si el programa plan existe, mostrar alerta
                $this->alertaProgramaPlan('¡Error!', 'El plan ya se encuentra registrado en el programa.', 'error', 'Aceptar', 'danger');
                //Limpia los campos del formulario
                $this->limpiar();
                //Cerramos el modal
                $this->dispatchBrowserEvent('modal', [
                    'titleModal' => '#modalPlanProceso',
                ]);
            } else {//Si el programa plan no existe, actualizarlo
                $programaPlan = ProgramaPlan::find($this->id_programa_plan);
                //Validar que el plan del programa no haya sido actualizado
                $programaPlanValidar = ProgramaPlan::where('id_programa', '=', $this->id_programa)
                                                    ->where('id_plan', '=', $this->plan)
                                                    ->where('programa_codigo', '=', $this->programa_codigo)
                                                    ->where('id_programa_plan', '=', $this->id_programa_plan)
                                                    ->first();
                if ($programaPlanValidar) {//Si el plan del programa no ha sido actualizado, mostrar alerta
                    $this->alertaProgramaPlan('¡Información!', 'El plan del programa no ha sido actualizado.', 'info', 'Aceptar', 'info');
                    //Limpia los campos del formulario
                    $this->limpiar();
                    //Cerramos el modal
                    $this->dispatchBrowserEvent('modal', [
                        'titleModal' => '#modalPlanProceso',
                    ]);
                } else {//Si el plan del programa ha sido actualizado, actualizarlo
                    $programaPlan->programa_codigo = $this->programa_codigo;
                    $programaPlan->id_plan = $this->plan;
                    $programaPlan->save();

                    //Mostrar alerta de confirmacion
                    $this->alertaProgramaPlan('¡Éxito!', 'El plan '.$programaPlan->plan->plan .' ha sido actualizado satisfactoriamente en el programa.', 'success', 'Aceptar', 'success');
                    //Limpiar los campos del formulario
                    $this->limpiar();
                    //Cerramos el modal
                    $this->dispatchBrowserEvent('modal', [
                        'titleModal' => '#modalPlanProceso',
                    ]);
                }
            }
        }
    }

    public function render()
    {
        $buscar = $this->search;
        $programaModel = Programa::find($this->id_programa);
        $planModel = Plan::all();
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
            'planModel' => $planModel,
        ]);
    }
}
