<?php

namespace App\Http\Livewire\ModuloCoordinador\GestionMatricula;

use App\Models\Admision;
use App\Models\Ciclo;
use App\Models\Coordinador;
use App\Models\MatriculaGestion;
use App\Models\ProgramaProceso;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // trait de paginacion
    protected $paginationTheme = 'bootstrap'; // tema de paginacion

    public $coordinador;
    public $matricula_gestion;

    //variables del modal
    public $title_modal = 'Nueva Matricula';
    public $modo = 'create';
    public $proceso;
    public $programa_academico;
    public $ciclo;
    public $fecha_inicio, $fecha_fin, $fecha_extemporanea_inicio, $fecha_extemporanea_fin;
    public $alumnos_minimos;

    public function mount()
    {
        $this->coordinador = Coordinador::where('id_trabajador',auth('usuario')->user()->trabajador_tipo_trabajador->id_trabajador)->first();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'proceso' => 'required',
            'programa_academico' => 'required',
            'ciclo' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'fecha_extemporanea_inicio' => 'required|date|after_or_equal:fecha_fin',
            'fecha_extemporanea_fin' => 'required|date|after_or_equal:fecha_extemporanea_inicio',
            'alumnos_minimos' => 'required|numeric|min:1'
        ]);
    }

    public function modo()
    {
        $this->limpiar_modal();
        $this->modo = 'create';
    }

    public function limpiar_modal()
    {
        $this->reset(
            'proceso',
            'programa_academico',
            'ciclo',
            'fecha_inicio',
            'fecha_fin',
            'fecha_extemporanea_inicio',
            'fecha_extemporanea_fin',
            'alumnos_minimos'
        );
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function cargar_matricula(MatriculaGestion $matricula_gestion)
    {
        $this->modo = 'edit';
        $this->title_modal = 'Editar Matricula';
        $this->matricula_gestion = $matricula_gestion;
        $this->proceso = $matricula_gestion->id_admision;
        $this->programa_academico = $matricula_gestion->id_programa_proceso;
        $this->ciclo = $matricula_gestion->id_ciclo;
        $this->fecha_inicio = $matricula_gestion->matricula_gestion_fecha_inicio;
        $this->fecha_fin = $matricula_gestion->matricula_gestion_fecha_fin;
        $this->fecha_extemporanea_inicio = $matricula_gestion->matricula_gestion_fecha_extemporanea_inicio;
        $this->fecha_extemporanea_fin = $matricula_gestion->matricula_gestion_fecha_extemporanea_fin;
        $this->alumnos_minimos = $matricula_gestion->matricula_gestion_minimo_alumnos;
    }

    public function guardar_matricula()
    {
        $this->validate([
            'proceso' => 'required',
            'programa_academico' => 'required',
            'ciclo' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'fecha_extemporanea_inicio' => 'required|date|after_or_equal:fecha_fin',
            'fecha_extemporanea_fin' => 'required|date|after_or_equal:fecha_extemporanea_inicio',
            'alumnos_minimos' => 'required|numeric|min:1'
        ]);

        if ($this->modo == 'create')
        {
            $matricula_gestion = new MatriculaGestion();
            $matricula_gestion->id_programa_proceso = $this->programa_academico;
            $matricula_gestion->id_admision = $this->proceso;
            $matricula_gestion->id_ciclo = $this->ciclo;
            $matricula_gestion->matricula_gestion_fecha_inicio = $this->fecha_inicio;
            $matricula_gestion->matricula_gestion_fecha_fin = $this->fecha_fin;
            $matricula_gestion->matricula_gestion_fecha_extemporanea_inicio = $this->fecha_extemporanea_inicio;
            $matricula_gestion->matricula_gestion_fecha_extemporanea_fin = $this->fecha_extemporanea_fin;
            $matricula_gestion->matricula_gestion_fecha_creacion = date('Y-m-d H:i:s');
            $matricula_gestion->matricula_gestion_estado = 1;
            $matricula_gestion->matricula_gestion_minimo_alumnos = $this->alumnos_minimos;
            $matricula_gestion->save();

            // asignamos el siguiente ciclo a los alumnos que estan en el ciclo actual

        }
        else
        {
            $this->matricula_gestion->id_programa_proceso = $this->programa_academico;
            $this->matricula_gestion->id_admision = $this->proceso;
            $this->matricula_gestion->id_ciclo = $this->ciclo;
            $this->matricula_gestion->matricula_gestion_fecha_inicio = $this->fecha_inicio;
            $this->matricula_gestion->matricula_gestion_fecha_fin = $this->fecha_fin;
            $this->matricula_gestion->matricula_gestion_fecha_extemporanea_inicio = $this->fecha_extemporanea_inicio;
            $this->matricula_gestion->matricula_gestion_fecha_extemporanea_fin = $this->fecha_extemporanea_fin;
            $this->matricula_gestion->matricula_gestion_minimo_alumnos = $this->alumnos_minimos;
            $this->matricula_gestion->save();
        }

        // evento para cerrar el modal
        $this->dispatchBrowserEvent('modal_gestion_matricula', ['action' => 'hide']);

        // evento para mostrar mensaje de éxito
        if ($this->modo == 'create')
        {
            $this->dispatchBrowserEvent('alerta_matricula', [
                'title' => '¡Éxito!',
                'text' => 'Matricula registrada correctamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }
        else
        {
            $this->dispatchBrowserEvent('alerta_matricula', [
                'title' => '¡Éxito!',
                'text' => 'Matricula actualizada correctamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);
        }
    }

    public function render()
    {
        $matriculas = MatriculaGestion::orderBy('id_matricula_gestion', 'desc')->paginate(10);

        $admisiones = Admision::orderBy('id_admision', 'desc')->get();

        if ($this->proceso)
        {
            $programas_model = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', 'programa_proceso.id_programa_plan')
                ->join('programa', 'programa.id_programa', 'programa_plan.id_programa')
                ->where('programa_proceso.id_admision', $this->proceso)
                ->where('programa.id_facultad', $this->coordinador->id_facultad)
                ->get();
            $ciclos_model = collect();
        }
        else
        {
            $programas_model = collect();
            $this->programa_academico = null;
            $ciclos_model = collect();
            $this->ciclo = null;
        }

        if ($this->programa_academico)
        {
            $programa = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', 'programa_proceso.id_programa_plan')
                ->join('programa', 'programa.id_programa', 'programa_plan.id_programa')
                ->where('programa_proceso.id_programa_proceso', $this->programa_academico)
                ->first();
            if($programa)
            {
                $ciclos_model = Ciclo::where(function ($query) use ($programa){
                                            $query->where('ciclo_programa', $programa->programa_tipo)
                                                ->orWhere('ciclo_programa', 0);
                                        })
                                        ->get();
            }
            else
            {
                $ciclos_model = collect();
                $this->ciclo = null;
            }
        }
        else
        {
            $ciclos_model = collect();
            $this->ciclo = null;
        }

        return view('livewire.modulo-coordinador.gestion-matricula.index', [
            'matriculas' => $matriculas,
            'admisiones' => $admisiones,
            'programas_model' => $programas_model,
            'ciclos_model' => $ciclos_model
        ]);
    }
}
