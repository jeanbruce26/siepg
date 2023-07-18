<div>
    <div class="row mt-8">
        <div class="col-md-6">
            <div class="alert @if($paso == 1) bg-primary @else bg-secondary @endif p-5">
                <div class="text-center w-100 @if($paso == 1) text-light @else text-muted @endif">
                    <span class="fw-bolder fs-3 text-center">
                        Paso 1
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert @if($paso == 2) bg-primary @else bg-secondary @endif p-5">
                <div class="text-center w-100 @if($paso == 2) text-light @else text-muted @endif">
                    <span class="fw-bolder fs-3 text-center">
                        Paso 2
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- formulario --}}
    <form autocomplete="off" class="mt-0">
        {{-- formulario paso 1 --}}
        @if ($paso === 1)
            <div class="card shadow-sm mt-5">
                <div class="card-header">
                    <h3 class="card-title fw-bold fs-2">
                        Selección de Modalidad y Programa
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="admision" class="required form-label">
                                    Proceso de Admisión
                                </label>
                                <select wire:model="admision" class="form-select @error('admision') is-invalid @enderror" id="admision" data-control="select2" data-placeholder="Seleccione su proceso de admision" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($admision_model as $item)
                                    <option value="{{ $item->id_admision }}">{{ $item->admision }}</option>
                                    @endforeach
                                </select>
                                <div class="mt-1 text-muted">
                                    <strong>Nota: </strong>El año del proceso que ingresó.
                                </div>
                                @error('admision')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="modalidad" class="required form-label">
                                    Modalidad
                                </label>
                                <select wire:model="modalidad" class="form-select @error('modalidad') is-invalid @enderror" id="modalidad" data-control="select2" data-placeholder="Seleccione su modalidad" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($modalidad_model as $item)
                                    <option value="{{ $item->id_modalidad }}">{{ $item->modalidad }}</option>
                                    @endforeach
                                </select>
                                @error('modalidad')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="programa" class="required form-label">
                                    Programa
                                </label>
                                <select wire:model="programa" class="form-select @error('programa') is-invalid @enderror" id="programa" data-control="select2" data-placeholder="Seleccione su programa">
                                    <option></option>
                                    @if ($modalidad && $admision)
                                        @foreach ($programas_model as $item)
                                            <option value="{{ $item->id_programa_proceso }}">
                                                {{ $item->programa_plan->programa->sede->sede }} /
                                                {{ $item->programa_plan->programa->programa }} /
                                                {{ $item->programa_plan->programa->subprograma }}
                                                @if($item->programa_plan->programa->mencion) / {{ $item->programa_plan->programa->mencion }} @endif
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('programa')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-8">
                <div></div>
                <button type="button" class="btn btn-primary hover-elevate-down" style="width: 150px" wire:click.prevent="paso_2()">
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
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="numero_documento" class="required form-label">
                                    Número de Documento
                                </label>
                                <input type="text" wire:model="numero_documento" class="form-control @error('numero_documento') is-invalid @enderror" id="numero_documento" placeholder="Ingrese su número de documento">
                                @error('numero_documento')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="apellido_paterno" class="required form-label">
                                    Apellido Paterno
                                </label>
                                <input type="text" wire:model="apellido_paterno" class="form-control @error('apellido_paterno') is-invalid @enderror" id="apellido_paterno" placeholder="Ingrese su apellido paterno">
                                @error('apellido_paterno')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="apellido_materno" class="required form-label">
                                    Apellidos Materno
                                </label>
                                <input type="text" wire:model="apellido_materno" class="form-control @error('apellido_materno') is-invalid @enderror" id="apellido_materno" placeholder="Ingrese su apellido apellido_materno">
                                @error('apellido_materno')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="nombre" class="required form-label">
                                    Nombres
                                </label>
                                <input type="text" wire:model="nombre" class="form-control @error('nombre') is-invalid @enderror" id="nombre" placeholder="Ingrese sus nombre">
                                @error('nombre')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="fecha_nacimiento" class="required form-label">
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" wire:model="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento">
                                @error('fecha_nacimiento')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="genero" class="required form-label">
                                    Genero
                                </label>
                                <select wire:model="genero" class="form-select @error('genero') is-invalid @enderror" id="genero" data-control="select2" data-placeholder="Seleccione su genero" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($genero_model as $item)
                                    <option value="{{ $item->id_genero }}">{{ $item->genero }}</option>
                                    @endforeach
                                </select>
                                @error('genero')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="estado_civil" class="required form-label">
                                    Estado Civil
                                </label>
                                <select wire:model="estado_civil" class="form-select @error('estado_civil') is-invalid @enderror" id="estado_civil" data-control="select2" data-placeholder="Seleccione su estado civil" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($estado_civil_model as $item)
                                    <option value="{{ $item->id_estado_civil }}">{{ $item->estado_civil }}</option>
                                    @endforeach
                                </select>
                                @error('estado_civil')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="discapacidad" class="required form-label">
                                    Discapacidad
                                </label>
                                <select wire:model="discapacidad" class="form-select @error('discapacidad') is-invalid @enderror" id="discapacidad"  data-control="select2" data-placeholder="Seleccione su discapacidad" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($discapacidad_model as $item)
                                    <option value="{{ $item->id_discapacidad }}">{{ $item->discapacidad }}</option>
                                    @endforeach
                                </select>
                                @error('discapacidad')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="celular" class="required form-label">
                                    Número de Celular
                                </label>
                                <input type="number" wire:model="celular" class="form-control @error('celular') is-invalid @enderror" id="celular" placeholder="Ingrese su número de celular">
                                @error('celular')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="celular_opcional" class="form-label">
                                    Número de Celular Opcional
                                </label>
                                <input type="number" wire:model="celular_opcional" class="form-control @error('celular_opcional') is-invalid @enderror" id="celular_opcional" placeholder="Ingrese su número de celular opcional">
                                @error('celular_opcional')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="correo" class="required form-label">
                                    Correo Electrónico
                                </label>
                                <input type="email" wire:model="correo" class="form-control @error('correo') is-invalid @enderror" id="correo" placeholder="Ingrese su correo electrónico">
                                @error('correo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="correo_opcional" class="form-label">
                                    Correo Electrónico Opcional
                                </label>
                                <input type="email" wire:model="correo_opcional" class="form-control @error('correo_opcional') is-invalid @enderror" id="correo_opcional" placeholder="Ingrese su correo electrónico opcional">
                                @error('correo_opcional')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm mt-10">
                <div class="card-header">
                    <h3 class="card-title fw-bold fs-2">
                        Información de Dirección y Lugar de Nacimiento
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-5">
                        <span class="fw-bold fs-3">
                            Datos de Dirección
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="ubigeo_direccion" class="required form-label">
                                    Ubigeo de Dirección
                                </label>
                                <select wire:model="ubigeo_direccion" class="form-select @error('ubigeo_direccion') is-invalid @enderror" id="ubigeo_direccion" data-control="select2" data-placeholder="Seleccione su ubigeo de direccion" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($ubigeo_model as $item)
                                    <option value="{{ $item->id_ubigeo }}">{{ $item->ubigeo }} / {{ $item->departamento }} / {{ $item->provincia }} / {{ $item->distrito }}</option>
                                    @endforeach
                                </select>
                                @error('ubigeo_direccion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if ($pais_direccion_estado === true && $ubigeo_direccion)
                            <div class="col-md-12">
                                <div class="mb-5">
                                    <label for="pais_direccion" class="required form-label">
                                        País de Dirección
                                    </label>
                                    <input type="text" wire:model="pais_direccion" class="form-control @error('pais_direccion') is-invalid @enderror" id="pais_direccion" placeholder="Ingrese el pais del ubigeo seleccionado">
                                    @error('pais_direccion')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="direccion" class="required form-label">
                                    Dirección
                                </label>
                                <input type="text" wire:model="direccion" class="form-control @error('direccion') is-invalid @enderror" id="direccion" placeholder="Ingrese su dirección">
                                @error('direccion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <span class="fw-bold fs-3">
                            Datos de Nacimiento
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="ubigeo_nacimiento" class="required form-label">
                                    Ubigeo de Nacimiento
                                </label>
                                <select wire:model="ubigeo_nacimiento" class="form-select @error('ubigeo_nacimiento') is-invalid @enderror" id="ubigeo_nacimiento" data-control="select2" data-placeholder="Seleccione su ubigeo de nacimiento" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($ubigeo_model as $item)
                                    <option value="{{ $item->id_ubigeo }}">{{ $item->ubigeo }} / {{ $item->departamento }} / {{ $item->provincia }} / {{ $item->distrito }}</option>
                                    @endforeach
                                </select>
                                @error('ubigeo_nacimiento')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @if ($pais_nacimiento_estado === true && $ubigeo_nacimiento)
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="pais_nacimiento" class="required form-label">
                                    Pais de Nacimiento
                                </label>
                                <input type="text" wire:model="pais_nacimiento" class="form-control @error('pais_nacimiento') is-invalid @enderror" id="pais_nacimiento" placeholder="Ingrese su pais de nacimiento">
                                @error('pais_nacimiento')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card shadow-sm mt-10">
                <div class="card-header">
                    <h3 class="card-title fw-bold fs-2">
                        Información de Grado Académico, Universidad y Experiencia Laboral
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="grado_academico" class="required form-label">
                                    Grado Académico o Titulo
                                </label>
                                <select wire:model="grado_academico" class="form-select @error('grado_academico') is-invalid @enderror" id="grado_academico"  data-control="select2" data-placeholder="Seleccione su grado academico" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($grado_academico_model as $item)
                                        <option value="{{ $item->id_grado_academico }}">{{ $item->grado_academico }}</option>
                                    @endforeach
                                </select>
                                @error('grado_academico')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="especialidad" class="required form-label">
                                    Especialidad de Carrera
                                </label>
                                <input type="text" wire:model="especialidad" class="form-control @error('especialidad') is-invalid @enderror" id="especialidad" placeholder="Ingrese la especialidad de su carrera">
                                @error('especialidad')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="año_egreso" class="required form-label">
                                    Año de Egreso de la Universidad o Maestría
                                </label>
                                <input type="number" wire:model="año_egreso" class="form-control @error('año_egreso') is-invalid @enderror" id="año_egreso" placeholder="Ingrese su año egreso">
                                @error('año_egreso')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-5">
                                <label for="universidad" class="required form-label">
                                    Universidad de Egreso
                                </label>
                                <select wire:model="universidad" class="form-select @error('universidad') is-invalid @enderror" id="universidad" data-control="select2" data-placeholder="Seleccione su universidad" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($universidad_model as $item)
                                        <option value="{{ $item->id_universidad }}">{{ $item->universidad }}</option>
                                    @endforeach
                                </select>
                                @error('universidad')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="centro_trabajo" class="required form-label">
                                    Centro de Trabajo
                                </label>
                                <input type="text" wire:model="centro_trabajo" class="form-control @error('centro_trabajo') is-invalid @enderror" id="centro_trabajo" placeholder="Ingrese su centro de trabajo">
                                @error('centro_trabajo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between mt-8">
                <button type="button" class="btn btn-secondary hover-elevate-down" style="width: 150px" wire:click.prevent="paso_1()">
                    Regresar
                </button>
                <button type="button" class="btn btn-primary hover-elevate-down" style="width: 150px" wire:click.prevent="guardarRegistro()">
                    Guardar
                </button>
            </div>
        @endif
</div>

@push('scripts')
    <script>
        //Paso 1
        // admision select2
        $(document).ready(function () {
            $('#admision').select2({
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
            $('#admision').on('change', function(){
                @this.set('admision', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#admision').select2({
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
                $('#admision').on('change', function(){
                    @this.set('admision', this.value);
                });
            });
        });
        // modalidad select2
        $(document).ready(function () {
            $('#modalidad').select2({
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
            $('#modalidad').on('change', function(){
                @this.set('modalidad', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#modalidad').select2({
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
                $('#modalidad').on('change', function(){
                    @this.set('modalidad', this.value);
                });
            });
        });
        // programa select2
        $(document).ready(function () {
            $('#programa').select2({
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
            $('#programa').on('change', function(){
                @this.set('programa', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#programa').select2({
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
                $('#programa').on('change', function(){
                    @this.set('programa', this.value);
                });
            });
        });

        //Paso 2
        // genero select2
        $(document).ready(function () {
            $('#genero').select2({
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
            $('#genero').on('change', function(){
                @this.set('genero', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#genero').select2({
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
                $('#genero').on('change', function(){
                    @this.set('genero', this.value);
                });
            });
        });
        // estado_civil select2
        $(document).ready(function () {
            $('#estado_civil').select2({
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
            $('#estado_civil').on('change', function(){
                @this.set('estado_civil', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#estado_civil').select2({
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
                $('#estado_civil').on('change', function(){
                    @this.set('estado_civil', this.value);
                });
            });
        });
        // discapacidad select2
        $(document).ready(function () {
            $('#discapacidad').select2({
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
            $('#discapacidad').on('change', function(){
                @this.set('discapacidad', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#discapacidad').select2({
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
                $('#discapacidad').on('change', function(){
                    @this.set('discapacidad', this.value);
                });
            });
        });
        // ubigeo_direccion select2
        $(document).ready(function () {
            $('#ubigeo_direccion').select2({
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
            $('#ubigeo_direccion').on('change', function(){
                @this.set('ubigeo_direccion', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#ubigeo_direccion').select2({
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
                $('#ubigeo_direccion').on('change', function(){
                    @this.set('ubigeo_direccion', this.value);
                });
            });
        });
        // ubigeo_nacimiento select2
        $(document).ready(function () {
            $('#ubigeo_nacimiento').select2({
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
            $('#ubigeo_nacimiento').on('change', function(){
                @this.set('ubigeo_nacimiento', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#ubigeo_nacimiento').select2({
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
                $('#ubigeo_nacimiento').on('change', function(){
                    @this.set('ubigeo_nacimiento', this.value);
                });
            });
        });
        // grado_academico select2
        $(document).ready(function () {
            $('#grado_academico').select2({
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
            $('#grado_academico').on('change', function(){
                @this.set('grado_academico', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#grado_academico').select2({
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
                $('#grado_academico').on('change', function(){
                    @this.set('grado_academico', this.value);
                });
            });
        });
        // universidad select2
        $(document).ready(function () {
            $('#universidad').select2({
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
            $('#universidad').on('change', function(){
                @this.set('universidad', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#universidad').select2({
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
                $('#universidad').on('change', function(){
                    @this.set('universidad', this.value);
                });
            });
        });
    </script>
@endpush