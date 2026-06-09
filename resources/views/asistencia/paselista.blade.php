@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Pase de Lista</h2>
            <p class="text-muted mb-0">
                Grupo: {{ $grupoActual ? $grupoActual->nombre_grupo : 'Sin asignar' }}
            </p>

            <div class="d-flex align-items-center gap-2 mt-2">
                <label for="select_grupo" class="small fw-bold text-muted text-uppercase mb-0">Grupo:</label>
                <select id="select_grupo" class="form-select form-select-sm w-auto shadow-sm"
                    onchange="location.href='{{ route('asistencia.paselista') }}?id_grupo=' + this.value">
                    <option value="" {{ !$grupoActual ? 'selected' : '' }} disabled>-- Seleccione un grupo --</option>
                    @foreach($grupos as $g)
                        <option value="{{ $g->id_grupo }}" {{ $grupoActual && $grupoActual->id_grupo == $g->id_grupo ? 'selected' : '' }}>
                            {{ $g->nombre_grupo }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            @if($grupoActual)
            <a href="{{ route('grupos.descargar_lista', $grupoActual->id_grupo) }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                Descargar Lista
            </a>
            @else
            <button class="btn btn-outline-dark px-5 fw-semibold rounded-3" disabled>
                Descargar Lista
            </button>
            @endif
        </div>
    </div>

    <div class="card border border-light-subtle shadow-sm rounded-3">

        <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
            <h5 class="fw-bold text-uppercase text-white mb-0">Lista de Asistencia Semanal</h5>
        </div>

        @if($esLectura)
            <div class="alert alert-warning border-0 rounded-0 m-0 d-flex align-items-center gap-2 px-4 py-3">
                <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                <div>
                    <strong>Grupo en Modo Lectura:</strong> La asistencia de este grupo ha sido bloqueada y no se admiten modificaciones.
                </div>
            </div>
        @endif

        @if(isset($alumnos) && count($alumnos) > 0)

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th class="px-4 py-3">Matrícula</th>
                                <th class="py-3">Nombre Completo</th>
                                <th class="py-3 text-center">Lunes</th>
                                <th class="py-3 text-center">Martes</th>
                                <th class="py-3 text-center">Miércoles</th>
                                <th class="py-3 text-center">Jueves</th>
                                <th class="py-3 text-center">Viernes</th>
                            </tr>
                        </thead>

                        <tbody class="small">
                            @foreach($alumnos as $alumno)
                            @php
                                $chkLunes = $chkMartes = $chkMiercoles = $chkJueves = $chkViernes = 0;

                                if (isset($asistenciasSemana) && isset($asistenciasSemana[$alumno->matricula_alumno])) {
                                    $asistenciasAlu = $asistenciasSemana[$alumno->matricula_alumno]->keyBy('fecha');
                                    
                                    $lunesFecha = $lunesSemana->copy()->toDateString();
                                    $martesFecha = $lunesSemana->copy()->addDays(1)->toDateString();
                                    $miercolesFecha = $lunesSemana->copy()->addDays(2)->toDateString();
                                    $juevesFecha = $lunesSemana->copy()->addDays(3)->toDateString();
                                    $viernesFecha = $lunesSemana->copy()->addDays(4)->toDateString();
                                    
                                    $asistLunes = $asistenciasAlu->get($lunesFecha);
                                    $asistMartes = $asistenciasAlu->get($martesFecha);
                                    $asistMiercoles = $asistenciasAlu->get($miercolesFecha);
                                    $asistJueves = $asistenciasAlu->get($juevesFecha);
                                    $asistViernes = $asistenciasAlu->get($viernesFecha);
                                    
                                    $chkLunes = $asistLunes ? $asistLunes->asistio : 0;
                                    $chkMartes = $asistMartes ? $asistMartes->asistio : 0;
                                    $chkMiercoles = $asistMiercoles ? $asistMiercoles->asistio : 0;
                                    $chkJueves = $asistJueves ? $asistJueves->asistio : 0;
                                    $chkViernes = $asistViernes ? $asistViernes->asistio : 0;
                                }
                            @endphp

                            <tr class="fila-alumno"
                                data-matricula="{{ $alumno->matricula_alumno }}"
                                data-nombre="{{ $alumno->nombres_alumno }}"
                                data-ap_pat="{{ $alumno->ap_pat }}"
                                data-ap_mat="{{ $alumno->ap_mat }}">

                                <td class="px-4 fw-bold text-dark">{{ $alumno->matricula_alumno }}</td>
                                <td class="text-dark nombre-alumno">{{ $alumno->nombres_alumno }} {{ $alumno->apellidos_alumno }}</td>

                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-lunes"     type="checkbox" {{ $chkLunes ? 'checked' : '' }} {{ $esLectura ? 'disabled' : '' }}></td>
                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-martes"    type="checkbox" {{ $chkMartes ? 'checked' : '' }} {{ $esLectura ? 'disabled' : '' }}></td>
                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-miercoles" type="checkbox" {{ $chkMiercoles ? 'checked' : '' }} {{ $esLectura ? 'disabled' : '' }}></td>
                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-jueves"    type="checkbox" {{ $chkJueves ? 'checked' : '' }} {{ $esLectura ? 'disabled' : '' }}></td>
                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-viernes"   type="checkbox" {{ $chkViernes ? 'checked' : '' }} {{ $esLectura ? 'disabled' : '' }}></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top border-light-subtle p-3 d-flex justify-content-between align-items-center">
                <span class="text-muted fw-bold small">{{ count($alumnos) }} estudiantes registrados</span>
                @if(!$esLectura)
                <button id="btnGuardarAsistencias" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                    Guardar
                </button>
                @endif
            </div>

        @else
            <div class="card-body p-5 text-center">
                <div class="py-5">
                    <i class="bi bi-people display-4 d-block mb-3 text-muted opacity-25"></i>
                    <h3 class="fw-bold text-dark mb-2">Aún no hay alumnos asignados</h3>
                    <p class="text-muted fs-5 w-75 mx-auto mb-4">
                        El pase de lista se habilitará automáticamente en cuanto el área de administración genere los grupos.
                    </p>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGuardar = document.getElementById('btnGuardarAsistencias');

    if (btnGuardar) {
        btnGuardar.addEventListener('click', function(e) {
            e.preventDefault();

            const selectElement = document.getElementById('select_grupo');
            if (!selectElement || !selectElement.value) {
                Swal.fire({
                    title: 'Seleccione un grupo',
                    text: 'Por favor, selecciona un grupo antes de guardar.',
                    icon: 'warning',
                    confirmButtonColor: '#00723F',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            btnGuardar.disabled = true;
            let textoOriginal = btnGuardar.innerText;
            btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

            let datosAsistencia = [];

            document.querySelectorAll('.fila-alumno').forEach(fila => {
                datosAsistencia.push({
                    matricula:  fila.dataset.matricula,
                    nombre:     fila.dataset.nombre  || 'ALUMNO',
                    ap_pat:     fila.dataset.ap_pat  || '',
                    ap_mat:     fila.dataset.ap_mat  || '',
                    lunes:      fila.querySelector('.chk-lunes').checked,
                    martes:     fila.querySelector('.chk-martes').checked,
                    miercoles:  fila.querySelector('.chk-miercoles').checked,
                    jueves:     fila.querySelector('.chk-jueves').checked,
                    viernes:    fila.querySelector('.chk-viernes').checked,
                });
            });

            let payload = {
                id_grupo:    selectElement.value,
                asistencias: datosAsistencia
            };

            fetch('{{ route("asistencias.guardarMasivo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.mensaje ?? JSON.stringify(data));
                }
                return data;
            })
            .then(data => {
                btnGuardar.disabled = false;
                btnGuardar.innerText = textoOriginal;
                Swal.fire({
                    title: '¡Guardado Exitoso!',
                    text: data.mensaje,
                    icon: 'success',
                    confirmButtonColor: '#00723F',
                    confirmButtonText: 'Aceptar'
                });
            })
            .catch(error => {
                btnGuardar.disabled = false;
                btnGuardar.innerText = textoOriginal;
                console.error("Error completo:", error);
                Swal.fire({
                    title: '¡Error!',
                    text: error.message,
                    icon: 'error',
                    confirmButtonColor: '#00723F',
                    confirmButtonText: 'Entendido'
                });
            });
        });
    }
});
</script>
@endsection