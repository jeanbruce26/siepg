<?php

namespace App\Http\Livewire\ModuloPlataforma\Notificaciones;

use App\Models\Pago;
use App\Models\PagoObservacion;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        'actualizar_notificaciones' => 'render'
    ];

    public function render()
    {
        $observaciones = collect([]);
        $pagos = Pago::where('pago_documento', auth('plataforma')->user()->usuario_estudiante)->orderBy('id_pago', 'desc')->get();
        foreach ($pagos as $pago) {
            $observacion = PagoObservacion::where('id_pago', $pago->id_pago)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->get();
            if ($observacion->count() > 0) {
                foreach ($observacion as $obs) {
                    $observaciones->push($obs);
                }
            } else {
                $observaciones = $observaciones;
            }
        }
        return view('livewire.modulo-plataforma.notificaciones.index', [
            'observaciones' => $observaciones
        ]);
    }
}
