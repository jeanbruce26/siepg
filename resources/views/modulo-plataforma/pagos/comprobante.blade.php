<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f2f2f2;
        }

        .comprobante-container {
            background-color: #fff;
            padding: 30px;
            border: 1px solid #ccc;
            width: 600px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100px;
        }

        .header h1 {
            font-size: 24px;
            margin: 10px 0;
        }

        .content {
            font-size: 14px;
            line-height: 1.6;
        }

        .content p {
            margin: 5px 0;
        }

        .content .note {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer a {
            margin: 0 10px;
            text-decoration: none;
            color: #000;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="comprobante-container">
        <div class="header">
            <div>
                <img src="{{ public_path('assets_pdf/posgrado.png') }}" width="75px" height="85px" alt="logo posgrado">
                <div>
                    <div style="font-size: 20px; font-weight: bold; margin-top: 10px;">
                        UNIVERSIDAD NACIONAL DE UCAYALI
                    </div>
                    <div style="font-size: 16px; font-weight: bold; margin-top: 5px;">
                        ESCUELA DE POSGRADO
                    </div>
                </div>
            </div>
            <h1>Operación realizada con éxito</h1>
        </div>
        <div class="content">
            <p><strong>Comprobante:</strong> CPS-{{ $pago->pago_documento }}-{{ $pago->pago_operacion }}</p>
            <p><strong>Fecha:</strong> {{ convertirFechaHora($pago->created_at) }}</p>
            <p><strong>Importe Pagado:</strong> S/. {{ number_format($pago->monto, 2) }}</p>
            <p><strong>{{ $admitido ? 'Alumno' : 'Postulante' }}:</strong> {{ ucwords(strtolower($persona->nombre_completo)) }}</p>

            <hr style="margin: 20px 0; border: 1px solid #ccc;">

            <p class="note">
                Nota importante: Ud. debe imprimir y conservar este comprobante de pago. Se le
                solicitará en caso de reclamo.
            </p>
        </div>
        {{-- <div class="footer">
            <a href="#">Imprimir</a>
            <a href="#">Cerrar Sesión</a>
        </div> --}}
    </div>
</body>

</html>
