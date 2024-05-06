<div>

    <div class="py-10 border-bottom border-gray-500 d-flex flex-column gap-5">
        <span class="text-success" style="font-weight: 700; font-size: 3rem">
            Gracias por registrarte
        </span>
        <span class="text-muted fs-2">
            Los datos rellenados han sido registrados con éxito.
        </span>
    </div>

    <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center gap-2 mt-5 p-5">
        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
        <div class="d-flex flex-column">
            <h4 class="mb-0">
                Sus credenciales de acceso han sido enviados a su correo electrónico.
            </h4>
        </div>
    </div>

    {{-- //Mostrar los datos del estudiante como du Numero de documento, nombre completo, programa, año de proceso, modalidad, codigo de estudiante y suscredenciales al ultimo --}}
    <div class="my-10">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <td colspan="3">
                                <h2 class="text-start mb-4 fw-bold">
                                    <strong>
                                        DATOS PERSONALES
                                    </strong>
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-2">
                                <strong>
                                    Nombre completo
                                </strong>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $trabajador->trabajador_nombre_completo }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="separator border-dark my-15 separator-content separator-content mb-5 mt-7">
                                    <i class="ki-outline ki-security-user fs-1 text-dark"></i>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <h2 class="text-start mb-4 fw-bold">
                                    <strong>
                                        CREDENCIALES DE ACCESO
                                    </strong>
                                </h2>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-2">
                                <strong>
                                    Usuario
                                </strong>
                            </td>
                            <td>
                                :
                            </td>
                            <td class="col-md-11">
                                {{ $usuario->usuario_correo }}
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-1">
                                <strong>
                                    Contraseña
                                </strong>
                            </td>
                            <td>
                                :
                            </td>
                            <td class="col-md-11">
                                {{ $trabajador->trabajador_numero_documento }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('posgrado.registro.docente') }}" class="btn btn-secondary">
            <i class="fa-sharp fa-solid fa-arrow-left"></i>
            Volver al registro
        </a>
        <button wire:click="login()" class="btn btn-primary hover-scale">
            Ir al login
            <i class="fa-sharp fa-solid fa-arrow-right ms-2"></i>
        </button>
    </div>
</div>
