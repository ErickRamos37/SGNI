@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            
            <h1 class="fw-bold mb-1 text-black">Panel de Seguimiento Psicológico</h1>
            <p class="text-muted mb-4">Monitoreo cronológico por grupos de riesgo académico, asistencias y documentación institucional</p>
            
            {{-- ========================================== --}}
            {{-- SECCIÓN 1: MONITOREO - CURSO PROPEDÉUTICO  --}}
            {{-- ========================================== --}}
            <div class="mb-5">
                <h3 class="fw-bold text-uppercase text-black mb-4 fs-5 tracking-wide">
                    1. Monitoreo - Curso Propedéutico
                </h3>

                @php $contPrope = 0; @endphp

                @foreach($gruposPropedeutico as $grupo)
                    @php $contPrope++; @endphp
                    
                    <div class="card border-1 rounded-3 mb-4" style="border-color: #dee2e6;">
                        <div class="card-header bg-light border-bottom py-3">
                            <h5 class="fw-bold text-dark mb-0 text-uppercase small tracking-wide">
                                Grupo: {{ $grupo->nombre_grupo }}
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-uppercase text-secondary small fw-bold border-bottom">
                                        <tr>
                                            <th scope="col" class="ps-3 py-3">Matrícula</th>
                                            <th scope="col" class="py-3">Nombre del Alumno</th>
                                            <th scope="col" class="py-3">Correo Institucional</th>
                                            <th scope="col" class="py-3 text-center">Estatus de Riesgo</th>
                                            <th scope="col" class="py-3 text-center">% Asistencias (Propé)</th>
                                            <th scope="col" class="py-3 text-center pe-3">Mejoría Exámenes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small text-dark">
                                        @forelse($grupo->alumnos as $alumno)
                                            <tr class="border-bottom">
                                                <td class="fw-bold ps-3 py-3 text-black">{{ $alumno->matricula }}</td>
                                                <td class="fw-semibold text-dark">{{ $alumno->nombre_completo }}</td>
                                                <td class="text-muted">{{ $alumno->correo_institucional ?? 'Sin Correo' }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $situacion = $alumno->seguimientoAcademico->situacionAlum->Situacion_alumnocol ?? 'Regular';
                                                        if (str_contains(strtolower($situacion), 'alto')) {
                                                            $badgeColor = 'bg-danger text-white';
                                                        } elseif (str_contains(strtolower($situacion), 'medio')) {
                                                            $badgeColor = 'bg-warning text-dark';
                                                        } else {
                                                            $badgeColor = 'bg-primary text-white';
                                                        }
                                                    @endphp
                                                    <span class="badge rounded-pill {{ $badgeColor }} px-3 py-2 text-uppercase fw-bold">
                                                        {{ $situacion }}
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold">
                                                    @php $pctP = $alumno->porcentaje_asistencia_propedeutico; @endphp
                                                    <span class="d-inline-block px-3 py-1 rounded {{ $pctP < 70 ? 'text-danger bg-danger' : ($pctP <= 85 ? 'text-warning bg-warning' : 'text-primary bg-primary') }} bg-opacity-10">
                                                        {{ $pctP }}%
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold text-primary pe-3 fs-5">
                                                    {{ $alumno->mejoria }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4 text-muted">No hay alumnos asignados a este grupo.</td>
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
            {{-- SECCIÓN 2: MONITOREO - CURSO DE INDUCCIÓN  --}}
            {{-- ========================================== --}}
            <div class="mb-5">
                <h3 class="fw-bold text-uppercase text-black mb-4 fs-5 tracking-wide">
                    2. Monitoreo - Curso de Inducción
                </h3>

                @php $contInduc = 0; @endphp

                @foreach($gruposInduccion as $grupo)
                    @php $contInduc++; @endphp
                    
                    <div class="card border-1 rounded-3 mb-4" style="border-color: #dee2e6;">
                        <div class="card-header bg-light border-bottom py-3">
                            <h5 class="fw-bold text-dark mb-0 text-uppercase small tracking-wide">
                                Grupo: {{ $grupo->nombre_grupo }}
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-uppercase text-secondary small fw-bold border-bottom">
                                        <tr>
                                            <th scope="col" class="ps-3 py-3">Matrícula</th>
                                            <th scope="col" class="py-3">Nombre del Alumno</th>
                                            <th scope="col" class="py-3">Correo Institucional</th>
                                            <th scope="col" class="py-3 text-center">Estatus de Riesgo</th>
                                            <th scope="col" class="py-3 text-center pe-3">% Asistencias (Induc)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small text-dark">
                                        @forelse($grupo->alumnos as $alumno)
                                            <tr class="border-bottom">
                                                <td class="fw-bold ps-3 py-3 text-black">{{ $alumno->matricula }}</td>
                                                <td class="fw-semibold text-dark">{{ $alumno->nombre_completo }}</td>
                                                <td class="text-muted">{{ $alumno->correo_institucional ?? 'Sin Correo' }}</td>
                                                <td class="text-center">
                                                    @php
                                                        $situacion = $alumno->seguimientoAcademico->situacionAlum->Situacion_alumnocol ?? 'Regular';
                                                        if (str_contains(strtolower($situacion), 'alto')) {
                                                            $badgeColor = 'bg-danger text-white';
                                                        } elseif (str_contains(strtolower($situacion), 'medio')) {
                                                            $badgeColor = 'bg-warning text-dark';
                                                        } else {
                                                            $badgeColor = 'bg-primary text-white';
                                                        }
                                                    @endphp
                                                    <span class="badge rounded-pill {{ $badgeColor }} px-3 py-2 text-uppercase fw-bold">
                                                        {{ $situacion }}
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold pe-3">
                                                    @php $pctI = $alumno->porcentaje_asistencia_induccion; @endphp
                                                    <span class="d-inline-block px-3 py-1 rounded {{ $pctI < 70 ? 'text-danger bg-danger' : ($pctI <= 85 ? 'text-warning bg-warning' : 'text-primary bg-primary') }} bg-opacity-10">
                                                        {{ $pctI }}%
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">No hay alumnos asignados a este grupo.</td>
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