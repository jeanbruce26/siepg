<div>

    <div class="card card-maestria shadow-sm">
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
                <table class="table table-hover table-rounded border gy-4 gs-4 mb-0">
                    <thead class="bg-light-success">
                        <tr align="center" class="fw-bold">
                            <th scope="col" class="col-md-1">ID</th>
                            <th scope="col" class="col-md-9">Username</th>
                            <th scope="col" class="col-md-2">CANTIDAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($programas_maestria as $item)
                        <tr>
                            <td align="center" class="fw-bold fs-5">{{ $loop->iteration }}</td>
                            <td style="white-space: initial" class="fs-5 text-uppercase">
                                @if ($item->mencion === null)
                                    {{ ucwords(strtolower($item->descripcion_programa))  }} en {{ ucwords(strtolower($item->subprograma)) }}
                                @else
                                    Mención en {{ ucwords(strtolower($item->mencion)) }}
                                @endif
                            </td>
                            <td align="center" class="fs-5">{{ $item->cantidad_mencion }}</td>
                        </tr>
                        @endforeach
                        @if ($programas_maestria->count() === 0)
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No hay inscritos en los programas de maestría
                                </td>
                            </tr>
                        @endif --}}
                    </tbody>
                    <tfoot>
                        {{-- <tr class="bg-light-secondary" align="center">
                            <td colspan="2" class="text-end fw-bold fs-6">TOTAL</td>
                            <td class="fw-bold fs-5">{{ $programas_maestria->sum('cantidad_mencion') }}</td>
                        </tr> --}}
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
