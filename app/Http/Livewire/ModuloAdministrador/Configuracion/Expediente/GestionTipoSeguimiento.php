<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Expediente;

use App\Models\Expediente;
use App\Models\ExpedienteTipoSeguimiento;
use App\Models\TipoSeguimiento;
use Livewire\Component;
use Livewire\WithPagination;

class GestionTipoSeguimiento extends Component
{
    use WithPagination;//Paginacion
    
    public $search;//Variable de busqueda
    public $titulo = "Agregar Tipo de Seguimiento";//titulo del modal
    public $modo = 1;//Variable que cambia entre agregar(1) o editar(0) un registro

    public $id_expediente;//id del expediente que ya se encuentra cargado en la vista de gestion de Tipo de Seguimiento
    //Variables para la gestion de Tipo de Seguimiento
    public $id_tipo_seguimiento;//id del ExpedienteTipoSeguimiento
    public $tipo_seguimiento;//id del TipoSeguimiento
    public $estado;

    protected $queryString = [//Variables de busqueda amigables
        'search' => ['except' => ''],
    ];

    protected $listeners = ['render', 'cambiarEstado'];//Escuchar evento para que se actualice el componente

    //Validaciones en tiempo real para los campos del formulario de expediente
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'tipo_seguimiento' => 'required|numeric',
        ]);
    }

    //Limpiar los campos del formulario y resetear el modo
    //Limpiar los campos del formulario y resetear el modo
    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
    }

    //Limpiar los campos del formulario
    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('tipo_seguimiento');
        $this->modo = 1;
        $this->titulo = 'Agregar Tipo de Seguimiento';
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
        $this->dispatchBrowserEvent('alerta-expediente-gestion-tipo-seguimiento', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Mostar modal de confirmacion para cambiar el estado del Tipo de Seguimiento
    public function cargarAlertaEstado($id)
    {   
        $tipoSeguimiento = TipoSeguimiento::find($id);
        $this->alertaConfirmacion('¿Estás seguro?',"¿Desea cambiar el estado del tipo de seguimiento $tipoSeguimiento->expediente?",'question','Modificar','Cancelar','primary','danger','cambiarEstado',$id);
    }

    public function cambiarEstado(ExpedienteTipoSeguimiento $expedienteTipoSeguimiento)
    {
        if ($expedienteTipoSeguimiento->expediente_tipo_seguimiento_estado == 1) {//Si el estado es 1 (activo), cambiar a 0 (inactivo)
            $expedienteTipoSeguimiento->expediente_tipo_seguimiento_estado = 0;
        } else {//Si el estado es 0 (inactivo), cambiar a 1 (activo)
            $expedienteTipoSeguimiento->expediente_tipo_seguimiento_estado = 1;
        }
        $expedienteTipoSeguimiento->save();//Actualizar el estado del expediente

        $tipoSeguimiento = TipoSeguimiento::find($expedienteTipoSeguimiento->id_tipo_seguimiento);
        //Mostrar alerta de confirmacion de cambio de estado
        $this->alertaTipoSeguimiento('¡Éxito!', "El estado del tipo de seguimiento $tipoSeguimiento->tipo_seguimiento ha sido actualizado satisfactoriamente.", 'success', 'Aceptar', 'success');
    }

    public function cargarTipoSeguimiento($idExpedienteTipoSeguimiento)
    {
        $this->limpiar();//Limpiamos los campos del formulario
        $this->modo = 2;//Cambiamos el modo a modificar
        $this->titulo = 'Modificar Tipo de Seguimiento';
        $expedienteTipoSeguimiento = ExpedienteTipoSeguimiento::find($idExpedienteTipoSeguimiento);
        $this->id_tipo_seguimiento = $expedienteTipoSeguimiento->id_expediente_tipo_seguimiento;
        $this->tipo_seguimiento = $expedienteTipoSeguimiento->id_tipo_seguimiento;
    }

    public function guardarTipoSeguimiento(){
        $this->validate([
            'tipo_seguimiento' => 'required|numeric',
        ]);

        $expedienteTipoSeguimiento = ExpedienteTipoSeguimiento::where('id_expediente',$this->id_expediente)
                                        ->where('id_tipo_seguimiento',$this->tipo_seguimiento)
                                        ->first();

        if ($this->modo == 1){//Si el modo es 1 (agregar)
            if ($expedienteTipoSeguimiento) {//Si el tipo de seguimiento ya existe en el expediente
                $tipoSeguimiento = TipoSeguimiento::find($this->tipo_seguimiento);
                $this->alertaTipoSeguimiento('¡Error!', "El tipo de seguimiento $tipoSeguimiento->tipo_seguimiento ya se encuentra registrado en el expediente.", 'error', 'Aceptar', 'danger');
                $this->limpiar();
            }else{//Si el tipo de seguimiento no existe en el expediente
                $expedienteTipoSeguimiento = new ExpedienteTipoSeguimiento();
                $expedienteTipoSeguimiento->id_expediente = $this->id_expediente;
                $expedienteTipoSeguimiento->id_tipo_seguimiento = $this->tipo_seguimiento;
                $expedienteTipoSeguimiento->expediente_tipo_seguimiento_estado = 1;
                $expedienteTipoSeguimiento->save();

                $tipoSeguimiento = TipoSeguimiento::find($this->tipo_seguimiento);
                $this->alertaTipoSeguimiento('¡Éxito!', "El tipo de seguimiento $tipoSeguimiento->tipo_seguimiento ha sido agregado satisfactoriamente.", 'success', 'Aceptar', 'success');
                $this->limpiar();
            }
        }else{
            if($expedienteTipoSeguimiento){//Si el tipo de seguimiento ya existe en el expediente
                $tipoSeguimiento = TipoSeguimiento::find($this->tipo_seguimiento);
                $this->alertaTipoSeguimiento('¡Error!', "El tipo de seguimiento $tipoSeguimiento->tipo_seguimiento ya se encuentra registrado en el expediente.", 'error', 'Aceptar', 'danger');
                $this->limpiar();
            }else{
                $expedienteTipoSeguimiento = ExpedienteTipoSeguimiento::find($this->id_tipo_seguimiento);
                $expedienteTipoSeguimiento->id_tipo_seguimiento = $this->tipo_seguimiento;
                $expedienteTipoSeguimiento->save();
                $tipoSeguimiento = TipoSeguimiento::find($this->tipo_seguimiento);
                $this->alertaTipoSeguimiento('¡Éxito!', "El tipo de seguimiento $tipoSeguimiento->tipo_seguimiento ha sido modificado satisfactoriamente.", 'success', 'Aceptar', 'success');
                $this->limpiar();
            }
        }
        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalExpedienteTipoSeguimiento',
        ]);
    }

    public function render()
    {
        $buscar = trim($this->search);

        $expedienteModel = Expediente::find($this->id_expediente);
        $tipoSeguimientoModel = TipoSeguimiento::all();
        $validarTipoSeguimiento = ExpedienteTipoSeguimiento::where('id_expediente',$this->id_expediente)->get();
        $expedienteTipoSeguimientoModel = ExpedienteTipoSeguimiento::join('tipo_seguimiento','tipo_seguimiento.id_tipo_seguimiento','=','expediente_tipo_seguimiento.id_tipo_seguimiento')
                                        ->where('expediente_tipo_seguimiento.id_expediente',$this->id_expediente)
                                        ->where(function($query) use ($buscar){
                                            $query->where('tipo_seguimiento.tipo_seguimiento','like','%'.$buscar.'%');
                                        })->orderBy('expediente_tipo_seguimiento.id_expediente_tipo_seguimiento','desc')
                                        ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.expediente.gestion-tipo-seguimiento', 
        [
            'expedienteModel' => $expedienteModel,
            'expedienteTipoSeguimientoModel' => $expedienteTipoSeguimientoModel,
            'tipoSeguimientoModel' => $tipoSeguimientoModel,
            'validarTipoSeguimiento' => $validarTipoSeguimiento
        ]);
    }
}
