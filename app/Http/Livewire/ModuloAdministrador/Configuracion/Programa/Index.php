<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Programa;

use App\Models\Facultad;
use App\Models\Programa;
use App\Models\Sede;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;
    //paginacion de bootstrap
    protected $paginationTheme = 'bootstrap';
    
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';
    public $titulo = 'Crear Programa de Estudios';

    public $modo = 1;//1 = Crear, 2 = Actualizar, 3 = Detalle

    //Valiables de los modelos de Programa
    public $id_programa;
    public $programa_iniciales;
    public $programa;
    public $subprograma;
    public $mencion;
    public $id_sunedu;
    public $codigo_sunedu;
    public $id_modalidad;
    public $id_facultad;
    public $id_sede;
    public $programa_tipo;
    public $programa_estado;



    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'programa_iniciales' => 'required, String, max:255',
            'programa' => 'required, String, max:255',
            'subprograma' => 'required, String, max:255',
            'mencion' => 'nullable, String, max:255',
            'id_sunedu' => 'required, numeric',
            'codigo_sunedu' => 'nullable, String, max:255',
            'id_modalidad' => 'required, numeric',
            'id_facultad' => 'required, numeric',
            'id_sede' => 'required, numeric',
            'programa_tipo' => 'required, numeric',
        ]);
    }

    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
        $this->titulo = 'Crear Programa de Estudios';
    }

    public function limpiar()
    {
        $this->resetErrorBag();//Elimina los mensajes de error de validacion
        $this->reset('programa_iniciales, programa, subprograma, mencion, id_sunedu, codigo_sunedu, id_modalidad, id_facultad, id_sede, programa_tipo');//Limpiar todas las variables
        $this->modo = 1;//1 = Crear, 2 = Actualizar, 3 = Detalle
    }
    
    public function render()
    {
        $buscar = $this->search;
        
        $sede_model = Sede::all();
        $facultad_model = Facultad::all();
        $programaModel = Programa::where('programa', 'like', '%'.$buscar.'%')
            ->orWhere('id_programa', 'like', '%'.$buscar.'%')
            ->orWhere('programa_iniciales', 'like', '%'.$buscar.'%')
            ->orWhere('subprograma', 'like', '%'.$buscar.'%')
            ->orWhere('mencion', 'like', '%'.$buscar.'%')
            ->orWhere('codigo_sunedu', 'like', '%'.$buscar.'%')
            ->orWhere('id_sunedu', 'like', '%'.$buscar.'%')
            ->orWhere('id_modalidad', 'like', '%'.$buscar.'%')
            ->orWhere('id_facultad', 'like', '%'.$buscar.'%')
            ->orWhere('id_sede', 'like', '%'.$buscar.'%')
            ->orWhere('programa_tipo', 'like', '%'.$buscar.'%')
            ->orderBy('id_programa', 'desc')
            ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.programa.index', [
            'programaModel' => $programaModel,
            'sede_model' => $sede_model,
            'facultad_model' => $facultad_model,
        ]);
    }
}
