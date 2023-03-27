<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Models\Inscripcion;
use Livewire\Component;

class Gracias extends Component
{
    public $id_inscripcion; // Este es el id de la inscripcion que realizo su inscripcion
    public $inscripcion; // Este es el objeto de la inscripcion que realizo su inscripcion

    public function mount()
    {
        $this->inscripcion = Inscripcion::find($this->id_inscripcion);
    }

    public function descargar_pdf(Inscripcion $inscripcion)
    {
        return response()->download($inscripcion->inscripcion_ficha_url);
    }

    public function render()
    {
        return view('livewire.modulo-inscripcion.gracias');
    }
}
