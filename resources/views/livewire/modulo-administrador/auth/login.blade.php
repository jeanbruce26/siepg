<div class="card rounded-3 w-md-500px">
    <div class="card-body d-flex flex-column p-10 p-lg-20 pb-lg-10">
        <div class="d-flex flex-center flex-column-fluid pb-5 pb-lg-15">
            <form class="form w-100" wire:submit.prevent="ingresar" novalidate>
                <div class="text-center mb-11">
                    <h1 class="text-dark fw-bolder mb-3">
                        Sistema Integrado<br>Escuela de Posgrado
                    </h1>
                    <div class="text-gray-500 fw-semibold fs-4">
                        Iniciar sesión
                    </div>
                </div>
                <div class="fv-row mb-5">
                    <label for="email" class="required form-label">
                        Correo electrónico
                    </label>
                    <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Ingrese su correo electrónico" autocomplete="off"/>
                    @error('email')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="fv-row mb-5">
                    <label for="password" class="required form-label">
                        Contraseña
                    </label>
                    <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Ingrese su contraseña" autocomplete="off"/>
                    @error('password')
                        <span class="text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                @if (session()->has('message'))
                    <div class="mt-5">
                        <div class="alert alert-danger d-flex align-items-center p-5">
                            <span class="fw-bold text-center">
                                {{ session('message') }}
                            </span>
                        </div>
                    </div>
                @endif
                <div class="mt-5">
                    <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                        <div wire:loading.remove wire:target="ingresar">
                            Ingresar
                        </div>
                        <div wire:loading wire:target="ingresar">
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
