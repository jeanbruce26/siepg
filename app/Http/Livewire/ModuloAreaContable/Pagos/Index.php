<?php

namespace App\Http\Livewire\ModuloAreaContable\Pagos;

use App\Models\CanalPago;
use App\Models\Pago;
use App\Models\PagoObservacion;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination; // trait para paginar los datos
    protected $paginationTheme = 'bootstrap'; // tema de paginacion
    protected $queryString = [
        'search' => ['except' => ''],
        'filtro_canal_pago' => ['except' => 'all'],
    ]; // variable para almacenar el texto de busqueda

    // public $pagos = null; // variable para almacenar los pagos
    public $search = ''; // variable para almacenar el texto de busqueda
    public $filtro_canal_pago = "all"; // variable para almacenar el texto de busqueda
    public $voucher = ''; // variable para almacenar el voucher
    public $observacion; // variable para almacenar la observacion
    public $id_pago; // variable para almacenar el id del pago

    public function mount()
    {
    }

    public function updatedFiltroCanalPago($value)
    {
        if($value == 'all' || $value == ''){
            $this->filtro_canal_pago = 'all';
        }
    }

    public function cargar_pago(Pago $pago)
    {
        $this->id_pago = $pago->id_pago;
        $this->voucher = $pago->pago_voucher_url;
        if ($pago->pago_observacion->count() > 0) {
            $observacion = PagoObservacion::where('id_pago', $pago->id_pago)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->first();
            if ($observacion) {
                $this->observacion = $observacion->pago_observacion;
            } else {
                $this->observacion = '';
            }
        } else {
            $this->observacion = '';
        }
    }

    public function limpiar()
    {
        $this->reset('voucher', 'observacion');
    }

    public function validar_pago()
    {
        // validacion de los campos
        $this->validate([
            'observacion' => 'nullable|max:255',
        ]);

        // almacenar los datos
        $pago = Pago::find($this->id_pago);
        $pago->pago_verificacion = 2;
        $pago->save();

        // almacenar los datos de observacion

        $observacion = PagoObservacion::where('id_pago', $this->id_pago)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->first();
        if ($observacion) {
            $observacion->pago_observacion_estado = 0;
            $observacion->save();
        }else{
            if ($this->observacion != '' || $this->observacion != null) {
                $observacion = new PagoObservacion();
                $observacion->pago_observacion = $this->observacion;
                $observacion->id_pago = $this->id_pago;
                $observacion->pago_observacion_creacion = now();
                $observacion->pago_observacion_estado = 1;
                $observacion->save();
            }
        }

        // mostramos alerta de confirmacion
        $this->dispatchBrowserEvent('alerta_pago_contable', [
            'title' => '!Validado!',
            'text' => 'Pago validado correctamente',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // cerra el modal
        $this->dispatchBrowserEvent('modal_pago_contable', ['action' => 'hide']);

        // limpiar los campos
        $this->limpiar();
    }

    public function observar_pago()
    {
        // validacion de los campos
        $this->validate([
            'observacion' => 'nullable|max:255',
        ]);

        // almacenar los datos
        $pago = Pago::find($this->id_pago);
        $pago->pago_verificacion = 0;
        $pago->save();

        // almacenar los datos de observacion
        $observacion = PagoObservacion::where('id_pago', $this->id_pago)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->first();
        if ($observacion) {
            $observacion->pago_observacion_estado = 1;
            $observacion->save();
        }else{
            if ($this->observacion != '' || $this->observacion != null) {
                $observacion = new PagoObservacion();
                $observacion->pago_observacion = $this->observacion;
                $observacion->id_pago = $this->id_pago;
                $observacion->pago_observacion_creacion = now();
                $observacion->pago_observacion_estado = 1;
                $observacion->save();
            }
        }

        // mostramos alerta de confirmacion
        $this->dispatchBrowserEvent('alerta_pago_contable', [
            'title' => '!Observado!',
            'text' => 'Pago observado correctamente',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // cerra el modal
        $this->dispatchBrowserEvent('modal_pago_contable', [
            'action' => 'hide'
        ]);

        // limpiar los campos
        $this->limpiar();
    }

    public function render()
    {
        $canal_pagos = CanalPago::where('canal_pago_estado', 1)->get();
        $pagos = Pago::where('id_canal_pago', $this->filtro_canal_pago == "all" ? '!=' : '=', $this->filtro_canal_pago)
                        ->where(function ($query) {
                            $query->where('pago_documento', 'like', '%' . $this->search . '%')
                                ->orWhere('pago_operacion', 'like', '%' . $this->search . '%');
                        })
                        ->orderBy('id_pago','desc')
                        ->paginate(100);
        return view('livewire.modulo-area-contable.pagos.index', [
            'pagos' => $pagos,
            'canal_pagos' => $canal_pagos,
        ]);
    }
}
