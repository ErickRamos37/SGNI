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

    {{-- ========================================== --}}
    {{-- TABLA: GRUPOS GENERALES DE INDUCCIÓN       --}}
    {{-- ========================================== --}}
    <div class="card border border-light-subtle shadow-sm rounded-3">

        <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
            <h5 class="fw-bold text-uppercase text-white mb-0">Grupos Generales (Inducción)</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Nombre del Grupo</th>
                            <th class="py-3">Programa</th>
                            <th class="py-3">Turno</th>
                            <th class="py-3">Total Estudiantes</th>
                            <th class="py-3">Lista</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        @forelse($gruposInduc as $grupo)
                        <tr>
                            <td class="px-4 fw-bold text-dark">{{ $grupo->nombre_grupo }}</td>
                            <td class="text-dark">
                                <span class="d-flex align-items-center gap-2 fw-semibold">
                                    <i class="bi bi-diagram-3-fill text-muted"></i> Tronco Común General
                                </span>
                            </td>
                            <td>
                                @if($grupo->id_turno == 1)
                                    <span class="badge rounded-pill bg-secondary text-dark px-3 py-2 fw-semibold">Mañana</span>
                                @else
                                    <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-semibold">Tarde</span>
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
@endsection