<div>
    {{-- alerta --}}
    <div class="alert bg-light-primary border border-primary d-flex align-items-center gap-2 p-5">
        <span class="svg-icon svg-icon-2hx svg-icon-primary me-3">
            <i class="fa-sharp fa-solid fa-circle-info fs-1 text-primary"></i>
        </span>
        <div class="d-flex flex-column">
            <h4 class="mb-1 text-dark">
                Nota
            </h4>
            <span class="fw-mediun">
                Al terminar con el registro de sus datos espere un momento, ya que se estará generando su ficha de inscripción.
            </span>
        </div>
    </div>
    {{-- pasos de inscripcion --}}
    <div class="row mt-8">
        <div class="col-md-4">
            <div class="alert @if($paso == 1) bg-primary @else bg-secondary @endif p-5">
                <div class="text-center w-100 @if($paso == 1) text-light @else text-muted @endif">
                    <span class="fw-bolder fs-3 text-center">
                        Paso 1
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert @if($paso == 2) bg-primary @else bg-secondary @endif p-5">
                <div class="text-center w-100 @if($paso == 2) text-light @else text-muted @endif">
                    <span class="fw-bolder fs-3 text-center">
                        Paso 2
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="alert @if($paso == 3) bg-primary @else bg-secondary @endif p-5">
                <div class="text-center w-100 @if($paso == 3) text-light @else text-muted @endif">
                    <span class="fw-bolder fs-3 text-center">
                        Paso 3
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
                        Selección de Programa
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-5">
                                <label for="sede" class="required form-label">
                                    Sede
                                </label>
                                <select wire:model="sede" class="form-select @error('sede') is-invalid @enderror" id="sede">
                                    <option>Seleccione la sede</option>
                                    <option value="1">PUCALLPA</option>
                                    <option value="2">OTRO LUGAR</option>
                                </select>
                                @error('sede')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="programa" class="required form-label">
                                    Programa
                                </label>
                                <select wire:model="programa" class="form-select @error('programa') is-invalid @enderror" id="programa">
                                    <option>Seleccione el programa</option>
                                    <option value="1">Maestria</option>
                                    <option value="2">Doctorado</option>
                                </select>
                                @error('programa')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="subprograma" class="required form-label">
                                    Subprograma
                                </label>
                                <select wire:model="subprograma" class="form-select @error('subprograma') is-invalid @enderror" id="subprograma">
                                    <option>Seleccione el subprograma</option>
                                    <option value="1">Maestria</option>
                                    <option value="2">Doctorado</option>
                                </select>
                                @error('subprograma')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="mencion" class="required form-label">
                                    Mención
                                </label>
                                <select wire:model="mencion" class="form-select @error('mencion') is-invalid @enderror" id="mencion">
                                    <option>Seleccione la mencion</option>
                                    <option value="1">Maestria</option>
                                    <option value="2">Doctorado</option>
                                </select>
                                @error('mencion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- botones --}}
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
                                <label for="paterno" class="required form-label">
                                    Apellidos Paterno
                                </label>
                                <input type="text" wire:model="paterno" class="form-control @error('paterno') is-invalid @enderror" id="paterno" placeholder="Ingrese su apellido paterno">
                                @error('paterno')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="materno" class="required form-label">
                                    Apellidos Materno
                                </label>
                                <input type="text" wire:model="materno" class="form-control @error('materno') is-invalid @enderror" id="materno" placeholder="Ingrese su apellido materno">
                                @error('materno')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="nombres" class="required form-label">
                                    Nombres
                                </label>
                                <input type="text" wire:model="nombres" class="form-control @error('nombres') is-invalid @enderror" id="nombres" placeholder="Ingrese sus nombres">
                                @error('nombres')
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
                                <select wire:model="genero" class="form-select @error('genero') is-invalid @enderror" id="genero">
                                    <option>Seleccione el genero</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
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
                                <select wire:model="estado_civil" class="form-select @error('genero') is-invalid @enderror" id="estado_civil">
                                    <option>Seleccione el genero</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
                                </select>
                                @error('estado_civil')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="discapacidad" class="form-label">
                                    Discapacidad
                                </label>
                                <select wire:model="discapacidad" class="form-select @error('discapacidad') is-invalid @enderror" id="discapacidad">
                                    <option>Seleccione el genero</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Femenino</option>
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
                                <label for="email" class="required form-label">
                                    Correo Electrónico
                                </label>
                                <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Ingrese su correo electrónico">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="email_opcional" class="form-label">
                                    Correo Electrónico Opcional
                                </label>
                                <input type="email" wire:model="email_opcional" class="form-control @error('email_opcional') is-invalid @enderror" id="email_opcional" placeholder="Ingrese su correo electrónico opcional">
                                @error('email_opcional')
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
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="departamento_direccion" class="required form-label">
                                    Departamento
                                </label>
                                <select wire:model="departamento_direccion" class="form-select @error('departamento_direccion') is-invalid @enderror" id="departamento_direccion">
                                    <option>Seleccione el departamento</option>
                                    {{-- @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                    @endforeach --}}
                                    <option value="1">Ucayali</option>
                                </select>
                                @error('departamento_direccion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="provincia_direccion" class="required form-label">
                                    Provincia
                                </label>
                                <select wire:model="provincia_direccion" class="form-select @error('provincia_direccion') is-invalid @enderror" id="provincia_direccion">
                                    <option>Seleccione la provincia</option>
                                    {{-- @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                    @endforeach --}}
                                    <option value="1">Ucayali</option>
                                </select>
                                @error('provincia_direccion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="distrito_direccion" class="required form-label">
                                    Distrito
                                </label>
                                <select wire:model="distrito_direccion" class="form-select @error('distrito_direccion') is-invalid @enderror" id="distrito_direccion">
                                    <option>Seleccione el distrito</option>
                                    {{-- @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                    @endforeach --}}
                                    <option value="1">Ucayali</option>
                                </select>
                                @error('distrito_direccion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
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
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="departamento_nacimiento" class="required form-label">
                                    Departamento
                                </label>
                                <select wire:model="departamento_nacimiento" class="form-select @error('departamento_nacimiento') is-invalid @enderror" id="departamento_nacimiento">
                                    <option>Seleccione el departamento</option>
                                    {{-- @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                    @endforeach --}}
                                    <option value="1">Ucayali</option>
                                </select>
                                @error('departamento_nacimiento')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="provincia_nacimiento" class="required form-label">
                                    Provincia
                                </label>
                                <select wire:model="provincia_nacimiento" class="form-select @error('provincia_nacimiento') is-invalid @enderror" id="provincia_nacimiento">
                                    <option>Seleccione la provincia</option>
                                    {{-- @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                    @endforeach --}}
                                    <option value="1">Ucayali</option>
                                </select>
                                @error('provincia_nacimiento')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="distrito_nacimiento" class="required form-label">
                                    Distrito
                                </label>
                                <select wire:model="distrito_nacimiento" class="form-select @error('distrito_nacimiento') is-invalid @enderror" id="distrito_nacimiento">
                                    <option>Seleccione el distrito</option>
                                    {{-- @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                    @endforeach --}}
                                    <option value="1">Ucayali</option>
                                </select>
                                @error('distrito_nacimiento')
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
                                <select wire:model="grado_academico" class="form-select @error('grado_academico') is-invalid @enderror" id="grado_academico">
                                    <option>Seleccione el grado académico</option>
                                    {{-- @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                                    @endforeach --}}
                                    <option value="1">Ucayali</option>
                                </select>
                                @error('grado_academico')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-5">
                                <label for="especialidad_carrera" class="required form-label">
                                    Especialidad de Carrera
                                </label>
                                <input type="text" wire:model="especialidad_carrera" class="form-control @error('especialidad_carrera') is-invalid @enderror" id="especialidad_carrera" placeholder="Ingrese la especialidad de su carrera">
                                @error('especialidad_carrera')
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
                                    @foreach ($universidades as $item)
                                        <option value="{{ $item->cod_uni }}">{{ $item->universidad }}</option>
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
            {{-- botones --}}
            <div class="d-flex justify-content-between mt-8">
                <button type="button" class="btn btn-secondary hover-elevate-down" style="width: 150px" wire:click.prevent="paso_1()">
                    Regresar
                </button>
                <button type="button" class="btn btn-primary hover-elevate-down" style="width: 150px" wire:click.prevent="paso_3()">
                    Siguiente
                </button>
            </div>
        @endif
        {{-- formulario paso 3 --}}
        @if ($paso === 3)
            {{-- alerta --}}
            <div class="alert bg-light-danger border border-danger d-flex align-items-center gap-2 p-5 mb-8 mt-3">
                <span class="svg-icon svg-icon-2hx svg-icon-danger me-3">
                    <div class="form-check form-check-custom form-check-danger">
                        <input class="form-check-input" type="checkbox" wire:model="check_expediente" />
                    </div>
                </span>
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-dark">
                        ¡Importante! - La casilla debe ser marcada sólo si no se cumple con el requisito de constancia de registro de la SUNEDU.
                    </h4>
                    <span class="fw-mediun">
                        En caso de no disponer de mi constancia de registro de la SUNEDU, presentaré un documento que acredite que se encuentra en trámite (resolución de grado, grado academico, entre otros).
                    </span>
                </div>
            </div>
            <div class="card shadow-sm mt-5">
                <div class="card-header">
                    <h3 class="card-title fw-bold fs-2">
                        Ingreso de Expedientes
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert bg-light-danger d-flex flex-column flex-sm-row p-5 mb-8">
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <span class="fw-bold">
                                Todos los documentos deben ser en formato <strong>PDF</strong>. (cualquier otro formato de archivo no es aceptado/compatible)
                            </span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle table-rounded table-hover border gy-5 gs-5 gx-5">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                                    <th colspan="3" class="fw-bold fs-4">Documentos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Nombre de expediente 1
                                    </td>
                                    <td align="center" class="col-md-2">
                                        <span class="badge badge-primary">Enviado</span>
                                    </td>
                                    <td align="center" class="col-md-2">
                                        <a href="#" class="btn btn-primary btn-sm">Subir</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Nombre de expediente 2
                                    </td>
                                    <td align="center" class="col-md-2">
                                        <span class="badge badge-danger">No enviado</span>
                                    </td>
                                    <td align="center" class="col-md-2">
                                        <a href="#" class="btn btn-primary btn-sm">Subir</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- declaracion jurada --}}
            <div class="alert bg-light-secondary border border-secondary d-flex align-items-center gap-2 p-5 mb-8 mt-8">
                <span class="svg-icon svg-icon-2hx svg-icon-secondary me-3">
                    <div class="form-check form-check-custom form-check-secondary">
                        <input class="form-check-input" type="checkbox" wire:model="declaracion_jurada" />
                    </div>
                </span>
                <div class="d-flex flex-column">
                    <span class="fw-bold fs-5">
                        DECLARO BAJO JURAMENTO QUE LOS DOCUMENTOS PRESENTADOS Y LOS DATOS CONSIGNADOS EN EL PRESENTE PROCESO DE ADMISIÓN SON FIDEDIGNOS
                    </span>
                </div>
            </div>
            {{-- botones --}}
            <div class="d-flex justify-content-between mt-8">
                <button type="button" class="btn btn-secondary hover-elevate-down" style="width: 150px" wire:click.prevent="paso_2()">
                    Regresar
                </button>
                <button type="submit" class="btn btn-primary hover-elevate-down" style="width: 150px">
                    Registrar
                </button>
            </div>
        @endif
    </form>
</div>

@push('scripts')
    <script>
        // universidad select2
        $(document).ready(function () {
            $('#universidad').select2({
                placeholder: 'Seleccione universidad',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando..";
                    }
                }
            });
            $('#universidad').on('change', function(){
                @this.set('universidad', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#universidad').select2({
                    placeholder: 'Seleccione universidad',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando..";
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
