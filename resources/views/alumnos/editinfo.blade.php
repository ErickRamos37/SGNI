@extends('layouts.app')

@section('contenido')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Editar Información Estudiantil</h2>
                <p class="text-muted">Modificación de datos del estudiante</p>

                {{-- Alerta de éxito --}}
                <div id="alert-success" class="alert alert-success d-flex align-items-center d-none shadow-sm rounded-3 mb-4"
                    role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                    <div>
                        <strong>¡Éxito!</strong> La información del alumno se ha actualizado correctamente.
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                                <i class="bi bi-pencil-square me-2 fs-4"></i>
                                <span>Editando: {{ $alumno->nombre }} {{ $alumno->ap_pat }} {{ $alumno->ap_mat }}</span>
                            </h5>

                            <form id="form-editar-alumno" novalidate>

                                {{-- Fila 1: Matrícula (solo lectura) + Nombre --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6 col-xl-4">
                                        <label for="matricula" class="form-label text-dark fw-semibold">Matrícula</label>
                                        <input type="number" id="matricula" class="form-control shadow-sm bg-light"
                                            value="{{ $alumno->matricula }}" disabled>
                                        <div class="form-text text-muted">La matrícula no puede modificarse.</div>
                                    </div>
                                    <div class="col-md-6 col-xl-8">
                                        <label for="nombre" class="form-label text-dark fw-semibold">Nombre(s) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="nombre" id="nombre" class="form-control shadow-sm"
                                            value="{{ $alumno->nombre }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- Fila 2: Apellidos --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label for="ap_pat" class="form-label text-dark fw-semibold">Apellido paterno
                                            <span class="text-danger">*</span></label>
                                        <input type="text" name="ap_pat" id="ap_pat" class="form-control shadow-sm"
                                            value="{{ $alumno->ap_pat }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ap_mat" class="form-label text-dark fw-semibold">Apellido
                                            materno</label>
                                        <input type="text" name="ap_mat" id="ap_mat" class="form-control shadow-sm"
                                            value="{{ $alumno->ap_mat ?? '' }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- Fila 3: Correo alternativo + Teléfono --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label for="correo_alternativo" class="form-label text-dark fw-semibold">Correo
                                            alternativo</label>
                                        <input type="email" name="correo_alternativo" id="correo_alternativo"
                                            class="form-control shadow-sm"
                                            value="{{ $alumno->correo_alternativo ?? '' }}"
                                            placeholder="ejemplo@gmail.com">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label text-dark fw-semibold">Teléfono <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" name="telefono" id="telefono" class="form-control shadow-sm"
                                            value="{{ $alumno->telefono }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- Fila 4: Correo institucional + Puntaje --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label for="correo_institucional" class="form-label text-dark fw-semibold">
                                            Correo institucional
                                        </label>
                                        <input type="email" name="correo_institucional" id="correo_institucional"
                                            class="form-control shadow-sm"
                                            value="{{ $alumno->correo_institucional ?? '' }}"
                                            placeholder="ejemplo@uabc.edu.mx">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="puntaje_ingreso" class="form-label text-dark fw-semibold">
                                            Puntaje de admisión 
                                        </label>
                                        <input type="number" name="puntaje_ingreso" id="puntaje_ingreso"
                                            class="form-control shadow-sm"
                                            value="{{ $alumno->puntaje_ingreso ?? '' }}"
                                            placeholder="Ej: 850" min="0" max="1300">
                                        <div class="form-text text-muted">Máximo 1300 puntos.</div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- Fila 5: Carrera (ancho completo) --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-12">
                                        <label for="id_carrera" class="form-label text-dark fw-semibold">Carrera <span
                                                class="text-danger">*</span></label>
                                        <select name="id_carrera" id="id_carrera" class="form-select shadow-sm" required>
                                            <option value="" disabled>-- Seleccione una carrera --</option>
                                            @foreach ($carreras as $carrera)
                                                <option value="{{ $carrera->id_carrera }}"
                                                    {{ $alumno->id_carrera == $carrera->id_carrera ? 'selected' : '' }}>
                                                    {{ $carrera->nombre_carrera }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <hr class="my-4 border-light-subtle">

                                <div class="d-flex justify-content-between gap-2">
                                    {{-- Botón Regresar a la izquierda --}}
                                    <a href="{{ route('alumnos.info') }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                        <i class="bi bi-arrow-left me-1"></i> Regresar
                                    </a>

                                    {{-- Botones Limpiar y Guardar a la derecha --}}
                                    <div class="d-flex gap-2">
                                        <button type="reset" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                            Limpiar
                                        </button>
                                        <button type="submit" id="btn-guardar"
                                            class="btn btn-outline-dark px-5 fw-semibold rounded-3 d-flex align-items-center gap-2">
                                            <span id="btn-text">Guardar</span>
                                            <span id="btn-spinner" class="spinner-border spinner-border-sm d-none"
                                                aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-editar-alumno');
            const btnGuardar = document.getElementById('btn-guardar');
            const btnText = document.getElementById('btn-text');
            const btnSpinner = document.getElementById('btn-spinner');
            const alertSuccess = document.getElementById('alert-success');
            const matricula = '{{ $alumno->matricula }}';

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Prevención de doble envío
                btnGuardar.disabled = true;
                btnText.classList.add('d-none');
                btnSpinner.classList.remove('d-none');
                alertSuccess.classList.add('d-none');

                // Limpiar errores visuales
                form.querySelectorAll('.form-control, .form-select').forEach(input => {
                    input.classList.remove('is-invalid');
                    const feedback = input.parentElement.querySelector('.invalid-feedback');
                    if (feedback) feedback.innerText = '';
                });

                const data = Object.fromEntries(new FormData(form).entries());

                fetch(`/alumnos/${matricula}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    })
                    .then(async response => {
                        const resData = await response.json();
                        if (!response.ok) throw {
                            status: response.status,
                            data: resData
                        };
                        return resData;
                    })
                    .then(() => {
                        alertSuccess.classList.remove('d-none');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    })
                    .catch(error => {
                        if (error.status === 422 && error.data.errors) {
                            for (const field in error.data.errors) {
                                const input = document.getElementById(field);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.parentElement.querySelector('.invalid-feedback');
                                    if (feedback) feedback.innerText = error.data.errors[field][0];
                                }
                            }
                        } else {
                            alert('Ocurrió un error en el servidor. Revise la consola.');
                            console.error(error);
                        }
                    })
                    .finally(() => {
                        btnGuardar.disabled = false;
                        btnText.classList.remove('d-none');
                        btnSpinner.classList.add('d-none');
                    });
            });
        });
    </script>
@endsection