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

    //Variables para el filtro de programas
    public $tipoProgramaFiltro;//Para la busqueda de programas por tipo
    public $filtro_programa;//Para el filtro de programas por tipo
    public $modalidadFiltro;//Para la busqueda de programas por modalidad
    public $filtro_modalidad;//Para el filtro de programas por modalidad

    //Valiables de los modelos de Programa
    public $id_programa;
    public $programa_iniciales;
    public $programa;
    public $subprograma;
    public $mencion;
    public $id_sunedu;
    public $codigo_sunedu;
    public $id_modalidad;//1 = presencial, 2 = virtual
    public $id_facultad;
    public $id_sede;
    public $programa_tipo;// 1 = Maestria, 2 = Doctorado
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

    //Alerta de confirmacion
    public function alertaConfirmacion($title, $text, $icon, $confirmButtonText, $cancelButtonText, $confimrColor, $cancelColor, $metodo, $id)
    {
        $this->dispatchBrowserEvent('alertaConfirmacion', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'cancelButtonText' => $cancelButtonText,
            'confimrColor' => $confimrColor,
            'cancelColor' => $cancelColor,
            'metodo' => $metodo,
            'id' => $id,
        ]);
    }

    //Alertas de exito o error
    public function alertaExpediente($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-programa', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Mostar modal de confirmacion para cambiar el estado del programa
    public function cargarAlertaEstado($id)
    {   
        $programa = Programa::findOrFail($id);//Busca el programa por su id
        if($programa->mencion){
            $nombre = $programa->programa . ' EN ' . $programa->subprograma . ' CON MENCION EN ' . $programa->mencion;//Concatena el nombre del programa
        }else{
            $nombre = $programa->programa . ' EN ' . $programa->subprograma;//Concatena el nombre del programa
        }
        $this->alertaConfirmacion('¿Estás seguro?',"¿Desea cambiar el estado del programa de $nombre?",'question','Modificar','Cancelar','primary','danger','cambiarEstado',$id);
    }

    public function resetear_filtro()
    {
        $this->reset('tipoProgramaFiltro', 'filtro_programa', 'modalidadFiltro', 'filtro_modalidad');
    }

    public function filtrar()
    {
        $this->tipoProgramaFiltro = $this->filtro_programa;
        $this->modalidadFiltro = $this->filtro_modalidad;
    }

    //Cambiar el estado del programa
    public function cambiarEstado(Programa $programa)
    {
        if ($programa->programa_estado == 1) {//Si el estado es 1 (activo), cambiar a 2 (inactivo)
            $programa->programa_estado = 2;
        } else {//Si el estado es 2 (inactivo), cambiar a 1 (activo)
            $programa->programa_estado = 1;
        }

        $programa->save();//Actualizar el estado del programa

        //Mostrar alerta de confirmacion de cambio de estado
        $this->alertaExpediente('¡Éxito!', "El estado del programa $programa->programa ha sido actualizado satisfactoriamente.", 'success', 'Aceptar', 'success');
    }

    //Cargar los datos del expediente en el formulario para actualizar
    public function cargarExpediente(Programa $programa, $modoTipo)
    {
        $this->limpiar();
        $this->modo = $modoTipo;//Modo 2 = actualizar | Modo 3 = detalle

        //Cargar el titulo del modal dependiendo del modo
        $this->modo == 2 ? $this->titulo = 'Actualizar Programa' : $this->titulo = 'Detalle de Programa';

        //Cargar los datos del programa en las variables
        $this->id_programa = $programa->id_programa;
        $this->programa_iniciales = $programa->programa_iniciales;
        $this->programa = $programa->programa;
        $this->subprograma = $programa->subprograma;
        $this->mencion = $programa->mencion;
        $this->id_sunedu = $programa->id_sunedu;
        $this->codigo_sunedu = $programa->codigo_sunedu;
        $this->id_modalidad = $programa->id_modalidad;
        $this->id_facultad = $programa->id_facultad;
        $this->id_sede = $programa->id_sede;
        $this->programa_tipo = $programa->programa_tipo;
    }
    
    public function render()
    {
        $buscar = $this->search;
        
        $sede_model = Sede::all();
        $facultad_model = Facultad::all();
        $programaModel = Programa::Join('sede', 'programa.id_sede', '=', 'sede.id_sede')
                                ->Join('facultad', 'programa.id_facultad', '=', 'facultad.id_facultad')
                                ->Join('modalidad', 'programa.id_modalidad', '=', 'modalidad.id_modalidad')
                                ->where('programa', 'like', '%'.$buscar.'%')
                                ->orWhere('id_programa', 'like', '%'.$buscar.'%')
                                ->orWhere('subprograma', 'like', '%'.$buscar.'%')
                                ->orWhere('mencion', 'like', '%'.$buscar.'%')
                                ->orWhere('modalidad', 'like', '%'.$buscar.'%')
                                ->orWhere('sede', 'like', '%'.$buscar.'%')
                                ->orderBy('id_programa', 'desc')
                                ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.programa.index', [
            'programaModel' => $programaModel,
            'sede_model' => $sede_model,
            'facultad_model' => $facultad_model,
        ]);
    }
}
