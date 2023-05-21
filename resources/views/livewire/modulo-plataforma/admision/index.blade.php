<div class="col-md-12 mb-md-5 mb-xl-10">
    {{-- alerta para ver la fecha de resultados de admitidos --}}
    @if ($admision->admision_fecha_resultados <= today())
        @if ($admitido)
            {{-- alerta de admitido --}}
            <div class="alert bg-light-success border border-3 border-success d-flex align-items-center p-5 mb-5">
                <span class="svg-icon svg-icon-2hx svg-icon-success me-4">
                    <i class="las la-check-circle fs-2 text-success"></i>
                </span>
                <div class="d-flex flex-column">
                    <span class="fw-bold fs-4">
                        Fue admitido en el Proceso de {{ ucwords(strtolower($inscripcion_admision->admision->admision)) }}
                    </span>
                </div>
            </div>
            {{-- alerta de observaciones de la evaluacion --}}
            <div class="alert bg-body border border-3 border-primary d-flex align-items-center p-5 mb-5">
                <div class="d-flex flex-column">
                    <span class="fw-bold fs-3">
                        Observaciones de la evaluación
                    </span>
                    <span class="mt-2 fs-5">
                        {{ $evaluacion->evaluacion_observacion ? '- ' . $evaluacion->evaluacion_observacion : '- No cuenta con observaciones en su evaluación.' }}
                    </span>
                </div>
            </div>
        @else
            @if ($evaluacion)
                @if ($evaluacion->evaluacion_estado == 2)
                    {{-- alerta de fecha de resultados de admitidos --}}
                    <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                            <i class="las la-info-circle fs-1 text-primary"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-4">
                                Los resultados de admitidos se presentará el {{ $admision_fecha_admitidos }}.
                            </span>
                        </div>
                    </div>
                @else
                    {{-- alerta de no admitido --}}
                    <div class="alert bg-light-danger border border-3 border-danger d-flex align-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                            <i class="las la-exclamation-triangle fs-2 text-danger"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-4">
                                No fuiste admitido en el Proceso de {{ ucwords(strtolower($inscripcion_admision->admision->admision)) }}
                            </span>
                        </div>
                    </div>
                    {{-- alerta de observaciones de la evaluacion --}}
                    <div class="alert bg-body border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-3">
                                Observaciones de la evaluación
                            </span>
                            <span class="mt-2 fs-5">
                                {{ $evaluacion->evaluacion_observacion ? '- ' . $evaluacion->evaluacion_observacion : '- No cuenta con observaciones en su evaluación.' }}
                            </span>
                        </div>
                    </div>
                @endif
            @else
                {{-- alerta de fecha de resultados de admitidos --}}
                <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                    <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                        <i class="las la-info-circle fs-1 text-primary"></i>
                    </span>
                    <div class="d-flex flex-column">
                        <span class="fw-bold fs-4">
                            Los resultados de admitidos se presentará el {{ $admision_fecha_admitidos }}.
                        </span>
                    </div>
                </div>
            @endif
        @endif
    @else
        {{-- alerta de fecha de resultados de admitidos --}}
        <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
            <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                <i class="las la-info-circle fs-1 text-primary"></i>
            </span>
            <div class="d-flex flex-column">
                <span class="fw-bold fs-4">
                    Los resultados de admitidos se presentará el {{ $admision_fecha_admitidos }}.
                </span>
            </div>
        </div>
    @endif
    {{-- card de estado de inscripcion puntajes --}}
    <div class="row g-5 mb-5">
        {{-- <div class="col-sm-12 col-md-6 col-lg-6 @if ($inscripcion_ultima->inscripcion_tipo_programa == 1) col-xl-6 @elseif ($inscripcion_ultima->inscripcion_tipo_programa == 2) col-xl-4 @endif">
            <div class="card card-body shadow-sm">
                <span class="fs-1 text-gray-800 text-center py-2" style="font-weight: 700">
                    Evaluación de Expedientes
                </span>
                @if ($inscripcion_admision->admision->admision_fecha_resultados <= today())
                    @if ($evaluacion)
                    <span class="fs-3 text-gray-800 text-center py-2">
                        Puntaje: <span class="fw-bold fs-2">{{ number_format($evaluacion->puntaje_expediente) }}</span> pts.
                    </span>
                    @else
                    <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                        Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                    </span>
                    @endif
                @else
                    <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                        Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                    </span>
                @endif
            </div>
        </div>
        @if ($inscripcion_ultima->inscripcion_tipo_programa == 2)
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                <div class="card card-body shadow-sm">
                    <span class="fs-1 text-gray-800 text-center py-2" style="font-weight: 700">
                        Evaluación de Investigación
                    </span>
                    @if ($inscripcion_admision->admision->admision_fecha_resultados <= today())
                        @if ($evaluacion)
                            <span class="fs-3 text-gray-800 text-center py-2">
                                Puntaje: <span class="fw-bold fs-2">{{ number_format($evaluacion->puntaje_investigacion) }}</span> pts.
                            </span>
                        @else
                            <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                                Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                            </span>
                        @endif
                    @else
                        <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                            Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
        <div class="col-sm-12 col-md-6 col-lg-6 @if ($inscripcion_ultima->inscripcion_tipo_programa == 1) col-xl-6 @elseif ($inscripcion_ultima->inscripcion_tipo_programa == 2) col-xl-4 @endif">
            <div class="card card-body shadow-sm">
                <span class="fs-1 text-gray-800 text-center py-2" style="font-weight: 700">
                    Evaluación de Entrevista
                </span>
                @if ($inscripcion_admision->admision->admision_fecha_resultados <= today())
                    @if ($evaluacion)
                    <span class="fs-3 text-gray-800 text-center py-2">
                        Puntaje: <span class="fw-bold fs-2">{{ number_format($evaluacion->puntaje_entrevista) }}</span> pts.
                    </span>
                    @else
                    <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                        Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                    </span>
                    @endif
                @else
                    <span class="fs-3 text-center py-2 d-flex align-items-center justify-content-center text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Los resultados se presentará el {{ $admision_fecha_admitidos }}">
                        Sin puntaje <i class="las la-info-circle fs-3 text-danger ms-2"></i>
                    </span>
                @endif
            </div>
        </div> --}}
    </div>
    {{-- card de ficha de inscripcion, prospecto de admision y expedientes --}}
    <div class="row g-5 mb-5">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card card-body shadow-sm">
                <div class="bg-light-info px-10 py-5 rounded-4 mx-auto mb-5">
                    <i class="bi bi-file-pdf text-info" style="font-size: 4rem;"></i>
                </div>
                <h2 class="card-title mb-5 text-center">
                    Ficha de Inscripción
                </h2>
                <a target="_blank" href="{{ asset($inscripcion_ultima->inscripcion_ficha_url) }}" class="btn btn-info">
                    Descargar
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card card-body shadow-sm">
                <div class="bg-light-info px-10 py-5 rounded-4 mx-auto mb-5">
                    <i class="bi bi-file-pdf text-info" style="font-size: 4rem;"></i>
                </div>
                <h2 class="card-title mb-5 text-center">
                    Prospecto de Admisión
                </h2>
                <a target="_blank" href="{{ asset('assets_pdf/prospecto-admision-posgrado.pdf') }}" class="btn btn-info">
                    Descargar
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card card-body shadow-sm">
                <div class="bg-light-info px-10 py-5 rounded-4 mx-auto mb-5">
                    <i class="bi bi-file-earmark-text text-info" style="font-size: 4rem;"></i>
                </div>
                <h2 class="card-title mb-5 text-center">
                    Expedientes
                </h2>
                <a href="{{ route('plataforma.expediente') }}" type="button" class="btn btn-info">
                    Ver detalle
                </a>
            </div>
        </div>
    </div>
    <!-- Modal Encuestas -->
    <div wire:init="open_modal_encuesta" wire:ignore.self class="modal fade" id="modal_encuesta" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modal_encuesta" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-5">
                    <div class="text-center p-5 rounded-4 bg-light-info">
                        <span class="svg-icon svg-icon-info svg-icon-5hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM11.7 17.7L16 14C16.4 13.6 16.4 12.9 16 12.5C15.6 12.1 15.4 12.6 15 13L11 16L9 15C8.6 14.6 8.4 14.1 8 14.5C7.6 14.9 8.1 15.6 8.5 16L10.3 17.7C10.5 17.9 10.8 18 11 18C11.2 18 11.5 17.9 11.7 17.7Z" fill="currentColor"/>
                                <path d="M10.4343 15.4343L9.25 14.25C8.83579 13.8358 8.16421 13.8358 7.75 14.25C7.33579 14.6642 7.33579 15.3358 7.75 15.75L10.2929 18.2929C10.6834 18.6834 11.3166 18.6834 11.7071 18.2929L16.25 13.75C16.6642 13.3358 16.6642 12.6642 16.25 12.25C15.8358 11.8358 15.1642 11.8358 14.75 12.25L11.5657 15.4343C11.2533 15.7467 10.7467 15.7467 10.4343 15.4343Z" fill="currentColor"/>
                                <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>

                    <form autocomplete="off" class="mt-5">
                        <h3 class="mb-3 text-center fw-bold">
                            Encuesta
                        </h4>
                        <div class="mt-5 text-center">
                            <span class="fs-5 fw-bold">
                                ¿Cómo se enteró de este proceso de admisión? <i class="las la-info-circle fs-3 text-primary ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Puede seleccionar mas de una opción"></i>
                            </span>
                        </div>
                        <div class="mt-5 mb-5 mx-5 px-5">
                            @foreach ($encuestas as $item)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="{{ $item->id_encuesta }}" id="{{ $item->id_encuesta }}" wire:model="encuesta" wire:key="{{ $item->id_encuesta }}">
                                <label class="fs-5" for="{{ $item->id_encuesta }}" wire:key="{{ $item->id_encuesta }}">
                                    {{ $item->encuesta }}
                                </label>
                            </div>
                            @endforeach
                            @if ($mostra_otros == true)
                            <div class="mt-3">
                                <div>
                                    <textarea class="form-control" placeholder="Especifique otro" wire:model="encuesta_otro" data-kt-autosize="true"></textarea>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="hstack gap-2 justify-content-center">
                            <button type="button" wire:click="guardar_encuesta" class="btn btn-info">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
