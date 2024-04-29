<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Pago Observado
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
            Nos complace informarle que su pago realizado por el concepto de <strong>{{ $concepto_pago }}</strong> al proceso de admisión ha sido
            observado.
        </p>
        <p>
            <strong>Observación:</strong>
            "<span style="color: #8e0000;">
                {{ $observacion }}
            </span>"
        </p>
        <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
            Por favor ingresar a su plataforma del postulante con las credenciales de acceso que se encuentra en su
            ficha de inscripción, para que pueda actualizar su información de su pago. Si tienes alguna pregunta o
            necesitas más
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
