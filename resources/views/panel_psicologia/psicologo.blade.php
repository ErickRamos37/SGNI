@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            
            <h1 class="fw-bold mb-1 text-primary">Panel de Seguimiento Psicológico</h1>
            <p class="text-muted mb-4">Monitoreo cronológico de riesgo académico, asistencias y documentación institucional</p>
            
            <div class="card border-0 shadow-sm rounded-3 mb-5">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold text-uppercase text-primary mb-4 small tracking-wide">
                        1. MONITOREO - CURSO PROPEDÉUTICO
                    </h5>
                    
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
                                @php $contPrope = 0; @endphp
                                @foreach($alumnos as $alumno)
                                    {{-- Un alumno aparece aquí si tiene exámenes o asistencias de propedéutico (id_grupo 1) --}}
                                    @if($alumno->resultadosPropedeutico || $alumno->asistencias()->where('id_grupo', 1)->exists())
                                        @php $contPrope++; @endphp
                                        <tr class="border-bottom">
                                            <td class="fw-bold ps-3 py-3 text-black">{{ $alumno->matricula }}</td>
                                            <td class="fw-semibold text-dark">{{ $alumno->nombre_completo }}</td>
                                            <td class="text-muted">{{ $alumno->correo_institucional ?? 'Sin Correo' }}</td>
                                            <td class="text-center">
                                                @php
                                                    // Traemos directamente la columna de texto de la situación, o 'Regular' si viene vacío
                                                    $situacion = $alumno->seguimientoAcademico->situacionAlum->Situacion_alumnocol ?? 'Regular';
                                                    
                                                    // Asignamos el color correcto según el texto encontrado
                                                    $badgeColor = 'bg-success text-white';
                                                    if (str_contains(strtolower($situacion), 'alto')) {
                                                        $badgeColor = 'bg-danger text-white';
                                                    } elseif (str_contains(strtolower($situacion), 'medio')) {
                                                        $badgeColor = 'bg-warning text-dark';
                                                    }
                                                @endphp
                                                <span class="badge rounded-pill {{ $badgeColor }} px-3 py-2 text-uppercase fw-bold">
                                                    {{ $situacion }}
                                                </span>
                                            </td>
                                            <td class="text-center fw-bold">
                                                @php $pctP = $alumno->porcentaje_asistencia_propedeutico; @endphp
                                                <span class="d-inline-block px-3 py-1 rounded {{ $pctP < 70 ? 'text-danger bg-danger' : ($pctP <= 85 ? 'text-warning bg-warning' : 'text-success bg-success') }} bg-opacity-10">
                                                    {{ $pctP }}%
                                                </span>
                                            </td>
                                            <td class="text-center fw-bold text-primary pe-3 fs-5">
                                                {{ $alumno->mejoria }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                
                                @if($contPrope === 0)
                                    <tr><td colspan="6" class="text-center py-4 text-muted">No hay alumnos registrados en el Curso Propedéutico.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold text-uppercase text-primary mb-4 small tracking-wide">
                        2. MONITOREO - CURSO DE INDUCCIÓN
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase text-secondary small fw-bold border-bottom">
                                <tr>
                                    <th scope="col" class="ps-3 py-3 ">Matrícula</th>
                                    <th scope="col" class="py-3">Nombre del Alumno</th>
                                    <th scope="col" class="py-3">Correo Institucional</th>
                                    <th scope="col" class="py-3 text-center">Estatus de Riesgo</th>
                                    <th scope="col" class="py-3 text-center pe-3">% Asistencias (Induc)</th>
                                </tr>
                            </thead>
                            <tbody class="small text-dark">
                                @php $contInduc = 0; @endphp
                                @foreach($alumnos as $alumno)
                                    {{-- Un alumno aparece en inducción si tiene asistencias del grupo 2 o si no hizo propedéutico pero ya es alumno formal --}}
                                    @if($alumno->asistencias()->where('id_grupo', 2)->exists() || !$alumno->resultadosPropedeutico)
                                        @php $contInduc++; @endphp
                                        <tr class="border-bottom">
                                            <td class="fw-bold ps-3 py-3 text-black">{{ $alumno->matricula }}</td>
                                            <td class="fw-semibold text-dark">{{ $alumno->nombre_completo }}</td>
                                            <td class="text-muted">{{ $alumno->correo_institucional ?? 'Sin Correo' }}</td>
                                            <td class="text-center">
                                                @php
                                                    $situacion = $alumno->seguimientoAcademico->situacionAlum->Situacion_alumnocol ?? 'Regular';
                                                    $badgeColor = str_contains(strtolower($situacion), 'alto') ? 'bg-danger text-white' : (str_contains(strtolower($situacion), 'medio') ? 'bg-warning text-dark' : 'bg-success text-white');
                                                @endphp
                                                <span class="badge rounded-pill {{ $badgeColor }} px-3 py-2 text-uppercase fw-bold">
                                                    {{ $situacion }}
                                                </span>
                                            </td>
                                            <td class="text-center fw-bold pe-3">
                                                @php $pctI = $alumno->porcentaje_asistencia_induccion; @endphp
                                                <span class="d-inline-block px-3 py-1 rounded {{ $pctI < 70 ? 'text-danger bg-danger' : ($pctI <= 85 ? 'text-warning bg-warning' : 'text-success bg-success') }} bg-opacity-10">
                                                    {{ $pctI }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                
                                @if($contInduc === 0)
                                    <tr><td colspan="5" class="text-center py-4 text-muted">No hay alumnos registrados en el Curso de Inducción.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection