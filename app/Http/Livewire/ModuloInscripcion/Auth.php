<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Models\CanalPago;
use Livewire\Component;
use Livewire\WithFileUploads;

class Auth extends Component
{
    use WithFileUploads; // Sirve para subir archivos
    public $documento_identidad, $numero_operacion, $monto_operacion, $fecha_pago, $canal_pago, $voucher, $iteration = 0; // variables para el formulario del modal de registro
    public $modo = ''; // variable para el modo de la vista
    public $documento_identidad_inscripcion, $numero_operacion_inscripcion; // variables para el formulario de inscripción



    public function updated($propertyName)
    {
        if($this->modo = 'registro_pago'){
            $this->validateOnly($propertyName, [
                'documento_identidad' => 'required|numeric|digits_between:8,9',
                'numero_operacion' => 'required|numeric',
                'monto_operacion' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'canal_pago' => 'required|numeric',
                'voucher' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
            ]);
        }
        $this->validateOnly($propertyName, [
            'documento_identidad_inscripcion' => 'required|numeric|digits_between:8,9',
            'numero_operacion_inscripcion' => 'required|numeric'
        ]);
    }

    public function cargar_registro_pago()
    {
        $this->limpiar_registro_pago();
        $this->modo = 'registro_pago';
    }

    public function limpiar_registro_pago()
    {
        $this->reset(['documento_identidad', 'numero_operacion', 'monto_operacion', 'fecha_pago', 'canal_pago', 'voucher']);
        $this->resetErrorBag();
        $this->resetValidation();
        $this->iteration++;
        $this->modo = '';
    }

    public function registrar_pago()
    {
        $this->validate([
            'documento_identidad' => 'required|numeric|digits_between:8,9',
            'numero_operacion' => 'required|numeric',
            'monto_operacion' => 'required|numeric',
            'fecha_pago' => 'required|date',
            'canal_pago' => 'required|numeric',
            'voucher' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);
    }

    public function render()
    {
        $canales_pagos = CanalPago::where('canal_pago_estado', 1)->get();

        return view('livewire.modulo-inscripcion.auth', [
            'canales_pagos' => $canales_pagos
        ]);
    }
}
