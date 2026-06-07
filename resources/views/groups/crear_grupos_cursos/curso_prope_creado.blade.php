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
                class="btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-outline-dark text-decoration-none active">
                Propedéutico
            </a>
            
            <a href="{{ route('curso_induc_creado') }}"
                class="btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-outline-dark border-0 text-decoration-none">
                Inducción
            </a>
            
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TABLA 1: GRUPOS DE INGENIERÍA              --}}
    {{-- ========================================== --}}
    <div class="card border border-light-subtle shadow-sm rounded-3 mb-5">

        <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
            <h5 class="fw-bold text-uppercase text-white mb-0">Grupos de Ingeniería</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tablaGruposInge" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light text-muted small text-uppercase text-start">
                        <tr>
                            <th class="px-4 py-3">Nombre del Grupo</th>
                            <th class="py-3">Programa</th>
                            <th class="py-3">Turno</th>
                            <th class="py-3">Total Estudiantes</th>
                            <th class="py-3">Lista</th>
                        </tr>
                    </thead>
                    <tbody class="small text-center">
                        @forelse($gruposInge as $grupo)
                        <tr>
                            <td class="px-4 fw-bold text-dark">{{ $grupo->nombre_grupo }}</td>
                            <td class="text-dark">
                                <span class="d-flex align-items-center gap-2 fw-semibold">
                                    <i class="bi bi-file-earmark-text text-muted"></i> Ingeniería
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
                                <a href="{{ route('lista_grupo', $grupo->id_grupo) }}" class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3 d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-eye"></i> Ver Lista
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2 text-light-subtle"></i>
                                No se han generado grupos de Ingeniería aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>{{-- /card --}}

    {{-- ========================================== --}}
    {{-- TABLA 2: GRUPOS DE ARQUITECTURA Y DISEÑO   --}}
    {{-- ========================================== --}}
    <div class="card border border-light-subtle shadow-sm rounded-3">

        <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
            <h5 class="fw-bold text-uppercase text-white mb-0">Grupos de Arquitectura y Diseño</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="tablaGruposArqui" class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light text-muted small text-uppercase text-start">
                        <tr>
                            <th class="px-4 py-3">Nombre del Grupo</th>
                            <th class="py-3">Programa</th>
                            <th class="py-3">Turno</th>
                            <th class="py-3">Total Estudiantes</th>
                            <th class="py-3">Lista</th>
                        </tr>
                    </thead>
                    <tbody class="small text-center">
                        @forelse($gruposArqui as $grupo)
                        <tr>
                            <td class="px-4 fw-bold text-dark">{{ $grupo->nombre_grupo }}</td>
                            <td class="text-dark">
                                <span class="d-flex align-items-center gap-2 fw-semibold">
                                    <i class="bi bi-file-earmark-text text-muted"></i> Arquitectura
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
                                <a href="{{ route('lista_grupo', $grupo->id_grupo) }}" class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3 d-inline-flex align-items-center gap-2">
                                    <i class="bi bi-eye"></i> Ver Lista
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2 text-light-subtle"></i>
                                No se han generado grupos de Arquitectura aún.
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
                { orderable: false, targets: [1, 4] } // Desactiva ordenamiento en 'Programa' (1) y 'Lista' (4)
            ],
            dom: '<"d-flex flex-wrap justify-content-between align-items-center"l>rt<"d-flex flex-wrap justify-content-between align-items-center mt-2"ip>'
        };

        if ($('#tablaGruposInge tbody tr').length > 0 && !$('#tablaGruposInge tbody tr td[colspan]').length) {
            $('#tablaGruposInge').DataTable(opcionesBase);
        }

        if ($('#tablaGruposArqui tbody tr').length > 0 && !$('#tablaGruposArqui tbody tr td[colspan]').length) {
            $('#tablaGruposArqui').DataTable(opcionesBase);
        }
    });
</script>
@endsection