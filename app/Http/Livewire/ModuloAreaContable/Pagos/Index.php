<?php

namespace App\Http\Livewire\ModuloAreaContable\Pagos;

use App\Models\Pago;
use Livewire\Component;

class Index extends Component
{
    public $pagos; // variable para almacenar los pagos

    public function mount()
    {
        // $this->pagos = Pago::where('dni','LIKE',"%{$buscar}%")
        //                     ->orWhere('nro_operacion','LIKE',"%{$buscar}%")
        //                     ->orWhere('pago_id','LIKE',"%{$buscar}%")
        //                     ->orderBy('pago_id','DESC')->paginate(200);
    }

    public function render()
    {
        return view('livewire.modulo-area-contable.pagos.index');
    }
}
