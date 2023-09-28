<?php

namespace App\Http\Livewire\ModuloCoordinador\GestionReingreso\Individual;

use App\Models\Reingreso;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // tema de paginacion

    public $search = ''; // variable para la busqueda

    // variables del model
    public $title_modal = 'Nuevo Reingreso Individual';
    public $paso = 1;
    public $total_paso = 2;

    protected $queryString = [ // para que la paginacion se mantenga con el buscador
        'search' => ['except' => '', 'as' => 's'],
    ];

    public function modo()
    {
        $this->limpiar_modal();
    }

    public function limpiar_modal()
    {
        // $this->reset();
    }

    public function atras_paso()
    {
        if ($this->paso > 1) {
            $this->paso--;
        }

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function siguiente_paso()
    {
        if ($this->paso < $this->total_paso) {
            $this->paso++;
        }

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        $reingresos = Reingreso::join('admitido', 'reingreso.id_admitido', 'admitido.id_admitido')
            ->join('persona', 'admitido.id_persona', 'persona.id_persona')
            ->join('programa_proceso', 'admitido.id_programa_proceso', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
            ->where(function ($query) {
                $query->where('reingreso.reingreso_codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('persona.nombre_completo', 'like', '%' . $this->search . '%');
            })
            ->where('reingreso.id_tipo_reingreso', 1) // Individual
            ->orderBy('id_reingreso', 'desc')
            ->paginate(20);

        return view('livewire.modulo-coordinador.gestion-reingreso.individual.index', [
            'reingresos' => $reingresos
        ]);
    }
}
