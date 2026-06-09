@extends('layouts.app')

@section('contenido')
    <div class="container-fluid">

        {{-- Encabezado de pagina --}}
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Grupos Generados</h2>
                <p class="text-dark mb-0">Listado de todos los grupos creados separados por programa académico</p>
            </div>
        </div>

        {{-- TABLA 1: INGENIERÍA (TRONCO COMÚN) --}}
        <div class="card border border-light-subtle shadow-sm rounded-3 mb-5">
            <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                <h5 class="fw-bold text-uppercase text-white mb-0">Grupos Finales - Tronco Común (Ingeniería)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablaGruposTC" class="table table-hover align-middle mb-0 w-100">
                        <thead class="table-light text-dark small text-uppercase text-start">
                            <tr>
                                <th class="px-4 py-3">Nombre del Grupo</th>
                                <th class="py-3">Programa</th>
                                <th class="py-3">Turno</th>
                                <th class="py-3">Total Estudiantes</th>
                                <th class="py-3">Lista</th>
                            </tr>
                        </thead>
                        <tbody class="small text-start">
                            @forelse($gruposTC as $grupo)
                                <tr>
                                    <td class="px-4 fw-bold text-dark">{{ $grupo->nombre_grupo }}</td>
                                    <<td class="text-dark">
                                        <span class="d-flex align-items-center gap-2 fw-semibold">
                                            <i class="bi bi-diagram-3-fill text-dark-50"></i> 
                                            Tronco Común Ingenieria
                                        </span>
                                    </td>
                                    <td>
                                        @if ($grupo->turno && strtolower($grupo->turno->tipo_turno) == 'matutino')
                                            <span class="badge rounded-pill bg-secondary text-dark px-3 py-2 fw-semibold">Matutino</span>
                                        @elseif($grupo->turno && strtolower($grupo->turno->tipo_turno) == 'intermedio')
                                            <span class="badge rounded-pill bg-dark text-white px-3 py-2 fw-semibold">Intermedio</span>
                                        @else
                                            <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-semibold">Vespertino</span>
                                        @endif
                                    </td>
                                    <td class="text-dark">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="bi bi-people text-dark-50"></i>
                                            <span class="fw-bold">{{ $grupo->alumnos_finales_count }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('grupos_finales.lista_grupo_final', $grupo->id_grupo) }}" class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3 d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-eye"></i> Ver Lista
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-dark">
                                        <i class="bi bi-inbox display-6 d-block mb-2 text-dark-50"></i>
                                        No se han generado grupos para Tronco Común.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TABLA 2: ARQUITECTURA --}}
        <div class="card border border-light-subtle shadow-sm rounded-3">
            {{-- Cambiamos bg-dark por bg-primary para unificar el diseño --}}
            <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                <h5 class="fw-bold text-uppercase text-white mb-0">Grupos Finales -Tronco Común (Arquitectura)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablaGruposArq" class="table table-hover align-middle mb-0 w-100">
                        <thead class="table-light text-dark small text-uppercase text-start">
                            <tr>
                                <th class="px-4 py-3">Nombre del Grupo</th>
                                <th class="py-3">Programa</th>
                                <th class="py-3">Turno</th>
                                <th class="py-3">Total Estudiantes</th>
                                <th class="py-3">Lista</th>
                            </tr>
                        </thead>
                        <tbody class="small text-start">
                            @forelse($gruposArq as $grupo)
                                <tr>
                                    <td class="px-4 fw-bold text-dark">{{ $grupo->nombre_grupo }}</td>
                                    <td class="text-dark">
                                        <span class="d-flex align-items-center gap-2 fw-semibold">
                                            <i class="bi bi-buildings-fill text-dark-50"></i> 
                                            Tronco Común Arquitectura
                                        </span>
                                    </td>
                                    <td>
                                        @if ($grupo->turno && strtolower($grupo->turno->tipo_turno) == 'matutino')
                                            <span class="badge rounded-pill bg-secondary text-dark px-3 py-2 fw-semibold">Matutino</span>
                                        @elseif($grupo->turno && strtolower($grupo->turno->tipo_turno) == 'intermedio')
                                            <span class="badge rounded-pill bg-dark text-white px-3 py-2 fw-semibold">Intermedio</span>
                                        @else
                                            <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-semibold">Vespertino</span>
                                        @endif
                                    </td>
                                    <td class="text-dark">
                                        <span class="d-flex align-items-center gap-2">
                                            <i class="bi bi-people text-dark-50"></i>
                                            <span class="fw-bold">{{ $grupo->alumnos_finales_count }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('grupos_finales.lista_grupo_final', $grupo->id_grupo) }}" class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3 d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-eye"></i> Ver Lista
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-dark">
                                        <i class="bi bi-inbox display-6 d-block mb-2 text-dark-50"></i>
                                        No se han generado grupos para Arquitectura.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- Inicializacion Múltiple de DataTables con Vanilla JavaScript --}}
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const tablas = ['tablaGruposTC', 'tablaGruposArq'];
            
            tablas.forEach(function(tablaId) {
                const tablaEl = document.getElementById(tablaId);
                
                if (tablaEl && tablaEl.querySelector('tbody tr:not([colspan])')) {
                    new DataTable(tablaEl, {
                        searching: false,
                        order: [[0, 'asc']],
                        pageLength: 10,
                        lengthMenu: [5, 10, 25, 50],
                        columnDefs: [{
                            orderable: false,
                            targets: [1, 4]
                        }],
                        dom: '<"d-flex flex-wrap justify-content-between align-items-center"l>rt<"d-flex flex-wrap justify-content-between align-items-center mt-2"ip>'
                    });
                }
            });
        });
    </script>
@endsection