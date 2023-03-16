<div class="col-md-12 mb-md-5 mb-xl-10">
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
                <a href="" type="button" class="btn btn-info">
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
