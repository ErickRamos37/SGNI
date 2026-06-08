@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Lista de Asistencia Grupal</h2>
            <p class="text-muted mb-0">
                Grupo: {{ $grupoActual ? $grupoActual->nombre_grupo : 'Sin asignar' }}
            </p>

            {{-- Selector de grupo --}}
            <div class="d-flex align-items-center gap-2 mt-2">
                <label class="small fw-bold text-muted text-uppercase mb-0">Grupo:</label>
                <select id="select_grupo_grupal" class="form-select form-select-sm w-auto shadow-sm"
                    onchange="location.href='{{ route('asistencia.grupal') }}?id_grupo=' + this.value">
                    <option value="" {{ !$grupoActual ? 'selected' : '' }} disabled>-- Seleccione un grupo --</option>
                    @foreach($grupos as $g)
                        <option value="{{ $g->id_grupo }}" {{ $grupoActual && $grupoActual->id_grupo == $g->id_grupo ? 'selected' : '' }}>
                            {{ $g->nombre_grupo }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($grupoActual)
            <a href="{{ route('grupos.descargar_lista', $grupoActual->id_grupo) }}"
               class="btn btn-outline-dark px-5 fw-semibold rounded-3 mt-3">
                Descargar Lista
            </a>
            @else
            <button class="btn btn-outline-dark px-5 fw-semibold rounded-3 mt-3" disabled>
                Descargar Lista
            </button>
            @endif
        </div>

        <div class="w-25">
            <label class="small fw-bold text-muted text-uppercase mb-1">Buscar Estudiante</label>
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="search-grupal" class="form-control border-start-0 ps-0"
                    placeholder="Matrícula o nombre..." {{ !$grupoActual ? 'disabled' : '' }}>
            </div>
        </div>
    </div>

    {{-- Tarjeta --}}
    <div class="card border border-light-subtle shadow-sm rounded-3">

        <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
            <h5 class="fw-bold text-uppercase text-white mb-0">Registro de Asistencia Semanal</h5>
        </div>

        @if($grupoActual && $alumnos->count() > 0)

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="px-4 py-3">Matrícula</th>
                                <th class="py-3">Nombre Completo</th>
                                @php
                            $nombresDias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                        @endphp
                        @foreach($diasSemana as $i => $dia)
                            <th class="py-3 text-center">{{ $nombresDias[$i] }} {{ $dia->format('d/m') }}</th>
                        @endforeach
                                <th class="py-3 text-center">% Semana</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            @foreach($alumnos as $alumno)
                            @php
                                $asistenciasAlumno = $alumno->asistencias->keyBy(function($a) {
                                    return \Carbon\Carbon::parse($a->fecha)->toDateString();
                                });
                                $totalPresente = $asistenciasAlumno->where('asistio', 1)->count();
                                $porcentaje    = $diasSemana ? round(($totalPresente / count($diasSemana)) * 100) : 0;
                            @endphp
                            <tr class="fila-grupal border-bottom"
                                data-search="{{ strtolower($alumno->matricula . ' ' . $alumno->nombre . ' ' . $alumno->ap_pat) }}">

                                <td class="px-4 fw-bold text-dark">{{ $alumno->matricula }}</td>
                                <td class="text-dark">{{ $alumno->nombre }} {{ $alumno->ap_pat }} {{ $alumno->ap_mat }}</td>

                                @foreach($diasSemana as $dia)
                                @php
                                    $asistencia = $asistenciasAlumno->get($dia->toDateString());
                                    $asistio    = $asistencia ? $asistencia->asistio : null;
                                @endphp
                                <td class="text-center">
                                    @if($asistio === 1)
                                        <span class="badge bg-success rounded-pill px-3 py-2">P</span>
                                    @elseif($asistio === 0)
                                        <span class="badge bg-danger rounded-pill px-3 py-2">A</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                @endforeach

                                <td class="text-center fw-bold
                                    {{ $porcentaje >= 80 ? 'text-success' : ($porcentaje >= 60 ? 'text-warning' : 'text-danger') }}">
                                    {{ $porcentaje }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top border-light-subtle p-3">
                <small class="text-muted fw-bold">{{ $alumnos->count() }} estudiantes en el grupo</small>
            </div>

        @else
            <div class="card-body p-5 text-center">
                <div class="py-5">
                    <i class="bi bi-people display-6 d-block mb-2 text-light-subtle"></i>
                    <h3 class="fw-bold text-dark">
                        {{ $grupoActual ? 'No hay alumnos asignados a este grupo' : 'No hay alumnos registrados' }}
                    </h3>
                    <p class="text-muted fs-5 mb-0">
                        {{ $grupoActual ? 'El grupo existe pero aún no tiene alumnos.' : 'Aún no se han generado los grupos o asignado estudiantes a esta sección.' }}
                    </p>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-grupal');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('.fila-grupal').forEach(row => {
                const data = row.getAttribute('data-search');
                row.classList.toggle('d-none', !data.includes(query));
            });
        });
    }
});
</script>
@endsection