@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    {{-- ─── Encabezado de página + Botón de acción ───────────────── --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Pase de Lista</h2>
            <p class="text-muted mb-0">Grupo: Ingeniería - Grupo A</p>
        </div>
        <div>
            <a href="{{ route('asistencias.importar') }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                Subir Lista Excel
            </a>
        </div>
    </div>

    {{-- ─── Tarjeta contenedora ───────────────────────────────────── --}}
    <div class="card border border-light-subtle shadow-sm rounded-3">

        <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
            <h5 class="fw-bold text-uppercase text-white mb-0">Lista de Asistencia Semanal</h5>
        </div>

        {{--
            EVALUAMOS SI EXISTEN ALUMNOS.
            Como ahorita tu controlador no manda la variable $alumnos,
            entrará directo al @else (Estado Vacío).
        --}}
        @if(isset($alumnos) && count($alumnos) > 0)

            {{-- SI HAY ALUMNOS: Muestra la tabla --}}
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

                            {{-- MAGIA: Buscamos si este alumno tiene asistencias en el Excel recién subido --}}
                            @php
                                $datosExcel = collect($asistenciasExcel)->firstWhere('matricula_alumno', $alumno->matricula_alumno);
                            @endphp

                            <tr class="fila-alumno" data-matricula="{{ $alumno->matricula_alumno }}">
                                <td class="px-4 fw-bold text-dark">{{ $alumno->matricula_alumno }}</td>
                                <td class="text-dark">{{ $alumno->nombres_alumno }} {{ $alumno->apellidos_alumno }}</td>

                                {{-- Si los datos del Excel dicen que vino, le ponemos el atributo 'checked' --}}
                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-lunes" type="checkbox" {{ ($datosExcel && $datosExcel->lunes) ? 'checked' : '' }}></td>
                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-martes" type="checkbox" {{ ($datosExcel && $datosExcel->martes) ? 'checked' : '' }}></td>
                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-miercoles" type="checkbox" {{ ($datosExcel && $datosExcel->miercoles) ? 'checked' : '' }}></td>
                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-jueves" type="checkbox" {{ ($datosExcel && $datosExcel->jueves) ? 'checked' : '' }}></td>
                                <td class="text-center"><input class="form-check-input fs-4 shadow-sm chk-viernes" type="checkbox" {{ ($datosExcel && $datosExcel->viernes) ? 'checked' : '' }}></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top border-light-subtle p-3 d-flex justify-content-between align-items-center">
                <span class="text-muted fw-bold small">{{ count($alumnos) }} estudiantes registrados</span>

                <button id="btnGuardarAsistencias" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                    Guardar
                </button>
            </div>

        @else

            {{-- NO HAY ALUMNOS (ESTADO VACÍO): Mensaje de espera para el profesor --}}
            <div class="card-body p-5 text-center">
                <div class="py-5">
                    {{-- Cambié el ícono por uno de "grupo de personas" para que tenga más sentido --}}
                    <i class="bi bi-people display-4 d-block mb-3 text-muted opacity-25"></i>

                    <h3 class="fw-bold text-dark mb-2">Aún no hay alumnos asignados</h3>
                    <p class="text-muted fs-5 w-75 mx-auto mb-4">
                        El pase de lista se habilitará automáticamente en cuanto el área de administración genere los grupos de Propedéutico o Inducción y te asigne tu lista oficial.
                    </p>

                    {{-- Botón visualmente desactivado para indicar que está en espera --}}
                    <button class="btn btn-outline-dark px-5 fw-semibold rounded-3 opacity-75" disabled>
                        <i class="bi bi-hourglass-split me-2"></i> Esperando asignación de grupo...
                    </button>
                </div>
            </div>

        @endif

    </div>{{-- /card --}}

</div>

{{-- Tu script de guardado se queda exactamente igual, listo para cuando la tabla exista --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGuardar = document.getElementById('btnGuardarAsistencias');

    if(btnGuardar) { // Verificamos si el botón existe en el HTML antes de asignarle el evento
        btnGuardar.addEventListener('click', function(e) {
            e.preventDefault();

            btnGuardar.disabled = true;
            let textoOriginal = btnGuardar.innerText;
            btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

            let datosAsistencia = [];
            
            document.querySelectorAll('.fila-alumno').forEach(fila => {
                datosAsistencia.push({
                    matricula: fila.dataset.matricula,
                    lunes: fila.querySelector('.chk-lunes').checked,
                    martes: fila.querySelector('.chk-martes').checked,
                    miercoles: fila.querySelector('.chk-miercoles').checked,
                    jueves: fila.querySelector('.chk-jueves').checked,
                    viernes: fila.querySelector('.chk-viernes').checked,
                });
            });

            let payload = {
                id_grupo: 1, 
                asistencias: datosAsistencia
            };

            fetch('{{ route("asistencias.guardarMasivo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                if (!response.ok) throw response;
                return response.json();
            })
            .then(data => {
                btnGuardar.disabled = false;
                btnGuardar.innerText = textoOriginal;
                if (data.success) {
                    alert(data.mensaje); 
                }
            })
            .catch(error => {
                btnGuardar.disabled = false;
                btnGuardar.innerText = textoOriginal;
                alert("Error de conexión al guardar las asistencias.");
                console.error(error);
            });
        });
    }
});
</script>
@endsection