<?php

namespace App\Http\Livewire\ModuloAreaContable\Pagos;

use App\Jobs\ObservarInscripcionJob;
use App\Models\CanalPago;
use App\Models\ConceptoPago;
use App\Models\ConstanciaIngreso;
use App\Models\Pago;
use App\Models\PagoObservacion;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;

class Index extends Component
{
    use WithPagination; // trait para paginar los datos
    protected $paginationTheme = 'bootstrap'; // tema de paginacion
    protected $queryString = [
        'search' => ['except' => ''],
        'filtro_concepto_pago' => ['except' => 'all'],
    ]; // variable para almacenar el texto de busqueda

    // public $pagos = null; // variable para almacenar los pagos
    public $search = ''; // variable para almacenar el texto de busqueda
    public $filtro_concepto_pago = "all"; // variable para almacenar el texto de busqueda
    public $voucher = ''; // variable para almacenar el voucher
    public $observacion; // variable para almacenar la observacion
    public $id_pago; // variable para almacenar el id del pago
    public $documento;
    public $nombres;
    public $operacion;
    public $monto;
    public $fecha_pago;
    public $canal_pago;

    public function mount()
    {
    }

    public function updatedFiltroConceptoPago($value)
    {
        if ($value == 'all' || $value == '') {
            $this->filtro_concepto_pago = 'all';
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
        $this->documento = $pago->pago_documento;
        $this->nombres = $pago->persona->nombre_completo;
        $this->operacion = $pago->pago_operacion;
        $this->monto = $pago->pago_monto;
        $this->fecha_pago = $pago->pago_fecha;
        $this->canal_pago = $pago->canal_pago->canal_pago;
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
        } else {
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
            'observacion' => 'required|max:255',
        ]);

        // almacenar los datos
        $pago = Pago::find($this->id_pago);
        $pago->pago_verificacion = 0;
        $pago->save();

        // almacenar los datos de observacion
        $observacion = PagoObservacion::where('id_pago', $this->id_pago)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->first();
        if ($observacion) {
            $observacion->pago_observacion = $this->observacion;
            $observacion->pago_observacion_estado = 1;
            $observacion->save();
        } else {
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

        // ejecutamos el job para enviar el correo de rechazo de pago
        ObservarInscripcionJob::dispatch($pago->inscripcion->id_inscripcion, 'observar-pago');

        // limpiar los campos
        $this->limpiar();
    }

    public function rechazar_pago()
    {
        // validacion de los campos
        $this->validate([
            'observacion' => 'required|max:255',
        ]);

        // cambiar el estado de la verificacion a 0 (rechazado - observado)
        $pago = Pago::find($this->id_pago);
        $pago->pago_estado = 0;
        $pago->pago_verificacion = 0;
        $pago->pago_leido = 1;
        if ($pago->pago_voucher_url) {
            File::delete($pago->pago_voucher_url);
        }
        $pago->pago_voucher_url = null;
        $pago->save();

        // eliminar la constancia de ingreso
        $constancia = ConstanciaIngreso::where('id_pago', $this->id_pago)->orderBy('id_constancia_ingreso')->first();
        if ($constancia) {
            $constancia->constancia_ingreso_codigo = null;
            if ($constancia->constancia_ingreso_url) {
                File::delete($constancia->constancia_ingreso_url);
            }
            $constancia->constancia_ingreso_url = null;
            $constancia->save();
        }

        // almacenar los datos de observacion
        $observacion = PagoObservacion::where('id_pago', $this->id_pago)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->first();
        if ($observacion) {
            $observacion->pago_observacion = $this->observacion;
            $observacion->pago_observacion_estado = 2;
            $observacion->save();
        } else {
            if ($this->observacion != '' || $this->observacion != null) {
                $observacion = new PagoObservacion();
                $observacion->pago_observacion = $this->observacion;
                $observacion->id_pago = $this->id_pago;
                $observacion->pago_observacion_creacion = now();
                $observacion->pago_observacion_estado = 2;
                $observacion->save();
            }
        }

        // mostramos alerta de confirmacion
        $this->dispatchBrowserEvent('alerta_pago_contable', [
            'title' => '!Rechazado!',
            'text' => 'Pago rechazado correctamente',
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
        $concepto_pagos = ConceptoPago::where('concepto_pago_estado', 1)->get();
        $pagos = Pago::where('id_concepto_pago', $this->filtro_concepto_pago == "all" ? '!=' : '=', $this->filtro_concepto_pago)
            ->where(function ($query) {
                $query->where('pago_documento', 'like', '%' . $this->search . '%')
                    ->orWhere('pago_operacion', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id_pago', 'desc')
            ->paginate(100);
        // dd($pagos, $this->filtro_concepto_pago);
        return view('livewire.modulo-area-contable.pagos.index', [
            'pagos' => $pagos,
            'concepto_pagos' => $concepto_pagos,
        ]);
    }
}
