<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Programa;

use App\Models\Facultad;
use App\Models\Plan;
use App\Models\Programa;
use App\Models\Sede;
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
    public $titulo = 'Crear Programa de Estudios';
    public $id_plan;

    public $modo = 1;

    public $buscar_programa = 'all';
    public $buscar_plan = 'all';

    //modelos
    public $programa_model_form;

    //form
    public $plan;
    public $sede;
    public $programa;
    public $programa_nombre;
    public $facultad;
    public $codigo_subprograma;
    public $inicial_subprograma;
    public $subprograma;
    public $id_subprograma;
    public $codigo_mencion;
    public $mencion;
    public $id_mencion;

    public $mencion_antiguo;

    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'plan' => 'required|numeric',
            'sede' => 'required|numeric',
            'programa' => 'required|numeric',   
        ]);

        if($this->programa){
            $this->programa_nombre = Programa::where('id_programa', $this->programa)->first()->descripcion_programa;
        }
    }

    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
        $this->titulo = 'Crear Programa de Estudios';
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('plan', 'sede', 'programa', 'programa_nombre', 'facultad', 'codigo_subprograma', 'inicial_subprograma', 'subprograma', 'codigo_mencion', 'mencion', 'titulo');
        $this->modo = 1;
    }

    public function updatedSede($id_sede)
    {
        $this->programa_model_form = Programa::where('id_sede', $id_sede)->get();
    }

    public function cargarAlerta($id)
    {
        $this->dispatchBrowserEvent('alertaConfirmacionPrograma', ['id' => $id]);
    }

    public function cambiarEstado(Mencion $mencion)
    {
        if ($mencion->mencion_estado == 1) {
            $mencion->mencion_estado = 0;
        } else {
            $mencion->mencion_estado = 1;
        }

        $mencion->save();

        $this->subirHistorial($mencion->mencion_estado,'Actualizacion de estado de programa','mencion');
        // $this->dispatchBrowserEvent('notificacionPrograma', ['message' =>'Estado del programa actualizado satisfactoriamente.', 'color' => '#2eb867']);
    }

    public function cargarPrograma(Mencion $mencion, $modo)
    {
        $this->limpiar();
        if($modo == 1){
            $this->modo = 2;
            $this->titulo = 'Editar Programa de Estudios';
            $this->id_plan = $mencion->id_plan;
            $this->plan = $mencion->id_plan;
            $this->sede = $mencion->subprograma->programa->sede->cod_sede;
            $this->programa_model_form = Programa::where('id_sede', $this->sede)->get();
            $this->programa = $mencion->subprograma->programa->id_programa;
            if($this->programa){
                $this->programa_nombre = Programa::where('id_programa', $this->programa)->first()->descripcion_programa;
            }
            $this->facultad = $mencion->subprograma->facultad->facultad_id;
            $this->subprograma = $mencion->subprograma->subprograma;
            $this->id_subprograma = $mencion->subprograma->id_subprograma;
            $this->codigo_subprograma = $mencion->subprograma->cod_subprograma;
            $this->inicial_subprograma = $mencion->iniciales;
            $this->codigo_mencion = $mencion->cod_mencion;
            $this->mencion = $mencion->mencion;
            $this->id_mencion = $mencion->id_mencion;
        }
        if($modo == 2){
            $this->modo = 3;
            $this->titulo = 'Copiar Programa de Estudios';
            $this->id_plan = $mencion->id_plan;
            $this->mencion_antiguo = $mencion->id_mencion;
            $this->plan = $mencion->id_plan;
            $this->sede = $mencion->subprograma->programa->sede->cod_sede;
            $this->programa_model_form = Programa::where('id_sede', $this->sede)->get();
            $this->programa = $mencion->subprograma->programa->id_programa;
            if($this->programa){
                $this->programa_nombre = Programa::where('id_programa', $this->programa)->first()->descripcion_programa;
            }
            $this->facultad = $mencion->subprograma->facultad->facultad_id;
            // $this->subprograma_model_form = SubPrograma::where('id_programa', $this->programa)->get();
            $this->codigo_subprograma = $mencion->subprograma->cod_subprograma;
            $this->id_subprograma = $mencion->subprograma->id_subprograma;
            $this->inicial_subprograma = $mencion->iniciales;
            $this->subprograma = $mencion->subprograma->subprograma;
            $this->codigo_mencion = $mencion->cod_mencion;
            $this->mencion = $mencion->mencion;
            $this->id_mencion = $mencion->id_mencion;
        }
    }

    public function guardarPrograma()
    {
        $this->validate([
            'plan' => 'required|numeric',
            'sede' => 'required|numeric',
            'programa' => 'required|numeric',
            'facultad' => 'required|numeric',
            'codigo_subprograma' => 'required|string',
            'inicial_subprograma' => 'required|string',
            'subprograma' => 'required|string',
            'codigo_mencion' => 'nullable|string',
            'mencion' => 'nullable|string',
        ]);
        
        if($this->modo == 1){
            $subprograma = new SubPrograma();
            $subprograma->cod_subprograma = $this->codigo_subprograma;
            $subprograma->subprograma = $this->subprograma;
            $subprograma->id_programa = $this->programa;
            $subprograma->facultad_id = $this->facultad;
            $subprograma->estado = 1;
            $subprograma->save();

            $mencion = new Mencion();
            $mencion->iniciales = $this->inicial_subprograma;
            $mencion->cod_mencion = $this->codigo_mencion;
            $mencion->mencion = $this->mencion;
            $mencion->id_subprograma = $subprograma->id_subprograma;
            $mencion->id_plan = $this->plan;
            $mencion->mencion_estado = 1;
            $mencion->save();

            $this->subirHistorial($mencion->id_mencion,'Creacion de programa','mencion');
            // $this->dispatchBrowserEvent('notificacionPrograma', ['message' =>'Programa creado satisfactoriamente.', 'color' => '#2eb867']);

        }else if($this->modo == 2){
            $subprograma = SubPrograma::find($this->id_subprograma);
            $subprograma->cod_subprograma = $this->codigo_subprograma;
            $subprograma->subprograma = $this->subprograma;
            $subprograma->id_programa = $this->programa;
            $subprograma->facultad_id = $this->facultad;
            $subprograma->estado = 1;
            $subprograma->save();

            $mencion = Mencion::find($this->id_mencion);
            $mencion->iniciales = $this->inicial_subprograma;
            $mencion->cod_mencion = $this->codigo_mencion;
            $mencion->mencion = $this->mencion;
            $mencion->id_subprograma = $this->id_subprograma;
            $mencion->id_plan = $this->plan;
            $mencion->save();
            
            $this->subirHistorial($mencion->id_mencion,'Actualizacion de programa','mencion');
            // $this->dispatchBrowserEvent('notificacionPrograma', ['message' =>'Programa actuaizado satisfactoriamente.', 'color' => '#2eb867']);

        }else if($this->modo == 3){
            $subprogram = SubPrograma::where('id_subprograma', $this->id_subprograma)
                ->where('id_programa', $this->programa)
                ->where('cod_subprograma', $this->codigo_subprograma)
                ->where('subprograma', $this->subprograma)
                ->where('facultad_id', $this->facultad)
                ->first();

            if($subprogram){
                $mencion = new Mencion();
                $mencion->iniciales = $this->inicial_subprograma;
                if($this->codigo_mencion){
                    $mencion->cod_mencion = $this->codigo_mencion;
                }
                if($this->mencion){
                    $mencion->mencion = $this->mencion;
                }
                $mencion->id_subprograma = $subprogram->id_subprograma;
                $mencion->id_plan = $this->plan;
                $mencion->mencion_estado = 1;
                $mencion->save();

                $mencion_antiguo = Mencion::find($this->mencion_antiguo);
                $mencion_antiguo->mencion_estado = 0;
                $mencion_antiguo->save();

                $this->subirHistorial($mencion->id_mencion,'Copia de programa para nuevo plan','mencion');
            }else{
                return back()->with(array('mensaje'=>'Error al ingresar los datos del programa.'));
            }

            // $this->dispatchBrowserEvent('notificacionPrograma', ['message' =>'Programa copiado satisfactoriamente.', 'color' => '#2eb867']);
        }

        $this->dispatchBrowserEvent('modalPrograma');

        $this->limpiar();
    }

    public function cargarVistaCurso($mencion_id){
        // dd('mencion_id = ' . $mencion_id);

        return redirect()->route('admin.programa.curso', $mencion_id);
    }
    
    public function render()
    {
        $buscar_programa = $this->buscar_programa;
        $buscar_plan = $this->buscar_plan;
        $buscar = $this->search;

        if ($buscar_programa == 'all' && $buscar_plan == 'all') {
            $programas = Mencion::join('subprograma', 'mencion.id_subprograma', '=', 'subprograma.id_subprograma')
                ->join('facultad', 'subprograma.facultad_id', '=', 'facultad.facultad_id')
                ->join('programa', 'subprograma.id_programa', '=', 'programa.id_programa')
                ->join('sede', 'programa.id_sede', '=', 'sede.cod_sede')
                ->join('plan', 'mencion.id_plan', '=', 'plan.id_plan')
                ->where('programa.descripcion_programa', 'LIKE', '%' . $this->search . '%')
                ->orWhere('sede.sede', 'LIKE', '%' . $this->search . '%')
                ->orWhere('subprograma.subprograma', 'LIKE', '%' . $this->search . '%')
                ->orWhere('mencion.mencion', 'LIKE', '%' . $this->search . '%')
                ->orWhere('programa.descripcion_programa', $this->buscar_programa)
                ->orWhere('mencion.id_mencion', 'LIKE', '%' . $this->search . '%')
                ->orderBy('mencion.id_mencion', 'DESC')
                ->paginate(50);
        }else if($buscar_programa != 'all' && $buscar_plan == 'all'){
            $programas = Mencion::join('subprograma', 'mencion.id_subprograma', '=', 'subprograma.id_subprograma')
                ->join('facultad', 'subprograma.facultad_id', '=', 'facultad.facultad_id')
                ->join('programa', 'subprograma.id_programa', '=', 'programa.id_programa')
                ->join('sede', 'programa.id_sede', '=', 'sede.cod_sede')
                ->join('plan', 'mencion.id_plan', '=', 'plan.id_plan')
                ->where(function($query) use ($buscar_programa){
                    $query->where('programa.descripcion_programa',$buscar_programa);
                })
                ->where(function($query) use ($buscar){
                    $query->where('programa.descripcion_programa', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('sede.sede', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('subprograma.subprograma', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('mencion.id_mencion', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('mencion.mencion', 'LIKE', '%' . $buscar . '%');
                })
                ->orderBy('mencion.id_mencion', 'DESC')
                ->paginate(50);
        }else if ($buscar_programa == 'all' && $buscar_plan != 'all') {
            $programas = Mencion::join('subprograma', 'mencion.id_subprograma', '=', 'subprograma.id_subprograma')
                ->join('facultad', 'subprograma.facultad_id', '=', 'facultad.facultad_id')
                ->join('programa', 'subprograma.id_programa', '=', 'programa.id_programa')
                ->join('sede', 'programa.id_sede', '=', 'sede.cod_sede')
                ->join('plan', 'mencion.id_plan', '=', 'plan.id_plan')
                ->where(function($query) use ($buscar_plan){
                    $query->where('plan.id_plan',$buscar_plan);
                })
                ->where(function($query) use ($buscar){
                    $query->where('programa.descripcion_programa', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('sede.sede', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('subprograma.subprograma', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('mencion.id_mencion', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('mencion.mencion', 'LIKE', '%' . $buscar . '%');
                })
                ->orderBy('mencion.id_mencion', 'DESC')
                ->paginate(50);
        }else if ($buscar_programa != 'all' && $buscar_plan != 'all') {
            $programas = Mencion::join('subprograma', 'mencion.id_subprograma', '=', 'subprograma.id_subprograma')
                ->join('facultad', 'subprograma.facultad_id', '=', 'facultad.facultad_id')
                ->join('programa', 'subprograma.id_programa', '=', 'programa.id_programa')
                ->join('sede', 'programa.id_sede', '=', 'sede.cod_sede')
                ->join('plan', 'mencion.id_plan', '=', 'plan.id_plan')
                ->where(function($query) use ($buscar_programa){
                    $query->where('programa.descripcion_programa',$buscar_programa);
                })
                ->where(function($query) use ($buscar_plan){
                    $query->where('plan.id_plan',$buscar_plan);
                })
                ->where(function($query) use ($buscar){
                    $query->where('programa.descripcion_programa', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('sede.sede', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('subprograma.subprograma', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('mencion.id_mencion', 'LIKE', '%' . $buscar . '%')
                    ->orWhere('mencion.mencion', 'LIKE', '%' . $buscar . '%');
                })
                ->orderBy('mencion.id_mencion', 'DESC')
                ->paginate(50);
        }
        
        $programa_model = Programa::groupBy('programa')->get();
        $plan_model = Plan::where('estado',1)->groupBy('plan')->get();
        $sede_model = Sede::all();
        $facultad_model = Facultad::all();

        return view('livewire.modulo-administrador.configuracion.programa.index', [
            'programas' => $programas,
            'programa_model' => $programa_model,
            'plan_model' => $plan_model,
            'sede_model' => $sede_model,
            'facultad_model' => $facultad_model,
        ]);
    }
}
