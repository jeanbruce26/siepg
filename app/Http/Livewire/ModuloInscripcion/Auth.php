<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Models\Admision;
use App\Models\CanalPago;
use App\Models\Pago;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Auth extends Component
{
    use WithFileUploads; // Sirve para subir archivos
    public $admision_year; // variable para el año de la admisión
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
                'voucher' => 'required|image|mimes:jpg,jpeg,png|max:2048'
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
        // validar formulario de registro de pago
        $this->validate([
            'documento_identidad' => 'required|numeric|digits_between:8,9',
            'numero_operacion' => 'required|numeric',
            'monto_operacion' => 'required|numeric',
            'fecha_pago' => 'required|date',
            'canal_pago' => 'required|numeric',
            'voucher' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // validar si el numero de operacion ya existe
        $pago = Pago::where('nro_operacion', $this->numero_operacion)->first();
        if ($pago) {
            if($pago->dni == $this->documento_identidad && $pago->fecha_pago == $this->fecha_pago){
                // emitir evento para mostrar mensaje de alerta
                $this->dispatchBrowserEvent('registro_pago', [
                    'title' => '¡Error!',
                    'text' => 'El Número de Operación y el Documento de Identidad ya se encuentran registrados en el sistema en la fecha seleccionada',
                    'icon' => 'error',
                    'confirmButtonText' => 'Cerrar',
                    'color' => 'danger'
                ]);
                return back();
            }else if ($pago->fecha_pago == $this->fecha_pago) {
                // emitir evento para mostrar mensaje de alerta
                $this->dispatchBrowserEvent('registro_pago', [
                    'title' => '¡Error!',
                    'text' => 'El número de operación ya ha sido ingresado en la fecha seleccionada',
                    'icon' => 'error',
                    'confirmButtonText' => 'Cerrar',
                    'color' => 'danger'
                ]);
                return redirect()->back();
            }else if($pago->dni == $this->documento_identidad){
                // emitir evento para mostrar mensaje de alerta
                $this->dispatchBrowserEvent('registro_pago', [
                    'title' => '¡Error!',
                    'text' => 'El Número de Operación y el Documento de Identidad ya se encuentran registrados en el sistema',
                    'icon' => 'error',
                    'confirmButtonText' => 'Cerrar',
                    'color' => 'danger'
                ]);
                return redirect()->back();
            }
        }

        // guardar datos en la base de datos de pago
        $pago = new Pago();
        $pago->dni = $this->documento_identidad;
        $pago->nro_operacion = $this->numero_operacion;
        $pago->monto = $this->monto_operacion;
        $pago->fecha_pago = $this->fecha_pago;
        $pago->estado = 1;
        $pago->canal_pago_id = $this->canal_pago;
        $pago->verificacion_pago = 1;
        if($this->voucher){
            $path = $this->documento_identidad . '/' . $this->admision_year . '/' . 'voucher/';
            $filename = 'voucher-pago' . $this->voucher->getClientOriginalExtension();
            $nombre_db = $path.$filename;
            $data = $this->voucher;
            $data->storeAs($path, $filename, 'files_publico');
            $pago->voucher = $nombre_db;
        }
        $pago->save();

        // cerrar modal de registro de pago
        $this->dispatchBrowserEvent('modal_registro_pago', [
            'action' => 'hide'
        ]);

        // emitir evento para mostrar mensaje de alerta de registro de pago
        $this->dispatchBrowserEvent('registro_pago', [
            'title' => 'Registro de pago',
            'text' => 'El pago se registró correctamente',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function iniciar_inscripcion()
    {
        // validar formulario de inicio de inscripcion
        $this->validate([
            'documento_identidad_inscripcion' => 'required|numeric|digits_between:8,9',
            'numero_operacion_inscripcion' => 'required|numeric'
        ]);

        // obtener fecha de fin de admision para sumarle 2 dias y cerrar el proceso de admision
        $admision = Admision::where('estado',1)->first();
        $valor = '+ 1 day';
        $fecha_final_admision = date('d/m/Y',strtotime($admision->fecha_fin.$valor));

        // buscar en la base de datos el pago ingresado
        $pago = Pago::where('dni', $this->documento_identidad_inscripcion)
            ->where('nro_operacion', $this->numero_operacion_inscripcion)
            ->first();

        // validar si la fecha de fin de admision es menor o igual a la fecha actual
        if($fecha_final_admision < date('d/m/Y', strtotime(today()))){
            // emitir evento para mostrar mensaje de alerta
            session()->flash('message', 'Proceso de Admisión cerrado');
            return redirect()->back();
        }

        // validar si el pago ingresado existe
        if(!$pago){
            // emitir evento para mostrar mensaje de alerta
            session()->flash('message', 'Credenciales incorrectas');
            return redirect()->back();
        }else{
            if($pago->estado == 1){
                auth('inscripcion')->login($pago);
                return redirect()->route('inscripcion.registro');
            }else{
                // emitir evento para mostrar mensaje de alerta
                session()->flash('message', 'El pago ingresado se encuentra anulado');
                return redirect()->back();
            }
        }
    }

    public function render()
    {
        $canales_pagos = CanalPago::where('canal_pago_estado', 1)->get();

        return view('livewire.modulo-inscripcion.auth', [
            'canales_pagos' => $canales_pagos
        ]);
    }
}
