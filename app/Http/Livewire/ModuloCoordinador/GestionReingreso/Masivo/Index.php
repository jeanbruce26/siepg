<?php

namespace App\Http\Livewire\ModuloCoordinador\GestionReingreso\Masivo;

use App\Models\Admision;
use App\Models\Admitido;
use App\Models\Plan;
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
    public $proceso;
    public $plan_nuevo;
    public $resolucion;
    public $resolucion_file;
    public $check_cambio_plan = 1;

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
        if ($this->check_cambio_plan) {
            $this->validateOnly($propertyName, [
                'programa' => 'required',
                'programa_reingreso' => 'required',
                'proceso' => 'required',
                'plan_nuevo' => 'required',
                'resolucion' => 'required|max:255',
                'resolucion_file' => 'nullable|mimes:pdf|max:10240',
            ]);
        } else {
            $this->validateOnly($propertyName, [
                'programa' => 'required',
                'proceso' => 'required',
                'resolucion' => 'required|string|max:255',
                'resolucion_file' => 'nullable|mimes:pdf|max:10240',
            ]);
        }
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
            'proceso',
            'plan_nuevo',
            'programa_reingreso',
            'resolucion',
            'resolucion_file',
            'check_cambio_plan',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPrograma($id_programa_proceso)
    {
        $programa = ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
            ->where('programa_proceso.id_programa_proceso', $id_programa_proceso)
            ->first();
        $this->tipo_programa = $programa->programa_tipo;
    }

    public function guardar_reingreso()
    {
        $this->validate([
            'check_cambio_plan' => 'required',
        ]);

        if ($this->check_cambio_plan == 1) {
            $this->validate([
                'proceso' => 'required',
                'programa' => 'required',
                'resolucion' => 'required|string|max:255',
                'resolucion_file' => 'nullable|mimes:pdf|max:10240',
            ]);
        } else {
            $this->validate([
                'proceso' => 'required',
                'programa' => 'required',
                'plan_nuevo' => 'required',
                'programa_reingreso' => 'required',
                'resolucion' => 'required|string|max:255',
                'resolucion_file' => 'nullable|mimes:pdf|max:10240',
            ]);
        }

        $admitidos = Admitido::where('id_programa_proceso', $this->programa)
            ->where('admitido_estado', 2) // 1 = admitido normal | 2 = retirado | 0 = desactivado
            ->get();

        // verificar si hay estudiantes admitidos que sea retirados
        if (count($admitidos) == 0) {
            $this->dispatchBrowserEvent('alerta-basica', [
                'title' => '¡Error!',
                'text' => 'No hay estudiantes retirados en el proceso seleccionado, por favor verifique e intente nuevamente.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        $codigo = date('YmdHis');
        $codigo = 'R' . $codigo . 'M';

        foreach ($admitidos as $admitido) {
            // crear reingreso masivo
            $reingreso = new Reingreso();
            $reingreso->reingreso_codigo = $codigo;
            $reingreso->id_admitido = $admitido->id_admitido;
            if ($this->check_cambio_plan == 2) { // si se cambia de plan
                $reingreso->id_programa_proceso = $this->programa_reingreso;
                $reingreso->id_programa_proceso_antiguo = $admitido->id_programa_proceso;
            } else { // no se cambia de plan
                $reingreso->id_programa_proceso = $admitido->id_programa_proceso;
                $reingreso->id_programa_proceso_antiguo = $admitido->id_programa_proceso;
            }
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

            // actualizar programa del admitido
            if ($this->check_cambio_plan == 2) {
                $admitido->id_programa_proceso_antiguo = $admitido->id_programa_proceso;
                $admitido->id_programa_proceso = $this->programa_reingreso;
            }

            // actualizar el estado del admitido
            $admitido->admitido_estado = 1; // 1 = admitido normal | 2 = retirado | 0 = desactivado
            $admitido->save();

            // asignar las nuevas notas de los cursos a su nuevo programa
            if ($this->check_cambio_plan) {
                //...
            }
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
        // paso 1

        $procesos = Admision::orderBy('admision', 'desc')->get();

        $programas = $this->proceso ?
            ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->join('facultad', 'programa.id_facultad', 'facultad.id_facultad')
                ->where('facultad.id_facultad', $this->facultad->id_facultad)
                ->where('programa_proceso.id_admision', $this->proceso)
                ->get() :
            collect();

        // paso 2

        $planes = Plan::orderBy('plan.plan', 'desc')->get();

        $programas_reingreso = $this->plan_nuevo ?
            ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->join('plan', 'programa_plan.id_plan', 'plan.id_plan')
                ->join('facultad', 'programa.id_facultad', 'facultad.id_facultad')
                ->join('admision', 'programa_proceso.id_admision', 'admision.id_admision')
                ->where('plan.id_plan', $this->plan_nuevo)
                ->where('programa.id_facultad', $this->facultad->id_facultad)
                ->where('programa.programa_tipo', $this->tipo_programa)
                ->get() :
            collect();

        // reingreso
        $reingresos = Reingreso::join('admitido', 'reingreso.id_admitido', 'admitido.id_admitido')
            ->join('persona', 'admitido.id_persona', 'persona.id_persona')
            ->join('programa_proceso', 'admitido.id_programa_proceso', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
            ->where(function ($query) {
                $query->where('reingreso.reingreso_codigo', 'like', '%' . $this->search . '%')
                    ->orWhere('persona.nombre_completo', 'like', '%' . $this->search . '%');
            })
            ->where('reingreso.id_tipo_reingreso', 2) // reingreso masivo
            ->orderBy('id_reingreso', 'desc')
            ->paginate(20);

        return view('livewire.modulo-coordinador.gestion-reingreso.masivo.index', [
            'reingresos' => $reingresos,
            'procesos' => $procesos,
            'programas' => $programas,
            'planes' => $planes,
            'programas_reingreso' => $programas_reingreso,
        ]);
    }
}
