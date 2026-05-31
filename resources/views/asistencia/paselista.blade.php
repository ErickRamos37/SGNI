@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-2">
    
    <div class="mb-4">
        <h1 class="fw-bold text-dark mb-1">Pase de Lista</h1>
        <p class="text-dark fw-semibold" style="opacity: 0.7;">Grupo: Ingeniería - Grupo A</p>
        
        <a href="{{ route('asistencias.importar') }}" class="btn btn-primary text-white fw-bold px-4 py-2 mt-2 shadow-sm rounded-3 border-0">
            <i class="bi bi-file-earmark-excel me-2 text-secondary fs-5"></i> IMPORTAR LISTA EXCEL
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        <div class="card-header bg-primary text-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Lista de Asistencia Semanal</h5>
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
                    <table class="table align-middle mb-0">
                        <thead class="bg-light text-dark small fw-bold text-uppercase" style="opacity: 0.8;">
                            <tr>
                                <th class="ps-4 py-3 border-bottom-0">Matrícula</th>
                                <th class="py-3 border-bottom-0">Nombre Completo</th>
                                <th class="text-center py-3 border-bottom-0">Lunes</th>
                                <th class="text-center py-3 border-bottom-0">Martes</th>
                                <th class="text-center py-3 border-bottom-0">Miércoles</th>
                                <th class="text-center py-3 border-bottom-0">Jueves</th>
                                <th class="text-center py-3 border-bottom-0">Viernes</th>
                            </tr>
                        </thead>
                        
                        <tbody class="border-top-0">
                            @foreach($alumnos as $alumno)
                            
                            {{-- MAGIA: Buscamos si este alumno tiene asistencias en el Excel recién subido --}}
                            @php
                                $datosExcel = collect($asistenciasExcel)->firstWhere('matricula_alumno', $alumno->matricula_alumno);
                            @endphp

                            <tr class="fila-alumno" data-matricula="{{ $alumno->matricula_alumno }}">
                                <td class="ps-4 text-dark fw-bold py-3">{{ $alumno->matricula_alumno }}</td>
                                <td class="fw-bold text-dark">{{ $alumno->nombres_alumno }} {{ $alumno->apellidos_alumno }}</td>
                                
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

            <div class="card-footer bg-white border-top py-4 px-4 d-flex justify-content-between align-items-center">
                <span class="text-dark fw-bold small" style="opacity: 0.7;">{{ count($alumnos) }} estudiantes registrados</span>
                
                <button id="btnGuardarAsistencias" class="btn btn-secondary text-dark fw-bold px-5 py-2 rounded-3 shadow-sm border-0">
                    GUARDAR
                </button>
            </div>

        @else
            
            {{-- NO HAY ALUMNOS (ESTADO VACÍO): Mensaje de espera para el profesor --}}
            <div class="card-body p-5 text-center">
                <div class="py-5">
                    {{-- Cambié el ícono por uno de "grupo de personas" para que tenga más sentido --}}
                    <i class="bi bi-people text-muted opacity-25 mb-3" style="font-size: 5rem;"></i>
                    
                    <h3 class="fw-bold text-dark mb-2">Aún no hay alumnos asignados</h3>
                    <p class="text-muted fs-5 mx-auto mb-4" style="max-width: 550px;">
                        El pase de lista se habilitará automáticamente en cuanto el área de administración genere los grupos de Propedéutico o Inducción y te asigne tu lista oficial.
                    </p>
                    
                    {{-- Botón visualmente desactivado para indicar que está en espera --}}
                    <button class="btn btn-secondary text-dark fw-bold px-4 py-2 rounded-3 shadow-sm opacity-75" disabled>
                        <i class="bi bi-hourglass-split me-2"></i> Esperando asignación de grupo...
                    </button>
                </div>
            </div>

        @endif

    </div>
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