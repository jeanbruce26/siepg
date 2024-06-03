<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Acta Evaluación
    </title>
    <style>
        body {
            margin-top: 150px;
            margin-bottom: 100px;
        }

        header {
            position: fixed;
            top: -10px;
            width: 100%;
        }

        #footer {
            position: fixed;
            left: 0px;
            right: 0px;
            bottom: -10px;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
            <thead>
                <tr>
                    <th align="left">
                        <div style="display: flex; align-items: center; margin-left: 0px;">
                            <img src="{{ public_path('assets_pdf/unu.png') }}" width="65px" height="80px" alt="logo unu">
                        </div>
                    </th>
                    <th>
                        <div style="text-align: center">
                            <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                UNIVERSIDAD NACIONAL DE UCAYALI
                            </div>
                            <div style="margin: 0.2rem"></div>
                            <div class="" style="font-weight: 700; font-size: 0.9rem;">
                                ESCUELA DE POSGRADO
                            </div>
                        </div>
                    </th>
                    <th align="right">
                        <div style="display: flex; align-items: center; margin-right: 0px;">
                            <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="65px" height="80px" alt="logo posgrado">
                        </div>
                    </th>
                </tr>
            </thead>
        </table>
        <div style="margin-top: 0.5rem; text-align: center;">
            <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                REGISTRO FINAL DE EVALUACIÓN ACADÉMICA
            </span>
        </div>
        <div style="margin-top: 0.2rem; text-align: center;">
            <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                {{ $admision_año }} - {{ $ciclo }}
            </span>
        </div>
    </header>
    <div id="footer">
        <div style="margin-top: 1.9rem; text-align: right;">
            <span style="text-align: center; font-weight: 400; font-size: 0.7rem">
                Fecha de emisión: ___/___/___
            </span>
        </div>
        <div style="margin-top: 2.8rem; text-align: right;">
            <span style="text-align: center; font-weight: 400; font-size: 0.7rem">
                {{ $docente }}
            </span>
        </div>
        <div style="margin-top: 0rem; text-align: right;">
            <span style="text-align: center; font-weight: 400; font-size: 0.7rem">
                Responsable del curso
            </span>
        </div>
    </div>
    <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
        <tbody>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        {{ $programa }}:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        {{ $subprograma }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        Mención:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        {{ $mencion }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        Curso:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        {{ $curso }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        Cod. Curso:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        {{ $codigo_curso }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        Docente:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        {{ $docente }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        Cod. Docente:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        {{ $codigo_docente }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        Créditos:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        {{ $creditos }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        Ciclo:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        {{ $ciclo }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        Grupo:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        {{ $grupo }}
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 85px;">
                    <div style="font-weight: 700; font-size: 0.7rem;">
                        Tipo de Acta:
                    </div>
                </td>
                <td>
                    <div style="font-weight: 400; font-size: 0.7rem;">
                        REGULAR
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 1rem; border-collapse: collapse;">
        <thead>
            <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                    Nro
                </th>
                <th rowspan="2" style="border: 1px solid black; padding: 8px;">
                    Código
                </th>
                <th rowspan="2" style="border: 1px solid black; padding: 8px; width: 190px;">
                    Alumno
                </th>
                <th colspan="3" style="border: 1px solid black; padding: 8px;">
                    Promedios
                </th>
                <th colspan="2" style="border: 1px solid black; padding: 8px;">
                    Promedio Final
                </th>
            </tr>
            <tr style="border: 1px solid black; padding: 8px; font-size: 0.6rem">
                <th style="border: 1px solid black; padding: 8px;">
                    Evaluación<br>Permanente
                </th>
                <th style="border: 1px solid black; padding: 8px;">
                    Evaluación<br>Medio Curso
                </th>
                <th style="border: 1px solid black; padding: 8px;">
                    Evaluación<br>Final
                </th>
                <th style="border: 1px solid black; padding: 8px;">
                    Número
                </th>
                <th style="border: 1px solid black; padding: 8px;">
                    Letras
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matriculados as $item)
            @php
                $notas = App\Models\NotaMatriculaCurso::where('id_matricula_curso', $item->id_matricula_curso)->first();
                $letras = array('Cero', 'Uno', 'Dos', 'Tres', 'Cuatro', 'Cinco', 'Seis', 'Siete', 'Ocho', 'Nueve', 'Diez', 'Once', 'Doce', 'Trece', 'Catorce', 'Quince', 'Dieciséis', 'Diecisiete', 'Dieciocho', 'Diecinueve', 'Veinte');
            @endphp
                <tr style="border: 1px solid black; padding: 4px; font-size: 0.6rem">
                    <td style="border: 1px solid black; padding: 4px;" align="center">
                        {{ $loop->iteration }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px;" align="center">
                        {{ $item->admitido_codigo }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px;">
                        {{ $item->nombre_completo }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px;" align="center">
                        {{ $notas->nota_evaluacion_permanente ? $notas->nota_evaluacion_permanente : '-' }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px;" align="center">
                        {{ $notas->nota_evaluacion_medio_curso ? $notas->nota_evaluacion_medio_curso : '-' }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px;" align="center">
                        {{ $notas->nota_evaluacion_final ? $notas->nota_evaluacion_final : '-' }}
                    </td>
                    <td style="border: 1px solid black; padding: 4px;" align="center">
                        @if ($notas->id_estado_cursos == 4)
                            NSP
                        @else
                            {{ $notas->nota_promedio_final }}
                        @endif
                    </td>
                    <td style="border: 1px solid black; padding: 4px;">
                        {{ $letras[$notas->nota_promedio_final] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
