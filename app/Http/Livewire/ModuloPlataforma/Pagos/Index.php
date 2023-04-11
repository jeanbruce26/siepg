<?php

namespace App\Http\Livewire\ModuloPlataforma\Pagos;

use App\Models\CanalPago;
use App\Models\ConceptoPago;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\PagoObservacion;
use App\Models\Persona;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads; // trait para subir archivos

    public $titulo_modal_pago = 'Registrar Pago'; // titulo del modal de pago
    public $id_pago; // variable para el id del pago
    public $documento_identidad, $numero_operacion, $monto_operacion, $fecha_pago, $canal_pago, $concepto_pago, $voucher, $iteration = 0; // variables para el formulario del modal de registro
    public $modo = 'create'; // variable para el modo de la vista
    public $button_modal = 'Registrar Pago'; // variable para el boton del modal de registro

    public function mount()
    {
        $this->limpiar_pago();
        $this->documento_identidad = auth('plataforma')->user()->usuario_estudiante;
    }

    public function updated($propertyName)
    {
        if($this->modo == 'create')
        {
            $this->validateOnly($propertyName, [
                'documento_identidad' => 'required|numeric|digits_between:8,9',
                'numero_operacion' => 'required|numeric',
                'monto_operacion' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'canal_pago' => 'required|numeric',
                'concepto_pago' => 'required|numeric',
                'voucher' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ]);
        }
        elseif($this->modo == 'edit')
        {
            $this->validateOnly($propertyName, [
                'documento_identidad' => 'required|numeric|digits_between:8,9',
                'numero_operacion' => 'required|numeric',
                'monto_operacion' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'canal_pago' => 'required|numeric',
                'concepto_pago' => 'required|numeric',
                'voucher' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);
        }
    }

    public function cargar_pago(Pago $pago)
    {
        $this->id_pago = $pago->id_pago;
        $this->titulo_modal_pago = 'Editar Pago';
        $this->documento_identidad = $pago->pago_documento;
        $this->numero_operacion = $pago->pago_operacion;
        $this->monto_operacion = $pago->pago_monto;
        $this->fecha_pago = $pago->pago_fecha;
        $this->canal_pago = $pago->id_canal_pago;
        $this->concepto_pago = $pago->id_concepto_pago;
        $this->modo = 'edit';
        $this->button_modal = 'Editar Pago';
    }

    public function limpiar_pago()
    {
        $this->reset(['documento_identidad', 'numero_operacion', 'monto_operacion', 'fecha_pago', 'canal_pago', 'voucher']);
        $this->resetErrorBag();
        $this->resetValidation();
        $this->iteration++;
        $this->modo = 'create';
    }

    public function guardar_pago()
    {
        // validar formulario de registro de pago
        if($this->modo == 'create')
        {
            $this->validate([
                'documento_identidad' => 'required|numeric|digits_between:8,9',
                'numero_operacion' => 'required|numeric',
                'monto_operacion' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'canal_pago' => 'required|numeric',
                'concepto_pago' => 'required|numeric',
                'voucher' => 'required|image|mimes:jpg,jpeg,png|max:2048'
            ]);
        }
        else
        {
            $this->validate([
                'documento_identidad' => 'required|numeric|digits_between:8,9',
                'numero_operacion' => 'required|numeric',
                'monto_operacion' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'canal_pago' => 'required|numeric',
                'concepto_pago' => 'required|numeric',
                'voucher' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);
        }

        // guardar pago
        if($this->modo == 'create')
        {
            $pago = new Pago();
            $pago->pago_documento = $this->documento_identidad;
            $pago->pago_operacion = $this->numero_operacion;
            $pago->pago_monto = $this->monto_operacion;
            $pago->pago_fecha = $this->fecha_pago;
            $pago->pago_estado = 1;
            $pago->pago_verificacion = 1;
            if($this->voucher)
            {
                $persona = Persona::where('persona_documento', $this->documento_identidad)->first();
                $inscripcion = Inscripcion::where('id_persona', $persona->id_persona)->orderBy('id_inscripcion', 'desc')->first();
                $admision = $inscripcion->programa_proceso->admision->admision;
                $path = 'Posgrado/' . $admision . '/' . $this->documento_identidad . '/' . 'Voucher/';
                $filename = 'voucher-pago.' . $this->voucher->getClientOriginalExtension();
                $nombre_db = $path.$filename;
                $data = $this->voucher;
                $data->storeAs($path, $filename, 'files_publico');
                $pago->pago_voucher_url = $nombre_db;
            }
            $pago->id_canal_pago = $this->canal_pago;
            $pago->id_concepto_pago = $this->concepto_pago;
            $pago->save();
        }
        else
        {
            $pago = Pago::find($this->id_pago);
            $pago->pago_documento = $this->documento_identidad;
            $pago->pago_operacion = $this->numero_operacion;
            $pago->pago_monto = $this->monto_operacion;
            $pago->pago_fecha = $this->fecha_pago;
            $pago->pago_verificacion = 1;
            if($this->voucher)
            {
                $persona = Persona::where('persona_documento', $this->documento_identidad)->first();
                $inscripcion = Inscripcion::where('id_persona', $persona->id_persona)->orderBy('id_inscripcion', 'desc')->first();
                $admision = $inscripcion->programa_proceso->admision->admision;
                $path = 'Posgrado/' . $admision . '/' . $this->documento_identidad . '/' . 'Voucher/';
                $filename = 'voucher-pago.' . $this->voucher->getClientOriginalExtension();
                $nombre_db = $path.$filename;
                $data = $this->voucher;
                $data->storeAs($path, $filename, 'files_publico');
                $pago->pago_voucher_url = $nombre_db;
            }
            $pago->id_canal_pago = $this->canal_pago;
            $pago->id_concepto_pago = $this->concepto_pago;
            $pago->save();

            // cambiar de estado a la observacion del pago
            $observacion = PagoObservacion::where('id_pago', $pago->id_pago)->orderBy('id_pago_observacion', 'desc')->first();
            $observacion->pago_observacion_estado = 0;
            $observacion->save();

            // emitir alerta de exito
            $this->dispatchBrowserEvent('alerta_pago_plataforma', [
                'title' => '!Exito!',
                'text' => 'Pago ha sido guardado con exito, espere a que sea validado por el administrador.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);

            // emitir evento para actualizar el contador de notificaciones
            $this->emit('actualizar_notificaciones');
        }

        // limpiar formulario
        $this->limpiar_pago();

        // cerra el modal
        $this->dispatchBrowserEvent('modal_pago_plataforma', [
            'action' => 'hide'
        ]);
    }

    public function render()
    {
        $canal_pagos = CanalPago::where('canal_pago_estado', 1)->get();
        $pagos = Pago::where('pago_documento', auth('plataforma')->user()->usuario_estudiante)
                        ->orderBy('id_pago', 'desc')
                        ->get(); // pagos del usuario logueado
        $persona = Persona::where('numero_documento', auth('plataforma')->user()->usuario_estudiante)->first(); // persona del usuario logueado
        $inscripcion_ultima = Inscripcion::where('id_persona', $persona->id_persona)->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        $evaluacion = $inscripcion_ultima->evaluacion; // evaluacion de la inscripcion del usuario logueado
        if($evaluacion)
        {
            $admitido = $persona->admitido->where('id_evaluacion', $evaluacion->id_evaluacion)->first(); // admitido de la inscripcion del usuario logueado
        }
        else
        {
            $admitido = null;
        }
        $canales_pagos = CanalPago::where('canal_pago_estado', 1)->get(); // canales de pago
        $conceptos_pagos = ConceptoPago::where('concepto_pago_estado', 1)->get(); // canales de pago
        return view('livewire.modulo-plataforma.pagos.index', [
            'canal_pagos' => $canal_pagos,
            'pagos' => $pagos,
            'admitido' => $admitido,
            'canales_pagos' => $canales_pagos,
            'conceptos_pagos' => $conceptos_pagos
        ]);
    }
}
