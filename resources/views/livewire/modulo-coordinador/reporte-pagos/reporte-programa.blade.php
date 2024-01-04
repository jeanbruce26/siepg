<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Reporte de Pagos - @if ($programa_proceso->mencion)
                        {{ ucfirst(strtolower($programa_proceso->programa)) }} en {{ ucwords(strtolower($programa_proceso->subprograma)) }} con Mencion en {{ ucwords(strtolower($programa_proceso->mencion)) }}
                    @else
                        {{ ucfirst(strtolower($programa_proceso->programa)) }} en {{ ucwords(strtolower($programa_proceso->subprograma)) }}
                    @endif - Modalidad {{ $programa_proceso->id_modalidad == 2 ? 'a Distancia' : 'Presencial' }}
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.reporte-pagos') }}" class="text-muted text-hover-primary">Reporte de Pagos</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Reporte de Programas
                    </li>
                </ul>
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
                        <i class="ki-outline ki-information-2 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold fs-5">
                                A continuación se muestra la lista de grupos que pertencen al programa seleccionado.
                            </span>
                        </div>
                    </div>
                    {{-- card la filtro y buscador --}}
                    <div class="row g-5">
                        @forelse ($grupos as $item)
                            <div class="@if($programa_proceso->programa_tipo == 1) col-lg-6 col-md-12 @else col-12 @endif">
                                <div class="card hover-elevate-up shadow-sm parent-hover card-bordered">
                                    <div class="card-body mb-0">
                                        <div class="d-flex flex-column gap-5">
                                            <span class="fs-1 fs-sm-2 fw-bold text-center">
                                                GRUPO {{ $item->grupo_detalle }}
                                            </span>
                                            <div class="table-responsive">
                                                <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                                    <thead class="bg-light-warning">
                                                        <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200">
                                                            @foreach ($ciclos as $item_ciclo)
                                                                <th class="text-center">
                                                                    CICLO {{ $item_ciclo->ciclo }}
                                                                </th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-semibold text-gray-700">
                                                        <tr class="fs-5">
                                                        @foreach ($ciclos as $item_ciclo)
                                                            @php
                                                                $matricula = App\Models\Matricula::where('id_programa_proceso_grupo', $item->id_programa_proceso_grupo)->where('id_ciclo', $item_ciclo->id_ciclo)->get();
                                                                $matriculados = $matricula->where('matricula_estado', 1)->count();
                                                                $costo_enseñanza = App\Models\CostoEnseñanza::where('id_programa_plan', $programa_proceso->id_programa_plan)->where('id_ciclo', $item_ciclo->id_ciclo)->first();
                                                                $total_pago = 0;
                                                                foreach ($matricula as $value) {
                                                                    $mensualidad = App\Models\Mensualidad::join('pago','pago.id_pago','=','mensualidad.id_pago')->where('mensualidad.id_matricula', $value->id_matricula)->get();
                                                                    if($costo_enseñanza->costo_ciclo <= $mensualidad->sum('pago.pago_monto')){
                                                                        $total_pago++;
                                                                    }
                                                                }
                                                            @endphp
                                                            <td>
                                                                <div class="d-flex flex-column">
                                                                    <div>
                                                                        Matriculados: <span class="fw-bold">{{ $matriculados }}</span>
                                                                    </div>
                                                                    <div>
                                                                        Total Pagados: <span class="fw-bold">{{ $total_pago }}</span>
                                                                    </div>
                                                                    <div class="text-danger">
                                                                        Total Deben: <span class="fw-bold">{{ $matriculados - $total_pago }}</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        @endforeach
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-end">
                                                <a href="#modal_buscar_programa" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_buscar_programa" wire:click="buscar_programa({{ $item->id_admision }})">
                                                    Ver detalle
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card hover-elevate-up shadow-sm parent-hover card-bordered">
                                    <div class="card-body mb-0 p-10">
                                        <div class="d-flex flex-column gap-5">
                                            <span class="fs-4 text-gray-600 fw-semibold text-center">
                                                No se encontraron resultados
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    {{-- paginacion de la tabla --}}
                    {{-- @if ($procesos->hasPages())
                        <div class="d-flex justify-content-between mt-5">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $procesos->firstItem() }} - {{ $procesos->lastItem() }} de
                                {{ $cursos->total() }} registros
                            </div>
                            <div>
                                {{ $procesos->links() }}
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-between mt-5">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $procesos->firstItem() }} - {{ $procesos->lastItem() }} de
                                {{ $procesos->total() }} registros
                            </div>
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
    {{-- <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_buscar_programa">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $title_modal }}
                    </h2>

                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close"
                        wire:click="limpiar_modal">
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
                    <div class="row g-2 mb-3">
                        <div class="col-12 mb-5">
                            <label class="form-label fw-semibold">
                                Modalidad:
                            </label>
                            <div>
                                <select class="form-select" wire:model="filtro_modalidad"
                                    id="filtro_modalidad" data-control="select2"
                                    data-placeholder="Seleccione su modalidad">
                                    <option value=""></option>
                                    @foreach ($modalidades as $item)
                                        <option value="{{ $item->id_modalidad }}">
                                            @if ($item->id_modalidad == 2)
                                                MODALIDAD A DISTANCIA
                                            @else
                                                MODALIDAD {{ $item->modalidad }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @forelse ($programas as $item)
                            <div class="col-6">
                                <a href="{{ route('coordinador.reporte-programas', ['id_programa_proceso' => $item->id_programa_proceso]) }}" class="btn btn-info hover-elevate-up w-100">
                                    <span class="fw-semibold text-white fs-5">
                                        @if ($item->mencion)
                                            {{ $item->programa }} EN {{ $item->subprograma }} CON MENCIÓN EN
                                            {{ $item->mencion }}
                                        @else
                                            {{ $item->programa }} EN {{ $item->subprograma }}
                                        @endif
                                    </span>
                                    <br>
                                    <span class="fw-semibold text-white fs-7">
                                        MODALIDAD {{ $item->id_modalidad == 2 ? 'A DISTANCIA' : $item->modalidad }}
                                    </span>
                                </a>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="bg-light-secondary p-5 rounded rounded-3 text-center fw-semibold text-gray-700">
                                    No se encontraron resultados
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@push('scripts')
    <script>
        // filtro_modalidad select2
        $(document).ready(function() {
            $('#filtro_modalidad').select2({
                placeholder: 'Seleccione su modalidad',
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
            $('#filtro_modalidad').on('change', function() {
                @this.set('filtro_modalidad', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_modalidad').select2({
                    placeholder: 'Seleccione su modalidad',
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
                $('#filtro_modalidad').on('change', function() {
                    @this.set('filtro_modalidad', this.value);
                });
            });
        });
        // docente select2
        // $(document).ready(function() {
        //     $('#docente').select2({
        //         placeholder: 'Buscar docente por si DNI o Apellidos y Nombres',
        //         allowClear: true,
        //         width: '100%',
        //         selectOnClose: true,
        //         language: {
        //             noResults: function() {
        //                 return "No se encontraron resultados";
        //             },
        //             searching: function() {
        //                 return "Buscando..";
        //             }
        //         }
        //     });
        //     $('#docente').on('change', function() {
        //         @this.set('docente', this.value);
        //     });
        //     Livewire.hook('message.processed', (message, component) => {
        //         $('#docente').select2({
        //             placeholder: 'Buscar docente por si DNI o Apellidos y Nombres',
        //             allowClear: true,
        //             width: '100%',
        //             selectOnClose: true,
        //             language: {
        //                 noResults: function() {
        //                     return "No se encontraron resultados";
        //                 },
        //                 searching: function() {
        //                     return "Buscando..";
        //                 }
        //             }
        //         });
        //         $('#docente').on('change', function() {
        //             @this.set('docente', this.value);
        //         });
        //     });
        // });
        // // filtro_proceso select2
        // $(document).ready(function() {
        //     $('#filtro_proceso').select2({
        //         placeholder: 'Seleccione su admisión',
        //         allowClear: true,
        //         width: '100%',
        //         selectOnClose: true,
        //         language: {
        //             noResults: function() {
        //                 return "No se encontraron resultados";
        //             },
        //             searching: function() {
        //                 return "Buscando..";
        //             }
        //         }
        //     });
        //     $('#filtro_proceso').on('change', function() {
        //         @this.set('filtro_proceso', this.value);
        //     });
        //     Livewire.hook('message.processed', (message, component) => {
        //         $('#filtro_proceso').select2({
        //             placeholder: 'Seleccione su admisión',
        //             allowClear: true,
        //             width: '100%',
        //             selectOnClose: true,
        //             language: {
        //                 noResults: function() {
        //                     return "No se encontraron resultados";
        //                 },
        //                 searching: function() {
        //                     return "Buscando..";
        //                 }
        //             }
        //         });
        //         $('#filtro_proceso').on('change', function() {
        //             @this.set('filtro_proceso', this.value);
        //         });
        //     });
        // });
        // // filtro_plan select2
        // $(document).ready(function() {
        //     $('#filtro_plan').select2({
        //         placeholder: 'Seleccione su plan',
        //         allowClear: true,
        //         width: '100%',
        //         selectOnClose: true,
        //         language: {
        //             noResults: function() {
        //                 return "No se encontraron resultados";
        //             },
        //             searching: function() {
        //                 return "Buscando...";
        //             }
        //         }
        //     });
        //     $('#filtro_plan').on('change', function() {
        //         @this.set('filtro_plan', this.value);
        //     });
        //     Livewire.hook('message.processed', (message, component) => {
        //         $('#filtro_plan').select2({
        //             placeholder: 'Seleccione su plan',
        //             allowClear: true,
        //             width: '100%',
        //             selectOnClose: true,
        //             language: {
        //                 noResults: function() {
        //                     return "No se encontraron resultados";
        //                 },
        //                 searching: function() {
        //                     return "Buscando...";
        //                 }
        //             }
        //         });
        //         $('#filtro_plan').on('change', function() {
        //             @this.set('filtro_plan', this.value);
        //         });
        //     });
        // });
        // // filtro_programa select2
        // $(document).ready(function() {
        //     $('#filtro_programa').select2({
        //         placeholder: 'Seleccione su programa',
        //         allowClear: true,
        //         width: '100%',
        //         selectOnClose: true,
        //         language: {
        //             noResults: function() {
        //                 return "No se encontraron resultados";
        //             },
        //             searching: function() {
        //                 return "Buscando...";
        //             }
        //         }
        //     });
        //     $('#filtro_programa').on('change', function() {
        //         @this.set('filtro_programa', this.value);
        //     });
        //     Livewire.hook('message.processed', (message, component) => {
        //         $('#filtro_programa').select2({
        //             placeholder: 'Seleccione su programa',
        //             allowClear: true,
        //             width: '100%',
        //             selectOnClose: true,
        //             language: {
        //                 noResults: function() {
        //                     return "No se encontraron resultados";
        //                 },
        //                 searching: function() {
        //                     return "Buscando...";
        //                 }
        //             }
        //         });
        //         $('#filtro_programa').on('change', function() {
        //             @this.set('filtro_programa', this.value);
        //         });
        //     });
        // });
        // // filtro_ciclo select2
        // $(document).ready(function() {
        //     $('#filtro_ciclo').select2({
        //         placeholder: 'Seleccione su ciclo',
        //         allowClear: true,
        //         width: '100%',
        //         selectOnClose: true,
        //         language: {
        //             noResults: function() {
        //                 return "No se encontraron resultados";
        //             },
        //             searching: function() {
        //                 return "Buscando...";
        //             }
        //         }
        //     });
        //     $('#filtro_ciclo').on('change', function() {
        //         @this.set('filtro_ciclo', this.value);
        //     });
        //     Livewire.hook('message.processed', (message, component) => {
        //         $('#filtro_ciclo').select2({
        //             placeholder: 'Seleccione su ciclo',
        //             allowClear: true,
        //             width: '100%',
        //             selectOnClose: true,
        //             language: {
        //                 noResults: function() {
        //                     return "No se encontraron resultados";
        //                 },
        //                 searching: function() {
        //                     return "Buscando...";
        //                 }
        //             }
        //         });
        //         $('#filtro_ciclo').on('change', function() {
        //             @this.set('filtro_ciclo', this.value);
        //         });
        //     });
        // });
    </script>
@endpush
