<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        Credenciales Docentes
    </title>
</head>
<body style="background-color: #e7f2ff; color: #212121; padding-top: 0.5rem; padding-bottom: 0.5rem; padding-left: 1.5rem; padding-right: 1.5rem; border-radius: 20px;">
    <h1 style="color: #212121; margin-bottom: 0.5rem; font-weight: 700; text-align: center;">
        Escuela de Posgrado
    </h1>
    <p style="margin-bottom: 1rem;">
        Estimado/a <span style="font-weight: 700">{{ $nombres }}</span>,
    </p>
    @if ($modo == 1)
        <p style="margin-bottom: 1rem;">
            Usted ha sido registrado como docente en la plataforma de la Escuela de Posgrado de la Universidad Nacional de Ucayali. Nos complace informarle que su cuenta ha sido creada exitosamente y ahora tiene acceso completo a la plataforma administrativa para Docentes de la Escuela de Posgrado.
        </p>
    @else
        <p style="margin-bottom: 1rem;">
            Se ha actualizado su cuenta de docente en la plataforma de la Escuela de Posgrado de la Universidad Nacional de Ucayali. Nos complace informarle que su cuenta ha sido actualizada exitosamente y ahora tiene acceso completo a la plataforma administrativa para Docentes de la Escuela de Posgrado.
        </p>
    @endif
    <p style="margin-bottom: 1rem;">
        Sus credenciales de acceso son las siguientes:
    </p>
    <div style="margin-bottom: 1rem;">
        <span style="font-weight: 700;">
            Usuario: <span style="font-weight: 400;">{{ $usuario_correo }}</span>
        </span>
        <br>
        <span style="font-weight: 700;">
            Contraseña: <span style="font-weight: 400;">{{ $usuario_contrasenia }}</span>
        </span>
        <br>
        <span style="font-weight: 700;">
            Link de acceso a la plataforma: <a href="http://posgrado.unu.edu.pe/login" style="font-weight: 400;">http://posgrado.unu.edu.pe/login</a>
        </span>
    </div>
    <p style="margin-bottom: 1rem;">
        Con estas credenciales, podrá ingresar a la plataforma administrativa de la Escuela de Posgrado. Una vez dentro, podrá gestionar los cursos que se le han asignado, así como las calificaciones de los estudiantes. Si necesita ayuda o tiene alguna consulta no dude en contactarnos.
    </p>
    <p style="margin-bottom: 1rem;">
        ¡Bienvenido(a) a la Escuela de Posgrado de la Universidad Nacional de Ucayali! Esperamos que su experiencia en nuestra plataforma sea de su agrado.
    </p>
    <p style="margin-bottom: 1.5rem;">
        Atentamente,
    </p>
    <p style="text-decoration: underline; text-transform: uppercase; font-weight: 700; margin-bottom: 1rem;">
        Escuela de Posgrado
    </p>
</body>
</html>
