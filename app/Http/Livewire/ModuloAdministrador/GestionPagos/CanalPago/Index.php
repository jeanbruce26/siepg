<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionPagos\CanalPago;

use App\Models\CanalPago;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';//paginacion de bootstrap

    public $search = '';
    public $titulo = 'Crear Canal de Pago';
    public $modo = 1;//1=new | 2=update

    // Para poder agregar los parámetros de búsqueda en la URL 
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $canalPago_id;

    public $canalPago;

    protected $listeners = ['render', 'cambiarEstado'];//Para que se escuche el evento y se actualice el componente

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
    public function cargarAlertaEstado($id_canal_pago)
    {
        $canalPago = CanalPago::find($id_canal_pago);//Buscamos el canal de pago
        $this->alertaConfirmacion('¿Estás seguro?',"¿Desea cambiar el estado del canal de pago $canalPago->canal_pago?",'question','Modificar','Cancelar','primary','danger','cambiarEstado',$id_canal_pago);
    }

    //Cambiar estado del canal de pago
    public function cambiarEstado(CanalPago $canalPago)
    {
        if ($canalPago->canal_pago_estado == 1) {//Si el estado es 1 "activo", lo cambiamos a 0 "inactivo"
            $canalPago->canal_pago_estado = 0;
        } else {//Si el estado es 0 "inactivo", lo cambiamos a 1 "activo"
            $canalPago->canal_pago_estado = 1;
        }

        $canalPago->save();//Guardamos los cambios realizados

        //Mostramos un mensaje de exito de cambio de estado
        $this->alertaCanalPago('¡Éxito!', "Se ha cambiado el estado del canal de pago $canalPago->canal_pago satisfactoriamente.", 'success', 'Continuar', 'success');
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
        if($this->modo == 1){//Modo 1 = crear
            $this->validate([
                'canalPago' => 'required|string'
            ]);

            $canalPago = new CanalPago();
            $canalPago->canal_pago = $this->canalPago;
            $canalPago->canal_pago_estado = 1;
            $canalPago->save();

            $this->alertaCanalPago('¡Éxito!', 'El Canal de Pago ' . $canalPago->canal_pago . ' ha sido creado satisfactoriamente.', 'success', 'Aceptar', 'success');

        }else{//Modo 2 = actualizar
            $this->validate([
                'canalPago' => 'required|string'
            ]);

            //Validar si no se hicieron cambios
            $validarCanalPago = CanalPago::find($this->canalPago_id);
            if($validarCanalPago->canal_pago == $this->canalPago){
                $this->alertaCanalPago('¡Información!', 'No se realizaron cambios en el Canal de Pago' . $validarCanalPago->canal_pago . '.', 'info', 'Aceptar', 'info');
            }else{
                $canalPago = CanalPago::find($this->canalPago_id);
                $canalPago->canal_pago = $this->canalPago;
                $canalPago->save();
    
                $this->alertaCanalPago('¡Éxito!', 'El Canal de Pago ' . $canalPago->canal_pago . ' ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
            }
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
                        ->orderBy('id_canal_pago','DESC')
                        ->paginate(10);
        return view('livewire.modulo-administrador.gestion-pagos.canal-pago.index',[
            'canalPagoModel' => $canalPagoModel,
        ]);

    }
}
