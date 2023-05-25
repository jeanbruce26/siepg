<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Expediente;

use App\Models\Admision;
use App\Models\Expediente;
use App\Models\ExpedienteAdmision;
use Livewire\Component;
use Livewire\WithPagination;

class GestionAdmision extends Component
{
    use WithPagination;

    public $search = '';//Variable de busqueda
    public $titulo = 'Agregar Admisión del Expediente - ';//Titulo del modal

    //Variables del modelo de expediente admision
    public $id_expediente;
    public $id_admision;
    public $expediente_admision_estado;

    protected $listeners = ['render', 'cambiarEstado'];//Escuchar evento para que se actualice el componente

    //Validaciones en tiempo real para los campos del formulario de expediente
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'id_admision' => 'required|numeric',
        ]);
    }

    //Limpiar los campos del formulario del modal
    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('id_admision');
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
    public function alertaExpedienteAdmision($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-expediente-gestion-admision', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Mostar modal de confirmacion para cambiar el estado del expediente
    public function cargarAlertaEstado($id_expediente_admision)
    {
        $expedienteAdmision = ExpedienteAdmision::find($id_expediente_admision);//Buscamos el expediente admision por su id
        $expedi = Expediente::find($id_expediente_admision);//Buscamos el expediente por el id del expediente admision
        $this->alertaConfirmacion('¿Estás seguro?','¿Desea cambiar el estado del admisión "'.$expedienteAdmision->admision->admision.'" del expediente "'.$expedi->expediente.'"?','question','Modificar','Cancelar','primary','danger','cambiarEstado',$id_expediente_admision);
    }

    //Cambiar el estado del expediente
    public function cambiarEstado(ExpedienteAdmision $expedienteAdmision)
    {
        if ($expedienteAdmision->expediente_admision_estado == 1) {//Si el estado es 1 (activo), cambiar a 2 (inactivo)
            $expedienteAdmision->expediente_admision_estado = 2;
        } else {//Si el estado es 2 (inactivo), cambiar a 1 (activo)
            $expedienteAdmision->expediente_admision_estado = 1;
        }

        $expedienteAdmision->save();//Actualizar el estado del expediente

        $admi = Admision::find($expedienteAdmision->id_admision);//Buscamos el admision por su id del expediente admision
        //Mostrar alerta de confirmacion de cambio de estado
        $this->alertaExpedienteAdmision('¡Éxito!', 'El estado del admisión "'.$admi->admision.'" del expediente "'.$expedienteAdmision->expediente->expediente.'" ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
    }

    public function render()
    {
        $buscar = $this->search;//Asignamos a la variable buscar, el valor del campo de busqueda
        $expedienteModel = Expediente::where('id_expediente',$this->id_expediente)->first();
        $expedienteAdmisionModel = ExpedienteAdmision::join('admision','admision.id_admision','=','expediente_admision.id_admision')
                                        ->where('expediente_admision.id_expediente',$this->id_expediente)
                                        ->where(function($query) use ($buscar){
                                            $query->where('admision.admision','like','%'.$buscar.'%');
                                        })->orderBy('expediente_admision.id_expediente_admision','desc')
                                        ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.expediente.gestion-admision',[
            'expedienteModel' => $expedienteModel,
            'expedienteAdmisionModel' => $expedienteAdmisionModel,
        ]);
    }
}
