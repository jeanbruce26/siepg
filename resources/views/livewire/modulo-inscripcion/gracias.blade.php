<div>
    {{-- header --}}
    <div class="py-10 border-bottom border-gray-500 d-flex flex-column gap-5">
        <span class="text-success" style="font-weight: 700; font-size: 3rem">
            Gracias por registrarte
        </span>
        <span class="text-muted fs-2">
            Los datos rellenados han sido registrados con éxito.
        </span>
    </div>
    {{-- alerta --}}
    <div class="alert bg-light-primary border border-primary d-flex align-items-center gap-2 mt-5 p-5">
        <span class="svg-icon svg-icon-2hx svg-icon-primary me-3">
            <i class="fa-sharp fa-solid fa-circle-info fs-1 text-primary"></i>
        </span>
        <div class="d-flex flex-column">
            <h4 class="mb-0">
                Su ficha de inscripción ha sido enviado a su correo electrónico y se encuentra disponible para descargarlo a continuación.
            </h4>
        </div>
    </div>
    {{-- boton --}}
    <div class="d-flex justify-content-between">
        <a href="{{ route('inscripcion.auth') }}" class="btn btn-secondary">
            <i class="fa-sharp fa-solid fa-arrow-left"></i>
            Volver al inicio
        </a>
        <button wire:click="descargar_pdf({{ $id_inscripcion }})" class="btn btn-primary hover-scale">
            <i class="fa-sharp fa-solid fa-download"></i>
            Descargar ficha de inscripción
        </button>
    </div>
    {{-- informacion --}}
    {{-- <div class="my-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <td class="col-md-3">
                                Código de Inscripción
                            </td>
                            <td>
                                :
                            </td>
                            <td class="col-md-9">
                                {{ $inscripcion->inscripcion_codigo }}
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-3">
                                Postulante
                            </td>
                            <td>
                                :
                            </td>
                            <td class="col-md-9">
                                {{ $inscripcion->persona->nombre_completo }}
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-3">
                                Documento de Identidad
                            </td>
                            <td>
                                :
                            </td>
                            <td class="col-md-9">
                                {{ $inscripcion->persona->num_doc }}
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-3">
                                Programa
                            </td>
                            <td>
                                :
                            </td>
                            <td class="col-md-9">
                                @if ($inscripcion->mencion->mencion == null)
                                    {{ $inscripcion->mencion->subprograma->programa->descripcion_programa }} EN {{ $inscripcion->mencion->subprograma->subprograma }}
                                @else
                                    {{ $inscripcion->mencion->subprograma->programa->descripcion_programa }} EN {{ $inscripcion->mencion->subprograma->subprograma }} CON MENCION EN {{ $inscripcion->mencion->mencion }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="col-md-3">
                                Correo Electrónico
                            </td>
                            <td>
                                :
                            </td>
                            <td class="col-md-9">
                                {{ $inscripcion->persona->email }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
    {{-- pdf --}}
    <div class="my-5">
        <embed src="{{ asset($inscripcion->inscripcion) }}" class="rounded" type="application/pdf" width="100%" height="700px" />
    </div>
</div>
