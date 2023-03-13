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
    // dd(response()->download($inscripcion->inscripcion));
        return response()->download($inscripcion->inscripcion);
    }

    public function render()
    {
        // $this->descargar_pdf($this->inscripcion);
        return view('livewire.modulo-inscripcion.gracias');
    }
}
