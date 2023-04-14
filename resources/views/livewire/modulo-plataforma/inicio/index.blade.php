<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Inicio
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('plataforma.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Inicio</li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta para que el usuario sepa de donde abrir los expedientes --}}
                    {{-- <div class="alert bg-light-primary border border-primary d-flex alig-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                            <i class="las la-exclamation-circle fs-2 text-primary"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">
                                asdasdasd
                            </span>
                        </div>
                    </div> --}}
                    {{-- tabla de expedientes --}}
                    <div class="row">
                        <div class="col-md-8 m-auto">
                            <div class="card shadow-sm mb-5 rounded-3">
                                <div id="carousel_inicio" class="carousel slide rounded-4" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active" data-bs-interval="5000">
                                            <img src="{{ asset('assets/media/auth/bg-login-posgrado-admin-3.jpg') }}" class="d-block w-100 rounded-3" alt="...">
                                        </div>
                                        <div class="carousel-item" data-bs-interval="5000">
                                            <img src="{{ asset('assets/media/auth/bg-login-posgrado-admin-2.jpg') }}" class="d-block w-100 rounded-3" alt="...">
                                        </div>
                                        <div class="carousel-item" data-bs-interval="5000">
                                            <img src="{{ asset('assets/media/auth/bg-login-posgrado-admin.jpg') }}" class="d-block w-100 rounded-3" alt="...">
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel_inicio" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel_inicio" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- tabla de expedientes --}}
                    {{-- <div class="card shadow-sm mb-5">
                        asdasdasd
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    {{-- <script>
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
    </script> --}}
@endpush
