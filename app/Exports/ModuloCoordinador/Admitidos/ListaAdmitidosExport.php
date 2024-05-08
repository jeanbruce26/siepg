<?php

namespace App\Exports\ModuloCoordinador\Admitidos;

use App\Models\Admitido;
use App\Models\ProgramaProceso;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ListaAdmitidosExport implements FromCollection, WithMapping, ShouldAutoSize, WithTitle, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $item = 0;
    protected $programa;
    protected $programa_nombre;

    public function __construct($id_programa, $id_admision)
    {
        $this->item = 1;
        $this->programa = ProgramaProceso::join('programa_plan','programa_proceso.id_programa_plan','=','programa_plan.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->join('admision','programa_proceso.id_admision','=','admision.id_admision')
            ->where('programa.id_programa',$id_programa)
            ->where('admision.id_admision',$id_admision)
            ->first();
        if ($this->programa->programa_plan->programa->mencion) {
            $this->programa_nombre = $this->programa->programa_plan->programa->programa . ' EN ' . $this->programa->programa_plan->programa->subprograma . ' CON MENCION EN ' . $this->programa->programa_plan->programa->mencion;
        } else {
            $this->programa_nombre = $this->programa->programa_plan->programa->programa . ' EN ' . $this->programa->programa_plan->programa->subprograma;
        }
    }

    public function collection()
    {
        $admitidos = Admitido::join('persona','admitido.id_persona','=','persona.id_persona')
            ->join('programa_proceso','admitido.id_programa_proceso','=','programa_proceso.id_programa_proceso')
            ->join('programa_plan','programa_proceso.id_programa_plan','=','programa_plan.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->where('admitido.id_programa_proceso',$this->programa->id_programa_proceso)
            ->get();
        return $admitidos;
    }

    public function map($admitidos): array
    {
        if ($admitidos->mencion) {
            $admitidos = $admitidos->programa . ' EN ' . $admitidos->subprograma . ' CON MENCION EN ' . $admitidos->mencion;
        } else {
            $admitidos = $admitidos->programa . ' EN ' . $admitidos->subprograma;
        }

        return [
            $this->item++,
            $admitidos->numero_documento,
            $admitidos->apellido_paterno . ' ' . $admitidos->apellido_materno,
            $admitidos->nombre,
            $admitidos->celular,
            $admitidos->correo,
            $admitidos->especialidad_carrera,
            $programa
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Listado de Admitidos - Escuela de Posgrado');
                $event->writer->getProperties()->setTitle('Listado de Admitidos - Escuela de Posgrado');

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

                $event->sheet->setCellValue('A1', 'LISTADO DE ADMITIDOS - ' . $this->programa_nombre);

                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray($tamanio);
                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray($negrita);
                $event->sheet->getDelegate()->mergeCells('A1:G1');
                $event->sheet->getDelegate()->getStyle('A1:G1')->applyFromArray($centrar);

                $columnas = ['N°', 'DNI', 'APELLIDOS', 'NOMBRES', 'CELULAR', 'CORREO', 'ESPECIALIDAD', 'PROGRAMA ACADEMICO'];
                $event->sheet->getDelegate()->fromArray($columnas, NULL, 'A3');

                $event->sheet->getDelegate()->getStyle('A3:H3')->applyFromArray($header);
                $event->sheet->getDelegate()->getStyle('A3:H3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99a3a4');
                for ($i = 1; $i <= $this->item; $i++) {
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2))->applyFromArray($negrita);
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':H' . ($i + 2))->applyFromArray($border);
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':B' . ($i + 2))->applyFromArray($centrar);
                    $event->sheet->getDelegate()->getStyle('E' . ($i + 2) . ':E' . ($i + 2))->applyFromArray($centrar);
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
        return 'LISTADO DE ADMITIDOS';
    }
}
