<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Models\Admision;
use App\Models\CanalPago;
use App\Models\ConceptoPago;
use App\Models\Inscripcion;
use App\Models\InscripcionPago;
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
        if ($pago)
        {
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

        // validar si el monto ingresado es igual al monto por concepto de inscripción
        $concepto_pago_monto = ConceptoPago::where('concepto_id', 1)->first()->monto;
        if($this->monto_operacion != $concepto_pago_monto)
        {
            // emitir evento para mostrar mensaje de alerta
            $this->dispatchBrowserEvent('registro_pago', [
                'title' => '¡Error!',
                'text' => 'El monto ingresado no es igual al monto por concepto de inscripción',
                'icon' => 'error',
                'confirmButtonText' => 'Cerrar',
                'color' => 'danger'
            ]);
            return redirect()->back();
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
        if($this->voucher)
        {
            $admision = Admision::where('estado', 1)->first()->admision;
            $path = 'Posgrado/' . $admision . '/' . $this->documento_identidad . '/' . 'Voucher/';
            $filename = 'voucher-pago' . $this->voucher->getClientOriginalExtension();
            $nombre_db = $path.$filename;
            $data = $this->voucher;
            $data->storeAs($path, $filename, 'files_publico');
            $pago->voucher = $nombre_db;
        }
        $pago->save();

        //  obtener el ultimo codigo de inscripcion
        $ultimo_codifo_inscripcion = Inscripcion::orderBy('inscripcion_codigo','DESC')->first();
        if($ultimo_codifo_inscripcion == null)
        {
            $codigo_inscripcion = 'IN0001';
        }else
        {
            $codigo_inscripcion = $ultimo_codifo_inscripcion->inscripcion_codigo;
            $codigo_inscripcion = substr($codigo_inscripcion, 2, 6);
            $codigo_inscripcion = intval($codigo_inscripcion) + 1;
            $codigo_inscripcion = str_pad($codigo_inscripcion, 4, "0", STR_PAD_LEFT);
            $codigo_inscripcion = 'IN'.$codigo_inscripcion;
        }

        // crear la inscripcion
        $inscripcion = new Inscripcion();
        $inscripcion->inscripcion_codigo = $codigo_inscripcion;
        $inscripcion->estado = 'activo';
        $inscripcion->admision_cod_admi = Admision::where('estado', 1)->first()->cod_admi;
        $inscripcion->save();

        // asigar el pago creado a la tabla de inscripcion pago
        $inscripcion_pago = new InscripcionPago();
        $inscripcion_pago->pago_id = $pago->pago_id;
        $inscripcion_pago->inscripcion_id = $inscripcion->id_inscripcion;
        $inscripcion_pago->concepto_pago_id = 1;
        $inscripcion_pago->save();

        // cerrar modal de registro de pago
        $this->dispatchBrowserEvent('modal_registro_pago', [
            'action' => 'hide'
        ]);

        // limpiar formulario de registro de pago
        $this->limpiar_registro_pago();

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
                // iniciar sesion con el pago ingresado
                auth('inscripcion')->login($pago);

                // redireccionar a la ruta de registro de inscripcion
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
