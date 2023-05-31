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
    public $modo = 1;//Variable que cambia entre agregar(1) o editar(2) un registro

    public $id_expediente;//id del expediente que ya se encuentra cargado en la vista de gestion de Tipo de Seguimiento
    //Variables para la gestion de Tipo de Seguimiento
    public $id_tipo_seguimiento;
    public $tipo_seguimiento;
    public $estado;

    protected $queryString = [//Variables de busqueda amigables
        'search' => ['except' => ''],
    ];

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
        $this->dispatchBrowserEvent('alerta-tipo-seguimiento', [
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

    public function cambiarEstado(TipoSeguimiento $tipoSeguimiento)
    {
        if ($tipoSeguimiento->tipo_seguimiento_estado == 1) {//Si el estado es 1 (activo), cambiar a 2 (inactivo)
            $tipoSeguimiento->tipo_seguimiento_estado = 2;
        } else {//Si el estado es 2 (inactivo), cambiar a 1 (activo)
            $tipoSeguimiento->tipo_seguimiento_estado = 1;
        }
        $tipoSeguimiento->save();//Actualizar el estado del expediente

        //Mostrar alerta de confirmacion de cambio de estado
        $this->alertaExpediente('¡Éxito!', "El estado del tipo de seguimiento $tipoSeguimiento->tipo_seguimiento ha sido actualizado satisfactoriamente.", 'success', 'Aceptar', 'success');
    }

    public function render()
    {
        $buscar = trim($this->search);

        $expedienteModel = Expediente::find($this->id_expediente);
        $tipoSeguimientoModel = TipoSeguimiento::all();
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
        ]);
    }
}
