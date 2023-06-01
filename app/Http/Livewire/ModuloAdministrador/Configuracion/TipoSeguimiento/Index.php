<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\TipoSeguimiento;

use App\Models\TipoSeguimiento;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;//Paginacion
    
    public $search;//Variable de busqueda
    public $titulo = "Agregar Tipo de Seguimiento";//titulo del modal
    public $modo = 1;//Variable que cambia entre agregar(1) o editar(0) un registro
    
    //Variables del modelo TipoSeguimiento
    public $id_tipo_seguimiento;
    public $tipo_seguimiento;
    public $tipo_seguimiento_titulo;
    public $tipo_seguimiento_descripcion;
    public $tipo_seguimiento_estado;
    public $tipo_seguimiento_fecha_creacion;

    protected $queryString = [//Variables de busqueda amigables
        'search' => ['except' => ''],
    ];

    protected $listeners = ['render', 'cambiarEstado'];//Escuchar evento para que se actualice el componente

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'tipo_seguimiento' => 'required|string|max:255',
            'tipo_seguimiento_titulo' => 'required|string|max:255',
            'tipo_seguimiento_descripcion' => 'required|string|max:255',
        ]);
    }

    //Limpiar los campos del formulario del modal
    public function limpiar()
    {
        $this->resetErrorBag();//Limpiar los errores
        $this->reset('tipo_seguimiento', 'tipo_seguimiento_titulo', 'tipo_seguimiento_descripcion');//Limpiar los campos del formulario
        $this->modo = 1;//Asignamos el modo de agregar
    }

    //Mostrar modal de agregar Vistas de Evaluación del Expediente
    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
        $this->titulo = 'Agregar Tipo de Seguimiento';//Asignamos el titulo
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
    public function alertaTipoSeguimiento($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-tipo-seguimiento', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    public function cargarAlertaEstado(TipoSeguimiento $tipoSeguimientoModel)
    {
        $this->alertaConfirmacion('¿Estás seguro?','¿Desea cambiar el estado del tipo de seguimiento '. $tipoSeguimientoModel->tipo_seguimiento .'?','question','Modificar','Cancelar','primary','danger','cambiarEstado',$tipoSeguimientoModel->id_tipo_seguimiento);
    }

    public function cambiarEstado(TipoSeguimiento $tipoSeguimientoModel)
    {
        if($tipoSeguimientoModel->tipo_seguimiento_estado == 1){//Si el estado es 1 (Activo) lo desactivamos a 0 (Inactivo)
            $tipoSeguimientoModel->tipo_seguimiento_estado = 0;
            $this->alertaTipoSeguimiento('¡Exito!','El estado del tipo de seguimiento '. $tipoSeguimientoModel->tipo_seguimiento .' ha sido actualizado satisfactoriamente','success','Aceptar','success');
        }else{//Si el estado es 0 (Inactivo) lo activamos a 1 (Activo)
            $tipoSeguimientoModel->tipo_seguimiento_estado = 1;
        }
        $tipoSeguimientoModel->save();

        $this->alertaTipoSeguimiento('¡Exito!','El estado del tipo de seguimiento '. $tipoSeguimientoModel->tipo_seguimiento .' ha sido actualizado satisfactoriamente','success','Aceptar','success');
    }

    //Cargar los datos del expediente en el formulario para actualizar
    public function cargarTipoSeguimiento(TipoSeguimiento $tipoSeguimientoModel, $modoTipo)
    {
        $this->limpiar();
        $this->modo = $modoTipo;//Modo 2 = actualizar | Modo 3 = detalle

        //Cargar el titulo del modal dependiendo del modo
        $this->modo == 2 ? $this->titulo = 'Actualizar Tipo de Seguimiento' : $this->titulo = 'Detalle de Tipo de Seguimiento';

        //Cargar los datos del TipoSeguimiento en el formulario
        $this->id_tipo_seguimiento = $tipoSeguimientoModel->id_tipo_seguimiento;
        $this->tipo_seguimiento = $tipoSeguimientoModel->tipo_seguimiento;
        $this->tipo_seguimiento_titulo = $tipoSeguimientoModel->tipo_seguimiento_titulo;
        $this->tipo_seguimiento_descripcion = $tipoSeguimientoModel->tipo_seguimiento_descripcion;
    }

    //Guardar o actualizar el TipoSeguimiento
    public function guardarTipoSeguimiento()
    {
        //Validar los campos del formulario 
        $this->validate([
            'tipo_seguimiento' => 'required|string|max:255',
            'tipo_seguimiento_titulo' => 'required|string|max:255',
            'tipo_seguimiento_descripcion' => 'required|string|max:255',
        ]);

        if($this->modo == 1){//Modo 1 = crear Tipo de Seguimiento
            //Creamos un nuevo Tipo de Seguimiento
            $tipoSeguimientoModel = new TipoSeguimiento();
            $tipoSeguimientoModel->tipo_seguimiento = $this->tipo_seguimiento;
            $tipoSeguimientoModel->tipo_seguimiento_titulo = $this->tipo_seguimiento_titulo;
            $tipoSeguimientoModel->tipo_seguimiento_descripcion = $this->tipo_seguimiento_descripcion;
            $tipoSeguimientoModel->tipo_seguimiento_estado = 1;
            $tipoSeguimientoModel->tipo_seguimiento_fecha_creacion = date('Y-m-d H:i:s');
            $tipoSeguimientoModel->save();

            $this->alertaTipoSeguimiento('¡Éxito!', "El Tipo de Seguimiento $tipoSeguimientoModel->tipo_seguimiento ha sido creado satisfactoriamente.", 'success', 'Aceptar', 'success');

        }else{//Modo 2 = actualizar Tipo de Seguimiento
            $tipoSeguimientoModel = TipoSeguimiento::find($this->id_tipo_seguimiento);
            
            //Validar si no hubo cambios en los campos del formulario
            if($tipoSeguimientoModel->tipo_seguimiento == $this->tipo_seguimiento && $tipoSeguimientoModel->tipo_seguimiento_titulo == $this->tipo_seguimiento_titulo && $tipoSeguimientoModel->tipo_seguimiento_descripcion == $this->tipo_seguimiento_descripcion){
                $this->alertaTipoSeguimiento('¡Información!', "No se detectaron cambios en el Tipo de Seguimiento.", 'info', 'Aceptar', 'info');
                //cerrar modal
                $this->dispatchBrowserEvent('modal', [
                    'titleModal' => '#modalTipoSeguimiento',
                ]);
                $this->limpiar();//Limpiar los campos del formulario
                return;
            }

            //Actualizar el Tipo de Seguimiento
            $tipoSeguimientoModel->tipo_seguimiento = $this->tipo_seguimiento;
            $tipoSeguimientoModel->tipo_seguimiento_titulo = $this->tipo_seguimiento_titulo;
            $tipoSeguimientoModel->tipo_seguimiento_descripcion = $this->tipo_seguimiento_descripcion;
            $tipoSeguimientoModel->save();

            //Mostrar alerta de confirmacion de actualizacion
            $this->alertaTipoSeguimiento('¡Éxito!', "El Tipo de Seguimiento $tipoSeguimientoModel->tipo_seguimiento ha sido actualizado satisfactoriamente.", 'success', 'Aceptar', 'success');
        }

        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalTipoSeguimiento',
        ]);

        $this->limpiar();//Limpiar los campos del formulario
    }

    public function render()
    {
        $tipoSeguimientoModel = TipoSeguimiento::where('id_tipo_seguimiento', 'like', '%'.$this->search.'%')
            ->orWhere('tipo_seguimiento', 'like', '%'.$this->search.'%')
            ->orWhere('tipo_seguimiento_titulo', 'like', '%'.$this->search.'%')
            ->orWhere('tipo_seguimiento_descripcion', 'like', '%'.$this->search.'%')
            ->orderBy('id_tipo_seguimiento', 'asc')
            ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.tipo-seguimiento.index',[
            'tipoSeguimientoModel' => $tipoSeguimientoModel,
        ]);
    }
}
