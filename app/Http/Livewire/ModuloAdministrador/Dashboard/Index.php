<?php

namespace App\Http\Livewire\ModuloAdministrador\Dashboard;

use App\Models\Inscripcion;
use App\Models\Pago;
use Livewire\Component;

class Index extends Component
{
    
    public function render()
    {
        $programas_maestria = Inscripcion::join('mencion','inscripcion.id_mencion','=','mencion.id_mencion')
                                ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
                                ->join('programa','subprograma.id_programa','=','programa.id_programa')
                                ->select('subprograma.subprograma', 'mencion.mencion', 'programa.descripcion_programa', Inscripcion::raw('count(inscripcion.id_mencion) as cantidad_mencion'))
                                ->where('mencion.mencion_estado',1)
                                ->where('programa.id_programa',1) // 1 = Maestria
                                ->groupBy('inscripcion.id_mencion')
                                ->orderBy(Inscripcion::raw('count(inscripcion.id_mencion)'), 'DESC')
                                ->get();
        
        $programas_doctorado = Inscripcion::join('mencion','inscripcion.id_mencion','=','mencion.id_mencion')
                                ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
                                ->join('programa','subprograma.id_programa','=','programa.id_programa')
                                ->select('subprograma.subprograma', 'mencion.mencion', 'programa.descripcion_programa', Inscripcion::raw('count(inscripcion.id_mencion) as cantidad_mencion'))
                                ->where('mencion.mencion_estado',1)
                                ->where('programa.id_programa',2) // 2 = Doctorado
                                ->groupBy('inscripcion.id_mencion')
                                ->orderBy(Inscripcion::raw('count(inscripcion.id_mencion)'), 'DESC')
                                ->get();
        
        
        $ingreso_total = Pago::sum('monto');
        $ingreso_inscripcion = Pago::where('estado', 3)->sum('monto');
        $ingreso_constancia = Pago::where('estado', 4)->sum('monto');

        return view('livewire.modulo-administrador.dashboard.index', [
            'programas_maestria' => $programas_maestria,
            'programas_doctorado' => $programas_doctorado,
            'ingreso_total' => $ingreso_total,
            'ingreso_inscripcion' => $ingreso_inscripcion,
            'ingreso_constancia' => $ingreso_constancia
        ]);
    }
}
