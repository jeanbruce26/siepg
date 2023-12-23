<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Models\Admision;
use App\Models\CanalPago;
use App\Models\ConceptoPago;
use App\Models\Inscripcion;
use App\Models\InscripcionPago;
use App\Models\Pago;
use App\Models\PagoObservacion;
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

        // validar si el pago ingresado, ver el numero de documento y si el concepto de pago ya se encuentra registrado para reemplazarlo
        $pago = Pago::where('pago_documento', $this->documento_identidad)->where('id_concepto_pago', 1)->orderBy('pago_fecha', 'desc')->first();
        if($pago)
        {
            if($pago->pago_verificacion == 0 && ($pago->pago_estado == 0 || $pago->pago_estado == 1))
            {
                $pago->pago_documento = $this->documento_identidad;
                $pago->pago_operacion = $this->numero_operacion;
                $pago->pago_monto = $this->monto_operacion;
                $pago->pago_fecha = $this->fecha_pago;
                $pago->pago_estado = 1;
                $pago->pago_verificacion = 1;
                if($this->voucher)
                {
                    $admision = Admision::where('admision_estado', 1)->first()->admision;
                    $path = 'Posgrado/' . $admision . '/' . $this->documento_identidad . '/' . 'Voucher/';
                    $filename = 'voucher-pago.' . $this->voucher->getClientOriginalExtension();
                    $nombre_db = $path.$filename;
                    $data = $this->voucher;
                    $data->storeAs($path, $filename, 'files_publico');
                    $pago->pago_voucher_url = $nombre_db;
                }
                $pago->id_canal_pago = $this->canal_pago;
                $pago->id_concepto_pago = 1;
                $pago->save();

                // cambiar de estado a la observacion del pago
                $observacion = PagoObservacion::where('id_pago', $pago->id_pago)->orderBy('id_pago_observacion', 'desc')->get();
                if($observacion)
                {
                    foreach($observacion as $item)
                    {
                        $item->pago_observacion_estado = 0;
                        $item->save();
                    }
                }
            }
            else
            {
                // validar si el numero de operacion ya existe
                $pago = Pago::where('pago_operacion', $this->numero_operacion)->first();
                if ($pago)
                {
                    if($pago->pago_documento == $this->documento_identidad && $pago->pago_fecha == $this->fecha_pago){
                        // emitir evento para mostrar mensaje de alerta
                        $this->dispatchBrowserEvent('registro_pago', [
                            'title' => '¡Error!',
                            'text' => 'El Número de Operación y el Documento de Identidad ya se encuentran registrados en el sistema en la fecha seleccionada',
                            'icon' => 'error',
                            'confirmButtonText' => 'Cerrar',
                            'color' => 'danger'
                        ]);
                        return back();
                    }else if ($pago->pago_fecha == $this->fecha_pago) {
                        // emitir evento para mostrar mensaje de alerta
                        $this->dispatchBrowserEvent('registro_pago', [
                            'title' => '¡Error!',
                            'text' => 'El número de operación ya ha sido ingresado en la fecha seleccionada',
                            'icon' => 'error',
                            'confirmButtonText' => 'Cerrar',
                            'color' => 'danger'
                        ]);
                        return redirect()->back();
                    }else if($pago->pago_documento == $this->documento_identidad){
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
                $concepto_pago_monto = ConceptoPago::where('id_concepto_pago', 1)->first()->concepto_pago_monto;
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
                $pago->pago_documento = $this->documento_identidad;
                $pago->pago_operacion = $this->numero_operacion;
                $pago->pago_monto = $this->monto_operacion;
                $pago->pago_fecha = $this->fecha_pago;
                $pago->pago_estado = 1;
                $pago->pago_verificacion = 1;
                if($this->voucher)
                {
                    $admision = Admision::where('admision_estado', 1)->first()->admision;
                    $path = 'Posgrado/' . $admision . '/' . $this->documento_identidad . '/' . 'Voucher/';
                    $filename = 'voucher-pago.' . $this->voucher->getClientOriginalExtension();
                    $nombre_db = $path.$filename;
                    $data = $this->voucher;
                    $data->storeAs($path, $filename, 'files_publico');
                    $pago->pago_voucher_url = $nombre_db;
                }
                $pago->id_canal_pago = $this->canal_pago;
                $pago->id_concepto_pago = 1;
                $pago->save();

                //  obtener el ultimo codigo de inscripcion y creamos el nuevo codigo de acuerdo al año y convocatoria del proceso de admision
                $admision_año = Admision::where('admision_estado', 1)->first()->admision_año;
                $admision_año = substr($admision_año, -2);
                $admision_convocatoria = Admision::where('admision_estado', 1)->first()->admision_convocatoria;

                $ultimo_codifo_inscripcion = Inscripcion::orderBy('inscripcion_codigo','DESC')->first();
                if($ultimo_codifo_inscripcion == null)
                {
                    $codigo_inscripcion = 'IN' . $admision_año . $admision_convocatoria . '00001';
                }
                else
                {
                    $codigo_inscripcion = $ultimo_codifo_inscripcion->inscripcion_codigo;
                    if(substr($codigo_inscripcion, 2, 2) != $admision_año || substr($codigo_inscripcion, 4, 1) != $admision_convocatoria )
                    {
                        $codigo_inscripcion = 'IN' . $admision_año . $admision_convocatoria . '00001';
                    }
                    else
                    {
                        $codigo_inscripcion = substr($codigo_inscripcion, 5, 5);
                        $codigo_inscripcion = intval($codigo_inscripcion) + 1;
                        $codigo_inscripcion = str_pad($codigo_inscripcion, 5, "0", STR_PAD_LEFT);
                        $codigo_inscripcion = 'IN' . $admision_año . $admision_convocatoria . $codigo_inscripcion;
                    }
                }

                // crear la inscripcion
                $inscripcion = new Inscripcion();
                $inscripcion->inscripcion_codigo = $codigo_inscripcion;
                $inscripcion->inscripcion_estado = 1;
                $inscripcion->id_pago = $pago->id_pago;
                $inscripcion->save();
            }
        }
        else
        {
            // validar si el numero de operacion ya existe
            $pago = Pago::where('pago_operacion', $this->numero_operacion)->first();
            if ($pago)
            {
                if($pago->pago_documento == $this->documento_identidad && $pago->pago_fecha == $this->fecha_pago){
                    // emitir evento para mostrar mensaje de alerta
                    $this->dispatchBrowserEvent('registro_pago', [
                        'title' => '¡Error!',
                        'text' => 'El Número de Operación y el Documento de Identidad ya se encuentran registrados en el sistema en la fecha seleccionada',
                        'icon' => 'error',
                        'confirmButtonText' => 'Cerrar',
                        'color' => 'danger'
                    ]);
                    return back();
                }else if ($pago->pago_fecha == $this->fecha_pago) {
                    // emitir evento para mostrar mensaje de alerta
                    $this->dispatchBrowserEvent('registro_pago', [
                        'title' => '¡Error!',
                        'text' => 'El número de operación ya ha sido ingresado en la fecha seleccionada',
                        'icon' => 'error',
                        'confirmButtonText' => 'Cerrar',
                        'color' => 'danger'
                    ]);
                    return redirect()->back();
                }else if($pago->pago_documento == $this->documento_identidad){
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
            $concepto_pago_monto = ConceptoPago::where('id_concepto_pago', 1)->first()->concepto_pago_monto;
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
            $pago->pago_documento = $this->documento_identidad;
            $pago->pago_operacion = $this->numero_operacion;
            $pago->pago_monto = $this->monto_operacion;
            $pago->pago_fecha = $this->fecha_pago;
            $pago->pago_estado = 1;
            $pago->pago_verificacion = 1;
            if($this->voucher)
            {
                $admision = Admision::where('admision_estado', 1)->first()->admision;
                $path = 'Posgrado/' . $admision . '/' . $this->documento_identidad . '/' . 'Voucher/';
                $filename = 'voucher-pago.' . $this->voucher->getClientOriginalExtension();
                $nombre_db = $path.$filename;
                $data = $this->voucher;
                $data->storeAs($path, $filename, 'files_publico');
                $pago->pago_voucher_url = $nombre_db;
            }
            $pago->id_canal_pago = $this->canal_pago;
            $pago->id_concepto_pago = 1;
            $pago->save();

            //  obtener el ultimo codigo de inscripcion y creamos el nuevo codigo de acuerdo al año y convocatoria del proceso de admision
            $admision_año = Admision::where('admision_estado', 1)->first()->admision_año;
            $admision_año = substr($admision_año, -2);
            $admision_convocatoria = Admision::where('admision_estado', 1)->first()->admision_convocatoria;

            $ultimo_codifo_inscripcion = Inscripcion::orderBy('inscripcion_codigo','DESC')->first();
            if($ultimo_codifo_inscripcion == null)
            {
                $codigo_inscripcion = 'IN' . $admision_año . $admision_convocatoria . '00001';
            }
            else
            {
                $codigo_inscripcion = $ultimo_codifo_inscripcion->inscripcion_codigo;
                if(substr($codigo_inscripcion, 2, 2) != $admision_año || substr($codigo_inscripcion, 4, 1) != $admision_convocatoria )
                {
                    $codigo_inscripcion = 'IN' . $admision_año . $admision_convocatoria . '00001';
                }
                else
                {
                    $codigo_inscripcion = substr($codigo_inscripcion, 5, 5);
                    $codigo_inscripcion = intval($codigo_inscripcion) + 1;
                    $codigo_inscripcion = str_pad($codigo_inscripcion, 5, "0", STR_PAD_LEFT);
                    $codigo_inscripcion = 'IN' . $admision_año . $admision_convocatoria . $codigo_inscripcion;
                }
            }

            // crear la inscripcion
            $inscripcion = new Inscripcion();
            $inscripcion->inscripcion_codigo = $codigo_inscripcion;
            $inscripcion->inscripcion_estado = 1;
            $inscripcion->id_pago = $pago->id_pago;
            $inscripcion->save();
        }

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

    public function alerta_registro_pago()
    {
        $admision = Admision::where('admision_estado', 1)->first();
        $this->dispatchBrowserEvent('registro_pago', [
            'title' => '¡Error!',
            'text' => 'El registro de pagos para el proceso de inscripcion ' . $admision->admision .' se encuentra cerrado.',
            'icon' => 'error',
            'confirmButtonText' => 'Cerrar',
            'color' => 'danger'
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
        $admision = Admision::where('admision_estado',1)->first();
        $valor = '+ 1 day';
        $fecha_final_admision = date('Y-m-d',strtotime($admision->admision_fecha_fin_inscripcion.$valor));

        // buscar en la base de datos el pago ingresado
        $pago = Pago::where('pago_documento', $this->documento_identidad_inscripcion)
            ->where('pago_operacion', $this->numero_operacion_inscripcion)
            ->first();

        // validar si la fecha de fin de admision es menor o igual a la fecha actual
        if($fecha_final_admision < date('Y-m-d'))
        {
            // emitir evento para mostrar mensaje de alerta
            session()->flash('message', 'Proceso de Admisión cerrado');
            return redirect()->back();
        }

        // validar si el pago ingresado no existe
        if(!$pago)
        {
            // emitir evento para mostrar mensaje de alerta
            session()->flash('message', 'Credenciales incorrectas');
            return redirect()->back();
        }

        // validar si el pago ingresado fue verificado
        if($pago->pago_verificacion == 1)
        {
            // emitir evento para mostrar mensaje de alerta
            session()->flash('message', 'Pago esta en proceso de verificación');
            return redirect()->back();
        }
        else if($pago->pago_verificacion == 0 && $pago->pago_estado == 1)
        {
            // emitir evento para mostrar mensaje de alerta
            session()->flash('message', 'Pago Observado');
            session()->flash('message2', 'Por favor, vuelva a registar el pago con las observaciones corregidas. Cualquier duda o consulta, escribanos al correo: admision_posgrado@unu.edu.pe');
            $observacion = PagoObservacion::where('id_pago', $pago->id_pago)->orderBy('id_pago_observacion', 'desc')->first();
            if($observacion)
            {
                session()->flash('observacion', $observacion->pago_observacion);
            }
            return redirect()->back();
        }
        else if($pago->pago_verificacion == 0 && $pago->pago_estado == 0)
        {
            // emitir evento para mostrar mensaje de alerta
            session()->flash('message', 'Pago Rechazado');
            session()->flash('message2', 'Por favor, vuelva a realizar el pago. Cualquier duda o consulta, escribanos al correo: admision_posgrado@unu.edu.pe');
            $observacion = PagoObservacion::where('id_pago', $pago->id_pago)->orderBy('id_pago_observacion', 'desc')->first();
            if($observacion)
            {
                session()->flash('observacion', $observacion->pago_observacion);
            }
            return redirect()->back();
        }

        // validar si el pago ingresado existe
        if($pago){
            if($pago->pago_estado == 1){
                // iniciar sesion con el pago ingresado
                auth('inscripcion')->login($pago);

                // redireccionar a la ruta de registro de inscripcion
                return redirect()->route('inscripcion.registro');
            }else{
                // emitir evento para mostrar mensaje de alerta
                session()->flash('message', 'Inscripcion de pago ya realizada');
                return redirect()->back();
            }
        }
    }

    public function ingresar() {
        $admision = Admision::where('admision_estado', 1)->first();
        if ($admision->admision_fecha_inicio_inscripcion <= date('Y-m-d') && $admision->admision_fecha_fin_inscripcion >= date('Y-m-d')) {
            return redirect()->route('inscripcion.registro');
        } else {
            // emitir evento para mostrar mensaje de alerta
            $this->dispatchBrowserEvent('toast-basico', [
                'type' => 'error',
                'title' => '¡Error!',
                'message' => 'El proceso de admisión se encuentra cerrado',
            ]);
            return ;
        }
    }

    public function render()
    {
        $canales_pagos = CanalPago::where('canal_pago_estado', 1)->get();
        $admision = Admision::where('admision_estado', 1)->first();

        $fecha_inicio_inscripcion = date('Y-m-d',strtotime($admision->admision_fecha_inicio_inscripcion)); // fecha de inicio de inscripcion
        $fecha_final_inscripcion = date('Y-m-d',strtotime($admision->admision_fecha_fin_inscripcion)); // fecha de fin de inscripcion

        return view('livewire.modulo-inscripcion.auth', [
            'canales_pagos' => $canales_pagos,
            'fecha_inicio_inscripcion' => $fecha_inicio_inscripcion,
            'fecha_final_inscripcion' => $fecha_final_inscripcion
        ]);
    }
}
