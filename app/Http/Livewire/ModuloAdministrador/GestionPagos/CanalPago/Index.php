<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionPagos\CanalPago;

use App\Models\CanalPago;
use Livewire\Component;

class Index extends Component
{

    public $search = '';
    public $titulo = 'Crear Canal de Pago';
    public $modo = 1;//1=new | 2=update

    public $canalPago_id;

    public $canalPago;

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'canalPago' => 'required|string',
        ]);
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('canalPago');//Limpia la variable
        $this->modo = 1;//Modo 1 = crear | modo 2 = actualizar
        $this->titulo = "Crear Canal de Pago";
    }

    //Formatear modo y limpiar campos
    public function modo()  
    {
        $this->limpiar();
        $this->modo = 1;
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

    //Alerta de exito y error
    public function alertaCanalPago($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-canal-pago', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Alerta para cambiar estado del canal de pago
    public function cargarAlertaEstado($id)
    {
        $canalPago = CanalPago::find($id);//Buscamos el canal de pago
        $this->alertaConfirmacion(
            '¿Estás seguro?',
            "¿Está seguro que desea cambiar el estado del canal de pago $canalPago->canal_pago?",
            'question',
            'Modificar',
            'Cancelar',
            'primary',
            'danger',
            'cambiarEstado',
            $id
        );
    }

    //Cambiar estado del canal de pago
    public function cambiarEstado(CanalPago $canalPago)
    {
        if ($canalPago->estado == 1) {//Si el estado es 1 "activo", lo cambiamos a 0 "inactivo"
            $canalPago->estado = 0;
            $canalPago->save();
            //Mostramos un mensaje de exito de cambio de estado
            $this->alertaCanalPago('¡Éxito!', "Se ha cambiado el estado del canal de pago: $canalPago->canal_pago", 'success', 'Continuar', 'success');
        } else {//Si el estado es 0 "inactivo", lo cambiamos a 1 "activo"
            $canalPago->estado = 1;
            $canalPago->save();
            //Mostramos un mensaje de exito de cambio de estado
            $this->alertaCanalPago('¡Éxito!', "Se ha cambiado el estado del canal de pago: $canalPago->canal_pago: satisfactoriamente.", 'success', 'Continuar', 'success');
        }
    }

    //Cargar canal de pago
    public function cargarCanalPago(CanalPago $canalPago)
    {
        $this->limpiar();
        $this->modo = 2;//Modo 2 = actualizar
        $this->titulo = 'Actualizar Canal de Pago';
        $this->canalPago_id = $canalPago->id_canal_pago;//Cargo el id del canal de pago
        $this->canalPago = $canalPago->canal_pago;//Cargo el nombre del canal de pago
    }

    //Guardar y actualizar canal de pago
    public function guardarCanalPago()
    {
        if($this->modo == 1){
            //Validamos los datos ingresados
            $this->validate([
                'canalPago' => 'required|string'
            ]);

            $canalPago = new CanalPago();//Creamos un nuevo objeto de tipo CanalPago
            $canalPago->canal_pago = $this->canalPago;//Asignamos el nombre del canal de pago
            $canalPago->canal_pago_estado = 1;//Asignamos el estado del canal de pago
            $canalPago->save();//Guardamos los cambios realizados

            //Mostramos un mensaje de exito de creacion
            $this->alertaPago('¡Éxito!', 'El Canal de Pago ' . $canalPago->canal_pago . ' ha sido creado satisfactoriamente.', 'success', 'Aceptar', 'success');
        }else{
            //Validamos los datos ingresados
            $this->validate([
                'canalPago' => 'required|string'
            ]);

            $canalPago = CanalPago::find($this->canalPago_id);//Buscamos el canal de pago a actualizar
            $canalPago->canal_pago = $this->canalPago;//Actualizamos el nombre del canal de pago
            $canalPago->save();//Guardamos los cambios realizados

            //Mostramos un mensaje de exito de actualizacion
            $this->alertaPago('¡Éxito!', 'El Canal de Pago ' . $canalPago->canal_pago . ' ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');

        }

        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalCanalPago',
        ]);

        $this->limpiar();//limpiamos las variables
    }

    public function render()
    {
        //Realizamos la busqueda de los datos y los ordenamos de forma descendente
        $buscar = $this->search;
        //Realizamos la busqueda de los datos y los ordenamos de forma descendente
        $canalPagoModel = CanalPago::where('id_canal_pago','LIKE',"%{$buscar}%")
                        ->orWhere('canal_pago','LIKE',"%{$buscar}%")
                        ->orderBy('id_canal_pago','DESC')->get();
        return view('livewire.modulo-administrador.gestion-pagos.canal-pago.index',[
            'canalPagoModel' => $canalPagoModel,
        ]);

    }
}
