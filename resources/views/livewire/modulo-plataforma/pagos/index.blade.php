<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Pagos</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('plataforma.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Pagos</li>
                </ul>
            </div>
            @if ($admitido)
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="#modal_pago" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#modal_pago">
                    Nuevo Pago
                </a>
            </div>
            @endif
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta de fecha de actualizacion de expedientes --}}
                    {{-- @if ($admision->fecha_fin < today())
                        <div class="alert bg-light-danger border border-danger d-flex alig-items-center p-5 mb-5">
                            <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                                <i class="las la-exclamation-circle fs-2 text-danger"></i>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">
                                    La fecha limite para actualizar sus expedientes ha expirado
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="alert bg-light-warning border border-warning d-flex alig-items-center p-5 mb-5">
                            <span class="svg-icon svg-icon-2hx svg-icon-warning me-4">
                                <i class="las la-exclamation-triangle fs-2 text-warning"></i>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">
                                    Recuerde que la fecha limite para actualizar sus expedientes es el {{ $fecha_fin_admision }}
                                </span>
                            </div>
                        </div>
                    @endif --}}
                    {{-- alerta para que el usuario sepa de donde abrir los expedientes --}}
                    {{-- <div class="alert bg-light-primary border border-primary d-flex alig-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                            <i class="las la-exclamation-circle fs-2 text-primary"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">
                                Nota: Para abrir los expedientes debe hacer click en el nombre de cada uno de los expedientes
                            </span>
                        </div>
                    </div> --}}
                    {{-- tabla de pagos --}}
                    <div class="card shadow-sm mb-5">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                        <th>ID</th>
                                        <th>Concepto Pago</th>
                                        <th>Numero Operacion</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pagos as $item)
                                    <tr>
                                        <td>
                                            {{ $item->id_pago }}
                                        </td>
                                        <td>
                                            @php
                                                $inscripcion = App\Models\Inscripcion::where('id_pago', $item->id_pago)->first();
                                            @endphp
                                            @if ($inscripcion)
                                                Concepto por {{ $inscripcion->concepto_pago->concepto_pago }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->pago_operacion }}
                                        </td>
                                        <td>
                                            S/. {{ $item->pago_monto }}
                                        </td>
                                        <td>
                                            {{ date('d/m/Y', strtotime($item->pago_fecha)) }}
                                        </td>
                                        <td>
                                            @if ($item->pago_verificacion == 1)
                                                <span class="badge badge-warning">Pendiente</span>
                                            @else
                                                <span class="badge badge-primary">Ok</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal create/edit expediente --}}
    {{-- <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_expediente">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{ $titulo_modal }}
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" wire:click="limpiar_expediente">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <form autocomplete="off">
                        <div class="mb-5">
                            <label for="expediente" class="required form-label">
                                {{ $expediente_nombre }}
                            </label>
                            <input type="file" wire:model="expediente" class="form-control mb-1 @error('expediente') is-invalid @enderror" accept=".pdf" id="upload{{ $iteration }}"/>
                            <span class="text-muted">
                                Nota: El archivo debe ser en formato PDF y no debe pesar mas de 10MB <br>
                            </span>
                            @error('expediente')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar_expediente">
                        Cerrar
                    </button>
                    <button type="button" wire:click="registrar_expediente" class="btn btn-primary" @if($expediente == null) disabled @endif wire:loading.attr="disabled">
                        <div wire:loading.remove wire:target="registrar_expediente">
                            {{ $boton_modal }}
                        </div>
                        <div wire:loading wire:target="registrar_expediente">
                            Procesando...
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@push('scripts')
    <script>
        // filtro_proceso select2
        $(document).ready(function () {
            $('#filtro_proceso').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                minimumResultsForSearch: Infinity,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando..";
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
                    minimumResultsForSearch: Infinity,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando..";
                        }
                    }
                });
                $('#filtro_proceso').on('change', function(){
                    @this.set('filtro_proceso', this.value);
                });
            });
        });
    </script>
@endpush
