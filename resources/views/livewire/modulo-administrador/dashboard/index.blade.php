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
            <div class="row g-5">
                <!--begin::Col-->
                <div class="col-sm-6 col-xl-4 mb-xl-10">
                    <!--begin::Card widget 2-->
                    <div class="card hover-elevate-up shadow-sm parent-hover text-dark">
                        <!--begin::Body-->
                        <div class="card-body d-flex align-items-start flex-column">
                            <!--begin::Icon-->
                            <div class="m-0">
                                <span class="card-title mb-1 fs-1 fw-bold parent-hover-primary">Ingreso Total</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Section-->
                            <div class="d-flex mt-5">
                                <!--begin::Number-->
                                @php
                                    use function App\Helpers\money_format;
                                @endphp
                                <span class="fs-2 fw-bold">
                                    <div>S/. {{ number_format($ingreso_total, 2, ',', ' ') }}</div>
                                </span>
                                <!--end::Number-->
                                @if($ingreso_por_dia_total > 0)
                                    <!--begin::Cantidad de ingresos por día-->
                                    <span class="badge badge-light-success fs-base ms-6" data-bs-toggle="tooltip" data-bs-placement="right" title="Ingresos por día">                                
                                        <div class="d-flex align-items-center">
                                            <!--begin::Svg Icon-->
                                            <span class="svg-icon svg-icon-muted svg-icon-3 text-success"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"/>
                                                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <div class="fs-7 fw-bold me-1">S/. {{  number_format($ingreso_por_dia_total, 2, ',', ' ') }}</div>
                                        </div>
                                    </span>
                                    <!--end::Cantidad de ingresos por día-->
                                @endif
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
                    <div class="card hover-elevate-up shadow-sm parent-hover text-dark">
                        <!--begin::Body-->
                        <div class="card-body d-flex align-items-start flex-column">
                            <!--begin::Icon-->
                            <div class="m-0">
                                <span class="card-title mb-1 fs-1 fw-bold parent-hover-primary">Ingreso de Inscripciones</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Section-->
                            <div class="d-flex mt-5">
                                <!--begin::Number-->
                                <span class="fs-2 fw-bold">
                                    <div>S/. {{ number_format($ingreso_inscripcion, 2, ',', ' ') }}</div>
                                </span>
                                <!--end::Number-->
                                @if($ingreso_por_dia_inscripcion > 0)
                                    <!--begin::Cantidad de ingresos por día-->
                                    <span class="badge badge-light-success fs-base ms-6" data-bs-toggle="tooltip" data-bs-placement="right" title="Ingresos por día">                                
                                        <div class="d-flex align-items-center">
                                            <!--begin::Svg Icon-->
                                            <span class="svg-icon svg-icon-muted svg-icon-3 text-success"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"/>
                                                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <div class="fs-7 fw-bold me-1">S/. {{  number_format($ingreso_por_dia_inscripcion, 2, ',', ' ') }}</div>
                                        </div>
                                    </span>
                                    <!--end::Cantidad de ingresos por día-->
                                @endif
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
                    <div class="card hover-elevate-up shadow-sm parent-hover text-dark">
                        <!--begin::Body-->
                        <div class="card-body d-flex align-items-start flex-column">
                            <!--begin::Icon-->
                            <div class="m-0">
                                <span class="card-title mb-1 fs-1 fw-bold parent-hover-primary">Ingreso de Constancia</span>
                            </div>
                            <!--end::Icon-->
                            <!--begin::Section-->
                            <div class="d-flex mt-5">
                                <!--begin::Number-->
                                <span class="fs-2 fw-bold">
                                    <div>S/. {{ number_format($ingreso_constancia, 2, ',', ' ') }}</div>
                                </span>
                                <!--end::Number-->
                                @if($ingreso_por_dia_constancia > 0)
                                    <!--begin::Cantidad de ingresos por día-->
                                    <span class="badge badge-light-success fs-base ms-6" data-bs-toggle="tooltip" data-bs-placement="right" title="Ingresos por día">                                
                                        <div class="d-flex align-items-center">
                                            <!--begin::Svg Icon-->
                                            <span class="svg-icon svg-icon-muted svg-icon-3 text-success"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"/>
                                                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                            <div class="fs-7 fw-bold me-1">S/. {{  number_format($ingreso_por_dia_constancia, 2, ',', ' ') }}</div>
                                        </div>
                                    </span>
                                    <!--end::Cantidad de ingresos por día-->
                                @endif
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Card widget 2-->
                </div>
                <!--end::Col-->
            </div>
        
            <div class="row g-5 card-maestria">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light-success">
                            <h3 class="card-title fw-bold">
                                Reporte de Inscritos por Programa de Maestría del Proceso de {{ ucwords(strtolower($admision->admision)) }}
                            </h3>
                        </div>
                        <div class="card-body mb-0">
                            <div class="table-responsive" wire:loading.class="table-loading" wire:target="aplicar_filtro">
                                <div class="table-loading-message">
                                    Cargando...
                                </div>
                                <table class="table table-hover table-rounded table-row-bordered border mb-0 gy-4 gs-4" wire:loading.class="opacity-25" wire:target="aplicar_filtro">
                                    <thead>
                                        <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="text-center col-md-1">#</th>
                                            <th>Programa</th>
                                            <th class="col-md-2 text-center">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($programas_maestria as $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    @if ($item->mencion)
                                                        Mencion en {{ ucwords(strtolower($item->mencion)) }}
                                                    @else
                                                        {{ ucwords(strtolower($item->programa)) }} en {{ ucwords(strtolower($item->subprograma)) }}
                                                    @endif
                                                </td>
                                                <td class="fw-bold text-center">
                                                    {{ $item->cantidad }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">
                                                    No hay registros
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-light-secondary">
                                        <td colspan="2" class="text-end">
                                            <span class="fw-bold">
                                                Total
                                            </span>
                                        </td>
                                        <td class="fw-bold text-center">
                                            {{ $programas_maestria->sum('cantidad') }}
                                        </td>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light-primary">
                            <h3 class="card-title fw-bold">
                                Reporte de Inscritos por Programa de Doctorado del Proceso de {{ ucwords(strtolower($admision->admision)) }}
                            </h3>
                        </div>
                        <div class="card-body mb-0">
                            <div class="table-responsive" wire:loading.class="table-loading" wire:target="aplicar_filtro">
                                <div class="table-loading-message">
                                    Cargando...
                                </div>
                                <table class="table table-hover table-rounded table-row-bordered border mb-0 gy-4 gs-4" wire:loading.class="opacity-25" wire:target="aplicar_filtro">
                                    <thead>
                                        <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="text-center col-md-1">#</th>
                                            <th>Programa</th>
                                            <th class="col-md-2 text-center">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($programas_doctorado as $item)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    @if ($item->mencion)
                                                        Mencion en {{ ucwords(strtolower($item->mencion)) }}
                                                    @else
                                                        {{ ucwords(strtolower($item->programa)) }} en {{ ucwords(strtolower($item->subprograma)) }}
                                                    @endif
                                                </td>
                                                <td class="fw-bold text-center">
                                                    {{ $item->cantidad }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">
                                                    No hay registros
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-light-secondary">
                                        <td colspan="2" class="text-end">
                                            <span class="fw-bold">
                                                Total
                                            </span>
                                        </td>
                                        <td class="fw-bold text-center">
                                            {{ $programas_doctorado->sum('cantidad') }}
                                        </td>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<!--end::Row-->
		</div>
		<!--end::Content container-->
	</div>
	<!--end::Content-->
</div>
<!--end::Content wrapper-->

</div>
