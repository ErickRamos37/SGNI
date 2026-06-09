@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    {{-- ─── Encabezado de página ─────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Grupos Generados</h2>
            <p class="text-muted mb-0">Listado de todos los grupos creados para Propedéutico e Inducción</p>
        </div>
    </div>

    {{-- TABS CON ESTILO OUTLINE DARK (EFECTO HOVER) --}}
    <div class="mb-4">
        <div class="d-inline-flex rounded-pill border bg-white shadow-sm p-1 gap-1">
            
            <a href="{{ route('curso_prope_creado') }}"
                class="btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-outline-dark border-0 text-decoration-none">
                Propedéutico
            </a>
            
            <a href="{{ route('curso_induc_creado') }}"
                class="btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-outline-dark text-decoration-none active">
                Inducción
            </a>
            
        </div>
    </div>

    {{-- SELECTOR DE PERIODO (HISTORIAL) --}}
    @if($periodos->count() > 0)
        <div class="card border border-light-subtle shadow-sm rounded-3 mb-4 bg-light">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-history fs-4 text-primary me-3"></i>
                    <div>
                        <span class="fw-bold text-dark">Historial de Grupos Generados:</span>
                        <span class="text-muted small ms-1">Selecciona el ciclo escolar para ver sus grupos</span>
                    </div>
                </div>
                <select id="selectorPeriodoInducCreado" class="form-select w-auto fw-bold shadow-sm">
                    @foreach($periodos as $p)
                        <option value="{{ $p }}" @if($p == $periodoActual) selected @endif>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <script>
            document.getElementById('selectorPeriodoInducCreado').addEventListener('change', function() {
                window.location.href = '?periodo=' + this.value;
            });
        </script>
    @endif

    {{-- ========================================== --}}
    {{-- TABLA: GRUPOS GENERALES DE INDUCCIÓN       --}}
    {{-- ========================================== --}}
    <div class="card border border-light-subtle shadow-sm rounded-3">

        <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
            <h5 class="fw-bold text-uppercase text-white mb-0">Grupos Generales (Inducción)</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tablaGruposInduc" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light text-muted small text-uppercase text-start">
                        <tr>
                            <th class="px-4 py-3">Nombre del Grupo</th>
                            <th class="py-3">Programa</th>
                            <th class="py-3">Turno</th>
                            <th class="py-3">Total Estudiantes</th>
                            <th class="py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="small text-center">
                        @forelse($gruposInduc as $grupo)
                        <tr>
                            <td class="px-4 fw-bold text-dark text-start">{{ $grupo->nombre_grupo }}</td>
                            <td class="text-dark">
                                <span class="d-flex align-items-center gap-2 fw-semibold">
                                    <i class="bi bi-diagram-3-fill text-muted"></i> Tronco Común General
                                </span>
                            </td>
                            <td>
                                @if($grupo->turno && strtolower($grupo->turno->tipo_turno) == 'matutino')
                                    <span class="badge rounded-pill bg-secondary text-dark px-3 py-2 fw-semibold">Matutino</span>
                                @else
                                    <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-semibold">Vespertino</span>
                                @endif
                            </td>
                            <td class="text-dark">
                                <span class="d-flex align-items-center gap-2">
                                    <i class="bi bi-people text-muted"></i>
                                    <span class="fw-bold">{{ $grupo->alumnos_count }}</span>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    {{-- Botón Lista --}}
                                    <a href="{{ route('lista_grupo', $grupo->id_grupo) }}" 
                                       class="btn btn-outline-dark btn-sm rounded-3 d-inline-flex align-items-center justify-content-center shadow-sm" 
                                       style="width: 32px; height: 32px;"
                                       title="Ver Lista"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Botón Seguridad --}}
                                    @php
                                        $estadoNombre = \DB::table('estado_grupo')->where('id_estado', $grupo->id_estado)->value('nombre_estado') ?? 'Editable';
                                        $esLectura = strtolower($estadoNombre) === 'lectura';
                                    @endphp

                                    @if(Auth::user()->rol->nombre_rol === 'Administrador')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-dark rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center" 
                                                style="width: 32px; height: 32px;" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEstado{{ $grupo->id_grupo }}"
                                                title="{{ $esLectura ? 'Habilitar Edición' : 'Bloquear (Modo Lectura)' }}"
                                                data-bs-placement="top">
                                            <i class="bi {{ $esLectura ? 'bi-lock-fill' : 'bi-unlock-fill' }}"></i>
                                        </button>

                                        <!-- Modal de Confirmación de Cambio de Estado -->
                                        <div class="modal fade text-start" id="modalEstado{{ $grupo->id_grupo }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-4">
                                                    <div class="modal-body p-4 text-center text-dark">
                                                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 85px; height: 85px;">
                                                            <i class="bi {{ $esLectura ? 'bi-unlock-fill' : 'bi-lock-fill' }} display-5 text-primary"></i>
                                                        </div>
                                                        <h3 class="fw-bold text-uppercase mb-2 text-dark">
                                                            {{ $esLectura ? '¿Habilitar Edición?' : '¿Activar Modo Lectura?' }}
                                                        </h3>
                                                        <p class="text-muted fw-semibold mb-4">
                                                            {{ $esLectura ? 'Los profesores podrán volver a modificar las calificaciones y asistencias de este grupo.' : 'Los profesores solo podrán visualizar la información pero no modificarla. Se bloquearán las capturas.' }}
                                                        </p>
                                                        <form action="{{ route('grupos.cambiar_estado', $grupo->id_grupo) }}" method="POST">
                                                            @csrf
                                                            <div class="d-flex justify-content-center gap-2">
                                                                <button type="button" class="btn btn-outline-dark px-4 fw-semibold rounded-3" data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-primary px-4 fw-semibold rounded-3">Confirmar Cambio</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="d-inline-flex align-items-center justify-content-center text-secondary" 
                                              style="width: 32px; height: 32px;"
                                              title="{{ $esLectura ? 'Grupo en modo lectura (Bloqueado)' : 'Grupo en modo editable' }}"
                                              data-bs-toggle="tooltip"
                                              data-bs-placement="top">
                                            <i class="bi {{ $esLectura ? 'bi-lock-fill text-muted fs-5' : 'bi-unlock-fill text-muted fs-5' }}"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2 text-light-subtle"></i>
                                No se han generado grupos de Inducción aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- /card --}}

</div>

<script type="module">
    $(document).ready(function() {
        const opcionesBase = {
            searching: false, // Desactiva el buscador
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            columnDefs: [
                { orderable: false, targets: [1, 4] } // Desactiva ordenamiento en 'Programa' y 'Acciones'
            ],
            dom: '<"d-flex flex-wrap justify-content-between align-items-center"l>rt<"d-flex flex-wrap justify-content-between align-items-center mt-2"ip>'
        };

        if ($('#tablaGruposInduc tbody tr').length > 0 && !$('#tablaGruposInduc tbody tr td[colspan]').length) {
            $('#tablaGruposInduc').DataTable(opcionesBase);
        }
    });
</script>
@endsection