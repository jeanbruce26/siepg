<?php

namespace App\Http\Livewire\ModuloCoordinador\GestionRetiro;

use App\Models\Admision;
use App\Models\Admitido;
use App\Models\ProgramaProceso;
use App\Models\Retiro;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // trait de paginacion
    protected $paginationTheme = 'bootstrap'; // tema de paginacion

    // variable de búsqueda
    public $search;

    // variables generales
    public $facultad;
    public $data = [];

    // variables de modal
    public $title_modal = 'Nuevo Retiro';
    public $subtitulo_modal = '';
    public $retiro, $estudiante, $proceso, $programa;

    // variables de pasos de registro
    public $paso = 1;
    public $total_pasos = 2;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar']
    ];

    protected $listeners = [
        'eliminar_retiro' => 'eliminar_retiro',
    ];

    public function mount()
    {
        $trabajador = auth('usuario')->user()->trabajador_tipo_trabajador->trabajador;
        $coordinador = $trabajador->coordinador;
        $this->facultad = $coordinador->facultad;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'retiro' => 'required',
            'estudiante' => 'required',
            'proceso' => 'required',
            'programa' => 'required',
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function modo()
    {
        $this->limpiar_modal();
        $this->title_modal = 'Nuevo Retiro';

    }

    public function limpiar_modal()
    {
        $this->reset([
            'retiro',
            'estudiante',
            'proceso',
            'programa',
            'data',
            'paso'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function modal_atras()
    {
        if ($this->paso > 1) {
            $this->paso--;
        }
        if ($this->paso == 1) {
            $this->subtitulo_modal = '';
        }
        $this->reset([
            'estudiante',
            'proceso',
            'programa',
            'data',
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function modal_siguiente()
    {
        if ($this->paso == 1) {
            $this->validate([
                'retiro' => 'required',
            ]);
        }

        if ($this->paso < $this->total_pasos) {
            $this->paso++;
        }

        if ($this->paso == 2) {
            $this->subtitulo_modal = $this->retiro == 1 ? 'Retiro Individual' : 'Paralizar Programa';
        }

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedEstudiante($id_admitido)
    {
        $admitido = Admitido::find($id_admitido);
        if (!$admitido) {
            $this->data = [
                'proceso' => '-',
                'plan' => '-',
                'programa' => '-',
                'codigo_estudiante' => '-',
                'estudiante' => '-',
            ];
            return;
        }
        $proceso = $admitido->programa_proceso->admision->admision;
        $plan = 'PLAN ' . $admitido->programa_proceso->programa_plan->plan->plan;
        $programa = $admitido->programa_proceso->programa_plan->programa->programa;
        $subprograma = $admitido->programa_proceso->programa_plan->programa->subprograma;
        $mencion = $admitido->programa_proceso->programa_plan->programa->mencion;
        if ($mencion) {
            $programa = $programa . ' EN ' . $subprograma . ' CON MENCIÓN ' . $mencion;
        } else {
            $programa = $programa . ' EN ' . $subprograma;
        }
        $codigo_estudiante = $admitido->admitido_codigo;
        $estudiante = $admitido->persona->nombre_completo;
        $this->data = [
            'proceso' => $proceso,
            'plan' => $plan,
            'programa' => $programa,
            'codigo_estudiante' => $codigo_estudiante,
            'estudiante' => $estudiante,
        ];
    }

    public function updatedProceso()
    {
        $this->programa = null;
    }

    public function updatedPrograma($id_programa_proceso)
    {
        $programa_progreso = ProgramaProceso::find($id_programa_proceso);
        if (!$programa_progreso) {
            $this->data = [
                'proceso' => '-',
                'plan' => '-',
                'programa' => '-',
                'codigo_estudiante' => '-',
                'estudiante' => '-',
            ];
            return;
        }
        $proceso = $programa_progreso->admision->admision;
        $plan = 'PLAN ' . $programa_progreso->programa_plan->plan->plan;
        $programa = $programa_progreso->programa_plan->programa->programa;
        $subprograma = $programa_progreso->programa_plan->programa->subprograma;
        $mencion = $programa_progreso->programa_plan->programa->mencion;
        if ($mencion) {
            $programa = $programa . ' EN ' . $subprograma . ' CON MENCIÓN ' . $mencion;
        } else {
            $programa = $programa . ' EN ' . $subprograma;
        }
        $this->data = [
            'proceso' => $proceso,
            'plan' => $plan,
            'programa' => $programa,
            'codigo_estudiante' => '-',
            'estudiante' => '-',
        ];
    }

    public function guardar_retiro()
    {
        if ($this->retiro == 1) {
            $this->validate([
                'estudiante' => 'required',
            ]);
        } else {
            $this->validate([
                'proceso' => 'required',
                'programa' => 'required'
            ]);
        }

        if ($this->retiro == 1) {
            // retiro individual
            $retiro = new Retiro();
            $retiro->id_admitido = $this->estudiante;
            $retiro->retiro_fecha_creacion = date('Y-m-d H:i:s');
            $retiro->retiro_estado = 1;
            $retiro->save();

            // actualizar estado de admitido
            $admitido = Admitido::find($this->estudiante);
            $admitido->admitido_estado = 2;
            $admitido->save();

            $this->dispatchBrowserEvent('alerta_base', [
                'title' => '¡Éxito!',
                'text' => 'Retiro registrado correctamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        } else {
            // paralizar programa
            // validamos si ya existe el retiro para el grupo de estudiantes para este programa proceso
            $retiros = Retiro::join('admitido', 'retiro.id_admitido', 'admitido.id_admitido')
                ->where('admitido.id_programa_proceso', $this->programa)->get();
            $retiros_count = $retiros->count();
            $admitidos_count = Admitido::where('id_programa_proceso', $this->programa)->count();
            if ($retiros_count == $admitidos_count) {
                $this->dispatchBrowserEvent('alerta_base', [
                    'title' => '¡Error!',
                    'text' => 'Ya existe un retiro para este grupo de estudiantes.',
                    'icon' => 'error',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'danger'
                ]);
                return;
            }

            // obtener admitidos
            $admitidos = Admitido::where('id_programa_proceso', $this->programa)->get();
            foreach ($admitidos as $admitido) {
                $retiro = new Retiro();
                $retiro->id_admitido = $admitido->id_admitido;
                $retiro->retiro_fecha_creacion = date('Y-m-d H:i:s');
                $retiro->retiro_estado = 1;
                $retiro->save();

                // actualizar estado de admitido
                $admitido->admitido_estado = 2;
                $admitido->save();
            }

            $this->dispatchBrowserEvent('alerta_base', [
                'title' => '¡Éxito!',
                'text' => 'Retiro registrado correctamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }

        // evento para cerrar modal
        $this->dispatchBrowserEvent('modal', [
            'modal' => '#modal_retiro',
            'action' => 'hide',
        ]);

        // limpiar modal
        $this->limpiar_modal();
    }

    public function eliminar_retiro_alerta($id_retiro)
    {
        $this->dispatchBrowserEvent('alerta_avanzada', [
            'title' => '¡Alerta!',
            'text' => '¿Está seguro de eliminar este retiro?',
            'icon' => 'question',
            'confirmButtonText' => 'Aceptar',
            'cancelButtonText' => 'Cancelar',
            'confirmButtonColor' => 'primary',
            'cancelButtonColor' => 'danger',
            'metodo' => 'eliminar_retiro',
            'id' => $id_retiro
        ]);
    }

    public function eliminar_retiro($id_retiro)
    {
        $retiro = Retiro::find($id_retiro);
        $retiro->retiro_estado = 0;
        $retiro->save();

        // actualizar estado de admitido
        $admitido = Admitido::find($retiro->id_admitido);
        $admitido->admitido_estado = 1;
        $admitido->save();

        $this->dispatchBrowserEvent('alerta_base', [
            'title' => '¡Éxito!',
            'text' => 'Retiro eliminado correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function render()
    {
        $retiros = Retiro::where('retiro_estado', 1)->orderBy('id_retiro', 'desc')->paginate(10);

        $estudiantes = Admitido::join('programa_proceso', 'admitido.id_programa_proceso', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
            ->join('persona', 'admitido.id_persona', 'persona.id_persona')
            ->where('programa.id_facultad', $this->facultad->id_facultad)
            ->where('admitido.admitido_estado', 1)
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();

        $procesos = Admision::orderBy('admision', 'desc')->get();

        $programas = $this->proceso ?
            ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->where('programa.id_facultad', $this->facultad->id_facultad)
                ->where('programa_proceso.id_admision', $this->proceso)
                ->orderBy('programa.programa', 'asc')
                ->get() :
            collect();

        return view('livewire.modulo-coordinador.gestion-retiro.index', [
            'retiros' => $retiros,
            'estudiantes' => $estudiantes,
            'procesos' => $procesos,
            'programas' => $programas,
        ]);
    }
}
