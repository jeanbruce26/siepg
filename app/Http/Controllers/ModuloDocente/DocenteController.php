<?php

namespace App\Http\Controllers\ModuloDocente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\CursoProgramaPlan;
use App\Models\Docente;
use App\Models\DocenteCurso;
use App\Models\MatriculaCurso;
use App\Models\Programa;
use App\Models\ProgramaProcesoGrupo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function inicio()
    {
        return view('modulo-docente.inicio.index');
    }

    public function perfil()
    {
        $id_tipo_trabajador = 1;
        return view('modulo-docente.perfil.index', [
            'id_tipo_trabajador' => $id_tipo_trabajador
        ]);
    }

    public function matriculados($id_docente_curso)
    {
        return view('modulo-docente.matriculados.index', [
            'id_docente_curso' => $id_docente_curso
        ]);
    }
}
