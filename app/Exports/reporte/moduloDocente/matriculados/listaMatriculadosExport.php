<?php

namespace App\Exports\reporte\moduloDocente\matriculados;

use App\Models\Matricula;
use App\Models\ProgramaProceso;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Support\Str;

class listaMatriculadosExport implements FromCollection, WithMapping, ShouldAutoSize, WithTitle, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $item = 0;
    protected $matriculados;
    protected $programa;
    protected $curso;
    protected $grupo;

    public function __construct($matriculados, $programa, $curso, $grupo)
    {
        $this->item = 1;
        $this->matriculados = $matriculados;
        $this->programa = $programa->programa . ' EN ' . $programa->subprograma . ($programa->mencion ? ' CON MENCION EN ' . $programa->mencion : '');
        $this->curso = $curso->curso_nombre;
        $this->grupo = $grupo->grupo_detalle;
    }

    public function collection()
    {
        $matriculados = $this->matriculados;

        return $matriculados;
    }

    public function map($matriculados): array
    {
        return [
            $this->item++,
            $matriculados->admitido_codigo,
            $matriculados->numero_documento,
            $matriculados->apellido_paterno . ' ' . $matriculados->apellido_materno,
            $matriculados->nombre,
            $matriculados->celular,
            $matriculados->correo,
            $this->programa
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

                $event->sheet->setCellValue('A1', 'LISTADO DE MATRICULADOS - ' . Str::upper($this->curso) . ' - GRUPO ' . $this->grupo);

                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray($tamanio);
                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray($negrita);
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->getDelegate()->getStyle('A1:H1')->applyFromArray($centrar);

                $columnas = ['N°', 'CODIGO ESTUDIANTE', 'DNI', 'APELLIDOS', 'NOMBRES', 'CELULAR', 'CORREO', 'PROGRAMA ACADEMICO'];
                $event->sheet->getDelegate()->fromArray($columnas, NULL, 'A3');

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
        return 'Lista de Matriculados';
    }
}
