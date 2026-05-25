@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-4">

        <div class="mb-4">
            <h1 class="fw-bolder text-dark mb-1 display-6">Criterios y Creación de Grupos Finales</h1>
            <p class="text-muted">Configure los criterios para la asignación de grupos finales del primer semestre</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">

                <div class="card border-0 shadow-sm rounded-4 bg-white p-4">

                    <div class="d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-file-earmark-text-fill text-secondary fs-4"></i>
                        <h5 class="mb-0 fw-bold text-uppercase text-secondary tracking-wider fs-6">
                            Criterios de Asignación
                        </h5>
                    </div>

                    <div class="d-flex flex-column gap-3 mb-4">

                        <div class="p-3 border border-light rounded-3 bg-light position-relative">
                            <button type="button"
                                class="btn-close position-absolute top-0 end-0 m-3 text-danger bg-danger-subtle rounded-circle p-2"
                                aria-label="Close"></button>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">
                                        Nombre del Criterio
                                    </label>
                                    <input type="text" class="form-control border-light shadow-none bg-white fw-semibold"
                                        value="Promedios Altos de Alumnos" readonly>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">
                                        Valor Mínimo
                                    </label>
                                    <input type="number"
                                        class="form-control border-light shadow-none bg-white text-center fw-bold"
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
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">
                                        Nombre del Criterio
                                    </label>
                                    <input type="text" class="form-control border-light shadow-none bg-white fw-semibold"
                                        value="Promedios Bajos" readonly>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-1">
                                        Valor Mínimo
                                    </label>
                                    <input type="number"
                                        class="form-control border-light shadow-none bg-white text-center fw-bold"
                                        value="70" readonly>
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr class="text-muted my-4 opacity-25">

                    <div class="mb-4">
                        <label class="small fw-bold text-muted text-uppercase d-block mb-1">
                            Agregar Nuevo Criterio
                        </label>
                        <div class="input-group">
                            <input type="text"
                                class="form-control border bg-light small ps-3 shadow-none rounded-start-3"
                                placeholder="Nombre del criterio (ej: Asistencia Mínima)">
                            <button class="btn btn-secondary text-white fw-bold px-4 rounded-end-3 shadow-sm"
                                type="button">
                                <i class="bi bi-plus-lg me-1"></i> Agregar
                            </button>
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

                    <div class="text-end">
                        <button type="button"
                            class="btn btn-secondary text-white fw-bold px-4 py-2 text-uppercase shadow-sm rounded-3">
                            Crear Grupos Finales
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
