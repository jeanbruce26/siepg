<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionReingreso\Individual;

use App\Models\Admitido;
use App\Models\CursoProgramaPlan;
use App\Models\Matricula;
use App\Models\MatriculaCurso;
use App\Models\NotaMatriculaCurso;
use App\Models\ProgramaPlan;
use App\Models\ProgramaProceso;
use App\Models\ProgramaProcesoGrupo;
use App\Models\Reingreso;
use App\Models\Retiro;
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

    // variables del model
    public $title_modal = 'Nuevo Reingreso Individual';
    public $paso = 1;
    public $total_paso = 3;
    public $estudiante;
    public $detalle_estudiante;
    public $plan;
    public $proceso;
    public $grupo;
    public $notas = [];
    public $resolucion;
    public $resolucion_file;

    protected $queryString = [ // para que la paginacion se mantenga con el buscador
        'search' => ['except' => '', 'as' => 's'],
    ];

    public function updated($propertyName)
    {
        if ($this->paso == 1) {
            $this->validateOnly($propertyName, [
                'estudiante' => 'required',
                'plan' => 'required',
                'proceso' => 'required',
                'grupo' => 'required',
            ]);
        }

        if ($this->paso == 2) {
            $this->validateOnly($propertyName, [
                'notas' => 'required|array|min:1',
                'notas.*' => 'nullable|numeric|min:0|max:20',
            ]);
        }

        if ($this->paso == 3) {
            $this->validateOnly($propertyName, [
                'resolucion' => 'required',
                'resolucion_file' => 'nullable|file|mimes:pdf|max:10240',
            ]);
        }

        foreach ($this->notas as $key => $nota) {
            if ($nota == null || $nota == '') {
                // elimianr la nota del array
                unset($this->notas[$key]);
            }
        }
    }

    public function modo()
    {
        $this->limpiar_modal();
        $this->resetErrorBag();
        $this->resetValidation();

        $this->title_modal = 'Nuevo Reingreso Individual';
        $this->paso = 1;
    }

    public function limpiar_modal()
    {
        $this->reset([
            'title_modal',
            'paso',
            'estudiante',
            'detalle_estudiante',
            'plan',
            'proceso',
            'grupo',
            'notas',
            'resolucion',
            'resolucion_file',
        ]);
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
        if ($this->paso == 1) {
            $this->validate([
                'estudiante' => 'required',
                'plan' => 'required',
                'proceso' => 'required',
                'grupo' => 'required',
            ]);
        }

        if ($this->paso == 2) {
            // validamos si el array de notas esta vacio
            if (count($this->notas) == 0) {
                $this->dispatchBrowserEvent('alerta-basica', [
                    'title' => '¡Error!',
                    'text' => 'Debe ingresar al menos una nota, para continuar con el proceso de reingreso.',
                    'icon' => 'error',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'danger'
                ]);
                return;
            }

            $this->validate([
                'notas' => 'required|array|min:1',
                'notas.*' => 'nullable|numeric|min:0|max:20',
            ]);
        }

        if ($this->paso < $this->total_paso) {
            $this->paso++;
        }

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedEstudiante($id_estudiante)
    {
        $estudiante = Admitido::find($id_estudiante);
        $this->detalle_estudiante = $estudiante;
    }

    public function guardar_reingreso()
    {
        $this->validate([
            'resolucion' => 'required',
            'resolucion_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // generar codigo de reingreso
        $codigo = date('YmdHis');
        $codigo = 'R' . $codigo . 'I';

        // obtener el estudiante
        $estudiante = Admitido::find($this->estudiante);

        // obtener el programa del estudiante
        $programa = ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->join('plan', 'programa_plan.id_plan', 'plan.id_plan')
                ->join('admision', 'programa_proceso.id_admision', 'admision.id_admision')
                ->where('programa.programa_tipo', $estudiante->programa_proceso->programa_plan->programa->programa_tipo)
                ->where('programa.id_programa', $estudiante->programa_proceso->programa_plan->programa->id_programa)
                ->where('plan.id_plan', $this->plan)
                ->where('admision.id_admision', $this->proceso)
                ->first();

        // registrar reingreso
        $reingreso = new Reingreso();
        $reingreso->reingreso_codigo = $codigo;
        $reingreso->id_admitido = $estudiante->id_admitido;
        $reingreso->id_programa_proceso = $programa->id_programa_proceso;
        $reingreso->id_programa_proceso_antiguo = $estudiante->id_programa_proceso;
        $reingreso->id_tipo_reingreso = 1; // Individual
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
        $reingreso->reingreso_estado = 1; // Pendiente
        $reingreso->save();

        // actualizar programa del admitido (estudiante) y el estado
        $estudiante->id_programa_proceso_antiguo = $estudiante->id_programa_proceso;
        $estudiante->save();
        $estudiante->id_programa_proceso = $programa->id_programa_proceso;
        $estudiante->admitido_estado = 1;  // 1 = admitido normal | 2 = retirado | 0 = desactivado
        $estudiante->save();

        // Desactivar el registro de retirado del alumno que esta reingresando
        $retiro = Retiro::where('id_admitido', $estudiante->id_admitido)->orderBy('id_retiro', 'desc')->first();
        if ($retiro) {
            $retiro->retiro_estado = 0;
            $retiro->save();
        }

        // volvemos a octener el estudiante con su nuevo programa
        $estudiante = Admitido::find($this->estudiante);

        // guardar las notas de los cursos en una matricula cero (0)
        // generar codigo de matricula
        $codigo = 'M' . date('Y') . str_pad(1, 5, "0", STR_PAD_LEFT);

        // obtener el ultimo registro de matricula
        $matricula = Matricula::orderBy('id_matricula', 'desc')->first();

        if ( $matricula ) {
            $codigo = $matricula->matricula_codigo;
            $codigo = substr($codigo, 5);
            $codigo = (int)$codigo + 1;
            $codigo = 'M' . date('Y') . str_pad($codigo, 5, "0", STR_PAD_LEFT);
        } else {
            $codigo = 'M' . date('Y') . str_pad(1, 5, "0", STR_PAD_LEFT);
        }

        // buscamos las matriculas del admitido
        $matriculas = Matricula::where('id_admitido', $estudiante->id_admitido)->where('matricula_estado', 1)->get();

        // obtener ultima matricula del admitido
        $ultima_matricula_admitido = Matricula::where('id_admitido', $estudiante->id_admitido)->orderBy('id_matricula', 'desc')->first();
        $grup_antiguo = $ultima_matricula_admitido ? $ultima_matricula_admitido->id_programa_proceso_grupo : null;

        // registrar matricula
        $matricula = new Matricula();
        $matricula->matricula_codigo = $codigo;
        if ($matriculas->count() == 0) {
            $matricula->matricula_proceso = $estudiante->programa_proceso->admision->admision_año . ' - ' . 0;
        } else {
            $matricula->matricula_proceso = $estudiante->programa_proceso->admision->admision_año . ' - ' . ($matriculas->count() + 1);
        }
        $matricula->matricula_year = date('Y-m-d');
        $matricula->matricula_fecha_creacion = date('Y-m-d H:i:s');
        $matricula->matricula_estado = 1;
        $matricula->id_admitido = $estudiante->id_admitido;
        if ( $matriculas->count() == 0 ) {
            $matricula->id_programa_proceso_grupo = $this->grupo;
        } else {
            $matricula->id_programa_proceso_grupo = $grup_antiguo;
        }
        $matricula->id_pago = null;
        if ( $matriculas->count() == 0 ) {
            $matricula->matricula_primer_ciclo = 1; // si es la primera matricula del admitido
        }
        $matricula->save();

        // registramos los cursos de la matricula cero (0)
        foreach ( $this->notas as $key => $item )
        {
            $matricula_curso = new MatriculaCurso();
            $matricula_curso->id_matricula = $matricula->id_matricula;
            $matricula_curso->id_curso_programa_plan = $key;
            $matricula_curso->id_admision = $estudiante->programa_proceso->id_admision;
            $matricula_curso->id_programa_proceso_grupo = $this->grupo;
            $matricula_curso->matricula_curso_fecha_creacion = date('Y-m-d H:i:s');
            $matricula_curso->matricula_curso_estado = 1;
            $matricula_curso->save();
        }

        // registramos las notas de los cursos de la matricula cero (0)
        foreach ( $this->notas as $key => $item )
        {
            // convertimos la nota en entero
            $item = (int)$item;
            $matricula_curso = MatriculaCurso::where('id_matricula', $matricula->id_matricula)->where('id_curso_programa_plan', $key)->first();
            $nota = new NotaMatriculaCurso();
            $nota->id_matricula_curso = $matricula_curso->id_matricula_curso;
            $nota->nota_promedio_final = $item;
            $nota->nota_matricula_curso_fecha_creacion = date('Y-m-d H:i:s');
            $nota->nota_matricula_curso_estado = 1;
            if ( $item >= 14 ) {
                $nota->id_estado_cursos = 1;
                // cambiamos el estado de la matricula_curso a finalizado
                $matricula_curso->matricula_curso_estado = 2; // 2 = curso finalizado
            } else if ( $item >= 10 && $item < 14) {
                $nota->id_estado_cursos = 2;
                // cambiamos el estado de la matricula_curso a finalizado
                $matricula_curso->matricula_curso_estado = 0; // 2 = curso finalizado
            } else {
                $nota->id_estado_cursos = 3;
                // cambiamos el estado de la matricula_curso a finalizado
                $matricula_curso->matricula_curso_estado = 0; // 2 = curso finalizado
            }
            $nota->id_docente = null;
            $nota->save();

            $matricula_curso->save();
        }

        // cerrar modal
        $this->dispatchBrowserEvent('modal', [
            'modal' => '#modal_reingreso',
            'action' => 'hide',
        ]);

        // emitir evento para mostrar mensaje de confirmacion
        $this->dispatchBrowserEvent('alerta-basica', [
            'title' => '¡Éxito!',
            'text' => 'Reingreso individual registrado correctamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // limpiar variables
        $this->limpiar_modal();
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

        $estudiantes = Admitido::join('programa_proceso', 'admitido.id_programa_proceso', 'programa_proceso.id_programa_proceso')
            ->join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
            ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
            ->join('persona', 'admitido.id_persona', 'persona.id_persona')
            ->where('admitido.admitido_estado', 2)
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();

        // obtener planes y proceso
        if ($this->estudiante) {
            $estudiante = Admitido::find($this->estudiante);

            $planes = ProgramaPlan::join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->join('plan', 'programa_plan.id_plan', 'plan.id_plan')
                ->orderBy('plan.plan', 'asc')
                ->select('plan.id_plan', 'plan.plan')
                ->distinct()
                ->get();

            if ($this->plan) {
                // if ($this->plan == $estudiante->programa_proceso->programa_plan->id_plan) {
                //     // emitir alerta de que el estudiante no puede reingresar al mismo plan de estudios
                //     $this->dispatchBrowserEvent('alerta-basica', [
                //         'title' => '¡Alerta!',
                //         'text' => 'El estudiante no puede reingresar al mismo plan de estudios',
                //         'icon' => 'warning',
                //         'confirmButtonText' => 'Aceptar',
                //         'color' => 'warning'
                //     ]);
                //     $procesos = collect();
                // } else {
                    $procesos = ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
                        ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                        ->join('plan', 'programa_plan.id_plan', 'plan.id_plan')
                        ->join('admision', 'programa_proceso.id_admision', 'admision.id_admision')
                        ->where('programa.programa_tipo', $estudiante->programa_proceso->programa_plan->programa->programa_tipo)
                        ->where('plan.id_plan', $this->plan)
                        ->orderBy('admision.admision', 'asc')
                        ->select('admision.id_admision', 'admision.admision')
                        ->distinct()
                        ->get();
                // }
            } else {
                $procesos = collect();
            }
        } else {
            $planes = collect();
            $procesos = collect();
        }

        // cargar los cursos del nuevo plan de estudios
        if ($this->proceso) {
            $programa = ProgramaProceso::join('programa_plan', 'programa_proceso.id_programa_plan', 'programa_plan.id_programa_plan')
                ->join('programa', 'programa_plan.id_programa', 'programa.id_programa')
                ->join('plan', 'programa_plan.id_plan', 'plan.id_plan')
                ->join('admision', 'programa_proceso.id_admision', 'admision.id_admision')
                ->where('programa.programa_tipo', $estudiante->programa_proceso->programa_plan->programa->programa_tipo)
                ->where('plan.id_plan', $this->plan)
                ->where('admision.id_admision', $this->proceso)
                ->where('programa.id_programa', $estudiante->programa_proceso->programa_plan->programa->id_programa)
                ->first();
            if ($programa) {
                $cursos = CursoProgramaPlan::join('curso', 'curso_programa_plan.id_curso', 'curso.id_curso')
                    ->where('curso_programa_plan.id_programa_plan', $programa->id_programa_plan)
                    ->orderBy('curso.curso_codigo', 'asc')
                    ->orderBy('curso.id_ciclo', 'asc')
                    ->get();
                $grupos = ProgramaProcesoGrupo::where('id_programa_proceso', $programa->id_programa_proceso)->get();
            } else {
                $cursos = collect();
                $grupos = collect();
            }
        } else {
            $cursos = collect();
            $grupos = collect();
        }



        return view('livewire.modulo-administrador.gestion-reingreso.individual.index', [
            'reingresos' => $reingresos,
            'estudiantes' => $estudiantes,
            'planes' => $planes,
            'procesos' => $procesos,
            'cursos' => $cursos,
            'grupos' => $grupos,
        ]);
    }
}
