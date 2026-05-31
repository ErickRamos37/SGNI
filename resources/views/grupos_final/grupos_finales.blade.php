@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-4">

        <div class="mb-4">
            <h1 class="fw-bolder text-dark mb-1 display-6">Grupos Finales - Primer Semestre</h1>
            <p class="text-muted">Grupos conformados para Arquitectura y Tronco Común de Ingeniería</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">

                <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">

                    <div class="bg-primary text-white px-4 py-3">
                        <h4 class="mb-0 fw-bold text-uppercase tracking-wider fs-5">Listado de Grupos Finales</h4>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">

                                <thead class="table-light border-bottom text-uppercase text-muted small fw-bold">
                                    <tr>
                                        <th class="py-3 ps-4" style="width: 20%;">Nombre del Grupo</th>
                                        <th class="py-3" style="width: 35%;">Programa</th>
                                        <th class="py-3 text-center" style="width: 15%;">Turno</th>
                                        <th class="py-3 text-center" style="width: 15%;">Total Estudiantes</th>
                                        <th class="py-3 text-center" style="width: 15%;">Lista</th>
                                    </tr>
                                </thead>

                                <tbody class="border-0">

                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 fw-bold text-dark">Grupo 11</td>
                                        <td class="fw-semibold text-primary">
                                            <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            <i class="bi bi-people me-1 text-muted"></i> 35
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 fw-bold text-dark">Grupo 12</td>
                                        <td class="fw-semibold text-primary">
                                            <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            <i class="bi bi-people me-1 text-muted"></i> 34
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 fw-bold text-dark">Grupo 14</td>
                                        <td class="fw-semibold text-secondary">
                                            <i class="bi bi-bank text-secondary me-1"></i> Arquitectura
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            <i class="bi bi-people me-1 text-muted"></i> 32
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 fw-bold text-dark">Grupo 17</td>
                                        <td class="fw-semibold text-primary">
                                            <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            <i class="bi bi-people me-1 text-muted"></i> 36
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 fw-bold text-dark">Grupo 19</td>
                                        <td class="fw-semibold text-secondary">
                                            <i class="bi bi-bank text-secondary me-1"></i> Arquitectura
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            <i class="bi bi-people me-1 text-muted"></i> 33
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 fw-bold text-dark">Grupo 15</td>
                                        <td class="fw-semibold text-primary">
                                            <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2 fw-bold">Intermedio</span>
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            <i class="bi bi-people me-1 text-muted"></i> 30
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 fw-bold text-dark">Grupo 18</td>
                                        <td class="fw-semibold text-secondary">
                                            <i class="bi bi-bank text-secondary me-1"></i> Arquitectura
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2 fw-bold">Intermedio</span>
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            <i class="bi bi-people me-1 text-muted"></i> 28
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 fw-bold text-dark">Grupo 13</td>
                                        <td class="fw-semibold text-secondary">
                                            <i class="bi bi-bank text-secondary me-1"></i> Arquitectura
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-dark-subtle text-dark-emphasis rounded-pill px-3 py-2 fw-bold">Vespertino</span>
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            <i class="bi bi-people me-1 text-muted"></i> 31
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                            </button>
                                        </td>
                                    </tr>

                                    <tr class="border-bottom border-0">
                                        <td class="ps-4 fw-bold text-dark">Grupo 16</td>
                                        <td class="fw-semibold text-primary">
                                            <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge bg-dark-subtle text-dark-emphasis rounded-pill px-3 py-2 fw-bold">Vespertino</span>
                                        </td>
                                        <td class="text-center fw-bold text-dark">
                                            <i class="bi bi-people me-1 text-muted"></i> 29
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm fw-bold px-3 rounded-3">
                                                <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                            </button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div
                        class="card-footer bg-light px-4 py-3 border-top border-light rounded-bottom-3 text-muted small fw-bold">
                        9 grupos mostrados - Total de estudiantes asignados: 288
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
