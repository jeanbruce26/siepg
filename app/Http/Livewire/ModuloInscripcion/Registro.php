<?php

namespace App\Http\Livewire\ModuloInscripcion;

use App\Models\Universidad;
use Livewire\Component;

class Registro extends Component
{
    public $paso = 0; // variable para el paso de la vista
    public $total_pasos = 3; // variable para el total de pasos de la vista
    public $universidad;


    public function mount()
    {
        $this->paso = 1;
    }

    public function paso_1()
    {
        $this->paso = 1;
    }

    public function paso_2()
    {
        $this->paso = 2;
    }

    public function paso_3()
    {
        $this->paso = 3;
    }

    public function render()
    {
        $universidades = Universidad::all();
        return view('livewire.modulo-inscripcion.registro', [
            'universidades' => $universidades
        ]);
    }
}
