<div>
    <div class="row">
        <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card px-4 py-3" style="height: 140px">
                <div class="pt-2 pb-2">
                    <span class="card-title mb-1 fs-3 fw-bold" style="color: rgb(71, 71, 71)">Ingreso Total</span>
                </div>
                <div class="pt-2 pb-2">
                    <span class="fs-2 fw-semibold" style="color: rgb(63, 63, 63)">S/. {{ number_format($ingreso_total, 2, ',', ' ') }} </span>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card px-4 py-3" style="height: 140px">
                <div class="pt-2 pb-2">
                    <span class="card-title mb-1 fs-3 fw-bold" style="color: rgb(71, 71, 71)">Ingreso por Inscripciones</span>
                </div>
                <div class="pt-2 pb-2">
                    <span class="fs-2 fw-semibold" style="color: rgb(63, 63, 63)">S/. {{ number_format($ingreso_inscripcion, 2, ',', ' ') }}</span>
                </div>
            </div>
        </div>
        <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="card px-4 py-3" style="height: 140px">
                <div class="pt-2 pb-2">
                    <span class="card-title mb-1 fs-3 fw-bold" style="color: rgb(71, 71, 71)">Ingreso por Constancia</span>
                </div>
                <div class="pt-2 pb-2">
                    <span class="fs-2 fw-semibold" style="color: rgb(63, 63, 63)">S/. {{ number_format($ingreso_constancia, 2, ',', ' ') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-6">
        <div class="card-header">
            <h5 class="card-title mb-0 ms-2 text-uppercase">Reporte de Inscritos por Programa en Mastría</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-rounded border gy-4 gs-4 mb-0">
                    <thead class="" style="background-color: #d9ffe3">
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
                        <tr class="table-light" align="center">
                            <td colspan="2" class="text-end fw-bold fs-6">TOTAL</td>
                            <td class="fw-bold fs-5">{{ $programas_maestria->sum('cantidad_mencion') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card mt-6">
        <div class="card-header">
            <h5 class="card-title mb-0 ms-2 text-uppercase">Reporte de Inscritos por Programa de Doctorado</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-rounded border gy-4 gs-4 mb-0">
                    <thead class="" style="background-color: #dcedff">
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
                        <tr class="table-light" align="center">
                            <td colspan="2" class="text-end fw-bold fs-6">TOTAL</td>
                            <td class="fw-bold fs-5">{{ $programas_doctorado->sum('cantidad_mencion') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
