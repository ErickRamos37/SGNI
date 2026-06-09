@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
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
            </div>

            {{-- Tarjeta --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-primary p-4 border-bottom">
                    <h5 class="fw-bold text-uppercase text-white mb-0">Registro de Asistencia Semanal</h5>
                </div>

                @if($grupoActual)
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table id="tabla-asistencia-grupal" class="table table-hover align-middle mb-0" style="width:100%">
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
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white p-4 border-top border-light-subtle">
                        <small class="text-muted fw-bold">{{ $totalAlumnos }} estudiantes en el grupo</small>
                    </div>
                @else
                    <div class="card-body p-5 text-center">
                           <div class="py-5">
                            <i class="bi bi-people display-6 d-block mb-2 text-light-subtle"></i>
                            <h3 class="fw-bold text-dark">No hay grupos disponibles</h3>
                            <p class="text-muted fs-5 mb-0">
                                Aún no se han generado los grupos o asignado estudiantes a esta sección.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script type="module">
$(document).ready(function() {
    @if($grupoActual)
    let table = $('#tabla-asistencia-grupal').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('asistencia.grupal') }}",
            data: function(d) {
                d.id_grupo = "{{ $grupoActual->id_grupo }}";
            }
        },
        columns: [
            { data: 'matricula', name: 'matricula', className: 'px-4 fw-bold text-dark' },
            { data: 'nombre_completo', name: 'nombre_completo' },
            { data: 'lunes', name: 'lunes', orderable: false, searchable: false, className: 'text-center' },
            { data: 'martes', name: 'martes', orderable: false, searchable: false, className: 'text-center' },
            { data: 'miercoles', name: 'miercoles', orderable: false, searchable: false, className: 'text-center' },
            { data: 'jueves', name: 'jueves', orderable: false, searchable: false, className: 'text-center' },
            { data: 'viernes', name: 'viernes', orderable: false, searchable: false, className: 'text-center' },
            { data: 'porcentaje', name: 'porcentaje', orderable: false, searchable: false, className: 'text-center' }
        ]
    });
    @endif
});
</script>
@endsection