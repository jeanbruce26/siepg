<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Estado de Cuenta
    </title>
    <style>
        body {
            margin-top: 135px;
            margin-bottom: 80px;
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
        <table class="table"
            style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
            <thead>
                <tr>
                    <th align="left">
                        <div style="display: flex; align-items: center; margin-left: 0px;">
                            <img src="{{ public_path('assets_pdf/unu.png') }}" width="65px" height="80px"
                                alt="logo unu">
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
                            <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="65px" height="80px"
                                alt="logo posgrado">
                        </div>
                    </th>
                </tr>
            </thead>
        </table>
        <div style="margin-top: 0.5rem; text-align: center;">
            <span style="text-align: center; font-weight: 700; font-size: 0.9rem">
                ESTADO DE CUENTA
            </span>
        </div>
    </header>
    <div id="footer">
        <div style="margin-top: 1.9rem; text-align: right;">
            <span style="text-align: center; font-weight: 400; font-size: 0.7rem">
                Estado de Cuenta - {{ date('d/m/Y') }}
            </span>
        </div>
    </div>
    <table class="table"
        style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
        <tr>
            <td style="width: 50%;">
                <table class="table"
                    style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                    <tr>
                        <td style="width: 80px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                {{ $admitido->programa_proceso->programa_plan->programa->programa }}
                            </div>
                        </td>
                        <td style="width: 20px;">
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                :
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $admitido->programa_proceso->programa_plan->programa->subprograma }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="50%">
                <table class="table"
                    style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                    <tr>
                        <td align="right">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                FECHA
                            </div>
                        </td>
                        <td align="right" style="width: 20px;">
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                :
                            </div>
                        </td>
                        <td align="right" style="width: 80px;">
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ date('d/m/Y') }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @if ($admitido->programa_proceso->programa_plan->programa->mencion)
        <table class="table"
            style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
            <tr>
                <td>
                    <table class="table"
                        style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                        <tr>
                            <td style="width: 80px;">
                                <div style="font-weight: 700; font-size: 0.7rem;">
                                    MENCION
                                </div>
                            </td>
                            <td style="width: 20px;">
                                <div style="font-weight: 400; font-size: 0.7rem;">
                                    :
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 400; font-size: 0.7rem;">
                                    {{ $admitido->programa_proceso->programa_plan->programa->mencion }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endif
    <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
        <tr>
            <td widht="100%">
                <table class="table"
                    style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                    <tr>
                        <td style="width: 80px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                ALUMNO
                            </div>
                        </td>
                        <td style="width: 20px;">
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                :
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $admitido->persona->nombre_completo }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
        <tr>
            <td>
                <table class="table"
                    style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                    <tr>
                        <td style="width: 80px;">
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                CODIGO
                            </div>
                        </td>
                        <td style="width: 20px;">
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                :
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 400; font-size: 0.7rem;">
                                {{ $admitido->admitido_codigo }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="width:100%; padding-right: 0rem; padding-left: 0rem; margin-bottom: 0.5rem; margin-top: 0.5rem; border-style: solid; border-width: 0.25px; border-color: black; border-collapse: collapse;">
    </div>
    <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
        <tr>
            <td widht="100%">
                <table class="table"
                    style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0.5rem;">
                    <tr>
                        <td>
                            <div style="font-weight: 700; font-size: 1rem; text-transform: uppercase;">
                                Ultima matricula
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
        <tr>
            <td widht="100%">
                <table class="table"
                    style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
                    <tr>
                        <td style="border: 1px solid; padding: 1rem; width: 30%;">
                            <div style="font-weight: 700; font-size: 1.7rem;">
                                S/. {{ number_format($monto_total, 2, ',', ' ') }}
                            </div>
                            <div style="margin-top: 0.3rem"></div>
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Monto Total a Pagar
                            </div>
                        </td>
                        <td style="width: 5%;"></td>
                        <td style="border: 1px solid; padding: 1rem; width: 30%;">
                            <div style="font-weight: 700; font-size: 1.7rem; color: #a70000">
                                S/. {{ number_format($deuda, 2, ',', ' ') }}
                            </div>
                            <div style="margin-top: 0.3rem"></div>
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Deuda
                            </div>
                        </td>
                        <td style="width: 5%;"></td>
                        <td style="border: 1px solid; padding: 1rem; width: 30%;">
                            <div style="font-weight: 700; font-size: 1.7rem; color: #179a11">
                                S/. {{ number_format($monto_total_pagado, 2, ',', ' ') }}
                            </div>
                            <div style="margin-top: 0.3rem"></div>
                            <div style="font-weight: 700; font-size: 0.7rem;">
                                Monto Total Pagado
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="padding: 0.3rem"></div>
    @foreach ($matriculas as $item)
        <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem;">
            <tr>
                <td widht="100%">
                    <table class="table"
                        style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0.5rem;">
                        <tr>
                            <td>
                                <div style="font-weight: 700; font-size: 0.9rem; text-transform: uppercase;">
                                    Matricula N° {{ $loop->iteration }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        @php
            $mensualidades = App\Models\Mensualidad::join('pago', 'pago.id_pago', '=', 'mensualidad.id_pago')
                ->join('concepto_pago', 'concepto_pago.id_concepto_pago', '=', 'pago.id_concepto_pago')
                ->join('canal_pago', 'canal_pago.id_canal_pago', '=', 'pago.id_canal_pago')
                ->where('id_matricula', $item->id_matricula)
                ->where('pago.pago_verificacion', 2)
                ->get();
            $pago_matricula = $item->pago;
        @endphp
        <table class="table" style="width:100%; padding-right: 0rem; padding-left: 0rem; padding-bottom: 0rem; padding-top: 0rem; border-collapse: collapse;">
            <thead>
                <tr style="border: 1px solid black; padding: 10px; font-size: 0.6rem; background: {{ $color }}">
                    <th style="border: 1px solid black; padding: 10px; width: 5%;">
                        #
                    </th>
                    <th style="border: 1px solid black; padding: 10px; width: 35%;">
                        CONCEPTO
                    </th>
                    <th style="border: 1px solid black; padding: 10px; width: 15%;">
                        OPERACIÓN
                    </th>
                    <th style="border: 1px solid black; padding: 10px; width: 15%;">
                        MONTO
                    </th>
                    <th style="border: 1px solid black; padding: 10px; width: 15%;">
                        FECHA
                    </th>
                    <th style="border: 1px solid black; padding: 10px; width: 15%;">
                        CANAL
                    </th>
                </tr>
            </thead>
            <tbody style="border: 1px solid black;">
                @foreach ($mensualidades as $mensualidad)
                    <tr style="padding: 7px; font-size: 0.6rem">
                        <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                            align="center">
                            {{ $loop->iteration }}
                        </td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;">
                            Concepto de {{ $mensualidad->concepto_pago }} - {{ $loop->iteration }}
                        </td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                            align="center">
                            {{ $mensualidad->pago_operacion }}
                        </td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                            align="center">
                            S/. {{ number_format($mensualidad->pago_monto, 2, ',', ' ') }}
                        </td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                            align="center">
                            {{ date('d/m/Y', strtotime($mensualidad->pago_fecha)) }}
                        </td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                            align="center">
                            {{ $mensualidad->canal_pago }}
                        </td>
                    </tr>
                @endforeach
                <tr style="padding: 7px; font-size: 0.6rem">
                    <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                        align="center">
                        {{ $mensualidades->count() + 1 }}
                    </td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;">
                        Concepto de {{ $pago_matricula->concepto_pago->concepto_pago }}
                    </td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                        align="center">
                        {{ $pago_matricula->pago_operacion }}
                    </td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                        align="center">
                        S/. {{ number_format($pago_matricula->pago_monto, 2, ',', ' ') }}
                    </td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                        align="center">
                        {{ date('d/m/Y', strtotime($pago_matricula->pago_fecha)) }}
                    </td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black; padding: 7px;"
                        align="center">
                        {{ $pago_matricula->canal_pago->canal_pago }}
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach
    <div style="position: absolute; right: 170px; top: 130px;">
        <img src="{{ public_path('assets_pdf/sello-posgrado.png') }}" width="110px" alt="sello posgrado">
    </div>
</body>

</html>
