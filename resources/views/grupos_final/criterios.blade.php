@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold text-dark">Criterios y Creación de Grupos Finales</h2>
            <p class="text-muted">Configure los criterios para la asignación de grupos finales del primer semestre</p>

            <div class="mb-4">
                <div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 p-md-5">

                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            <i class="bi bi-file-earmark-text-fill me-2 fs-4"></i>
                            <span>Criterios de Asignación</span>
                        </h5>

                    <div class="d-flex flex-column gap-3 mb-4">

                        <div class="p-3 border border-light rounded-3 bg-light position-relative">
                            <button type="button"
                                class="btn-close position-absolute top-0 end-0 m-3 text-danger bg-danger-subtle rounded-circle p-2"
                                aria-label="Close"></button>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-dark fw-semibold">
                                        Nombre del Criterio
                                    </label>
                                    <input type="text" class="form-control shadow-sm bg-white fw-semibold"
                                        value="Promedios Altos de Alumnos" readonly>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label text-dark fw-semibold">
                                        Valor Mínimo
                                    </label>
                                    <input type="number"
                                        class="form-control shadow-sm bg-white text-center fw-bold"
                                        value="85" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 border border-light rounded-3 bg-light position-relative">
                            <button type="button"
                                class="btn-close position-absolute top-0 end-0 m-3 text-danger bg-danger-subtle rounded-circle p-2"
                                aria-label="Close"></button>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-dark fw-semibold">
                                        Nombre del Criterio
                                    </label>
                                    <input type="text" class="form-control shadow-sm bg-white fw-semibold"
                                        value="Promedios Bajos" readonly>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label text-dark fw-semibold">
                                        Valor Mínimo
                                    </label>
                                    <input type="number"
                                        class="form-control shadow-sm bg-white text-center fw-bold"
                                        value="70" readonly>
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr class="my-4 border-light-subtle">

                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <label class="form-label text-dark fw-semibold">
                                Agregar Nuevo Criterio
                            </label>
                            <div class="input-group shadow-sm">
                                <input type="text"
                                    class="form-control"
                                    placeholder="Nombre del criterio (ej: Asistencia Mínima)">
                                <button class="btn btn-outline-dark px-5 fw-semibold rounded-3"
                                    type="button">
                                    Crear
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <button type="button"
                            class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold px-3 text-dark border-light bg-light">+
                            Promedios Altos</button>
                        <button type="button"
                            class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold px-3 text-dark border-light bg-light">+
                            Promedios Bajos</button>
                        <button type="button"
                            class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold px-3 text-dark border-light bg-light">+
                            Asistencia</button>
                        <button type="button"
                            class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold px-3 text-dark border-light bg-light">+
                            Examen Diagnóstico</button>
                        <button type="button"
                            class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold px-3 text-dark border-light bg-light">+
                            Examen Final</button>
                        <button type="button"
                            class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold px-3 text-dark border-light bg-light">+
                            Puntaje de Admisión</button>
                    </div>

                    <div class="alert alert-info border-0 mb-0 p-4 rounded-3 shadow-sm text-start" role="alert">
                        <p class="mb-0 fs-5 text-dark lh-base">
                            <strong>Importante:</strong> Los estudiantes que cumplan con estos criterios serán asignados automáticamente a sus grupos
                            finales del primer semestre según su carrera (Arquitectura o Tronco Común de Ingeniería).
                        </p>
                    </div>

                    <hr class="my-4 border-light-subtle">

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                            Crear
                        </button>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
