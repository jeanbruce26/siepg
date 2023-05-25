<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionPagos\ConceptoPago;

use App\Models\ConceptoPago;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $titulo = 'Crear Concepto de Pago';
    public $modo = 1; //1=new | 2=update

    // Para poder agregar los parámetros de búsqueda en la URL
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $conceptoPago_id;

    public $concepto;
    public $monto;
    public $estado;

    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'concepto' => 'required|string',
            'monto' => 'required|numeric'
        ]);
    }

    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('concepto','monto');
        $this->modo = 1;
        $this->titulo = 'Crear Concepto de Pago';
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
    public function alertaConceptoPago($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-concepto-pago', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    //Cargar alerta de confirmacion para cambiar estado
    public function cargarAlertaEstado($id_concepto_pago)
    {
        $conceptoPago = ConceptoPago::find($id_concepto_pago);
        $this->alertaConfirmacion('¿Estás seguro?',"¿Desea cambiar el estado del concepto de pago $conceptoPago->concepto_pago?", 'question','Modificar','Cancelar','primary','danger','cambiarEstado',$id_concepto_pago);
    }

    //Cambiar estado del concepto de pago
    public function cambiarEstado(ConceptoPago $conceptoPago)
    {
        if ($conceptoPago->concepto_pago_estado == 1) {
            $conceptoPago->concepto_pago_estado = 0;
        } else {
            $conceptoPago->concepto_pago_estado = 1;
        }

        $conceptoPago->save();//Guardamos el cambio de estado

        //Mostramos un mensaje de exito de cambio de estado
        $this->alertaConceptoPago('¡Éxito!', "Se ha cambiado el estado del concepto de pago $conceptoPago->concepto_pago satisfactoriamente.", 'success', 'Continuar', 'success');
    }

    //Cargar datos del concepto de pago
    public function cargarConceptoPago(ConceptoPago $conceptoPago)
    {
        $this->limpiar();

        $this->modo = 2;
        $this->titulo = 'Actualizar Concepto de Pago';
        $this->conceptoPago_id = $conceptoPago->id_concepto_pago;

        $this->concepto = $conceptoPago->concepto_pago;
        $this->monto = $conceptoPago->concepto_pago_monto;
    }

    //Guardar y actualizar concepto de pago
    public function guardarConceptoPago()
    {
        if ($this->modo == 1) {//Si el modo es 1 "crear"
            $this->validate([
                'concepto' => 'required|string',
                'monto' => 'required|numeric'
            ]);

            //Creamos el concepto de pago
            $conceptoPago = new ConceptoPago();
            $conceptoPago->concepto_pago = $this->concepto;
            $conceptoPago->concepto_pago_monto = $this->monto;
            $conceptoPago->concepto_pago_estado = 1;
            $conceptoPago->save();//Guardamos el concepto de pago
            
            //Mostramos un mensaje de exito de creacion
            $this->alertaConceptoPago('¡Éxito!', "El concepto de pago $conceptoPago->concepto_pago ha sido creado satisfactoriamente.", 'success', 'Continuar', 'success');

        } else {//Si el modo es 2 "actualizar"
            //Validamos los campos
            $this->validate([
                'concepto' => 'required|string',
                'monto' => 'required|numeric'
            ]);

            $conceptoPago = ConceptoPago::find($this->conceptoPago_id);//Buscamos el concepto de pago

            //Validar que el concepto de pago haya sido actualizado
            if($conceptoPago->concepto_pago == $this->concepto && $conceptoPago->concepto_pago_monto == $this->monto){
                //Mostramos un mensaje de informacion de que no se realizaron cambios
                $this->alertaConceptoPago('¡Información!', "No se realizaron cambios en los datos en el concepto de pago $conceptoPago->concepto_pago.", 'info', 'Aceptar', 'info');
                //Cerramos el modal
                $this->dispatchBrowserEvent('modal', [
                    'titleModal' => '#modalConceptoPago',
                ]);
                return;
            }

            $conceptoPago->concepto_pago = $this->concepto;
            $conceptoPago->concepto_pago_monto = $this->monto;
            $conceptoPago->save();//Guardamos los cambios realizados

            //Mostramos un mensaje de exito de actualizacion
            $this->alertaConceptoPago('¡Éxito!', "El concepto de pago $conceptoPago->concepto_pago ha sido actualizado satisfactoriamente.", 'success', 'Continuar', 'success');
        }

        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalConceptoPago',
        ]);

        $this->limpiar();
        
    }

    public function render()
    {
        $buscar = $this->search;
        $conceptoPagoModel = ConceptoPago::where('id_concepto_pago','LIKE',"%{$buscar}%")
                        ->orWhere('concepto_pago','LIKE',"%{$buscar}%")
                        ->orderBy('id_concepto_pago','DESC')
                        ->paginate(10);
        return view('livewire.modulo-administrador.gestion-pagos.concepto-pago.index', [
            'conceptoPagoModel' => $conceptoPagoModel,
        ]);
    }

}
