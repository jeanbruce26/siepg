<?php

namespace App\Http\Livewire\ModuloCoordinador\GestionCursos;

use App\Models\CursoProgramaPlan;
use App\Models\EquivalenciaCursos;
use App\Models\Plan;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Equivalencia extends Component
{
    use WithPagination; // Trait de paginación
    protected $paginationTheme = 'bootstrap'; // Tema de paginación

    use WithFileUploads; // Trait de carga de archivos

    // variables de búsqueda
    public $search = '';
    public $facultad;

    // variable para modal
    public $title_modal = '';
    public $plan, $curso, $plan_equivalencia, $curso_equivalencia, $plan_nombre, $plan_equivalencia_nombre;
    public $resolucion, $resolucion_file;

    protected $queryString = [ // variables de búsqueda
        'search' => ['except' => '', 'as' => 'buscar']
    ];

    protected $listeners = [ // listeners / escuchadores activos
        'eliminar_equivalencia_db' => 'eliminar_equivalencia_db'
    ];

    public function mount()
    {
        //
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'plan' => 'required',
            'curso' => 'required',
            'plan_equivalencia' => 'required|different:plan',
            'curso_equivalencia' => 'required|different:curso',
            'resolucion' => 'required',
            'resolucion_file' => 'nullable|mimes:pdf|max:10240'
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function modo()
    {
        $this->limpiar_modal();
        $this->title_modal = 'Agregar equivalencia';
    }

    public function limpiar_modal()
    {
        $this->reset([
            'plan',
            'curso',
            'plan_equivalencia',
            'curso_equivalencia',
            'resolucion',
            'resolucion_file'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
        Session::forget('equivalencias_modal'); // Eliminar los datos de equivalencias de la sesión
    }

    public function updatedPlan($id_plan)
    {
        $equivalencias_modal = Session::get('equivalencias_modal', []); // Obtener las equivalencias almacenadas en la sesión

        $value = false;
        foreach ($equivalencias_modal as $equivalencia_modal) {
            if ($equivalencia_modal['curso']->programa_plan->id_plan != $id_plan) {
                $value = true;
                break;
            }
        }

        if ($value) {
            $this->dispatchBrowserEvent('alerta_base', [
                'title' => '¡Error!',
                'text' => 'El plan seleccionado no coincide con el plan de las equivalencias registradas. Intente con otro plan.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            $this->plan = null;
            return;
        }
    }

    public function updatedPlanEquivalencia($id_plan)
    {
        $equivalencias_modal = Session::get('equivalencias_modal', []); // Obtener las equivalencias almacenadas en la sesión

        $value = false;
        foreach ($equivalencias_modal as $equivalencia_modal) {
            if ($equivalencia_modal['curso_equivalencia']->programa_plan->id_plan != $id_plan) {
                $value = true;
                break;
            }
        }

        if ($value) {
            $this->dispatchBrowserEvent('alerta_base', [
                'title' => '¡Error!',
                'text' => 'El plan seleccionado no coincide con el plan de las equivalencias registradas. Intente con otro plan.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            $this->plan_equivalencia = null;
            return;
        }
    }

    public function agregar_equivalencia()
    {
        $this->validate([
            'plan' => 'required',
            'curso' => 'required',
            'plan_equivalencia' => 'required|different:plan',
            'curso_equivalencia' => 'required|different:curso'
        ]);

        $curso_programa_plan = CursoProgramaPlan::where('id_curso', $this->curso)->first();
        $curso_programa_plan_equivalencia = CursoProgramaPlan::where('id_curso', $this->curso_equivalencia)->first();

        $equivalencias_modal = Session::get('equivalencias_modal', []); // Obtener las equivalencias almacenadas en la sesión

        $value = false;
        foreach ($equivalencias_modal as $equivalencia_modal) {
            if ($equivalencia_modal['curso']->id_curso == $curso_programa_plan->id_curso) {
                $value = true;
                break;
            }
        }

        if ($value) {
            $this->dispatchBrowserEvent('alerta_base', [
                'title' => '¡Error!',
                'text' => 'El curso seleccionado ya tiene una equivalencia registrada. Intente con otro curso.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        // verificar si la equivalencia ya existe en la base de datos
        $equivalencia_db = EquivalenciaCursos::where('id_curso', $this->curso)
            ->where('id_curso_equivalente', $this->curso_equivalencia)
            ->first();

        if ($equivalencia_db) {
            $this->dispatchBrowserEvent('alerta_base', [
                'title' => '¡Error!',
                'text' => 'La equivalencia ya está registrada. Intente con otra equivalencia',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        $equivalencias_modal[] = [
            'curso' => $curso_programa_plan,
            'curso_equivalencia' => $curso_programa_plan_equivalencia
        ];

        Session::put('equivalencias_modal', $equivalencias_modal); // Almacenar las equivalencias actualizadas en la sesión

        // Limpiar los campos del modal
        $this->reset([
            'curso',
            'curso_equivalencia'
        ]);
    }

    public function eliminar_equivalencia($index)
    {
        $equivalencias_modal = Session::get('equivalencias_modal', []); // Obtener las equivalencias almacenadas en la sesión
        unset($equivalencias_modal[$index]); // Eliminar la equivalencia seleccionada
        Session::put('equivalencias_modal', $equivalencias_modal); // Almacenar las equivalencias actualizadas en la sesión
    }

    public function guardar_equivalencia()
    {
        $this->validate([
            // 'plan' => 'required',
            // 'curso' => 'required',
            // 'plan_equivalencia' => 'required|different:plan',
            // 'curso_equivalencia' => 'required|different:curso',
            'resolucion' => 'required',
            'resolucion_file' => 'nullable|mimes:pdf|max:10240'
        ]);

        $equivalencias_modal = Session::get('equivalencias_modal', []); // Obtener las equivalencias almacenadas en la sesión

        if (count($equivalencias_modal) == 0) {
            $this->dispatchBrowserEvent('alerta_base', [
                'title' => '¡Error!',
                'text' => 'No se ha agregado ninguna equivalencia. Agregue al menos una equivalencia.',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        foreach ($equivalencias_modal as $equivalencia_modal) {
            $equivalencia = new EquivalenciaCursos();
            $equivalencia->id_curso = $equivalencia_modal['curso']->id_curso;
            $equivalencia->id_curso_equivalente = $equivalencia_modal['curso_equivalencia']->id_curso;
            $equivalencia->equivalencia_resolucion = $this->resolucion;
            if ($this->resolucion_file) {
                $slug_resolucion = Str::slug($this->resolucion);
                $path = 'Posgrado/Equivalencia/Resolucion/';
                $filename = 'equivalencia-' . $slug_resolucion . '-' . date('YmdHis') . '.pdf';
                $nombre_db = $path.$filename;
                $data = $this->resolucion_file;
                $data->storeAs($path, $filename, 'files_publico');
                $equivalencia->equivalencia_resolucion_url = $nombre_db;
            }
            $equivalencia->equivalencia_fecha_creacion = date('Y-m-d H:i:s');
            $equivalencia->equivalencia_estado = 1;
            $equivalencia->save();
        }

        Session::forget('equivalencias_modal'); // Eliminar los datos de equivalencias de la sesión

        $this->dispatchBrowserEvent('alerta_base', [
            'title' => '¡Éxito!',
            'text' => 'Equivalencia registrada correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function eliminar_equivalencia_db_alerta($id_equivalencia)
    {
        $this->dispatchBrowserEvent('alerta_avanzada', [
            'title' => '¡Alerta!',
            'text' => '¿Está seguro de eliminar la equivalencia?',
            'icon' => 'question',
            'confirmButtonText' => 'Aceptar',
            'cancelButtonText' => 'Cancelar',
            'confirmButtonColor' => 'primary',
            'cancelButtonColor' => 'danger',
            'metodo' => 'eliminar_equivalencia_db',
            'id' => $id_equivalencia
        ]);
    }

    public function eliminar_equivalencia_db($id_equivalencia)
    {
        $equivalencia = EquivalenciaCursos::find($id_equivalencia);
        $equivalencia->equivalencia_estado = 0;
        $equivalencia->save();

        $this->dispatchBrowserEvent('alerta_base', [
            'title' => '¡Éxito!',
            'text' => 'Equivalencia eliminada correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function render()
    {
        $trabajador = auth('usuario')->user()->trabajador_tipo_trabajador->trabajador;
        $coordinador = $trabajador->coordinador;
        $this->facultad = $coordinador->facultad;

        $this->plan_nombre = Plan::find($this->plan);
        $this->plan_nombre = $this->plan_nombre ? 'del Plan '.$this->plan_nombre->plan : '';
        $this->plan_equivalencia_nombre = Plan::find($this->plan_equivalencia);
        $this->plan_equivalencia_nombre = $this->plan_equivalencia_nombre ? 'del Plan '.$this->plan_equivalencia_nombre->plan : '';

        $equivalencias = EquivalenciaCursos::where('equivalencia_estado', 1)
            ->orderBy('id_equivalencia', 'desc')
            ->paginate(10);

        $planes = Plan::orderBy('plan', 'desc')->get();

        $cursos = $this->plan ?
            CursoProgramaPlan::join('curso', 'curso.id_curso', '=', 'curso_programa_plan.id_curso')
                ->join('ciclo', 'ciclo.id_ciclo', '=', 'curso.id_ciclo')
                ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'curso_programa_plan.id_programa_plan')
                ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                ->join('plan', 'plan.id_plan', '=', 'programa_plan.id_plan')
                ->where('plan.id_plan', $this->plan)
                ->where('programa.id_facultad', $this->facultad->id_facultad)
                ->get() :
            collect();

        $cursos_equivalencias = $this->plan_equivalencia ?
            CursoProgramaPlan::join('curso', 'curso.id_curso', '=', 'curso_programa_plan.id_curso')
                ->join('ciclo', 'ciclo.id_ciclo', '=', 'curso.id_ciclo')
                ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'curso_programa_plan.id_programa_plan')
                ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                ->join('plan', 'plan.id_plan', '=', 'programa_plan.id_plan')
                ->where('plan.id_plan', $this->plan_equivalencia)
                ->where('programa.id_facultad', $this->facultad->id_facultad)
                ->get() :
            collect();

        $equivalencias_modal = Session::get('equivalencias_modal', []); // Obtener las equivalencias almacenadas en la sesión


        return view('livewire.modulo-coordinador.gestion-cursos.equivalencia', [
            'equivalencias' => $equivalencias,
            'planes' => $planes,
            'cursos' => $cursos,
            'cursos_equivalencias' => $cursos_equivalencias,
            'equivalencias_modal' => $equivalencias_modal
        ]);
    }
}
