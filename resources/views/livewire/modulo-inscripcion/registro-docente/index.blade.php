<div>
    <div class="row mt-8">
        <div class="col-md-6">
            <div class="alert @if ($paso == 1) bg-primary @else bg-secondary @endif p-5">
                <div class="text-center w-100 @if ($paso == 1) text-light @else text-muted @endif">
                    <span class="fw-bolder fs-3 text-center">
                        Paso 1
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert @if ($paso == 2) bg-primary @else bg-secondary @endif p-5">
                <div class="text-center w-100 @if ($paso == 2) text-light @else text-muted @endif">
                    <span class="fw-bolder fs-3 text-center">
                        Paso 2
                    </span>
                </div>
            </div>
        </div>
    </div>

    <form autocomplete="off" class="mt-0">
        {{-- formulario paso 1 --}}
        @if ($paso === 1)
            <div class="card shadow-sm mt-5">
                <div class="card-header">
                    <h3 class="card-title fw-bold fs-2">
                        Información Personal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="tipo_documento" class="required form-label">
                                Tipo de Documento
                            </label>
                            <select
                                class="form-select @if ($errors->has('tipo_documento')) is-invalid @elseif($tipo_documento) is-valid @endif"
                                wire:model="tipo_documento" id="tipo_documento" data-control="select2"
                                data-placeholder="Seleccione su tipo de documento" data-allow-clear="true">
                                <option></option>
                                @foreach ($tipo_documentos as $item)
                                    <option value="{{ $item->id_tipo_documento }}">{{ $item->tipo_documento }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_documento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Numero de Documento <span class="text-danger">*</span></label>
                            <input type="number"
                                class="form-control @if ($errors->has('documento')) is-invalid @elseif($documento) is-valid @endif"
                                wire:model="documento" placeholder="Ingrese su número de documento">
                            @error('documento')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @if ($errors->has('apellidos')) is-invalid @elseif($apellidos) is-valid @endif"
                                wire:model="apellidos" placeholder="Ingrese sus apellidos">
                            @error('apellidos')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Nombres <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @if ($errors->has('nombres')) is-invalid @elseif($nombres) is-valid @endif"
                                wire:model="nombres" placeholder="Ingrese su nombre">
                            @error('nombres')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email"
                                class="form-control @if ($errors->has('correo')) is-invalid @elseif($correo) is-valid @endif"
                                wire:model="correo" placeholder="Ingrese su correo electrónico">
                            @error('correo')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="grado" class="required form-label">
                                Grado Académico
                            </label>
                            <select
                                class="form-select @if ($errors->has('grado')) is-invalid @elseif($grado) is-valid @endif"
                                wire:model="grado" id="grado" data-control="select2"
                                data-placeholder="Seleccione su grado académico" data-allow-clear="true">
                                <option></option>
                                @foreach ($grados_academicos as $item)
                                    <option value="{{ $item->id_grado_academico }}">{{ $item->grado_academico }}
                                    </option>
                                @endforeach
                            </select>
                            @error('grado')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm mt-10">
                <div class="card-header">
                    <h3 class="card-title fw-bold fs-2">
                        Información de Dirección
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Dirección <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @if ($errors->has('direccion')) is-invalid @elseif($direccion) is-valid @endif"
                                wire:model="direccion" placeholder="Ingrese su direccion de domicilio">
                            @error('direccion')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-8">
                <div></div>
                <button type="button" class="btn btn-primary hover-elevate-down" style="width: 150px"
                    wire:click.prevent="paso_2()">
                    Siguiente
                </button>
            </div>
        @endif
        {{-- formulario paso 2 --}}
        @if ($paso === 2)
            <div class="card shadow-sm mt-5">
                <div class="card-header">
                    <h3 class="card-title fw-bold fs-2">
                        Información Personal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="tipo_docente" class="required form-label">
                                Tipo de Docente
                            </label>
                            <select
                                class="form-select  @if ($errors->has('tipo_docente')) is-invalid @elseif($tipo_docente) is-valid @endif"
                                wire:model="tipo_docente" id="tipo_docente" data-control="select2"
                                data-placeholder="Seleccione el tipo de docente" data-allow-clear="true">
                                <option></option>
                                @foreach ($tipo_docentes as $item)
                                    <option value="{{ $item->id_tipo_docente }}">
                                        {{ $item->tipo_docente }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_docente')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="categoria_docente" class="required form-label">
                                Categoria Docente
                            </label>
                            <select
                                class="form-select @if ($errors->has('categoria_docente')) is-invalid @elseif($categoria_docente) is-valid @endif"
                                wire:model="categoria_docente" id="categoria_docente" data-control="select2"
                                data-placeholder="Seleccione la categoria" data-allow-clear="true">
                                <option></option>
                                @foreach ($categoria_docentes as $item)
                                    {{-- @if ($tipo_docente)
                                        @if ($item->categoria_docente != 'DOCENTE CONTRATADO' && $tipo_docente == 1)
                                            <option value="{{ $item->id_categoria_docente }}">
                                                {{ $item->categoria_docente }}
                                            </option>
                                        @else
                                            @if ($item->categoria_docente == 'DOCENTE CONTRATADO' && $tipo_docente == 2)
                                                <option value="{{ $item->id_categoria_docente }}">
                                                    {{ $item->categoria_docente }}
                                                </option>
                                            @endif
                                            @if ($item->categoria_docente == 'SIN CATEGORIA') --}}
                                                <option value="{{ $item->id_categoria_docente }}">
                                                    {{ $item->categoria_docente }}
                                                </option>
                                            {{-- @endif
                                        @endif
                                    @endif --}}
                                @endforeach
                            </select>
                            @error('categoria_docente')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($tipo_docente == 2)
                            <div class="mb-3 col-md-12">
                                <label for="cv" class="required form-label">
                                    Curriculum Vitae
                                </label>
                                <input type="file"
                                    class="form-control @if ($errors->has('cv')) is-invalid @elseif($cv) is-valid @endif"
                                    wire:model="cv" accept=".pdf">
                                @error('cv')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="alert bg-light-secondary border border-secondary d-flex align-items-center gap-2 p-5 mb-8 mt-8">
                <div class="form-check form-check-custom form-check-solid">
                    <input class="form-check-input @error('declaracion_jurada') is-invalid @enderror" type="checkbox"
                        wire:model="declaracion_jurada" id="declaracion_jurada" style="cursor: pointer" />
                    <label class="fw-bold fs-5 @error('declaracion_jurada') text-danger @enderror ms-5"
                        for="declaracion_jurada" style="cursor: pointer">
                        DECLARO BAJO JURAMENTO QUE LOS DATOS CONSIGNADOS EN EL PRESENTE REGISTRO SON FIDEDIGNOS
                    </label>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-8">
                <button type="button" class="btn btn-secondary hover-elevate-down" style="width: 150px"
                    wire:click.prevent="paso_1()">
                    Regresar
                </button>
                <button type="button" class="btn btn-primary hover-elevate-down" style="width: 150px"
                    wire:click.prevent="guardarRegistro()">
                    Guardar
                </button>
            </div>
        @endif
    </form>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#grado').select2({
                    placeholder: 'Seleccione',
                    allowClear: false,
                    width: '100%',
                    selectOnClose: false,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#grado').on('change', function() {
                    @this.set('grado', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#grado').select2({
                        placeholder: 'Seleccione',
                        allowClear: false,
                        width: '100%',
                        selectOnClose: false,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#grado').on('change', function() {
                        @this.set('grado', this.value);
                    });
                });
            });

            $(document).ready(function() {
                $('#tipo_documento').select2({
                    placeholder: 'Seleccione',
                    allowClear: false,
                    width: '100%',
                    selectOnClose: false,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#tipo_documento').on('change', function() {
                    @this.set('tipo_documento', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#tipo_documento').select2({
                        placeholder: 'Seleccione',
                        allowClear: false,
                        width: '100%',
                        selectOnClose: false,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#tipo_documento').on('change', function() {
                        @this.set('tipo_documento', this.value);
                    });
                });
            });

            $(document).ready(function() {
                $('#tipo_docente').select2({
                    placeholder: 'Seleccione',
                    allowClear: false,
                    width: '100%',
                    selectOnClose: false,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#tipo_docente').on('change', function() {
                    @this.set('tipo_docente', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#tipo_docente').select2({
                        placeholder: 'Seleccione',
                        allowClear: false,
                        width: '100%',
                        selectOnClose: false,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#tipo_docente').on('change', function() {
                        @this.set('tipo_docente', this.value);
                    });
                });
            });

            $(document).ready(function() {
                $('#categoria_docente').select2({
                    placeholder: 'Seleccione',
                    allowClear: false,
                    width: '100%',
                    selectOnClose: false,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#categoria_docente').on('change', function() {
                    @this.set('categoria_docente', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#categoria_docente').select2({
                        placeholder: 'Seleccione',
                        allowClear: false,
                        width: '100%',
                        selectOnClose: false,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#categoria_docente').on('change', function() {
                        @this.set('categoria_docente', this.value);
                    });
                });
            });
        </script>
    @endpush
</div>
