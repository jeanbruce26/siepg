<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionAdmision\InscripcionPago;

use App\Models\Inscripcion;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; //paginacion de bootstrap

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';
    public function render()
    {
        $inscripcion_pagos = Inscripcion::join('persona', 'inscripcion.id_persona', '=', 'persona.id_persona')
            ->join('pago', 'inscripcion.id_pago', '=', 'pago.id_pago')
            ->join('canal_pago', 'pago.id_canal_pago', '=', 'canal_pago.id_canal_pago')
            ->join('concepto_pago', 'pago.id_concepto_pago', '=', 'concepto_pago.id_concepto_pago')
            ->where('persona.nombre', 'LIKE', "%{$this->search}%")
            ->orWhere('persona.apellido_paterno', 'LIKE', "%{$this->search}%")
            ->orWhere('persona.apellido_materno', 'LIKE', "%{$this->search}%")
            ->orWhere('persona.numero_documento', 'LIKE', "%{$this->search}%")
            ->orWhere('inscripcion.id_pago', 'LIKE', "%{$this->search}%")
            ->orWhere('pago.pago_documento', 'LIKE', "%{$this->search}%")
            ->orWhere('canal_pago.canal_pago', 'LIKE', "%{$this->search}%")
            ->orWhere('concepto_pago.concepto_pago', 'LIKE', "%{$this->search}%")
            ->orderBy('id_inscripcion', 'DESC')
            ->paginate(100);

        return view('livewire.modulo-administrador.gestion-admision.inscripcion-pago.index', [
            'inscripcion_pagos' => $inscripcion_pagos
        ]);
    }
}
