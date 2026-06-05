@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                {{--- Encabezado de página + Buscador ---}}
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Seguimiento del Alumno</h2>
                        <p class="text-muted mb-0">Monitoreo de riesgo académico y asistencias</p>
                    </div>
                    <div class="w-25">
                        <label class="small fw-bold text-muted text-uppercase mb-1">Buscar Alumno</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" id="search-seguimiento" class="form-control border-start-0 ps-0"
                                placeholder="Matrícula, nombre, correo...">
                        </div>
                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- SECCIÓN 1: MONITOREO - CURSO PROPEDÉUTICO --}}
                {{-- ========================================== --}}
                <div class="mb-5">
                    <h3 class="fw-bold text-uppercase text-black mb-4 fs-5 tracking-wide">
                        1. Monitoreo - Curso Propedéutico
                    </h3>

                    @php $contPrope = 0; @endphp

                    @foreach($gruposPropedeutico as $grupo)
                        @php $contPrope++; @endphp

                        <div class="card border border-light-subtle shadow-sm rounded-3 mb-4">
                            <div class="card-header p-4 border-bottom border-light-subtle" style="background-color: #006633;">
                                <h5 class="fw-bold text-uppercase text-white mb-0">TABLA DE SEGUIMIENTO DE RIESGO - GRUPO:
                                    {{ $grupo->nombre_grupo }}</h5>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light text-muted small text-uppercase">
                                            <tr>
                                                <th class="px-4 py-3">Matrícula</th>
                                                <th class="py-3">Nombre del Alumno</th>
                                                <th class="py-3">Correo Institucional</th>
                                                <th class="py-3">Programa</th>
                                                <th class="py-3 text-center">Estatus de Riesgo</th>
                                                <th class="py-3 text-center">Asistencias</th>
                                                <th class="py-3 text-center">Mejoría</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @forelse($grupo->alumnosPropedeutico as $alumno)
                                                <tr class="seguimiento-row border-bottom"
                                                    data-search="{{ strtolower($alumno->matricula . ' ' . $alumno->nombre_completo . ' ' . $alumno->correo_institucional) }}">
                                                    <td class="px-4 fw-bold text-dark">{{ $alumno->matricula }}</td>
                                                    <td class="text-dark">{{ $alumno->nombre_completo }}</td>
                                                    <td class="text-muted">{{ $alumno->correo_institucional ?? 'Sin Correo' }}</td>
                                                    <td class="text-dark">Propedéutico</td>
                                                    
                                                    @php
                                                        $pctP = $alumno->porcentaje_asistencia_propedeutico ?? 0;

                                                        if ($pctP < 60) {
                                                            $estatusRiesgo = 'Riesgo Alto';
                                                            $badgeColor = 'bg-danger text-white';
                                                            $bgAsistencia = 'bg-danger bg-opacity-10 text-danger';
                                                        } elseif ($pctP <= 80) {
                                                            $estatusRiesgo = 'Riesgo Medio';
                                                            $badgeColor = 'bg-warning text-dark';
                                                            $bgAsistencia = 'bg-warning bg-opacity-25 text-warning-dark';
                                                        } else {
                                                            $estatusRiesgo = 'Regular';
                                                            $badgeColor = 'bg-primary text-white';
                                                            $bgAsistencia = 'bg-primary bg-opacity-10 text-primary';
                                                        }
                                                    @endphp
                                                    
                                                    <td class="text-center">
                                                        <span class="badge rounded-pill {{ $badgeColor }} px-3 py-2 text-uppercase fw-bold">
                                                            {{ $estatusRiesgo }}
                                                        </span>
                                                    </td>
                                                    
                                                    <td class="text-center fw-bold py-3 {{ $bgAsistencia }}">
                                                        {{ $pctP }}%
                                                    </td>
                                                    
                                                    <td class="text-center fw-bold text-primary fs-5">
                                                        {{ $alumno->mejoria > 0 ? '+' : '' }}{{ $alumno->mejoria }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No hay alumnos asignados a
                                                        este grupo.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($contPrope === 0)
                        <div class="alert alert-secondary border-1 text-center py-4 rounded-3">
                            No hay grupos registrados en el Curso Propedéutico.
                        </div>
                    @endif
                </div>

                {{-- ========================================== --}}
                {{-- SECCIÓN 2: MONITOREO - CURSO DE INDUCCIÓN --}}
                {{-- ========================================== --}}
                <div class="mb-5">
                    <h3 class="fw-bold text-uppercase text-black mb-4 fs-5 tracking-wide">
                        2. Monitoreo - Curso de Inducción
                    </h3>

                    @php $contInduc = 0; @endphp

                    @foreach($gruposInduccion as $grupo)
                        @php $contInduc++; @endphp

                        <div class="card border border-light-subtle shadow-sm rounded-3 mb-4">
                            <div class="card-header p-4 border-bottom border-light-subtle" style="background-color: #006633;">
                                <h5 class="fw-bold text-uppercase text-white mb-0">TABLA DE SEGUIMIENTO DE RIESGO - GRUPO:
                                    {{ $grupo->nombre_grupo }}</h5>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light text-muted small text-uppercase">
                                            <tr>
                                                <th class="px-4 py-3">Matrícula</th>
                                                <th class="py-3">Nombre del Alumno</th>
                                                <th class="py-3">Correo Institucional</th>
                                                <th class="py-3">Programa</th>
                                                <th class="py-3 text-center">Estatus de Riesgo</th>
                                                <th class="py-3 text-center">Asistencias</th>
                                                <th class="py-3 text-center">Mejoría</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @forelse($grupo->alumnosInduccion as $alumno)
                                                <tr class="seguimiento-row border-bottom"
                                                    data-search="{{ strtolower($alumno->matricula . ' ' . $alumno->nombre_completo . ' ' . $alumno->correo_institucional) }}">
                                                    <td class="px-4 fw-bold text-dark">{{ $alumno->matricula }}</td>
                                                    <td class="text-dark">{{ $alumno->nombre_completo }}</td>
                                                    <td class="text-muted">{{ $alumno->correo_institucional ?? 'Sin Correo' }}</td>
                                                    <td class="text-dark">Inducción</td>
                                                    
                                                    @php
                                                        // ¡AQUÍ ESTABA EL BUG DE VÍCTOR HUGO! YA ESTÁ CORREGIDO:
                                                        $pctI = $alumno->porcentaje_asistencia_induccion ?? 0;

                                                        if ($pctI < 60) {
                                                            $estatusRiesgo = 'Riesgo Alto';
                                                            $badgeColor = 'bg-danger text-white';
                                                            $bgAsistencia = 'bg-danger bg-opacity-10 text-danger';
                                                        } elseif ($pctI <= 80) {
                                                            $estatusRiesgo = 'Riesgo Medio';
                                                            $badgeColor = 'bg-warning text-dark';
                                                            $bgAsistencia = 'bg-warning bg-opacity-25 text-warning-dark';
                                                        } else {
                                                            $estatusRiesgo = 'Regular';
                                                            $badgeColor = 'bg-primary text-white';
                                                            $bgAsistencia = 'bg-primary bg-opacity-10 text-primary';
                                                        }
                                                    @endphp
                                                    
                                                    <td class="text-center">
                                                        <span class="badge rounded-pill {{ $badgeColor }} px-3 py-2 text-uppercase fw-bold">
                                                            {{ $estatusRiesgo }}
                                                        </span>
                                                    </td>
                                                    
                                                    <td class="text-center fw-bold py-3 {{ $bgAsistencia }}">
                                                        {{ $pctI }}%
                                                    </td>
                                                    
                                                    <td class="text-center fw-bold text-primary fs-5">
                                                        {{ $alumno->mejoria > 0 ? '+' : '' }}{{ $alumno->mejoria }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4 text-muted">No hay alumnos asignados a
                                                        este grupo.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($contInduc === 0)
                        <div class="alert alert-secondary border-1 text-center py-4 rounded-3">
                            No hay grupos registrados en el Curso de Inducción.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection