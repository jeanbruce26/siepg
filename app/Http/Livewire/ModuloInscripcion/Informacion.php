<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Models\Expediente;
use Livewire\Component;

class Informacion extends Component
{
    public function render()
    {
        return view('livewire.modulo-inscripcion.informacion', [
            'expedientes' => Expediente::where('estado',1)->get(),
        ]);
    }
}
