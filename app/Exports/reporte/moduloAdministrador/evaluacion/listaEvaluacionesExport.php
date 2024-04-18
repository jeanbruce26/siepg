<?php

namespace App\Exports\reporte\moduloAdministrador\evaluacion;

use App\Models\Inscripcion;
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

class listaEvaluacionesExport implements FromCollection, WithMapping, ShouldAutoSize, WithTitle, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $item = 0;
    protected $programa;
    protected $programa_nombre;

    public function __construct($programa)
    {
        $this->item = 1;
        $this->programa = ProgramaProceso::find($programa);
        $this->programa_nombre = $this->programa->mencion
            ? ($this->programa->programa . ' EN ' . $this->programa->subprograma . ' CON MENCION EN ' . $this->programa->mencion)
            : ($this->programa->programa . ' EN ' . $this->programa->subprograma);
    }

    public function collection()
    {
        $programas = Inscripcion::join('programa_proceso','inscripcion.id_programa_proceso','=','programa_proceso.id_programa_proceso')
            ->join('programa_plan','programa_proceso.id_programa_plan','=','programa_plan.id_programa_plan')
            ->join('programa','programa_plan.id_programa','=','programa.id_programa')
            ->join('persona','inscripcion.id_persona','=','persona.id_persona')
            ->where('programa.programa_estado',1)
            ->where('programa.id_modalidad',2)
            ->where('inscripcion.inscripcion_estado',1)
            ->where('inscripcion.retiro_inscripcion',0)
            ->where('inscripcion.verificar_expedientes',1)
            ->where('inscripcion.id_programa_proceso',$this->programa->id_programa_proceso)
            ->orderBy('persona.nombre_completo', 'asc')
            ->get();

        return $programas;
    }

    public function map($inscritos): array
    {
        if ($inscritos->mencion) {
            $programa = $inscritos->programa . ' EN ' . $inscritos->subprograma . ' CON MENCION EN ' . $inscritos->mencion;
        } else {
            $programa = $inscritos->programa . ' EN ' . $inscritos->subprograma;
        }
        return [
            $this->item++,
            $inscritos->numero_documento,
            $inscritos->apellido_paterno . ' ' . $inscritos->apellido_materno,
            $inscritos->nombre,
            $programa
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class => function (BeforeExport $event) {
                $event->writer->getProperties()->setCreator('Listado de Evaluaciones - Escuela de Posgrado');
                $event->writer->getProperties()->setTitle('Listado de Evaluaciones - Escuela de Posgrado');

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

                $event->sheet->setCellValue('A1', 'LISTADO DE INSCRITOS PARA LAS EVALUACIONES - ' . $this->programa_nombre);
                $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray($tamanio);
                $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray($negrita);
                $event->sheet->getDelegate()->mergeCells('A1:F1');
                $event->sheet->getDelegate()->getStyle('A1:F1')->applyFromArray($centrar);

                $columnas = ['N°', 'DNI', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'NOMBRES', 'PROGRAMA ACADEMICO'];
                $event->sheet->getDelegate()->fromArray($columnas, NULL, 'A3');

                $event->sheet->getDelegate()->getStyle('A3:F3')->applyFromArray($header);
                $event->sheet->getDelegate()->getStyle('A3:F3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99a3a4');
                for ($i = 1; $i <= $this->item; $i++) {
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2))->applyFromArray($negrita);
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':F' . ($i + 2))->applyFromArray($border);
                    $event->sheet->getDelegate()->getStyle('A' . ($i + 2) . ':B' . ($i + 2))->applyFromArray($centrar);
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
        return $this->programa_nombre;
    }
}
