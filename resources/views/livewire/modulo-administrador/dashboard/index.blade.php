<div>

    <!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">
	<!--begin::Toolbar-->
	<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
		<!--begin::Toolbar container-->
		<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
			<!--begin::Page title-->
			<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
				<!--begin::Title-->
				<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
					Dashboard
				</h1>
				<!--end::Title-->
				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">
                        <a href="{{ route('administrador.dashboard') }}" class="text-muted text-hover-primary">
                            Dashboard
                        </a>
                    </li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item">
						<span class="bullet bg-gray-400 w-5px h-2px"></span>
					</li>
					<!--end::Item-->
					<!--begin::Item-->
					<li class="breadcrumb-item text-muted">Dashboard</li>
					<!--end::Item-->
				</ul>
				<!--end::Breadcrumb-->
			</div>
			<!--end::Page title-->
			<!--begin::Actions-->
			<div class="d-flex align-items-center gap-2 gap-lg-3">
				<!--begin::Primary button-->
				<a href="#" class="btn btn-primary btn-sm">Exportar PDF</a>
				<!--end::Primary button-->
			</div>
			<!--end::Actions-->
		</div>
		<!--end::Toolbar container-->
	</div>
	<!--end::Toolbar-->
	
	<!--begin::Content-->
	<div id="kt_app_content" class="app-content flex-column-fluid">
		<!--begin::Content container-->
		<div id="kt_app_content_container" class="app-container container-fluid pt-5">
			<!--begin::Row-->
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
                                <span class="card-title mb-1 fs-1 fw-bold">Ingreso de Inscripciones</span>
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
                                <span class="card-title mb-1 fs-1 fw-bold"">Ingreso de Constancia</span>
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
                                            {{ ucwords(strtolower($item->programa))  }} en {{ ucwords(strtolower($item->subprograma)) }}
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
        
            {{-- <div class="card mt-10 shadow-sm">
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
            </div> --}}
			<!--end::Row-->
		</div>
		<!--end::Content container-->
	</div>
	<!--end::Content-->
</div>
<!--end::Content wrapper-->

</div>
