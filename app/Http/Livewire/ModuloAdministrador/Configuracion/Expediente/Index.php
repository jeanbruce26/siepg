<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Expediente;

use App\Models\Expediente;
use Livewire\Component;
use Illuminate\Support\Str;

class Index extends Component
{

    public $search = '';
    public $titulo = 'Crear Expediente';
    public $modo = 1;//1=new | 2=update

    public $expediente_id;

    public $expediente;
    public $complemento;
    public $requerido;
    public $tipo; // 0 = maestria y doctorado, 1 = maestria, 2 = doctorado
    public $estado;
    public $nombre_archivo;

    protected $listeners = ['render', 'cambiarEstado'];//Escuchar evento para que se actualice el componente

    //Validaciones en tiempo real para los campos del formulario de expediente
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'expediente' => 'required|string',
            'complemento' => 'nullable|string',
            'nombre_archivo' => 'required|string',
            'requerido' => 'required|numeric',
            'tipo' => 'required|numeric',
        ]);
    }

    //Formatear el nombre del archivo a slug para que no tenga espacios ni caracteres especiales, en formato de url
    public function updatedTipoDocumento($value)
    {
        $this->nombre_archivo = Str::slug($value);
    }

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
        $this->reset('expediente', 'complemento', 'requerido', 'tipo', 'estado', 'nombre_archivo');
        $this->modo = 1;
        $this->titulo = 'Crear Expediente';
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
        $this->dispatchBrowserEvent('alerta-expediente', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Mostar modal de confirmacion para cambiar el estado del expediente
    public function cargarAlertaEstado($id)
    {        
        $expedi = Expediente::find($id);//Buscamos el expediente por el id
        $this->alertaConfirmacion('¿Estás seguro?',"¿Desea cambiar el estado del expediente $expedi->expediente?",'question','Modificar','Cancelar','primary','danger','cambiarEstado',$id);
    }

    //Cambiar el estado del expediente
    public function cambiarEstado(Expediente $expediente)
    {
        if ($expediente->expediente_estado == 1) {//Si el estado es 1 (activo), cambiar a 2 (inactivo)
            $expediente->expediente_estado = 2;
        } else {//Si el estado es 2 (inactivo), cambiar a 1 (activo)
            $expediente->expediente_estado = 1;
        }

        $expediente->save();//Actualizar el estado del expediente

        //Mostrar alerta de confirmacion de cambio de estado
        $this->alertaExpediente('¡Éxito!', "El estado del expediente $expediente->expediente ha sido actualizado satisfactoriamente.", 'success', 'Aceptar', 'success');
    }

    //Cargar los datos del expediente en el formulario para actualizar
    public function cargarExpediente(Expediente $expediente)
    {
        $this->limpiar();
        $this->modo = 2;//Modo 2 = actualizar
        $this->titulo = 'Actualizar Expediente';

        //Cargar los datos del expediente en el formulario
        $this->expediente_id = $expediente->id_expediente;
        $this->expediente = $expediente->expediente;
        $this->complemento = $expediente->expediente_complemento;
        $this->nombre_archivo = $expediente->expediente_nombre_file;
        $this->requerido = $expediente->expediente_requerido;
        $this->tipo = $expediente->expediente_tipo;
    }

    //Guardar o actualizar el expediente 
    public function guardarExpediente()
    {
        //Validar los campos del formulario
        $this->validate([
            'expediente' => 'required|string',
            'complemento' => 'nullable|string',
            'nombre_archivo' => 'required|string',
            'requerido' => 'required|numeric',
            'tipo' => 'required|numeric',
        ]);

        if($this->modo == 1){//Modo 1 = crear expediente
            //Creamos un nuevo espediente
            $expediente = new Expediente();
            $expediente->expediente = $this->expediente;
            $expediente->expediente_complemento = $this->complemento;
            $expediente->expediente_nombre_file = $this->nombre_archivo;
            $expediente->expediente_requerido = $this->requerido;
            $expediente->expediente_tipo = $this->tipo;
            $expediente->expediente_estado = 1;
            $expediente->save();

            $this->alertaExpediente('¡Éxito!', "El Expediente $expediente->expediente ha sido creado satisfactoriamente.", 'success', 'Aceptar', 'success');

        }else{//Modo 2 = actualizar expediente
            //Actualizar el expediente 
            $expediente = Expediente::find($this->expediente_id);
            $expediente->expediente = $this->expediente;
            $expediente->expediente_complemento = $this->complemento;
            $expediente->expediente_nombre_file = $this->nombre_archivo;
            $expediente->expediente_requerido = $this->requerido;
            $expediente->expediente_tipo = $this->tipo;
            $expediente->save();

            //Mostrar alerta de confirmacion de actualizacion
            $this->alertaExpediente('¡Éxito!', "El Expediente $expediente->expediente ha sido actualizado satisfactoriamente.", 'success', 'Aceptar', 'success');
        }

        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalExpediente',
        ]);

        $this->limpiar();//Limpiar los campos del formulario
    }

    public function render()
    {
        $buscar = $this->search;//Asignamos a la variable buscar, el valor del campo de busqueda
        $expedienteModel = Expediente::where('id_expediente','LIKE',"%{$buscar}%")
                            ->orwhere('expediente','LIKE',"%{$buscar}%")
                            ->orwhere('expediente_complemento','LIKE',"%{$buscar}%")->get();
        return view('livewire.modulo-administrador.configuracion.expediente.index',[
            'expedienteModel' => $expedienteModel,
        ]);
    }
}