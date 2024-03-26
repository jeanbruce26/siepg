<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionRetiro;

use App\Models\Admision;
use App\Models\Admitido;
use App\Models\ProgramaProceso;
use App\Models\Retiro;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithPagination; // trait de paginacion
    use WithFileUploads; // trait de subida de archivos
    protected $paginationTheme = 'bootstrap'; // tema de paginacion

    // variable de búsqueda
    public $search;

    // variables generales
    public $data = [];

    // variables de modal
    public $title_modal = 'Nuevo Retiro';
    public $subtitulo_modal = '';
    public $retiro, $estudiante, $proceso, $programa, $solicitud;

    // variables de pasos de registro
    public $paso = 1;
    public $total_pasos = 2;

    protected $queryString = [
        'search' => ['except' => '', 'as' => 'buscar']
    ];

    protected $listeners = [
        'eliminar_retiro' => 'eliminar_retiro',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'retiro' => 'required',
            'estudiante' => 'required',
            'proceso' => 'required',
            'programa' => 'required',
            'solicitud' => 'required|file|mimes:pdf|max:10240',
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
            'paso',
            'solicitud'
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
                'solicitud' => 'required|file|mimes:pdf|max:10240',
            ]);
        } else {
            $this->validate([
                'proceso' => 'required',
                'programa' => 'required',
                'solicitud' => 'required|file|mimes:pdf|max:10240',
            ]);
        }

        if ($this->retiro == 1) {
            // retiro individual
            $retiro = new Retiro();
            $retiro->id_admitido = $this->estudiante;
            if ($this->solicitud) {
                $slug_solicitud = 'solicitud-' . Str::random(8) . '-' . date('YmdHis');
                $path = 'Posgrado/Retiro/Solicitud/';
                $filename = $slug_solicitud . '.pdf';
                $nombre_db = $path.$filename;
                $data = $this->solicitud;
                $data->storeAs($path, $filename, 'files_publico');
                $retiro->retiro_solicitud_url = $nombre_db;
            }
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
            if ($this->solicitud) {
                $slug_solicitud = 'solicitud-' . Str::random(8) . '-' . date('YmdHis');
                $path = 'Posgrado/Retiro/Solicitud/';
                $filename = $slug_solicitud . '.pdf';
                $nombre_db = $path.$filename;
                $data = $this->solicitud;
                $data->storeAs($path, $filename, 'files_publico');
            }
            foreach ($admitidos as $admitido) {
                $retiro = new Retiro();
                $retiro->id_admitido = $admitido->id_admitido;
                if ($this->solicitud) {
                    $retiro->retiro_solicitud_url = $nombre_db;
                }
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
        // comprobar si el alumno retirado ya se encuentra reingresado
        $retiro = Retiro::find($id_retiro);
        if ($retiro->retiro_estado == 0) {
            $this->dispatchBrowserEvent('alerta_base', [
                'title' => '¡Error!',
                'text' => 'El retiro que desea eliminar ya se encuentra reingresado.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

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
        $retiro->delete();

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
        $retiros = Retiro::orderBy('id_retiro', 'desc')->paginate(10);

        $estudiantes = Admitido::join('programa_proceso', 'admitido.id_programa_proceso', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
            ->join('persona', 'admitido.id_persona', 'persona.id_persona')
            ->where('admitido.admitido_estado', 1)
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();

        $procesos = Admision::orderBy('admision', 'desc')->get();

        $programas = $this->proceso ?
            ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->where('programa_proceso.id_admision', $this->proceso)
                ->orderBy('programa.programa', 'asc')
                ->get() :
            collect();

        return view('livewire.modulo-administrador.gestion-retiro.index', [
            'retiros' => $retiros,
            'estudiantes' => $estudiantes,
            'procesos' => $procesos,
            'programas' => $programas,
        ]);
    }
}
