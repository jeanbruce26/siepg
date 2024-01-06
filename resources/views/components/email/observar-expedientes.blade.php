<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Expedientes Observados
    </title>
</head>

<body>
    <main>
        <h1 style="color: #212121; margin-bottom: 0.5rem; font-weight: 700; text-align: center;">
            Escuela de Posgrado
        </h1>
        <p style="margin-bottom: 1rem;">
            Estimado/a {{ $nombre }},
        </p>
        <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
            Nos complace informarle que su expediente ha sido observado:
        </p>
        <div style="margin-bottom: 0.5rem">
            <table>
                <thead style="border-bottom: 1px solid #414141; padding-bottom: 0.5rem;">
                    <tr>
                        <th style="text-align: left; padding-right: 1rem; padding-bottom: 0.5rem;">
                            <strong>
                                Nro.
                            </strong>
                        </th>
                        <th style="text-align: left; padding-right: 1rem; padding-bottom: 0.5rem;">
                            <strong>
                                Expediente
                            </strong>
                        </th>
                        <th style="text-align: left; padding-right: 1rem; padding-bottom: 0.5rem;">
                            <strong>
                                Estado
                            </strong>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expedientes as $item)
                        <tr>
                            <td style="padding-right: 1rem; padding-bottom: 0.5rem;">
                                {{ $loop->iteration }}
                            </td>
                            <td style="padding-right: 1rem; padding-bottom: 0.5rem;">
                                {{ $item->expediente_admision->expediente->expediente }}
                            </td>
                            <td style="padding-right: 1rem; padding-bottom: 0.5rem;">
                                @if ($item->expediente_inscripcion_verificacion == 2)
                                    <span style="color: #f44336;">
                                        Observado
                                    </span>
                                @elseif ($item->expediente_inscripcion_verificacion == 1)
                                    <span style="color: #4caf50;">
                                        Aprobado
                                    </span>
                                @else
                                    <span style="color: #ff9800;">
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
            Por favor ingresar a su plataforma del postulante con las credenciales de acceso que se encuentra en su
            ficha de inscripción, para que pueda actualizar su expediente. Si tienes alguna pregunta o necesitas más
            información, por favor no dudes en contactarnos. Estamos aquí para ayudarte.
        </p>
        <p style="margin-bottom: 1rem;">
            Atentamente,
        </p>
        <p>
            Escuela de Posgrado
        </p>
    </main>
</body>

</html>
