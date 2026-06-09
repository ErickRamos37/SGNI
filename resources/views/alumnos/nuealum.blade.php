@extends('layouts.app')

@section('contenido')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="fw-bold text-dark">Alta de Alumnos Tardíos</h2>
                <p class="text-muted">Registro manual de estudiantes de nuevo ingreso</p>

                {{-- Alerta de éxito --}}
                <div id="alert-success" class="alert alert-success d-flex align-items-center d-none shadow-sm rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                    <div><strong>¡Éxito!</strong> El alumno se ha registrado correctamente en el sistema.</div>
                </div>

                <div class="mb-4">
                    <div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                                <i class="bi bi-person-plus me-2 fs-4"></i>
                                <span>Registrar alumno</span>
                            </h5>

                            <form id="form-nuevo-alumno" novalidate>

                                {{-- Fila 1: Matrícula + Nombre --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6 col-xl-4">
                                        <label for="matricula" class="form-label text-dark fw-semibold">Matrícula <span class="text-danger">*</span></label>
                                        <input type="number" name="matricula" id="matricula" class="form-control shadow-sm" placeholder="Ej: 1234567" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6 col-xl-8">
                                        <label for="nombre" class="form-label text-dark fw-semibold">Nombre(s) <span class="text-danger">*</span></label>
                                        <input type="text" name="nombre" id="nombre" class="form-control shadow-sm" placeholder="Ej: Juan Carlos" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- Fila 2: Apellidos --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label for="ap_pat" class="form-label text-dark fw-semibold">Apellido paterno <span class="text-danger">*</span></label>
                                        <input type="text" name="ap_pat" id="ap_pat" class="form-control shadow-sm" placeholder="Ej: López" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ap_mat" class="form-label text-dark fw-semibold">Apellido materno</label>
                                        <input type="text" name="ap_mat" id="ap_mat" class="form-control shadow-sm" placeholder="Ej: Martínez">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- Fila 3: Correo alternativo + Teléfono --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label for="correo_alternativo" class="form-label text-dark fw-semibold">Correo alternativo <span class="text-danger">*</span></label>
                                        <input type="email" name="correo_alternativo" id="correo_alternativo" class="form-control shadow-sm" placeholder="ejemplo@gmail.com" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label text-dark fw-semibold">Teléfono <span class="text-danger">*</span></label>
                                        <input type="tel" name="telefono" id="telefono" class="form-control shadow-sm" placeholder="Ej: 6861234567" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- Fila 4: Puntaje --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label for="puntaje_ingreso" class="form-label text-dark fw-semibold">Puntaje de admisión</label>
                                        <input type="number" name="puntaje_ingreso" id="puntaje_ingreso" class="form-control shadow-sm" placeholder="Ej: 850" min="0" max="1300">
                                        <div class="form-text text-muted">Máximo 1300 puntos.</div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- Fila 5: Carrera --}}
                                <div class="row g-4 mb-4">
                                    <div class="col-12">
                                        <label for="id_carrera" class="form-label text-dark fw-semibold">Carrera <span class="text-danger">*</span></label>
                                        <select name="id_carrera" id="id_carrera" class="form-select shadow-sm" required>
                                            <option value="" selected disabled>-- Seleccione una carrera --</option>
                                            @foreach ($carreras as $carrera)
                                                <option value="{{ $carrera->id_carrera }}">{{ $carrera->nombre_carrera }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                {{-- Fila 6: Único Select de Grupos --}}
                                <div id="seccion-grupos" class="d-none">
                                    <div class="row g-4 mb-4">
                                        <div class="col-12">
                                            <label for="id_grupo" class="form-label text-dark fw-semibold">Curso / Grupo Asignado</label>
                                            <select name="id_grupo" id="id_grupo" class="form-select shadow-sm">
                                                <option value="">-- Sin asignar --</option>
                                            </select>
                                            <div class="form-text text-muted">Seleccione el curso propedéutico o de inducción correspondiente.</div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 border-light-subtle">

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="reset" class="btn btn-outline-dark px-5 fw-semibold rounded-3">Limpiar</button>
                                    <button type="submit" id="btn-guardar" class="btn btn-outline-dark px-5 fw-semibold rounded-3 d-flex align-items-center gap-2">
                                        <span id="btn-text">Guardar</span>
                                        <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form         = document.getElementById('form-nuevo-alumno');
            const btnGuardar   = document.getElementById('btn-guardar');
            const btnText      = document.getElementById('btn-text');
            const btnSpinner   = document.getElementById('btn-spinner');
            const alertSuccess = document.getElementById('alert-success');
            
            const selectCarrera = document.getElementById('id_carrera');
            const seccionGrupos = document.getElementById('seccion-grupos');
            const selectGrupo   = document.getElementById('id_grupo');

            // ── Cargar grupos/cursos dinámicamente al cambiar carrera ────────
            selectCarrera.addEventListener('change', function () {
                const idCarrera = this.value;
                if (!idCarrera) {
                    seccionGrupos.classList.add('d-none');
                    return;
                }

                // Mostrar estado de carga temporal
                selectGrupo.innerHTML = '<option value="">Cargando cursos disponibles...</option>';
                seccionGrupos.classList.remove('d-none');

                fetch(`/alumnos/grupos-por-carrera/${idCarrera}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(async r => {
                    if (!r.ok) throw new Error(`Error de servidor: ${r.status}`);
                    return r.json();
                })
                .then(data => {
                    selectGrupo.innerHTML = '<option value="">-- Sin asignar --</option>';
                    let tieneCursos = false;

                    // Validar si viene como "propedeutico" o "propedeuticos"
                    const listaPrope = data.propedeutico || data.propedeuticos || [];
                    // Validar si viene como "induccion" o "inducciones"
                    const listaInduc = data.induccion || data.inducciones || [];

                    // Construir Optgroup para Cursos Propedéuticos
                    if (listaPrope && listaPrope.length > 0) {
                        tieneCursos = true;
                        const optgroupPrope = document.createElement('optgroup');
                        optgroupPrope.label = 'Cursos Propedéuticos';
                        
                        listaPrope.forEach(g => {
                            optgroupPrope.innerHTML += `<option value="${g.id_grupo}">${g.nombre_grupo}</option>`;
                        });
                        selectGrupo.appendChild(optgroupPrope);
                    }

                    // Construir Optgroup para Cursos de Inducción
                    if (listaInduc && listaInduc.length > 0) {
                        tieneCursos = true;
                        const optgroupInduc = document.createElement('optgroup');
                        optgroupInduc.label = 'Cursos de Inducción';
                        
                        listaInduc.forEach(g => {
                            optgroupInduc.innerHTML += `<option value="${g.id_grupo}">${g.nombre_grupo}</option>`;
                        });
                        selectGrupo.appendChild(optgroupInduc);
                    }

                    // Mensaje en caso de que ambos bloques estén vacíos
                    if (!tieneCursos) {
                        selectGrupo.innerHTML += '<option value="" disabled>No hay cursos/grupos editables registrados para esta carrera</option>';
                    }
                })
                .catch(error => {
                    console.error("Error al procesar la petición:", error);
                    selectGrupo.innerHTML = '<option value="">-- Error al cargar (Verifica la consola) --</option>';
                });
            });

            // ── Envío vía AJAX del formulario ───────────────────────────────
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                btnGuardar.disabled = true;
                btnText.classList.add('d-none');
                btnSpinner.classList.remove('d-none');
                alertSuccess.classList.add('d-none');

                form.querySelectorAll('.form-control, .form-select').forEach(input => {
                    input.classList.remove('is-invalid');
                    const feedback = input.parentElement.querySelector('.invalid-feedback');
                    if (feedback) feedback.innerText = '';
                });

                const data = Object.fromEntries(new FormData(form).entries());

                fetch('/alumnos', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(async response => {
                    const resData = await response.json();
                    if (!response.ok) throw { status: response.status, data: resData };
                    return resData;
                })
                .then(() => {
                    form.reset();
                    seccionGrupos.classList.add('d-none');
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