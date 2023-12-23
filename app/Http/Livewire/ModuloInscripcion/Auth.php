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

    public function render() {
        $admision = Admision::where('admision_estado', 1)->first();

        $fecha_inicio_inscripcion = date('Y-m-d',strtotime($admision->admision_fecha_inicio_inscripcion)); // fecha de inicio de inscripcion
        $fecha_final_inscripcion = date('Y-m-d',strtotime($admision->admision_fecha_fin_inscripcion)); // fecha de fin de inscripcion

        return view('livewire.modulo-inscripcion.auth', [
            'fecha_inicio_inscripcion' => $fecha_inicio_inscripcion,
            'fecha_final_inscripcion' => $fecha_final_inscripcion
        ]);
    }
}
