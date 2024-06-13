<?php

namespace App\Exports\Reporte\ModuloCoordinador\ReportePagos;

use App\Models\Matricula;
use Illuminate\Support\Str;
use App\Models\ProgramaProceso;
use Illuminate\Support\Collection;
use App\Models\ProgramaProcesoGrupo;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ListaReportePagosAdmitidosExport implements FromCollection, WithMapping, ShouldAutoSize, WithTitle, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $item = 0;
    protected $matriculados;
    protected $programa_proceso;
    protected $programa;
    protected $grupo;
    protected $tipo_programa;

    public function __construct($id_programa_proceso, $id_grupo)
    {
        $this->item = 1;
        $this->programa_proceso = ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
            ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
            ->join('modalidad', 'modalidad.id_modalidad', '=', 'programa.id_modalidad')
            ->where('programa_proceso.id_programa_proceso', $id_programa_proceso)
            ->first();
        $this->tipo_programa = $this->programa_proceso->programa_tipo;
        $this->matriculados = Matricula::join('admitido','admitido.id_admitido','=','matricula.id_admitido')
            ->join('programa_proceso','programa_proceso.id_programa_proceso','=','admitido.id_programa_proceso')
            ->join('programa_plan','programa_plan.id_programa_plan','=','programa_proceso.id_programa_plan')
            ->join('programa','programa.id_programa','=','programa_plan.id_programa')
            ->join('persona','persona.id_persona','=','admitido.id_persona')
            ->join('programa_proceso_grupo','programa_proceso_grupo.id_programa_proceso_grupo','=','matricula.id_programa_proceso_grupo')
            ->where('admitido.id_programa_proceso',$id_programa_proceso)
            ->where('matricula.id_programa_proceso_grupo',$id_grupo)
            ->where('matricula.matricula_estado',1)
            ->orderBy('persona.nombre_completo','asc')
            ->get();
        $this->programa = $this->programa_proceso->programa . ' EN ' . $this->programa_proceso->subprograma . ($this->programa_proceso->mencion ? ' CON MENCION EN ' . $this->programa_proceso->mencion : '');
        $this->grupo = ProgramaProcesoGrupo::where('id_programa_proceso_grupo', $id_grupo)->first()->grupo_detalle;
        // dd($this->programa_proceso, $this->tipo_programa, $this->matriculados, $this->programa, $this->grupo);
    }

    public function collection(): Collection
    {
        $matriculados = $this->matriculados;

        return $matriculados;
    }

    public function map($matriculados): array
    {
        $monto_total = dataPagoMatricula($matriculados)['monto_total'];
        $monto_pagado = dataPagoMatricula($matriculados)['monto_pagado'];
        $deuda = dataPagoMatricula($matriculados)['deuda'];
        return [
            $this->item++,
            $matriculados->admitido_codigo,     // codigo de admitido
            $matriculados->numero_documento,    // numero de documento
            $matriculados->apellido_paterno . ' ' . $matriculados->apellido_materno . ', ' . $matriculados->nombre,
            $matriculados->celular,             // celular
            $matriculados->correo,              // correo
            $monto_total,                       // monto total
            $monto_pagado,                      // monto pagado
            $deuda,                             // deuda
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Listado de Matriculados - Escuela de Posgrado');
                $event->writer->getProperties()->setTitle('Listado de Matriculados - Escuela de Posgrado');

            },
            AfterSheet::class => function (AfterSheet $event) {

                $negrita = [
                    'font' => [
                        'bold' => true,
                    ],
                ];
                $header = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ];
                $centrar = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ]
                ];
                $italic = [
                    'font' => [
                        'italic' => true,
                    ]
                ];
                $tamanio = [
                    'font' => [
                        'size' => 14,
                    ]
                ];
                $border = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ];

                $event->sheet->setCellValue('A1', $this->programa . ' - GRUPO ' . $this->grupo);

                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray($tamanio);
                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray($negrita);
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray($centrar);

                $columnas = ['N°', 'CODIGO ESTUDIANTE', 'DNI', 'NOMBRES', 'CELULAR', 'CORREO', 'DEPOSITO', 'COSTO ENSEÑANZA', 'PAGO', 'DEUDA'];
                $event->sheet->getDelegate()->fromArray($columnas, NULL, 'A3');
                $columas_2 = ['', '', '', '', '', '', 'S/.', 'S/.', 'S/.'];
                $event->sheet->getDelegate()->fromArray($columas_2, NULL, 'G3');

                $event->sheet->getDelegate()->getStyle('A3:H3')->applyFromArray($header);
                $event->sheet->getDelegate()->getStyle('A3:H3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99a3a4');
                for ($i = 1; $i <= $this->item; $i++) {
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2))->applyFromArray($negrita);
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':H' . ($i + 2))->applyFromArray($border);
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':C' . ($i + 2))->applyFromArray($centrar);
                    $event->sheet->getDelegate()->getStyle('F' . ($i + 2) . ':F' . ($i + 2))->applyFromArray($centrar);
                }

            },
        ];
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function title(): string
    {
        return 'Reporte de pagos';
    }
}
