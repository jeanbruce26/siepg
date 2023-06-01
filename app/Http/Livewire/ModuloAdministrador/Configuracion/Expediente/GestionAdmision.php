<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Expediente;

use App\Models\Admision;
use App\Models\Expediente;
use App\Models\ExpedienteAdmision;
use App\Models\ExpedienteInscripcion;
use Livewire\Component;
use Livewire\WithPagination;

class GestionAdmision extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';//paginacion de bootstrap

    public $search = '';//Variable de busqueda
    public $titulo = 'Agregar Admisión del Expediente';//Titulo del modal
    public $modo = 1;//Variable para cambiar el modo del formulario | 1 = agregar | 2 = modificar

    //Variables del modelo de expediente admision
    public $id_expediente_admision;//Variable para el id del expediente admision
    public $id_expediente;//Variable para el id del expediente. Esta variable ya se encuentra cargada en este componente
    public $id_admision;
    public $expediente_admision_estado;//Variable para el estado del expediente admision | 1 = activo | 0 = inactivo

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
        $this->resetErrorBag();//Limpiar los errores
        $this->reset('id_admision');//Limpiar el campo de admision
        $this->modo = 1;//Asignamos el modo de agregar
    }

    //Mostrar modal de agregar expediente admision
    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
        $this->titulo = 'Agregar Admisión del Expediente';//Asignamos el titulo 
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
        $this->alertaConfirmacion('¿Estás seguro?','¿Desea cambiar el estado del admisión "'.$expedienteAdmision->admision->admision.'" del expediente?','question','Modificar','Cancelar','primary','danger','cambiarEstado',$id_expediente_admision);
    }

    //Cambiar el estado del expediente
    public function cambiarEstado(ExpedienteAdmision $expedienteAdmision)
    {
        if ($expedienteAdmision->expediente_admision_estado == 1) {//Si el estado es 1 (activo), cambiar a 0 (inactivo)
            $expedienteAdmision->expediente_admision_estado = 0;
        } else {//Si el estado es 0 (inactivo), cambiar a 1 (activo)
            $expedienteAdmision->expediente_admision_estado = 1;
        }

        $expedienteAdmision->save();//Actualizar el estado del expediente

        $admi = Admision::find($expedienteAdmision->id_admision);//Buscamos el admision por su id del expediente admision
        //Mostrar alerta de confirmacion de cambio de estado
        $this->alertaExpedienteAdmision('¡Éxito!', 'El estado del admisión "'.$admi->admision.'" del expediente ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
    }

    public function cargarExpedienteAdmision($id_expediente_admision)
    {
        $this->limpiar();//Limpiamos los campos del formulario
        $this->modo = 2;//Cambiamos el modo a modificar
        $expedienteAdmision = ExpedienteAdmision::find($id_expediente_admision);//Buscamos el expediente admision por su id
        $this->titulo = 'Modificar Admisión del Expediente';//Asignamos el titulo del modal
        $this->id_expediente = $expedienteAdmision->id_expediente;//Asignamos el id del expediente
        $this->id_admision = $expedienteAdmision->id_admision;//Asignamos el id del admision
        $this->id_expediente_admision = $expedienteAdmision->id_expediente_admision;//Asignamos el id del expediente admision
    }

    public function guardarExpedienteAdmision()
    {
        $this->validate([
            'id_admision' => 'required|numeric',
        ]);

        $expedienteAdmision = ExpedienteAdmision::where('id_expediente',$this->id_expediente)//Buscamos el expediente admision por el id del expediente
                                                ->where('id_admision',$this->id_admision)//Buscamos el expediente admision por el id del admision
                                                ->first();

        //Validamos el modo del modal
        if ($this->modo == 1) {//Si el modo es 1 (agregar), creamos un nuevo expediente admision
            if ($expedienteAdmision) {//Validamos si el expediente admision ya existe
                $this->alertaExpedienteAdmision('¡Error!', 'El admisión ya se encuentra registrado en el expediente.', 'error', 'Aceptar', 'danger');
            } else {//Si el expediente admision no existe, lo creamos
                $expedienteAdmision = new ExpedienteAdmision();//Nueva instancia del modelo de expediente admision
                $expedienteAdmision->id_expediente = $this->id_expediente;//Asignamos el id del expediente
                $expedienteAdmision->id_admision = $this->id_admision;//Asignamos el id del admision
                $expedienteAdmision->expediente_admision_estado = 1;//Asignamos el estado del expediente admision
                $expedienteAdmision->save();//Guardamos el expediente admision

                $admi = Admision::find($this->id_admision);//Buscamos el admision por su id
                $expedi = Expediente::find($this->id_expediente);//Buscamos el expediente por su id
                //Mostrar alerta de confirmacion de registro
                $this->alertaExpedienteAdmision('¡Éxito!', 'El admisión del expediente "'.$expedi->expediente.'" ha sido registrado satisfactoriamente.', 'success', 'Aceptar', 'success');
                $this->limpiar();
            }
        } else {//Si el modo es 2 (modificar), modificamos el expediente admision
            if ($expedienteAdmision) {//Validamos si el expediente admision ya existe
                $this->alertaExpedienteAdmision('¡Información!', 'No se registraron cambios en el admisión del expediente.', 'info', 'Aceptar', 'info');
            } else {//Si el expediente admision no existe, lo modificamos
                $expedienteAdmision = ExpedienteAdmision::find($this->id_expediente_admision);//Buscamos el expediente admision por su id
                //validamos si el expediente admision a sido usado en la tabla de expediente inscripcion
                $expedienteInscripcion = ExpedienteInscripcion::where('id_expediente_admision',$expedienteAdmision->id_expediente_admision)->first();//Buscamos el expediente inscripcion por el id del expediente admision
                if ($expedienteInscripcion) {//Si el expediente admision a sido usado en la tabla de expediente inscripcion, no se puede modificar
                    $expedienteModel = Expediente::where('id_expediente',$this->id_expediente)->first();
                    $this->alertaExpedienteAdmision('¡Error!', 'El admisión del expediente "'. $expedienteModel->expediente .'" no se puede modificar porque ya ha sido usado en una inscripción.', 'error', 'Aceptar', 'danger');
                    //Cerramos el modal
                    $this->dispatchBrowserEvent('modal', [
                        'titleModal' => '#modalExpedienteAdmision',
                    ]);
                    return;
                }
                $expedienteAdmision->id_admision = $this->id_admision;
                $expedienteAdmision->save();
                $admi = Admision::find($this->id_admision);//Buscamos el admision por su id
                $expedi = Expediente::find($this->id_expediente);//Buscamos el expediente por su id
                //Mostrar alerta de confirmacion de modificacion
                $this->alertaExpedienteAdmision('¡Éxito!', 'El admisión del expediente "'.$expedi->expediente.'" ha sido modificado satisfactoriamente.', 'success', 'Aceptar', 'success');
                $this->limpiar();
            }
        }

        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalExpedienteAdmision',
        ]);
    }

    public function render()
    {
        $buscar = $this->search;//Asignamos a la variable buscar, el valor del campo de busqueda
        $admisionModel = Admision::all();//Cargamos todos los admisiones para mostrarlos un select
        $validarSelect = ExpedienteAdmision::all();//Cargamos todos los expediente admisiones para validar si ya existe un admision en el expediente
        $expedienteModel = Expediente::where('id_expediente',$this->id_expediente)->first();
        $expedienteAdmisionModel = ExpedienteAdmision::join('admision','admision.id_admision','=','expediente_admision.id_admision')
                                        ->where('expediente_admision.id_expediente',$this->id_expediente)
                                        ->where(function($query) use ($buscar){
                                            $query->where('admision.admision','like','%'.$buscar.'%');
                                        })->orderBy('expediente_admision.id_expediente_admision','desc')
                                        ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.expediente.gestion-admision',[
            'admisionModel' => $admisionModel,
            'validarSelect' => $validarSelect,
            'expedienteModel' => $expedienteModel,
            'expedienteAdmisionModel' => $expedienteAdmisionModel,
        ]);
    }
}
