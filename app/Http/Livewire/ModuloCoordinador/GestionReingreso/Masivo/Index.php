<?php

namespace App\Http\Livewire\ModuloCoordinador\GestionReingreso\Masivo;

use App\Models\Admitido;
use App\Models\Programa;
use App\Models\ProgramaPlan;
use App\Models\ProgramaProceso;
use App\Models\Reingreso;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // tema de paginacion

    public $search = ''; // variable para la busqueda

    protected $queryString = [ // para que la paginacion se mantenga con el buscador
        'search' => ['except' => '', 'as' => 's'],
    ];

    // variables para el modal
    public $title_modal = 'Crear Reingreso Masivo';
    public $modo = 'create';
    public $programa;
    public $programa_reingreso;
    public $plan;
    public $plan_nuevo;
    public $resolucion;
    public $resolucion_file;

    // variables
    public $id_reingreso;
    public $facultad;
    public $tipo_programa;

    public function mount()
    {
        $trabajador = auth('usuario')->user()->trabajador_tipo_trabajador->trabajador;
        $coordinador = $trabajador->coordinador;
        $this->facultad = $coordinador->facultad;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'programa' => 'required',
            'programa_reingreso' => 'required',
            'plan' => 'required',
            'plan_nuevo' => 'required',
            'resolucion' => 'required|string|max:255',
            'resolucion_file' => 'nullable|mimes:pdf|max:10240',
        ]);
    }

    public function modo()
    {
        $this->limpiar_modal();
        $this->modo = 'create';
        $this->title_modal = 'Crear Reingreso Masivo';
    }

    public function limpiar_modal()
    {
        $this->reset([
            'programa',
            'programa_reingreso',
            'plan',
            'plan_nuevo',
            'resolucion',
            'resolucion_file',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPrograma($id_programa)
    {
        $programa = Programa::find($id_programa);
        $this->tipo_programa = $programa->programa_tipo;
    }

    public function guardar_reingreso()
    {
        $this->validate([
            'programa' => 'required',
            'programa_reingreso' => 'required',
            'plan' => 'required',
            'plan_nuevo' => 'required',
            'resolucion' => 'required|string|max:255',
            'resolucion_file' => 'nullable|mimes:pdf|max:10240',
        ]);

        $admitidos = Admitido::join('programa_proceso', 'admitido.id_programa_proceso', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
            ->join('plan', 'programa_plan.id_plan', 'plan.id_plan')
            ->where('programa.id_programa', $this->programa)
            ->where('programa_plan.id_programa_plan', $this->plan)
            ->get();

        $codigo = date('YmdHis');
        $codigo = 'R' . $codigo . 'M';

        foreach ($admitidos as $admitido) {
            $reingreso = new Reingreso();
            $reingreso->reingreso_codigo = $codigo;
            $reingreso->id_admitido = $admitido->id_admitido;
            $reingreso->id_programa_proceso = $this->programa_reingreso;
            $reingreso->id_programa_proceso_antiguo = $admitido->id_programa_proceso;
            $reingreso->id_tipo_reingreso = 2; // reingreso masivo
            $reingreso->reingreso_resolucion = $this->resolucion;
            if ($this->resolucion_file) {
                $slug_resolucion = Str::slug($this->resolucion);
                $path = 'Posgrado/Reingreso/Resolucion/';
                $filename = 'reingreso-' . $slug_resolucion . '-' . date('YmdHis') . '.pdf';
                $nombre_db = $path.$filename;
                $data = $this->resolucion_file;
                $data->storeAs($path, $filename, 'files_publico');
                $reingreso->reingreso_resolucion_url = $nombre_db;
            }
            $reingreso->reingreso_fecha_creacion = date('Y-m-d H:i:s');
            $reingreso->reingreso_estado = 1;
            $reingreso->save();

            // actualizar admitido
            $admitido->id_programa_proceso_antiguo = $admitido->id_programa_proceso;
            $admitido->id_programa_proceso = $this->programa_reingreso;
            $admitido->save();
        }

        // cerrar modal
        $this->dispatchBrowserEvent('modal', [
            'action' => 'hide',
            'modal' => '#modal_reingreso',
        ]);

        // mensaje de alerta de exito
        $this->dispatchBrowserEvent('alerta-basica', [
            'title' => '¡Éxito!',
            'text' => 'Reingreso masivo creado con éxito.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // limpiar modal
        $this->limpiar_modal();
    }

    public function render()
    {
        $programas = Programa::join('facultad', 'programa.id_facultad', 'facultad.id_facultad')
            ->where('facultad.id_facultad', $this->facultad->id_facultad)
            ->get();

        if ($this->programa) {
            $planes = ProgramaPlan::join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->join('plan', 'programa_plan.id_plan', 'plan.id_plan')
                ->join('facultad', 'programa.id_facultad', 'facultad.id_facultad')
                ->where('programa.id_programa', $this->programa)
                ->orderBy('plan.plan', 'desc')
                ->get();
        } else {
            $planes = collect();
        }

        if ($this->plan_nuevo) {
            $programas_reingreso = ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->join('plan', 'programa_plan.id_plan', 'plan.id_plan')
                ->join('facultad', 'programa.id_facultad', 'facultad.id_facultad')
                ->where('plan.id_plan', $this->plan_nuevo)
                ->where('programa.id_facultad', $this->facultad->id_facultad)
                ->where('programa.programa_tipo', $this->tipo_programa)
                ->get();
        } else {
            $programas_reingreso = collect();
        }

        $reingresos = Reingreso::join('admitido', 'reingreso.id_admitido', 'admitido.id_admitido')
            ->join('persona', 'admitido.id_persona', 'persona.id_persona')
            ->join('programa_proceso', 'admitido.id_programa_proceso', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
            ->where(function ($query) {
                $query->where('reingreso.reingreso_codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('persona.nombre_completo', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id_reingreso', 'desc')
            ->paginate(20);

        return view('livewire.modulo-coordinador.gestion-reingreso.masivo.index', [
            'reingresos' => $reingresos,
            'planes' => $planes,
            'programas' => $programas,
            'programas_reingreso' => $programas_reingreso,
        ]);
    }
}
