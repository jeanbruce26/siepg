<div>

    <div class="row gy-5 g-xl-10">
        <!--begin::Col-->
        <div class="col-sm-6 col-xl-4 mb-xl-10">
            <!--begin::Card widget 2-->
            <div class="card shadow-sm">
                <!--begin::Body-->
                <div class="card-body d-flex align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <span class="card-title mb-1 fs-1 fw-bold"">Ingreso Total</span>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-5">
                        <!--begin::Number-->
                        <span class="fs-1 fw-bold">S/. {{ number_format($ingreso_total, 2, ',', ' ') }}</span>
                        <!--end::Number-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card widget 2-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-sm-6 col-xl-4 mb-xl-10">
            <!--begin::Card widget 2-->
            <div class="card shadow-sm">
                <!--begin::Body-->
                <div class="card-body d-flex align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <span class="card-title mb-1 fs-1 fw-bold">Ingreso por Inscripciones</span>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-5">
                        <!--begin::Number-->
                        <span class="fs-1 fw-bold">S/. {{ number_format($ingreso_inscripcion, 2, ',', ' ') }}</span>
                        <!--end::Number-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card widget 2-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-sm-6 col-xl-4 mb-xl-10">
            <!--begin::Card widget 2-->
            <div class="card shadow-sm">
                <!--begin::Body-->
                <div class="card-body d-flex align-items-start flex-column">
                    <!--begin::Icon-->
                    <div class="m-0">
                        <span class="card-title mb-1 fs-1 fw-bold"">Ingreso por Constancia</span>
                    </div>
                    <!--end::Icon-->
                    <!--begin::Section-->
                    <div class="d-flex flex-column mt-5">
                        <!--begin::Number-->
                        <span class="fs-1 fw-bold"">S/. {{ number_format($ingreso_constancia, 2, ',', ' ') }}</span>
                        <!--end::Number-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Card widget 2-->
        </div>
        <!--end::Col-->
    </div>

    <div class="card card-maestria shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0 ms-2 text-uppercase">Reporte de Inscritos por Programa en Mastría</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-rounded border gy-4 gs-4 mb-0">
                    <thead class="bg-light-success">
                        <tr align="center" class="fw-bold">
                            <th scope="col" class="col-md-1">NRO</th>
                            <th scope="col" class="col-md-9">PROGRAMA</th>
                            <th scope="col" class="col-md-2">CANTIDAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programas_maestria as $item)
                        <tr>
                            <td align="center" class="fw-bold fs-5">{{ $loop->iteration }}</td>
                            <td style="white-space: initial" class="fs-5 text-uppercase">
                                @if ($item->mencion === null)
                                    {{ ucwords(strtolower($item->descripcion_programa))  }} en {{ ucwords(strtolower($item->subprograma)) }}
                                @else
                                    Mención en {{ ucwords(strtolower($item->mencion)) }}
                                @endif
                            </td>
                            <td align="center" class="fs-5">{{ $item->cantidad_mencion }}</td>
                        </tr>
                        @endforeach
                        @if ($programas_maestria->count() === 0)
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No hay inscritos en los programas de maestría
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="bg-light-secondary" align="center">
                            <td colspan="2" class="text-end fw-bold fs-6">TOTAL</td>
                            <td class="fw-bold fs-5">{{ $programas_maestria->sum('cantidad_mencion') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-10 shadow-sm">
        <div class="card-header">
            <h5 class="card-title mb-0 ms-2 text-uppercase">Reporte de Inscritos por Programa de Doctorado</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-rounded border gy-4 gs-4 mb-0">
                    <thead class="bg-light-primary">
                        <tr align="center" class="fw-bold">
                            <th scope="col" class="col-md-1">NRO</th>
                            <th scope="col" class="col-md-9">PROGRAMA</th>
                            <th scope="col" class="col-md-2">CANTIDAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($programas_doctorado as $item)
                        <tr>
                            <td align="center" class="fw-bold fs-5">{{ $loop->iteration }}</td>
                            <td style="white-space: initial" class="fs-5 text-uppercase">
                                @if ($item->mencion === null)
                                    {{ ucwords(strtolower($item->descripcion_programa))  }} en {{ ucwords(strtolower($item->subprograma)) }}
                                @else
                                    Mención en {{ ucwords(strtolower($item->mencion)) }}
                                @endif
                            </td>
                            <td align="center" class="fs-5">{{ $item->cantidad_mencion }}</td>
                        </tr>
                        @endforeach
                        @if ($programas_doctorado->count() === 0)
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No hay inscritos en los programas de doctorado
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="bg-light-secondary" align="center">
                            <td colspan="2" class="text-end fw-bold fs-6">TOTAL</td>
                            <td class="fw-bold fs-5">{{ $programas_doctorado->sum('cantidad_mencion') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>