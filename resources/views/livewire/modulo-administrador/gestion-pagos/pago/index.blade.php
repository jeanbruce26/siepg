<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Pago
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('administrador.dashboard') }}" class="text-muted text-hover-primary">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Pago</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#modalPago" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modalPago">Nuevo</a>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="me-1">
                                <a class="btn btn-sm btn-light-primary me-3 fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    Filtro
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-500px" data-kt-menu="true" id="menu_pago" wire:ignore.self>
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bold">
                                            Opciones de filtrado
                                        </div> 
                                    </div>
                                    <div class="separator border-gray-200"></div>
                                    <div class="px-7 py-5 row">
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Año / Proceso:</label>
                                            <div>
                                                <select class="form-select" wire:model="filtro_proceso" id="filtro_proceso"  data-control="select2" data-placeholder="Seleccione el año / proceso">
                                                    <option></option>
                                                    @foreach ($aniosUnicos as $item)
                                                        <option value="{{ $item }}">Año / Proceso {{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Modalidad del Programa:</label>
                                            <div>
                                                <select class="form-select" wire:model="filtro_modalidad" id="filtro_modalidad" data-control="select2" data-placeholder="Seleccione la Modalidad">
                                                    <option></option>
                                                    @foreach ($modalidades as $item)
                                                        <option value="{{ $item->id_modalidad }}">{{ $item->modalidad }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-12">
                                            <label class="form-label fw-semibold">Programa:</label>
                                            <div>
                                                <select class="form-select" wire:model="filtro_programa" id="filtro_programa" data-control="select2" data-placeholder="Seleccione el Programa">
                                                    <option></option>
                                                    @if($filtro_modalidad)
                                                        @php
                                                            $programas = App\Models\Programa::where('id_modalidad', $filtro_modalidad)->get();
                                                        @endphp
                                                        @foreach ($programas as $item)
                                                            <option value="{{ $item->id_programa }}">{{ $item->programa }} EN {{ $item->subprograma }} @if($item->mencion != '') CON MENCION EN {{ $item->mencion }}@endif</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Ciclo Académico:</label>
                                            <div>
                                                <select class="form-select" wire:model="filtro_ciclo" id="filtro_ciclo" data-control="select2" data-placeholder="Seleccione el ciclo académico">
                                                    <option></option>
                                                    @foreach ($ciclos as $item)
                                                        <option value="{{ $item->id_ciclo }}">{{ $item->ciclo }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Concepto de Pago:</label>
                                            <div>
                                                <select class="form-select" wire:model="filtro_concepto" id="filtro_concepto"  data-control="select2" data-placeholder="Seleccione el concepto de pago">
                                                    <option></option>
                                                    @foreach ($conceptoPago as $item)
                                                        <option value="{{ $item->id_concepto_pago }}">{{ $item->concepto_pago }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Mes:</label>
                                            <div>
                                                <select class="form-select" wire:model="filtro_mes" id="filtro_mes" data-control="select2" data-placeholder="Seleccione el mes">
                                                    <option></option>
                                                    @foreach ($meses as $item)
                                                        <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-6">
                                            <label class="form-label fw-semibold">Verificación:</label>
                                            <div>
                                                <select class="form-select" wire:model="filtro_verificacion" id="filtro_verificacion" data-control="select2" data-placeholder="Seleccione la verificación">
                                                    <option></option>
                                                    @foreach ($pago_verificaciones as $item)
                                                        <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-end">
                                            <button type="button" wire:click="resetear_filtro" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Resetear</button>
                                            <button type="button" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true" wire:click="filtrar">Aplicar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ms-2">
                                <input class="form-control form-control-sm text-muted" type="search" wire:model="search"
                                    placeholder="Buscar...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded border gy-4 gs-4 mb-0 align-middle">
                                <thead class="bg-light-primary">
                                    <tr align="center" class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                        <th scope="col" class="col-md-1">ID</th>
                                        <th scope="col">Documento</th>
                                        <th scope="col">Operación</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Concepto</th>
                                        <th scope="col" class="col-md-1">Estado</th>
                                        <th scope="col" class="col-md-1">Verificación</th>
                                        <th scope="col" class="col-md-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pago_model as $item)
                                        <tr>
                                            <td align="center" class="fw-bold fs-5">{{ $item->id_pago }}</td>
                                            <td align="center">{{ $item->pago_documento }}</td>
                                            <td align="center">{{ $item->pago_operacion }}</td>
                                            <td align="center">S/. {{ $item->pago_monto }}</td>
                                            <td align="center">{{ date('d/m/Y', strtotime($item->pago_fecha)) }}</td>
                                            <td align="center">{{ $item->concepto_pago->concepto_pago }}</td>
                                            <td align="center">
                                                @if ($item->pago_estado == 0)
                                                    <span class="badge badge-light-danger fs-6 px-3 py-2">Rechazado</span> 
                                                @elseif($item->pago_estado == 1)
                                                    <span class="badge badge-light-info fs-6 px-3 py-2">Pagado</span>
                                                @elseif($item->pago_estado == 2)
                                                    <span class="badge badge-light-primary fs-6 px-3 py-2">Asignado</span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                @if ($item->pago_verificacion == 0)
                                                    @if($item->pago_estado == 0)
                                                        <span class="badge badge-light-danger fs-6 px-3 py-2">Rechazado</span>
                                                    @else
                                                        <span class="badge badge-light-danger fs-6 px-3 py-2">Observado</span>
                                                    @endif
                                                @elseif($item->pago_verificacion == 1)
                                                    <span class="badge badge-light-warning fs-6 px-3 py-2">Pendiente</span>
                                                @elseif($item->pago_verificacion == 2)
                                                    <span class="badge badge-light-success fs-6 px-3 py-2">Verificado</span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <a class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-sm" data-bs-toggle="dropdown">
                                                    Acciones
                                                    <span class="svg-icon fs-5 m-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="#modalPago"
                                                        wire:click="cargarIdPago({{ $item->id_pago }})"
                                                        class="menu-link px-3" data-bs-toggle="modal" 
                                                        data-bs-target="#modalPago">
                                                            Editar
                                                        </a>
                                                    </div>
                                                    <div class="menu-item px-3">
                                                        <a href="#modalPago"
                                                        wire:click="cargarVerPago({{ $item->id_pago }})"
                                                        class="menu-link px-3" data-bs-toggle="modal" 
                                                        data-bs-target="#modalVerPago">
                                                            Ver Pago
                                                        </a>
                                                    </div>
                                                    @if (($item->pago_estado == 1 && $item->pago_verificacion == 1) || ($item->pago_estado == 0 && $item->pago_verificacion == 0))
                                                        <div class="menu-item px-3">
                                                            <a wire:click="eliminar({{ $item->id_pago }})" class="menu-link px-3">
                                                                Eliminar
                                                            </a>
                                                        </div>
                                                    @endif
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
                        @if ($pago_model->hasPages())
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $pago_model->firstItem() }} - {{ $pago_model->lastItem() }} de
                                    {{ $pago_model->total() }} registros
                                </div>
                                <div>
                                    {{ $pago_model->links() }}
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $pago_model->firstItem() }} - {{ $pago_model->lastItem() }} de
                                    {{ $pago_model->total() }} registros
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Sede --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modalPago">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $titulo }}
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
                    <form autocomplete="off">
                        <div class="row g-3">
                            <div class="mb-3 col-md-4">
                                <label for="documento" class="required form-label">
                                    Documento
                                </label>
                                <input type="text" wire:model="documento" 
                                    class="form-control @error('documento') is-invalid @enderror"
                                    placeholder="Ingrese su número de documento" id="documento"/>
                                @error('documento') 
                                    <span class="error text-danger" >{{ $message }}</span> 
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="numero_operacion" class="required form-label">
                                    Número de Operación
                                </label>
                                <input type="text" wire:model="numero_operacion" 
                                    class="form-control @error('numero_operacion') is-invalid @enderror"
                                    placeholder="Ingrese su número de operación" id="numero_operacion"/>
                                <div class="mt-1 text-muted">
                                    <strong>Nota: </strong>Omitir los ceros iniciales.
                                </div>
                                @error('numero_operacion') 
                                <span class="error text-danger" >{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="monto" class="required form-label">
                                    Monto
                                </label>
                                <input type="text" wire:model="monto" 
                                    class="form-control @error('monto') is-invalid @enderror"
                                    placeholder="Ingrese el monto en soles" id="monto"/>
                                @error('monto') 
                                    <span class="error text-danger" >{{ $message }}</span> 
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="fecha_pago" class="required form-label">
                                    Fecha de Pago
                                </label>
                                <input type="date" wire:model="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror" id="fecha_pago" max="{{ date('Y-m-d') }}" >
                                @error('fecha_pago')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="canal_pago" class="required form-label">
                                    Canal de Pago
                                </label>
                                <select class="form-select @error('canal_pago') is-invalid @enderror"
                                    wire:model="canal_pago" id="canal_pago" data-control="select2"
                                    data-placeholder="Seleccione su canal de pago" data-allow-clear="true"
                                    data-dropdown-parent="#modalPago">
                                    <option></option>
                                    @foreach ($canalPago as $item)
                                        @if($item->canal_pago_estado == 1)
                                            <option value="{{$item->id_canal_pago}}">{{$item->canal_pago}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('canal_pago')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="concepto_pago" class="required form-label">
                                    Concepto de Pago
                                </label>
                                <select class="form-select @error('concepto_pago') is-invalid @enderror"
                                    wire:model="concepto_pago" id="concepto_pago" data-control="select2"
                                    data-placeholder="Seleccione su concepto de pago" data-allow-clear="true"
                                    data-dropdown-parent="#modalPago">
                                    <option></option>
                                    @foreach ($conceptoPago as $item)
                                        @if($item->concepto_pago_estado == 1)
                                            <option value="{{$item->id_concepto_pago}}">{{$item->concepto_pago}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('concepto_pago')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-12">
                                <label for="voucher" class="required form-label">
                                    Voucher de Pago
                                </label>
                                <input type="file" wire:model="voucher_url" class="form-control mb-2 @error('voucher_url') is-invalid @enderror" id="upload{{ $iteracion }}" accept="image/jpeg, image/png, image/jpg" />
                                <span class="form-text text-muted" >
                                    <strong>Nota: </strong> El voucher debe ser imagen en formato JPG, JPEG, PNG y no debe superar los 2MB. <br>
                                </span>
                                @error('voucher_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer col-12 d-flex justify-content-between">
                    <button type="button" wire:click="limpiar()" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click="guardarPago" class="btn btn-primary" wire:loading.attr="disabled" wire:target="guardarPago">
                        <div wire:loading.remove wire:target="guardarPago">
                            Guardar
                        </div>
                        <div wire:loading wire:target="guardarPago">
                            Procesando <span class="spinner-border spinner-border-sm align-middle ms-2">
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal pago --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modalVerPago">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $titulo }}
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
                    <form autocomplete="off">
                        <div class="mb-5">
                            <div class="row">
                                <div class="row mb-1">
                                    <h6 class="fw-bold">Datos Personales</h6>
                                </div>
                                <table class="ms-4">
                                    <tbody>
                                        <tr>
                                            <td  width="160">Documento</td>
                                            <td  width="20">:</td>
                                            <td>{{ $documento }}</td>
                                        </tr>
                                        <tr>
                                            <td>Número de Operación</td>
                                            <td>:</td>
                                            <td>{{ $numero_operacion }}</td>
                                        </tr>
                                        <tr>
                                            <td>Monto</td>
                                            <td>:</td>
                                            <td>{{ $monto }}</td>
                                        </tr>
                                        <tr>
                                            <td>Fecha</td>
                                            <td>:</td>
                                            <td>{{ $fecha_pago }}</td>
                                        </tr>
                                        <tr>
                                            <td>Concepto</td>
                                            <td>:</td>
                                            <td>{{ $canal_pago }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="expediente" class="form-label d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">
                                    Voucher
                                </span>
                                @if(file_exists($voucher_url))
                                    <a href="{{ asset($voucher_url) }}" target="_blank" class="btn btn-sm btn-light-info">
                                        Ver Voucher Completo
                                    </a>
                                @endif
                            </label>
                            <div class="form-control">
                                @if(file_exists($voucher_url))
                                    <img src="{{ asset($voucher_url) }}" alt="voucher_url" class="img-fluid rounded">
                                @else
                                    @if($valida_pago_estado == 0)
                                        <div class="alert alert-danger d-flex align-items-center p-5">
                                            <i class="ki-duotone ki-delete-folder fs-2hx text-danger me-4">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                            </i>
                                            <div class="d-flex flex-column">
                                                <h4 class="mb-1 text-danger">Voucher Rechazado</h4>
                                                <span>Lamentamos informarle que el voucher de pago ha sido eliminado del sistema debido a que su pago ha sido rechazado.</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning d-flex align-items-center p-5">
                                            <i class="ki-duotone ki-search-list fs-2hx text-warning me-4">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                                <i class="path3"></i>
                                            </i>
                                            <div class="d-flex flex-column">
                                                <h4 class="mb-1 text-warning">Voucher no encontrado</h4>
                                                <span>Lamentablemente, no se ha podido encontrar el voucher en el sistema.</span>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                            <div class="">
                                <label for="observacion" class="form-label">
                                    Observacion
                                </label>
                                <textarea class="form-control @error('observacion') is-invalid @enderror" id="observacion" wire:model="observacion" rows="3" @if($valida_pago_verificacion == 2 || $valida_pago_estado == 0) readonly @endif ></textarea>
                                @error('observacion')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                    </form>
                </div>
                <div class="modal-footer d-flex align-items-center @if($valida_pago_verificacion == 2 || $valida_pago_estado == 0) justify-content-end @else justify-content-between @endif">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="limpiar">
                        Cerrar
                    </button>
                    @if($valida_pago_verificacion == 2 || $valida_pago_estado == 0)
                        
                    @else
                    {{-- //Hacer que cuando sea pequeño la pantalla, se ordene bonito --}}
                    <div class="d-flex gap-2 justify-content-end align-items-center">
                        <button type="button" wire:click="rechazar_pago" class="btn btn-danger" wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="rechazar_pago">
                                Rechazar
                            </div>
                            <div wire:loading wire:target="rechazar_pago">
                                Procesando...
                            </div>
                        </button>
                        <button type="button" wire:click="observar_pago" class="btn btn-warning" wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="observar_pago">
                                Observar
                            </div>
                            <div wire:loading wire:target="observar_pago">
                                Procesando...
                            </div>
                        </button>
                        <button type="button" wire:click="validar_pago" class="btn btn-primary" wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="validar_pago">
                                Validar
                            </div>
                            <div wire:loading wire:target="validar_pago">
                                Procesando...
                            </div>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        //Select2 de Filtro
        // filtro_proceso select2
        $(document).ready(function () {
            $('#filtro_proceso').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#filtro_proceso').on('change', function(){
                @this.set('filtro_proceso', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_proceso').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#filtro_proceso').on('change', function(){
                    @this.set('filtro_proceso', this.value);
                });
            });
        });
        // filtro_concepto select2
        $(document).ready(function () {
            $('#filtro_concepto').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#filtro_concepto').on('change', function(){
                @this.set('filtro_concepto', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_concepto').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#filtro_concepto').on('change', function(){
                    @this.set('filtro_concepto', this.value);
                });
            });
        });


        //Select2 de Modal Pago
        // canal_pago select2
        $(document).ready(function () {
            $('#canal_pago').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#canal_pago').on('change', function(){
                @this.set('canal_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#canal_pago').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#canal_pago').on('change', function(){
                    @this.set('canal_pago', this.value);
                });
            });
        });
        // concepto_pago select2
        $(document).ready(function () {
            $('#concepto_pago').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#concepto_pago').on('change', function(){
                @this.set('concepto_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#concepto_pago').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#concepto_pago').on('change', function(){
                    @this.set('concepto_pago', this.value);
                });
            });
        });
    </script>
@endpush