<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Plantilla Correo</title>
</head>
<body style="background-color: #e7f2ff; padding-top: 0.5rem; padding-bottom: 0.5rem; padding-left: 1.5rem; padding-right: 1.5rem; border-radius: 20px;">
    <main>
        <h1 style="color: #212121; margin-bottom: 0.5rem; font-weight: 700; text-align: center;">
            Escuela de Posgrado
        </h1>
        <p style="margin-bottom: 1rem;">
            Estimado/a {{ $nombres }},
        </p>
        <p style="margin-bottom: 1rem;">
            Agradecemos su registro en la plataforma de posgrado de la Escuela de Posgrado de la Universidad Nacional de Ucayali. Nos complace informarle que su cuenta ha sido creada exitosamente y ahora tiene acceso completo a la plataforma.
        </p>
        <p>
            Sus credenciales de acceso son las siguientes:
        </p>
        <p style="margin-bottom: 1rem;">
            <b>Usuario: </b> {{ $usuario }} <br>
            <b>Contraseña: </b> {{ $contraseña }} <br>
            <b>Link de acceso a la plataforma: </b><a href="http://posgrado.unu.edu.pe/plataforma/login">http://posgrado.unu.edu.pe/plataforma/login</a>
        </p>
        <p style="text-align: justify; text-justify: inter-word;">
            Con estas credenciales, podrá ingresar a la plataforma y matricularse en los cursos correspondientes a su programa de posgrado. Si necesita ayuda o tiene alguna consulta sobre el proceso de matrícula o el funcionamiento de la plataforma, no dude en ponerse en contacto con nuestro equipo de soporte técnico.
        </p>
        <p style="text-align: justify; text-justify: inter-word; margin-bottom: 1rem;">
            ¡Bienvenido(a) a la Escuela de Posgrado de la Universidad Nacional de Ucayali! Esperamos que su experiencia de aprendizaje sea enriquecedora y exitosa.
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
