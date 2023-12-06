<?php

namespace App\Http\Livewire\ModuloPlataforma\Notificaciones;

use App\Models\Pago;
use App\Models\PagoObservacion;
use App\Models\Persona;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'actualizar_notificaciones' => 'render'
    ];

    public function leer_pago_validado(Pago $pago)
    {
        $pago->pago_leido = 2; // 1 = no leido, 2 = leido
        $pago->save();

        $this->emit('actualizar_notificaciones'); // emite el evento para actualizar el contador de notificaciones

        return redirect()->route('plataforma.pago'); // redirecciona a la ruta de pagos
    }

    public function render()
    {
        $observaciones = collect([]);
        $persona = Persona::where('id_persona', auth('plataforma')->user()->id_persona)->first();
        $pagos = Pago::where('pago_documento', $persona->numero_documento)->orderBy('id_pago', 'desc')->get();
        foreach ($pagos as $pago) {
            $observacion = PagoObservacion::where('id_pago', $pago->id_pago)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->get();
            if ($observacion->count() > 0) {
                foreach ($observacion as $obs) {
                    $observaciones->push($obs);
                }
            } else {
                $observaciones = $observaciones;
            }
            $observacion2 = PagoObservacion::where('id_pago', $pago->id_pago)->where('pago_observacion_estado', 2)->orderBy('id_pago_observacion', 'desc')->get();
            if ($observacion2->count() > 0) {
                foreach ($observacion2 as $obs) {
                    $observaciones->push($obs);
                }
            } else {
                $observaciones = $observaciones;
            }
        }
        $validaciones = collect([]);
        foreach ($pagos as $pago) {
            if($pago->pago_verificacion == 2 && $pago->pago_leido == 1){
                $validaciones->push($pago);
            }
        }
        return view('livewire.modulo-plataforma.notificaciones.index', [
            'observaciones' => $observaciones,
            'validaciones' => $validaciones
        ]);
    }
}
