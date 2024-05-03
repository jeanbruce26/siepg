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
        <i class="ki-duotone ki-information-5 fs-2qx me-4 text-primary">
            <i class="path1"></i>
            <i class="path2"></i>
            <i class="path3"></i>
        </i>
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
                                {{ $personaModel->apellido_paterno }} {{ $personaModel->apellido_materno }} {{ $personaModel->nombres }}
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-2">
                                <strong>
                                    Programa
                                </strong>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $admitidoModel->programa_proceso->programa_plan->programa->programa }} EN
                                {{ $admitidoModel->programa_proceso->programa_plan->programa->subprograma }}
                                @if ($admitidoModel->programa_proceso->programa_plan->programa->mencion)
                                    CON MENCIÓN EN {{ $admitidoModel->programa_proceso->programa_plan->programa->mencion }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-2">
                                <strong>
                                    Año de proceso
                                </strong>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $admitidoModel->programa_proceso->admision->admision_año }}
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-2">
                                <strong>
                                    Modalidad
                                </strong>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $admitidoModel->programa_proceso->programa_plan->programa->modalidad->modalidad }}
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-2">
                                <strong>
                                    Código de estudiante
                                </strong>
                            </td>
                            <td>
                                :
                            </td>
                            <td>
                                {{ $admitidoModel->admitido_codigo }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div class="separator border-dark my-15 separator-content separator-content mb-5 mt-7">
                                    <i class="ki-duotone ki-security-user fs-1 text-dark">
                                        <i class="path1"></i>
                                        <i class="path2"></i>
                                        <i class="path3"></i>
                                    </i>
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
                                {{ $usuarioModel->usuario_estudiante }}
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
                                {{ $personaModel->numero_documento }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('posgrado.registro') }}" class="btn btn-secondary">
            <i class="fa-sharp fa-solid fa-arrow-left"></i>
            Volver al registro
        </a>
        <button wire:click="plataforma()" class="btn btn-primary hover-scale">
            Ir a plataforma de inicio
            <i class="fa-sharp fa-solid fa-arrow-right ms-2"></i>
        </button>
    </div>
</div>
