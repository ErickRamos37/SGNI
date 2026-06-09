@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-1">
        {{-- Alineado por completo hacia la izquierda --}}
        <div class="row">
            <div class="col-12">

                {{-- Encabezado de la pantalla --}}
                <div class="mb-4">
                    <h1 class="fw-bold text-dark mb-1">Criterios para la Creación de Grupos Finales</h1>
                    {{-- Ajustado a text-dark para un tono gris oscuro de alto contraste --}}
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

                        {{-- Ajustado a text-dark para el parrafo informativo --}}
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
                                        {{-- Ajustado a text-dark --}}
                                        <label for="porcentaje-alto"
                                            class="form-label text-uppercase fw-bold text-dark small d-block mb-3">
                                            Alumnos con Puntaje Alto (%)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" id="porcentaje-alto" name="porcentaje_alto"
                                                class="form-control form-control-lg text-center fw-bold rounded-3 bg-white border-0 py-3 fs-2 shadow-none"
                                                value="80" min="1" max="99" required>
                                        </div>
                                        {{-- Ajustado a text-dark --}}
                                        <span class="text-dark small d-block mt-2 text-center-sm">
                                            Estudiantes con los mejores puntajes de admision.
                                        </span>
                                    </div>
                                </div>

                                {{-- Columna Derecha: Alumnos de Puntaje Bajo --}}
                                <div class="col-12 col-md-6">
                                    <div class="p-4 rounded-3 bg-light border border-light-subtle">
                                        {{-- Ajustado a text-dark --}}
                                        <label for="porcentaje-bajo"
                                            class="form-label text-uppercase fw-bold text-dark small d-block mb-3">
                                            Alumnos con Puntaje Bajo (%)
                                        </label>
                                        <div class="input-group">
                                            <input type="number" id="porcentaje-bajo" name="porcentaje_bajo"
                                                class="form-control form-control-lg text-center fw-bold rounded-3 bg-white border-0 py-3 fs-2 shadow-none"
                                                value="20" min="1" max="99" required>
                                        </div>
                                        {{-- Ajustado a text-dark --}}
                                        <span class="text-dark small d-block mt-2 text-center-sm">
                                            Estudiantes con puntajes de admision en la terna baja.
                                        </span>
                                    </div>
                                </div>

                            </div>

                            {{-- Pie de tarjeta unificado con botones en los extremos --}}
                            <hr class="my-4 border-light-subtle">
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="button" onclick="window.history.back();"
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

    {{-- Script de validacion dinamica espejo usando jQuery --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const formCriterios = document.getElementById('form-criterios');
            const btnSubmit = formCriterios.querySelector('button[type="submit"]');

            formCriterios.addEventListener('submit', function (e) {
                e.preventDefault(); // Detenemos el viaje normal de HTML

                // Desactivamos el botón visualmente para evitar doble clic (Regla del SGNI)
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generando...';

                // Recolectamos el 80 y el 20
                const formData = new FormData(formCriterios);

                // Enviamos los datos al Controlador silenciosamente
                fetch(formCriterios.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',        // ¡AGREGA ESTA LÍNEA! (Obligatorio en tu arquitectura SGNI)
                        'X-Requested-With': 'XMLHttpRequest' // Le avisa a Laravel que es AJAX
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.redirect_url) {
                            // ¡Aquí ocurre la magia! El servidor nos mandó la URL y JS nos redirige a la tabla
                            window.location.href = data.redirect_url;
                        } else if (data.message) {
                            alert(data.message); // Si hubo un error (ej. la suma no da 100)
                            btnSubmit.disabled = false;
                            btnSubmit.innerHTML = 'Generar Distribución';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Ocurrió un error al procesar los grupos.');
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = 'Generar Distribución';
                    });
            });
        });
    </script>
@endsection