<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Plan;

use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;


class Index extends Component
{

    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';
    public $titulo = 'Crear Plan de Estudios';
    public $id_plan;

    public $modo = 1;

    public $plan;

    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'plan' => 'required|numeric'
        ]);
    }

    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
        $this->titulo = 'Crear Plan de Estudios';
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('plan');
        $this->modo = 1;
    }

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

    public function alertaPlan($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-plan', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    public function cargarAlerta($id)
    {
        $this->alertaConfirmacion('¿Estás seguro?', '¿Desea modificar el estado del plan de estudios?', 'question', 'Modificar', 'Cancelar', 'primary', 'danger', 'cambiarEstado', $id);
    }

    public function cambiarEstado(Plan $plan)
    {
        if ($plan->plan_estado == 1) {
            $plan->plan_estado = 0;
        } else {
            $plan->plan_estado = 1;
        }

        $plan->save();

        $this->alertaPlan('¡Éxito!', 'El estado del plan ' . $plan->plan . ' ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
    }

    public function cargarPlan(Plan $plan)
    {
        $this->limpiar();
        $this->modo = 2;
        $this->titulo = 'Editar Plan de Estudios';
        $this->id_plan = $plan->id_plan;
        $this->plan = $plan->plan;
    }

    public function guardarPlan()
    {
        if ($this->modo == 1) {
            $this->validate([
                'plan' => 'required|numeric|unique:plan,plan'
            ]);
    
            $plan = new Plan();
            $plan->plan_codigo = 'P'.$this->plan;
            $plan->plan = $this->plan;
            $plan->plan_estado = 1;
            $plan->save();
    
            $this->alertaPlan('¡Éxito!', 'El plan ' . $plan->plan . ' ha sido creado satisfactoriamente.', 'success', 'Aceptar', 'success');
        }else{
            $this->validate([
                'plan' => 'required|numeric|unique:plan,plan,'.$this->id_plan.',id_plan'
            ]);

            $plan = Plan::find($this->id_plan);

            if($plan->plan == $this->plan){
                $this->alertaPlan('¡Información!', 'No se realizaron cambios en los datos del plan.', 'info', 'Aceptar', 'info');
            }else{
                $plan->plan_codigo = 'P'.$this->plan;
                $plan->plan = $this->plan;
                $plan->save();
    
                $this->alertaPlan('¡Éxito!', 'El plan ' . $plan->plan . ' ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
            }
        }

        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalPlan'
        ]);

        $this->limpiar();
    }
    
    public function render()
    {
        $plan_model = Plan::where('plan', 'LIKE', '%' . $this->search . '%')
                ->orWhere('id_plan', 'LIKE', '%' . $this->search . '%')
                ->orderBy('id_plan', 'DESC')
                ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.plan.index', [
            'plan_model' => $plan_model
        ]);
    }

}
