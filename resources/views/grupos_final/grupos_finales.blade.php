@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    {{-- ─── Encabezado de página ─────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Grupos Finales - Primer Semestre</h2>
            <p class="text-muted mb-0">Grupos conformados para Arquitectura y Tronco Común de Ingeniería</p>
        </div>
    </div>

    {{-- ─── Tarjeta contenedora ───────────────────────────────────── --}}
    <div class="card border border-light-subtle shadow-sm rounded-3">

        <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
            <h5 class="fw-bold text-uppercase text-white mb-0">Listado de Grupos Finales</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3">Nombre del Grupo</th>
                            <th class="py-3">Programa</th>
                            <th class="py-3 text-center">Turno</th>
                            <th class="py-3 text-center">Total Estudiantes</th>
                            <th class="py-3 text-center">Lista</th>
                        </tr>
                    </thead>

                    <tbody class="small">

                        <tr>
                            <td class="px-4 fw-bold text-dark">Grupo 11</td>
                            <td class="fw-semibold text-primary">
                                <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-people me-1 text-muted"></i> 35
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3">
                                    <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 fw-bold text-dark">Grupo 12</td>
                            <td class="fw-semibold text-primary">
                                <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-people me-1 text-muted"></i> 34
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3">
                                    <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 fw-bold text-dark">Grupo 14</td>
                            <td class="fw-semibold text-secondary">
                                <i class="bi bi-bank text-secondary me-1"></i> Arquitectura
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-people me-1 text-muted"></i> 32
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3">
                                    <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 fw-bold text-dark">Grupo 17</td>
                            <td class="fw-semibold text-primary">
                                <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-people me-1 text-muted"></i> 36
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3">
                                    <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 fw-bold text-dark">Grupo 19</td>
                            <td class="fw-semibold text-secondary">
                                <i class="bi bi-bank text-secondary me-1"></i> Arquitectura
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-2 fw-bold">Matutino</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-people me-1 text-muted"></i> 33
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3">
                                    <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 fw-bold text-dark">Grupo 15</td>
                            <td class="fw-semibold text-primary">
                                <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2 fw-bold">Intermedio</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-people me-1 text-muted"></i> 30
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3">
                                    <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 fw-bold text-dark">Grupo 18</td>
                            <td class="fw-semibold text-secondary">
                                <i class="bi bi-bank text-secondary me-1"></i> Arquitectura
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info-emphasis rounded-pill px-3 py-2 fw-bold">Intermedio</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-people me-1 text-muted"></i> 28
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3">
                                    <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 fw-bold text-dark">Grupo 13</td>
                            <td class="fw-semibold text-secondary">
                                <i class="bi bi-bank text-secondary me-1"></i> Arquitectura
                            </td>
                            <td class="text-center">
                                <span class="badge bg-dark-subtle text-dark-emphasis rounded-pill px-3 py-2 fw-bold">Vespertino</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-people me-1 text-muted"></i> 31
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3">
                                    <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="px-4 fw-bold text-dark">Grupo 16</td>
                            <td class="fw-semibold text-primary">
                                <i class="bi bi-gear text-primary me-1"></i> Tronco Común de Ingeniería
                            </td>
                            <td class="text-center">
                                <span class="badge bg-dark-subtle text-dark-emphasis rounded-pill px-3 py-2 fw-bold">Vespertino</span>
                            </td>
                            <td class="text-center fw-bold text-dark">
                                <i class="bi bi-people me-1 text-muted"></i> 29
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-dark btn-sm fw-semibold px-3 rounded-3">
                                    <i class="bi bi-eye-fill me-1"></i> Ver Lista
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top border-light-subtle p-3 text-muted small fw-bold">
            9 grupos mostrados - Total de estudiantes asignados: 288
        </div>

    </div>{{-- /card --}}

</div>
@endsection
