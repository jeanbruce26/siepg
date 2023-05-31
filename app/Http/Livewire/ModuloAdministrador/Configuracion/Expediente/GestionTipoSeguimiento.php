<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Expediente;

use App\Models\Expediente;
use Livewire\Component;

class GestionTipoSeguimiento extends Component
{

    public $id_expediente;//id del expediente que ya se encuentra cargado en la vista de gestion de Tipo de Seguimiento
    //Variables para la gestion de Tipo de Seguimiento
    public $id_tipo_seguimiento;
    public $tipo_seguimiento;
    public $estado;

    public function render()
    {
        $expedienteModel = Expediente::find($this->id_expediente);

        return view('livewire.modulo-administrador.configuracion.expediente.gestion-tipo-seguimiento', 
        [
            'expedienteModel' => $expedienteModel
        ]);
    }
}
