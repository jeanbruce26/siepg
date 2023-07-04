<?php

namespace App\Http\Livewire\ModuloAdministrador\Estudiante;

use App\Models\Admision;
use App\Models\Inscripcion;
use App\Models\Persona;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    //paginacion de bootstrapprocesoFiltro
    protected $paginationTheme = 'bootstrap';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'procesoFiltro' => ['except' => ''],
    ];

    public $search = '';
    public $titulo = 'Detalle del estudiante';
    public $modo = 1;//1 = Detalle, 2 = Editar

    //Variables para el filtro de Persona
    public $procesoFiltro;
    public $filtro_proceso;

    //Valiables de los modelos de Persona

    protected $listeners = ['render', 'cambiarEstado'];


    //Limpiamos los filtros
    public function resetear_filtro()
    {
        $admisionActivo = Admision::where('admision_estado', 1)->first();
        $this->procesoFiltro = $admisionActivo->id_admision;
        $this->filtro_proceso = $admisionActivo->id_admision;
    }

    //Asignamos los filtros
    public function filtrar()
    {
        if($this->procesoFiltro == null || $this->procesoFiltro == "")
        {
            $admisionActivo = Admision::where('admision_estado', 1)->first();
            $this->procesoFiltro = $admisionActivo->id_admision;
            $this->filtro_proceso = $admisionActivo->id_admision;
        }else{
            $this->procesoFiltro = $this->filtro_proceso;
        }
    }

    public function render()
    {
        $admisionActivo = Admision::where('admision_estado', 1)->first();
        //Filtrar siempre el proceso activo
        if($this->procesoFiltro == null || $this->procesoFiltro == "")
        {
            $this->procesoFiltro = $admisionActivo->id_admision;
            $this->filtro_proceso = $admisionActivo->id_admision;
        }

        $estudiantesModel = Inscripcion::join('persona', 'persona.id_persona', '=', 'inscripcion.id_persona')
                                    ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                    ->join('admision', 'admision.id_admision', '=', 'programa_proceso.id_admision')
                                    ->where(function ($query){
                                        $query->where('nombre_completo', 'like', '%'.$this->search.'%')
                                        ->orWhere('numero_documento', 'like', '%'.$this->search.'%')
                                        ->orWhere('correo', 'like', '%'.$this->search.'%')
                                        ->orWhere('celular', 'like', '%'.$this->search.'%');
                                    })
                                    ->where('admision.id_admision', 'like', '%'.$this->procesoFiltro.'%')
                                    ->orderBy('persona.id_persona', 'desc')
                                    ->paginate(10);
        

        return view('livewire.modulo-administrador.estudiante.index', [
            "estudiantesModel" => $estudiantesModel,
            "procesos" => Admision::all(),
        ]);
    }
}
