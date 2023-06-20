<?php

namespace App\Http\Livewire\ModuloPlataforma\EstadoCuenta;

use App\Models\Admitido;
use App\Models\AdmitidoCiclo;
use App\Models\CostoEnseñanza;
use App\Models\Mensualidad;
use App\Models\Persona;
use Livewire\Component;

class Index extends Component
{
    public $usuario;
    public $persona;
    public $admitido;
    public $mensualidades;

    public function mount()
    {
        $this->usuario = auth('plataforma')->user();
        $this->persona = Persona::where('numero_documento', $this->usuario->usuario_estudiante)->first();
        $this->admitido = Admitido::where('id_persona', $this->persona->id_persona)->orderBy('id_admitido', 'desc')->first();
        if ( $this->admitido == null )
        {
            abort(403);
        }
    }

    public function render()
    {
        $this->mensualidades  = Mensualidad::where('id_admitido', $this->admitido->id_admitido)->orderBy('id_mensualidad', 'asc')->get();

        $admitido_ciclo = AdmitidoCiclo::where('id_admitido', $this->admitido->id_admitido)->orderBy('id_admitido_ciclo', 'desc')->first();
        $costo_enseñanza = CostoEnseñanza::where('id_programa_plan', $this->admitido->programa_proceso->id_programa_plan)->where('id_ciclo', $admitido_ciclo->id_ciclo)->first();

        $monto_total = $costo_enseñanza->costo_ciclo;
        $monto_pagado = 0;

        foreach($this->mensualidades as $mensualidad)
        {
            if ( $mensualidad->pago->pago_estado == 2 && $mensualidad->pago->pago_verificacion == 2 )
            {
                $monto_pagado += $mensualidad->pago->pago_monto;
            }
        }

        $deuda = $monto_total - $monto_pagado;

        return view('livewire.modulo-plataforma.estado-cuenta.index', [
            'costo_enseñanza' => $costo_enseñanza,
            'monto_total' => $monto_total,
            'monto_pagado' => $monto_pagado,
            'deuda' => $deuda,
        ]);
    }
}
