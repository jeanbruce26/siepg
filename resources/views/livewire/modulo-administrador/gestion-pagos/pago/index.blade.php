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
                            <div class="d-flex justify-content-between align-items-center gap-4">
                            </div>
                            <div class="w-25">
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
                                        {{-- <th scope="col">Canal de Pago</th> --}}
                                        <th scope="col">Concepto</th>
                                        <th scope="col" class="col-md-2">Estado</th>
                                        <th scope="col" class="col-md-1">Voucher</th>
                                        <th scope="col" class="col-md-1">Acciones</th>
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
                                            {{-- <td align="center">{{ $item->canal_pago->canal_pago }}</td> --}}
                                            <td align="center">{{ $item->concepto_pago->concepto_pago }}</td>
                                            <td align="center">
                                                @if ($item->pago_estado == 1)
                                                    <span class="badge badge-warning text-light">Pagado</span>
                                                @else
                                                    @if($item->pago_estado == 2)
                                                        <span class="badge badge-info text-light">Verificado</span>
                                                    @else
                                                        <span class="badge badge-success text-light">Inscrito</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td align="center">
                                                <a href="#modalPago" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modalPago">Ver</a>
                                            </td>
                                            <td align="center">
                                                <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                                    Actions
                                                    <span class="svg-icon fs-5 m-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </a>
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="#modalPago"
                                                        wire:click="cargarIdPago({{ $item->id_pago }})" 
                                                        class="menu-link px-3" data-bs-toggle="modal" 
                                                        data-bs-target="#modalPago">
                                                            Editar
                                                        </a>
                                                    </div>
                                                </div>
                                                @if ($item->pago_estado == 1)
                                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a wire:click="eliminar({{ $item->id_pago }})" class="menu-link px-3">
                                                                Eliminar
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <div class="text-center p-3 text-muted">
                                            <span>No hay resultados para la busqueda "<strong>{{ $search }}</strong>"</span>
                                        </div>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Sede --}}
    <div wire:ignore.self class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPago"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $titulo }}</h5>
                    <button type="button" wire:click="limpiar()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form novalidate>
                        <div class="row g-3">
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Documento <span class="text-danger">*</span></label>
                                <input wire:model="documento" type="text" class="form-control @error('documento') is-invalid  @enderror" placeholder="Ingrese número de documento">
                                @error('documento') 
                                    <span class="error text-danger" >{{ $message }}</span> 
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Número de Operación <span class="text-danger">*</span></label>
                                <input wire:model="numero_operacion" type="text" class="form-control mb-2 @error('numero_operacion') is-invalid  @enderror" placeholder="Ingrese el número de operación">
                                <div class="mt-1 text-muted">
                                    <strong>Nota: </strong>Omitir los ceros iniciales.
                                </div>
                                @error('numero_operacion') 
                                <span class="error text-danger" >{{ $message }}</span> 
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Monto <span class="text-danger">*</span></label>
                                <input wire:model="monto" type="text" class="form-control @error('monto') is-invalid  @enderror" placeholder="Ingrese el monto en soles">
                                @error('monto') 
                                    <span class="error text-danger" >{{ $message }}</span> 
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Fecha de Pago <span class="text-danger">*</span></label>
                                <input wire:model="fecha_pago" type="text" class="form-control @error('fecha_pago') is-invalid  @enderror" placeholder="Ingrese la fecha de pago">
                                @error('fecha_pago') 
                                    <span class="error text-danger" >{{ $message }}</span> 
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Canal de Pago <span class="text-danger">*</span></label>
                                <select class="form-select @error('canal_pago') is-invalid  @enderror" wire:model="canal_pago">
                                    <option value="" selected>Seleccione</option>
                                    @foreach ($canalPago as $item)
                                        <option value="{{$item->id_canal_pago}}">{{$item->canal_pago}}</option>
                                    @endforeach
                                </select>
                                @error('canal_pago')
                                    <span class="error text-danger" >{{ $message }}</span> 
                                @enderror
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label">Concepto de Pago <span class="text-danger">*</span></label>
                                <select class="form-select @error('concepto_pago') is-invalid  @enderror" wire:model="concepto_pago">
                                    <option value="" selected>Seleccione</option>
                                    @foreach ($conceptoPago as $item)
                                        <option value="{{$item->id_concepto_pago}}">{{$item->concepto_pago}}</option>
                                    @endforeach
                                </select>
                                @error('concepto_pago')
                                    <span class="error text-danger" >{{ $message }}</span> 
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
                    <button type="button" wire:click="limpiar()" class="btn btn-secondary hover-elevate-up" data-bs-dismiss="modal">Cancelar</button>                    
                    <button type="button" wire:click="guardarAdmision()" class="btn btn-primary hover-elevate-up">Guardar</button>
                </div>
            </div>
        </div>
    </div>

</div>
