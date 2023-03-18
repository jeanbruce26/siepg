<div class="col-md-12 mb-md-5 mb-xl-10">
    {{-- alerta para ver la fecha de resultados de admitidos --}}
    @if ($admision->fecha_admitidos <= today())
        @if ($admitido)
        {{-- alerta de admitido --}}
        <div class="alert bg-light-success border border-success d-flex alig-items-center p-5 mb-5">
            <span class="svg-icon svg-icon-2hx svg-icon-success me-4">
                <i class="las la-check-circle fs-2 text-success"></i>
            </span>
            <div class="d-flex flex-column">
                <span class="fw-bold">
                    Fue admitido en la {{ ucwords(strtolower($admision->admision)) }}
                </span>
            </div>
        </div>
        @else
            @if ($evaluacion)
            {{-- alerta de no admitido --}}
            <div class="alert bg-light-danger border border-danger d-flex alig-items-center p-5 mb-5">
                <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                    <i class="las la-exclamation-triangle fs-2 text-danger"></i>
                </span>
                <div class="d-flex flex-column">
                    <span class="fw-bold">
                        No fuiste admitido en la {{ ucwords(strtolower($admision->admision)) }}
                    </span>
                </div>
            </div>
            @else
            {{-- alerta de fecha de resultados de admitidos --}}
            <div class="alert bg-light-primary border border-primary d-flex alig-items-center p-5 mb-5">
                <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                    <i class="las la-info-circle fs-2 text-primary"></i>
                </span>
                <div class="d-flex flex-column">
                    <span class="fw-bold">
                        Los resultados de admitidos se presentará el {{ $admision_fecha_admitidos }}.
                    </span>
                </div>
            </div>
            @endif
        @endif
    @else
    {{-- alerta de fecha de resultados de admitidos --}}
    <div class="alert bg-light-primary border border-primary d-flex alig-items-center p-5 mb-5">
        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
            <i class="las la-info-circle fs-2 text-primary"></i>
        </span>
        <div class="d-flex flex-column">
            <span class="fw-bold">
                Los resultados de admitidos se presentará el {{ $admision_fecha_admitidos }}.
            </span>
        </div>
    </div>
    @endif
    {{-- card de estado de inscripcion puntajes --}}
    <div class="row g-5 mb-5">
        <div class="col-sm-12 col-md-6 col-lg-6 @if ($inscripcion_ultima->tipo_programa == 1) col-xl-6 @elseif ($inscripcion_ultima->tipo_programa == 2) col-xl-4 @endif">
            <div class="card card-body shadow-sm">
                <span class="fs-1 text-gray-800 text-center py-2" style="font-weight: 700">
                    Evaluación de Expedientes
                </span>
                @if ($inscripcion_admision->fecha_admitidos <= today())
                    @if ($evaluacion)
                    <span class="fs-3 text-gray-800 text-center py-2">
                        Puntaje: <span class="fw-bold fs-2">{{ number_format($evaluacion->p_expediente) }}</span> pts.
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
        @if ($inscripcion_ultima->tipo_programa == 2)
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card card-body shadow-sm">
                <span class="fs-1 text-gray-800 text-center py-2" style="font-weight: 700">
                    Evaluación de Investigación
                </span>
                @if ($inscripcion_admision->fecha_admitidos <= today())
                    @if ($evaluacion)
                    <span class="fs-3 text-gray-800 text-center py-2">
                        Puntaje: <span class="fw-bold fs-2">{{ number_format($evaluacion->p_investigacion) }}</span> pts.
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
        <div class="col-sm-12 col-md-6 col-lg-6 @if ($inscripcion_ultima->tipo_programa == 1) col-xl-6 @elseif ($inscripcion_ultima->tipo_programa == 2) col-xl-4 @endif">
            <div class="card card-body shadow-sm">
                <span class="fs-1 text-gray-800 text-center py-2" style="font-weight: 700">
                    Evaluación de Entrevista
                </span>
                @if ($inscripcion_admision->fecha_admitidos <= today())
                    @if ($evaluacion)
                    <span class="fs-3 text-gray-800 text-center py-2">
                        Puntaje: <span class="fw-bold fs-2">{{ number_format($evaluacion->p_entrevista) }}</span> pts.
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
    </div>
    {{-- card de ficha de inscripcion, prospecto de admision y expedientes --}}
    <div class="row g-5 mb-5">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card card-body shadow-sm">
                <div class="bg-light-info px-10 py-5 rounded-4 mx-auto mb-5">
                    <i class="bi bi-file-pdf text-info" style="font-size: 4rem;"></i>
                </div>
                <h4 class="card-title mb-5 text-center">
                    Ficha de Inscripción
                </h4>
                <a target="_blank" href="{{ asset($inscripcion_ultima->inscripcion) }}" class="btn btn-info">
                    Descargar
                </a>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
            <div class="card card-body shadow-sm">
                <div class="bg-light-info px-10 py-5 rounded-4 mx-auto mb-5">
                    <i class="bi bi-file-pdf text-info" style="font-size: 4rem;"></i>
                </div>
                <h4 class="card-title mb-5 text-center">
                    Prospecto de Admisión
                </h4>
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
                <h4 class="card-title mb-5 text-center">
                    Expedientes
                </h4>
                <a href="{{ route('plataforma.expediente') }}" type="button" class="btn btn-info">
                    Ver detalle
                </a>
            </div>
        </div>
    </div>
    {{-- <div class="card shadow-sm mb-5">
        <div class="card-body">
            asdasdas
        </div>
    </div> --}}
</div>
