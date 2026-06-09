@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-1">
        {{-- Alineado por completo hacia la izquierda --}}
        <div class="row">
            <div class="col-12">

                {{-- Encabezado de la pantalla --}}
                <div class="mb-4">
                    <h1 class="fw-bold text-dark mb-1">Criterios para la Creación de Grupos Finales</h1>
                    <p class="text-dark small mb-0">Configure los criterios para la asignación de grupos finales del primer
                        semestre</p>
                </div>

                {{-- Tarjeta principal de configuracion --}}
                <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-dark text-uppercase d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-clipboard-check-fill text-primary fs-4"></i>
                            <span>Criterios de Asignación</span>
                        </h5>

                        <p class="text-dark small mb-4">
                            Defina el balance porcentual para la distribucion de alumnos en cada grupo. El sistema
                            segmentara a los estudiantes basándose en su puntaje de ingreso (900 a 1300 puntos). La suma de
                            ambos lados debe dar exactamente 100%.
                        </p>

                        {{-- Formulario de criterios estructurado en paralelo (lado a lado) --}}
                        <form action="{{ route('grupos_finales.generar') }}" method="POST" id="form-criterios">
                            @csrf

                            <div class="row g-4">

                                {{-- Columna Izquierda: Alumnos de Puntaje Alto --}}
                                <div class="col-12 col-md-6">
                                    <div class="p-4 rounded-3 bg-light border border-light-subtle">
                                        <label for="porcentaje-alto"
                                            class="form-label text-uppercase fw-bold text-dark small d-block mb-3">
                                            Alumnos con Puntaje Alto (%)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" id="porcentaje-alto" name="porcentaje_alto"
                                                class="form-control form-control-lg text-center fw-bold rounded-3 bg-white border-0 py-3 fs-2 shadow-none"
                                                value="80" min="1" max="99" required>
                                        </div>
                                        <span class="text-dark small d-block mt-2 text-center-sm">
                                            Estudiantes con los mejores puntajes de admision.
                                        </span>
                                    </div>
                                </div>

                                {{-- Columna Derecha: Alumnos de Puntaje Bajo --}}
                                <div class="col-12 col-md-6">
                                    <div class="p-4 rounded-3 bg-light border border-light-subtle">
                                        <label for="porcentaje-bajo"
                                            class="form-label text-uppercase fw-bold text-dark small d-block mb-3">
                                            Alumnos con Puntaje Bajo (%)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" id="porcentaje-bajo" name="porcentaje_bajo"
                                                class="form-control form-control-lg text-center fw-bold rounded-3 bg-white border-0 py-3 fs-2 shadow-none"
                                                value="20" min="1" max="99" required>
                                        </div>
                                        <span class="text-dark small d-block mt-2 text-center-sm">
                                            Estudiantes con puntajes de admision en la terna baja.
                                        </span>
                                    </div>
                                </div>

                            </div>

                            {{-- Pie de tarjeta unificado con botones en los extremos --}}
                            <hr class="my-4 border-light-subtle">
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                {{-- Boton regresar protegido con historial nativo --}}
                                <button type="button" id="btn-regresar-dinamico"
                                    class="btn btn-outline-dark px-4 fw-semibold rounded-3 d-inline-flex align-items-center gap-2">
                                    <span>Regresar</span>
                                </button>
                                <button type="submit" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                    Generar Distribución
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Script optimizado para calculo espejo en tiempo real y envio AJAX --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formCriterios = document.getElementById('form-criterios');
            const btnSubmit = formCriterios.querySelector('button[type="submit"]');
            const inputAlto = document.getElementById('porcentaje-alto');
            const inputBajo = document.getElementById('porcentaje-bajo');

            // =========================================================
            // LÓGICA MODO ESPEJO DINÁMICO (Suma exacta de 100%)
            // =========================================================
            inputAlto.addEventListener('input', function() {
                let valorAlto = parseInt(inputAlto.value) || 0;
                if (valorAlto >= 1 && valorAlto <= 99) {
                    inputBajo.value = 100 - valorAlto;
                }
            });

            inputBajo.addEventListener('input', function() {
                let valorBajo = parseInt(inputBajo.value) || 0;
                if (valorBajo >= 1 && valorBajo <= 99) {
                    inputAlto.value = 100 - valorBajo;
                }
            });
            // =========================================================

            // Envio del formulario por AJAX mediante Fetch
            formCriterios.addEventListener('submit', function (e) {
                e.preventDefault();

                const valorAlto = parseInt(inputAlto.value) || 0;
                const valorBajo = parseInt(inputBajo.value) || 0;

                // Validacion de seguridad local
                if (valorAlto + valorBajo !== 100) {
                    alert('Inconsistencia: La suma de ambos porcentajes debe ser exactamente igual a 100%.');
                    return;
                }

                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generando...';

                const formData = new FormData(formCriterios);

                fetch(formCriterios.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw response;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.redirect_url) {
                        // Redirecciona a la vista general de los grupos generados
                        window.location.href = data.redirect_url;
                    } else {
                        alert(data.message || 'Distribución completada con éxito.');
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = 'Generar Distribución';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.json) {
                        error.json().then(errData => {
                            alert(errData.message || 'Ocurrió un problema al procesar la distribución.');
                        });
                    } else {
                        alert('Ocurrió un error interno en el servidor.');
                    }
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = 'Generar Distribución';
                });
            });

            // Logica del boton regresar basado en longitud del historial
            document.getElementById('btn-regresar-dinamico').addEventListener('click', function() {
                if (window.history.length > 1) {
                    window.history.back();
                }
            });
        });
    </script>
@endsection
