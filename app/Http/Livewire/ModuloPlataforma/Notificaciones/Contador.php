<?php

namespace App\Http\Livewire\ModuloPlataforma\Notificaciones;

use App\Models\Pago;
use App\Models\PagoObservacion;
use App\Models\Persona;
use Livewire\Component;

class Contador extends Component
{
    public $contador = 0; // variable para almacenar el contador de notificaciones

    protected $listeners = [
        'actualizar_notificaciones' => 'render'
    ];

    public function render()
    {
        $observaciones = collect([]);
        $observaciones2 = collect([]);
        $persona = Persona::where('id_persona', auth('plataforma')->user()->id_persona)->first();
        $pagos = Pago::where('pago_documento', $persona->numero_documento)->orderBy('id_pago', 'desc')->get();
        foreach ($pagos as $pago) {
            $observacion = PagoObservacion::where('id_pago', $pago->id_pago)
                ->where('pago_observacion_estado', 1)
                ->orderBy('id_pago_observacion', 'desc')
                ->get();
            if ($observacion->count() > 0) {
                foreach ($observacion as $obs) {
                    $observaciones->push($obs);
                }
            } else {
                $observaciones = $observaciones;
            }

            $observacion2 = PagoObservacion::where('id_pago', $pago->id_pago)
                ->where('pago_observacion_estado', 2)
                ->orderBy('id_pago_observacion', 'desc')
                ->get();
            if ($observacion2->count() > 0) {
                foreach ($observacion2 as $obs) {
                    $observaciones2->push($obs);
                }
            } else {
                $observaciones2 = $observaciones2;
            }
        }
        $observaciones_count = $observaciones->count() + $observaciones2->count();
        $validaciones = collect([]);
        foreach ($pagos as $pago) {
            if ($pago->pago_verificacion == 2 && $pago->pago_leido == 1) {
                $validaciones->push($pago);
            }
        }
        $validaciones_count = $validaciones->count();

        $this->contador = $observaciones_count + $validaciones_count;

        return view('livewire.modulo-plataforma.notificaciones.contador');
    }
}
