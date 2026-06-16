@extends('layouts.app')

@section('contenido')

<div class="mb-4">
    <h2 class="fw-bold fs-3 mb-1">Grupos Generados</h2>
    <p class="text-muted mb-0">Listado de todos los grupos creados para Propedéutico e Inducción</p>
</div>

{{-- TABS CON COLORES OFICIALES --}}
<div class="mb-4">
    <div class="d-inline-flex rounded-pill border bg-white shadow-sm p-1 gap-1">
        <a href="{{ route('curso_prope_creado') }}"
            class="btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-secondary text-dark text-decoration-none">
            Propedéutico
        </a>
        <a href="{{ route('curso_induc_creado') }}"
            class="btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-light text-muted text-decoration-none">
            Inducción
        </a>
    </div>
</div>

{{-- ========================================== --}}
{{-- TABLA 1: GRUPOS DE INGENIERÍA              --}}
{{-- ========================================== --}}
<div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-5">
    <div class="px-4 py-3 bg-primary d-flex align-items-center">
        <i class="bi bi-gear-fill text-warning fs-4 me-2"></i>
        <h5 class="text-white fw-bold mb-0 text-uppercase">
            Grupos de Ingeniería
        </h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted">Nombre del Grupo</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Programa</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Turno</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Total Estudiantes</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Lista</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gruposInge as $grupo)
                <tr>
                    <td class="px-4 py-3 fw-semibold">{{ $grupo->nombre_grupo }}</td>
                    <td class="py-3">
                        <span class="d-flex align-items-center gap-2 text-secondary fw-semibold">
                            <i class="bi bi-file-earmark-text"></i> Ingeniería
                        </span>
                    </td>
                    <td class="py-3">
                        @if($grupo->id_turno == 1)
                            <span class="badge rounded-pill bg-secondary text-dark px-3 py-2 fw-semibold">Mañana</span>
                        @else
                            <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-semibold">Tarde</span>
                        @endif
                    </td>
                    <td class="py-3">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bi bi-people text-muted"></i> 
                            <span class="fw-bold fs-6">{{ $grupo->alumnos_count }}</span>
                        </span>
                    </td>
                    <td class="py-3">
                        <a href="{{ route('lista_grupo', $grupo->id_grupo) }}" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-eye"></i> Ver Lista
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No se han generado grupos de Ingeniería aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ========================================== --}}
{{-- TABLA 2: GRUPOS DE ARQUITECTURA Y DISEÑO   --}}
{{-- ========================================== --}}
<div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
    <div class="px-4 py-3 bg-primary d-flex align-items-center">
        <i class="bi bi-palette-fill text-warning fs-4 me-2"></i>
        <h5 class="text-white fw-bold mb-0 text-uppercase">
            Grupos de Arquitectura y Diseño
        </h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted">Nombre del Grupo</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Programa</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Turno</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Total Estudiantes</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Lista</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gruposArqui as $grupo)
                <tr>
                    <td class="px-4 py-3 fw-semibold">{{ $grupo->nombre_grupo }}</td>
                    <td class="py-3">
                        <span class="d-flex align-items-center gap-2 text-secondary fw-semibold">
                            <i class="bi bi-file-earmark-text"></i> Arquitectura
                        </span>
                    </td>
                    <td class="py-3">
                        @if($grupo->id_turno == 1)
                            <span class="badge rounded-pill bg-secondary text-dark px-3 py-2 fw-semibold">Mañana</span>
                        @else
                            <span class="badge rounded-pill bg-light text-dark border px-3 py-2 fw-semibold">Tarde</span>
                        @endif
                    </td>
                    <td class="py-3">
                        <span class="d-flex align-items-center gap-2">
                            <i class="bi bi-people text-muted"></i> 
                            <span class="fw-bold fs-6">{{ $grupo->alumnos_count }}</span>
                        </span>
                    </td>
                    <td class="py-3">
                        <a href="{{ route('lista_grupo', $grupo->id_grupo) }}" class="btn btn-sm btn-primary fw-semibold px-3 py-2 rounded-2 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-eye"></i> Ver Lista
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No se han generado grupos de Arquitectura aún.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection