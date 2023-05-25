<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Gestión de Docentes
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Gestión de Docentes
                    </li>
                </ul>
            </div>
            <div class="d-flex flex-stack">
                <div class="d-flex align-items-center text-center gap-2 gap-lg-3 ms-5">
                    {{-- boton para crear docentes --}}
                    <button type="button" class="btn btn-primary fw-bold" wire:click="modo" data-bs-toggle="modal"
                        data-bs-target="#modal_docente">
                        Nuevo Docente
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div
                        class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4 d-flex align-items-center">
                            <i class="las la-exclamation-circle fs-1 text-primary"></i>
                        </span>
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold fs-5">
                                A continuación se muestra la lista de docentes registrados en el sistema.
                            </span>
                        </div>
                    </div>
                    {{-- card la tabla --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            <div class="table-responsive">
                                {{-- header de la tabla --}}
                                <div class="d-flex flex-column flex-md-row align-items-center mb-5 w-100">
                                    <div class="col-md-4 pe-md-3 mb-2 mb-md-0">
                                        <button type="button" class="btn btn-light-primary fw-bold w-100px w-md-125px"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <span class="svg-icon svg-icon-3 me-1">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z"
                                                        fill="currentColor" />
                                                </svg>
                                            </span>
                                            Filtrar
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                            data-kt-menu="true" id="filtros_docentes" wire:ignore.self>
                                            <div class="px-7 py-5">
                                                <div class="fs-4 text-dark fw-bold">
                                                    Opciones de Filtro
                                                </div>
                                            </div>

                                            <div class="separator border-gray-200"></div>

                                            <form class="px-7 py-5" wire:submit.prevent="aplicar_filtro">
                                                <div class="mb-5">
                                                    <label class="form-label fw-semibold">
                                                        Grado Académico:
                                                    </label>
                                                    <div>
                                                        <select class="form-select" wire:model="filtro_grado_academico"
                                                            id="filtro_grado_academico" data-control="select2"
                                                            data-placeholder="Seleccione su grado académico">
                                                            @foreach ($grados_academicos as $item)
                                                                <option value=""></option>
                                                                <option value="{{ $item->id_grado_academico }}">
                                                                    {{ $item->grado_academico }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-10">
                                                    <label class="form-label fw-semibold">
                                                        Tipo de Docente:
                                                    </label>
                                                    <div>
                                                        <select class="form-select" wire:model="filtro_tipo_docente"
                                                            id="filtro_tipo_docente" data-control="select2"
                                                            data-placeholder="Seleccione el tipo de docente">
                                                            @foreach ($tipos_docentes as $item)
                                                                <option value=""></option>
                                                                <option value="{{ $item->id_tipo_docente }}">
                                                                    {{ $item->tipo_docente }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" wire:click="resetear_filtro"
                                                        class="btn btn-light btn-active-light-primary me-2"
                                                        data-kt-menu-dismiss="true">Resetear</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        data-kt-menu-dismiss="true">Aplicar</button>
                                                </div>
                                            </form>

                                            {{-- <div class="mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label fs-5 fw-semibold mb-3">Payment Type:</label>
                                                <!--end::Label-->

                                                <!--begin::Options-->
                                                <div class="d-flex flex-column flex-wrap fw-semibold" data-kt-customer-table-filter="payment_type">
                                                    <!--begin::Option-->
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                        <input class="form-check-input" type="radio" name="payment_type" value="all" checked="checked">
                                                        <span class="form-check-label text-gray-600">
                                                            All
                                                        </span>
                                                    </label>
                                                    <!--end::Option-->

                                                    <!--begin::Option-->
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                        <input class="form-check-input" type="radio" name="payment_type" value="visa">
                                                        <span class="form-check-label text-gray-600">
                                                            Visa
                                                        </span>
                                                    </label>
                                                    <!--end::Option-->

                                                    <!--begin::Option-->
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                                        <input class="form-check-input" type="radio" name="payment_type" value="mastercard">
                                                        <span class="form-check-label text-gray-600">
                                                            Mastercard
                                                        </span>
                                                    </label>
                                                    <!--end::Option-->

                                                    <!--begin::Option-->
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" name="payment_type" value="american_express">
                                                        <span class="form-check-label text-gray-600">
                                                            American Express
                                                        </span>
                                                    </label>
                                                    <!--end::Option-->
                                                </div>
                                                <!--end::Options-->
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="col-md-4 px-md-3 mb-2 mb-md-0"></div>
                                    <div class="col-md-4 ps-md-3">
                                        <input type="search" wire:model="search" class="form-control w-100"
                                            placeholder="Buscar..." />
                                    </div>
                                </div>
                                <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200">
                                            <th>#</th>
                                            <th>Apellidos y Nombres</th>
                                            <th>Documento</th>
                                            <th>Correo Electrónico</th>
                                            <th>Grado Académico</th>
                                            <th>Estado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-700">
                                        {{-- @foreach ($docentes as $item)
                                            <tr>
                                                <td class="fw-bold fs-6">
                                                    {{ $item->id_docente }}
                                                </td>
                                                <td class="fs-6">
                                                    <div class="d-flex justify-content-start align-items-center gap-2">
                                                        <div>
                                                            @if ($item->trabajador_perfil_url)
                                                                <img src="{{ asset($item->trabajador_perfil_url) }}"
                                                                    alt="foto de perfil"
                                                                    class="w-40px h-40px rounded object-cover">
                                                            @else
                                                                <img src="{{ asset('assets/media/avatars/blank.png') }}"
                                                                    alt="foto de perfil" class="w-40px h-40px rounded">
                                                            @endif
                                                        </div>
                                                        <span>
                                                            {{ $item->trabajador_apellido }},
                                                            {{ $item->trabajador_nombre }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->trabajador_numero_documento }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->trabajador_correo }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->grado_academico }}
                                                </td>
                                                <td class="fs-6">
                                                    @if ($item->docente_estado == 1)
                                                        <span class="badge badge-primary fs-6 px-3 py-2"
                                                            wire:click="alerta_cambiar_estado({{ $item->id_docente }})"
                                                            style="cursor: pointer;">
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger fs-6 px-3 py-2"
                                                            wire:click="alerta_cambiar_estado({{ $item->id_docente }})"
                                                            style="cursor: pointer;">
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-flex btn-center fw-bold btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                        data-kt-menu-trigger="click"
                                                        data-kt-menu-placement="bottom-end">
                                                        Acciones
                                                        <span class="svg-icon fs-5 rotate-180 ms-2 me-0 m-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                width="24px" height="24px" viewBox="0 0 24 24"
                                                                version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                    <path
                                                                        d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z"
                                                                        fill="currentColor" fill-rule="nonzero"
                                                                        transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)">
                                                                    </path>
                                                                </g>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-150px py-4"
                                                        data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a href="#modal_docente_detalle"
                                                                wire:click="cargar_docente({{ $item->id_docente }}, 'show')"
                                                                class="menu-link px-3 fs-6" data-bs-toggle="modal"
                                                                data-bs-target="#modal_docente_detalle">
                                                                Detalle
                                                            </a>
                                                        </div>
                                                        <div class="menu-item px-3">
                                                            <a href="#modal_docente"
                                                                wire:click="cargar_docente({{ $item->id_docente }}, 'edit')"
                                                                class="menu-link px-3 fs-6" data-bs-toggle="modal"
                                                                data-bs-target="#modal_docente">
                                                                Editar Datos
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($docentes->count() == 0)
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda
                                                        "{{ $search }}"
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No hay registros
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif --}}
                                        @forelse ($docentes as $item)
                                            <tr>
                                                <td class="fw-bold fs-6">
                                                    {{ $item->id_docente }}
                                                </td>
                                                <td class="fs-6">
                                                    <div class="d-flex justify-content-start align-items-center gap-2">
                                                        <div>
                                                            @if ($item->trabajador_perfil_url)
                                                                <img src="{{ asset($item->trabajador_perfil_url) }}"
                                                                    alt="foto de perfil"
                                                                    class="w-40px h-40px rounded object-cover">
                                                            @else
                                                                <img src="{{ asset('assets/media/avatars/blank.png') }}"
                                                                    alt="foto de perfil" class="w-40px h-40px rounded">
                                                            @endif
                                                        </div>
                                                        <span>
                                                            {{ $item->trabajador_apellido }},
                                                            {{ $item->trabajador_nombre }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->trabajador_numero_documento }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->trabajador_correo }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->grado_academico }}
                                                </td>
                                                <td class="fs-6">
                                                    @if ($item->docente_estado == 1)
                                                        <span class="badge badge-primary fs-6 px-3 py-2"
                                                            wire:click="alerta_cambiar_estado({{ $item->id_docente }})"
                                                            style="cursor: pointer;">
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger fs-6 px-3 py-2"
                                                            wire:click="alerta_cambiar_estado({{ $item->id_docente }})"
                                                            style="cursor: pointer;">
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-flex btn-center fw-bold btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                        {{-- data-kt-menu-trigger="click" --}}
                                                        {{-- data-kt-menu-placement="bottom-end" --}}
                                                        data-bs-toggle="dropdown">
                                                        Acciones
                                                        <span class="svg-icon fs-5 rotate-180 ms-2 me-0 m-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                width="24px" height="24px" viewBox="0 0 24 24"
                                                                version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                    <path
                                                                        d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z"
                                                                        fill="currentColor" fill-rule="nonzero"
                                                                        transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)">
                                                                    </path>
                                                                </g>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-150px py-4"
                                                        data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a href="#modal_docente_detalle"
                                                                wire:click="cargar_docente({{ $item->id_docente }}, 'show')"
                                                                class="menu-link px-3 fs-6" data-bs-toggle="modal"
                                                                data-bs-target="#modal_docente_detalle">
                                                                Detalle
                                                            </a>
                                                        </div>
                                                        <div class="menu-item px-3">
                                                            <a href="#modal_docente"
                                                                wire:click="cargar_docente({{ $item->id_docente }}, 'edit')"
                                                                class="menu-link px-3 fs-6" data-bs-toggle="modal"
                                                                data-bs-target="#modal_docente">
                                                                Editar Datos
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda
                                                        "{{ $search }}"
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No hay registros
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{-- paginacion de la tabla --}}
                            @if ($docentes->hasPages())
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $docentes->firstItem() }} - {{ $docentes->lastItem() }} de
                                        {{ $docentes->total() }} registros
                                    </div>
                                    <div>
                                        {{ $docentes->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $docentes->firstItem() }} - {{ $docentes->lastItem() }} de
                                        {{ $docentes->total() }} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_docente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $title_modal }}
                    </h2>

                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                    rx="5" fill="currentColor" />
                                <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                    transform="rotate(-45 7 15.3137)" fill="currentColor" />
                                <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                    transform="rotate(45 8.41422 7)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" class="row g-5">
                        <div class="col-md-6">
                            <label for="documento_identidad" class="required form-label">
                                Documento de Identidad
                            </label>
                            <input type="number" wire:model="documento_identidad"
                                class="form-control @error('documento_identidad') is-invalid @enderror"
                                placeholder="12345678" id="documento_identidad" />
                            @error('documento_identidad')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nombre" class="required form-label">
                                Nombre
                            </label>
                            <input type="text" wire:model="nombre"
                                class="form-control @error('nombre') is-invalid @enderror"
                                placeholder="Ingrese el nombre del docente" id="nombre" />
                            @error('nombre')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="apellido_paterno" class="required form-label">
                                Apellido Paterno
                            </label>
                            <input type="text" wire:model="apellido_paterno"
                                class="form-control @error('apellido_paterno') is-invalid @enderror"
                                placeholder="Ingrese el apellido paterno del docente" id="apellido_paterno" />
                            @error('apellido_paterno')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="apellido_materno" class="required form-label">
                                Apellido Materno
                            </label>
                            <input type="text" wire:model="apellido_materno"
                                class="form-control @error('apellido_materno') is-invalid @enderror"
                                placeholder="Ingrese el apellido materno del docente" id="apellido_materno" />
                            @error('apellido_materno')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="correo_electronico" class="required form-label">
                                Correo Electrónico
                            </label>
                            <input type="email" wire:model="correo_electronico"
                                class="form-control @error('correo_electronico') is-invalid @enderror"
                                placeholder="docente@unu.edu.pe" id="correo_electronico" />
                            @error('correo_electronico')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="direccion" class="required form-label">
                                Dirección
                            </label>
                            <input type="text" wire:model="direccion"
                                class="form-control @error('direccion') is-invalid @enderror"
                                placeholder="Jr. Nombre 123" id="direccion" />
                            @error('direccion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="grado_academico" class="required form-label">
                                Grado Académico
                            </label>
                            <select class="form-select @error('grado_academico') is-invalid @enderror"
                                wire:model="grado_academico" id="grado_academico" data-control="select2"
                                data-placeholder="Seleccione su grado académico" data-allow-clear="true"
                                data-dropdown-parent="#modal_docente">
                                <option></option>
                                @foreach ($grados_academicos as $item)
                                    <option value="{{ $item->id_grado_academico }}">{{ $item->grado_academico }}
                                    </option>
                                @endforeach
                            </select>
                            @error('grado_academico')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tipo_docente" class="required form-label">
                                Tipo de Docente
                            </label>
                            <select class="form-select @error('tipo_docente') is-invalid @enderror"
                                wire:model="tipo_docente" id="tipo_docente" data-control="select2"
                                data-placeholder="Seleccione su tipo de docente" data-allow-clear="true"
                                data-dropdown-parent="#modal_docente">
                                <option></option>
                                @foreach ($tipos_docentes as $item)
                                    <option value="{{ $item->id_tipo_docente }}">{{ $item->tipo_docente }}</option>
                                @endforeach
                            </select>
                            @error('tipo_docente')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($mostrar_curriculum == true)
                            <div class="col-md-12">
                                <label for="curriculum_vitae" class="required form-label">
                                    Curriculum Vitae
                                </label>
                                <input type="file" wire:model="curriculum_vitae"
                                    class="form-control @error('curriculum_vitae') is-invalid @enderror"
                                    id="upload{{ $iteration }}" accept="application/pdf" />
                                <span class="form-text text-muted mt-2 fst-italic">
                                    Nota: El currivulum vitae debe ser en formato PDF y no debe superar los 10MB. <br>
                                </span>
                                @error('curriculum_vitae')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        <div class="col-md-12">
                            <label for="foto_perfil"
                                class="@if ($modo == 'create') required @endif form-label">
                                Foto de Perfil
                            </label>
                            <input type="file" wire:model="foto_perfil"
                                class="form-control @error('foto_perfil') is-invalid @enderror"
                                id="upload{{ $iteration }}" accept="image/jpeg, image/png, image/jpg" />
                            <span class="form-text text-muted mt-2 fst-italic">
                                Nota: La foto de perfil debe ser imagen en formato JPG, JPEG, PNG y no debe superar los
                                2MB. <br>
                            </span>
                            @error('foto_perfil')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="limpiar_modal">Cerrar</button>
                    <button type="button" wire:click="guardar_docente" class="btn btn-primary" style="width: 150px"
                        wire:loading.attr="disabled" wire:target="guardar_docente">
                        <div wire:loading.remove wire:target="guardar_docente">
                            Guardar
                        </div>
                        <div wire:loading wire:target="guardar_docente">
                            Procesando <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_docente_detalle">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $title_modal }}
                    </h2>

                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                    rx="5" fill="currentColor" />
                                <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                    transform="rotate(-45 7 15.3137)" fill="currentColor" />
                                <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                    transform="rotate(45 8.41422 7)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" class="row g-5">
                        <div class="col-md-12 px-md-15">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <div>
                                    @if ($trabajador)
                                        @if ($trabajador->trabajador_perfil_url)
                                            <img src="{{ asset($trabajador->trabajador_perfil_url) }}"
                                                alt="foto de perfil" class="w-150px h-150px rounded object-cover">
                                        @else
                                            <img src="{{ asset('assets/media/avatars/blank.png') }}"
                                                alt="foto de perfil" class="w-150px h-150px rounded">
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 px-md-15">
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Apellidos y Nombres
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $apellido_paterno }} {{ $apellido_materno }}, {{ $nombre }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Documento de Identidad
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $documento_identidad }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Correo Electrónico
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $correo_electronico }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Dirección
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $direccion }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Grado Académico
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $grado_academico }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-4 fw-semibold text-gray-600 fs-5">
                                    Tipo de Docente
                                </span>
                                <span class="col-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-7 fw-bold text-gray-900 fs-5">
                                    {{ $tipo_docente }}
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // grado_academico select2
        $(document).ready(function() {
            $('#grado_academico').select2({
                placeholder: 'Seleccione su grado académico',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#grado_academico').on('change', function() {
                @this.set('grado_academico', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#grado_academico').select2({
                    placeholder: 'Seleccione su grado académico',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#grado_academico').on('change', function() {
                    @this.set('grado_academico', this.value);
                });
            });
        });
        // tipo_docente select2
        $(document).ready(function() {
            $('#tipo_docente').select2({
                placeholder: 'Seleccione su tipo de docente',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#tipo_docente').on('change', function() {
                @this.set('tipo_docente', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#tipo_docente').select2({
                    placeholder: 'Seleccione su tipo de docente',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#tipo_docente').on('change', function() {
                    @this.set('tipo_docente', this.value);
                });
            });
        });
        // filtro_grado_academico select2
        $(document).ready(function() {
            $('#filtro_grado_academico').select2({
                placeholder: 'Seleccione su grado académico',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#filtro_grado_academico').on('change', function() {
                @this.set('filtro_grado_academico', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_grado_academico').select2({
                    placeholder: 'Seleccione su grado académico',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#filtro_grado_academico').on('change', function() {
                    @this.set('filtro_grado_academico', this.value);
                });
            });
        });
        // filtro_tipo_docente select2
        $(document).ready(function() {
            $('#filtro_tipo_docente').select2({
                placeholder: 'Seleccione el tipo de docente',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
            $('#filtro_tipo_docente').on('change', function() {
                @this.set('filtro_tipo_docente', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_tipo_docente').select2({
                    placeholder: 'Seleccione el tipo de docente',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#filtro_tipo_docente').on('change', function() {
                    @this.set('filtro_tipo_docente', this.value);
                });
            });
        });
    </script>
@endpush
