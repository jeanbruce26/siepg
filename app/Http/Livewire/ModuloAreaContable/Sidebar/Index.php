<?php

namespace App\Http\Livewire\ModuloAreaContable\Sidebar;

use App\Models\Pago;
use Livewire\Component;

class Index extends Component
{
    public $pago;
    public $route;

    protected $listeners = [
        'actualizar_notificacion_pagos' => 'render'
    ];

    public function mount()
    {
        $this->route = request()->route()->getName();
        // dd($this->route);
    }

    public function render()
    {
        $this->pago = Pago::where('pago_verificacion', 1)->count();
        return view('livewire.modulo-area-contable.sidebar.index');
    }
}
