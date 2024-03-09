<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Crear Correo
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
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('administrador.gestion-correo') }}" class="text-muted text-hover-primary">
                            Gestión de Correos
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Crear Correos
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid pt-5">
            <div class="card shadow-sm mb-5">
                <div class="card-body">
                    <div class="row g-5">
                        <div class="col-md-12">
                            <span class="fs-2 fw-bold">
                                Crear un nuevo correo
                            </span>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-check-custom mb-3">
                                <input class="form-check-input" type="radio" wire:model="tipo_envio" value="2"
                                    id="envio_masivo" />
                                <label class="form-check-label" for="envio_masivo">
                                    Envio masivo
                                </label>
                            </div>
                            <div class="form-check form-check-custom">
                                <input class="form-check-input" type="radio" wire:model="tipo_envio" value="1"
                                    id="envio_individual" />
                                <label class="form-check-label" for="envio_individual">
                                    Envio individual
                                </label>
                            </div>
                        </div>
                        @if ($tipo_envio == 1)
                            <div class="col-12">
                                <hr>
                                <div class="row g-5">
                                    <div class="col-md-6">
                                        <div class="row g-3">
                                            <div class="col-md-9">
                                                <input
                                                    class="form-control text-muted @error('buscar_dni') is-invalid @enderror"
                                                    type="text" wire:model="buscar_dni"
                                                    placeholder="Buscar persona por dni">
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-primary hover-elevate-up w-100"
                                                    wire:click="buscar_persona">
                                                    Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($persona)
                                        <div class="col-md-12">
                                            <div class="d-flex flex-column gap-3">
                                                <span>
                                                    Persona: <strong>{{ $persona->nombre_completo }}</strong>
                                                </span>
                                                <span>
                                                    DNI: <strong>{{ $persona->numero_documento }}</strong>
                                                </span>
                                                <span>
                                                    Correo Electrónico: <strong>{{ $persona->correo }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if ($tipo_envio == 2)
                            <div class="col-12">
                                <hr>
                                <div class="row g-5">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-custom mb-3">
                                            <input class="form-check-input" type="radio" wire:model="tipo_envio_tabla"
                                                value="1" id="inscripciones" />
                                            <label class="form-check-label" for="inscripciones">
                                                Inscripciones
                                            </label>
                                        </div>
                                        <div class="form-check form-check-custom">
                                            <input class="form-check-input" type="radio" wire:model="tipo_envio_tabla"
                                                value="2" id="admitidos" />
                                            <label class="form-check-label" for="admitidos">
                                                Admitidos
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Proceso de Admisión</label>
                                        <select class="form-select" wire:model="proceso" id="proceso_filtro">
                                            <option value="">
                                                Seleccione el Proceso
                                            </option>
                                            @foreach ($procesos as $item)
                                                <option value="{{ $item->id_admision }}">
                                                    {{ $item->admision }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Modalidad del Programa</label>
                                        <select class="form-select" wire:model="modalidad" id="modalidad_filtro">
                                            <option value="">
                                                Seleccione la Modalidad
                                            </option>
                                            @foreach ($modalidades as $item)
                                                <option value="{{ $item->id_modalidad }}">
                                                    {{ $item->modalidad }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Programa</label>
                                        <select class="form-select" wire:model="programa" id="programa_filtro">
                                            <option>
                                                Seleccione el Programa
                                            </option>
                                            @foreach ($programas as $item)
                                                <option value="{{ $item->id_programa }}">
                                                    {{ $item->programa }} EN {{ $item->subprograma }}
                                                    @if ($item->mencion)
                                                        CON MENCION EN {{ $item->mencion }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        Cantidad de correos que se enviará: {{ $cantidad_correos }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body mb-0">
                    <div class="row g-5">
                        <div class="col-12">
                            <label for="asunto" class="form-label required">
                                Asunto
                            </label>
                            <input wire:model="asunto" type="text" id="asunto"
                                class="form-control @error('asunto') is-invalid @enderror"
                                placeholder="Ingrese el asunto del correo">
                            @error('asunto')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12" wire:ignore>
                            <label for="mensaje" class="form-label required">
                                Mensaje
                            </label>
                            <textarea class="form-control @error('mensaje') is-invalid @enderror" wire:model="mensaje" rows="10"
                                id="mensaje" placeholder="Ingrese el mensaje del correo">
                            </textarea>
                            @error('mensaje')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success hover-elevate-up w-100" wire:click="enviar_correo">
                                Enviar Correo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#mensaje'))
                .then(editor => {
                    editor.model.document.on('change:data', () => {
                        @this.set('mensaje', editor.getData());
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
    @endpush
</div>
