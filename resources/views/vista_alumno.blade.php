@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-primary fw-bold">
                <i class="bi bi-person-badge"></i> Seguimiento Académico y Reporte de Calificaciones
            </h2>
            <p class="text-muted">Visualización dinámica del estatus de los estudiantes y distribución grupal.</p>
        </div>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-book"></i> ETAPA CURSOS PROPEDÉUTICOS DE MATEMATICAS</span>
            <span class="badge bg-primary">Reporte Detallado</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-uabc">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nombre del Alumno</th>
                            <th>Grupo</th>
                            <th class="text-center">Examen 1</th>
                            <th class="text-center">Examen 2</th>                            
                            <th class="text-center">% Asistencia</th>
                            <th class="text-center">Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>367465</td>
                            <td>Ramos Alvarado Erick</td>
                            <td>Grupo 3</td>
                            <td class="text-center fw-bold">95</td>
                            <td class="text-center fw-bold">88</td>                            
                            <td class="text-center">100%</td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-success text-dark">Calificado</span>
                            </td>
                        </tr>
                        <tr>
                            <td>367444</td>
                            <td>Vazquez Figueroa Raul</td>
                            <td>Grupo 3</td>
                            <td class="text-center fw-bold">0</td>
                            <td class="text-center fw-bold">0</td>                            
                            <td class="text-center">0%</td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-warning text-dark">Sin calificar</span>
                            </td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom-color: $primary;">
            <span><i class="bi bi-info-circle"></i> ETAPA DE CURSO DE INDUCCIÓN</span>
            <span class="badge bg-secondary text-dark">Solo Asistencia</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr style="color: #00723F;">
                            <th>Matrícula</th>
                            <th>Nombre del Alumno</th>
                            <th>Grupo</th>
                            <th class="text-center">% Asistencia</th>
                            <th class="text-center">Estatus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>346980</td>
                            <td>Sanchez Dominguez Michelle</td>
                            <td>Grupo 4</td>
                            <td class="text-center">90%</td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-success text-dark">Aprobado</span>
                            </td>
                        </tr>
                        <tr>
                            <td>346978</td>
                            <td>Sanchez Dominguez Michelle</td>
                            <td>Grupo 4</td>
                            <td class="text-center">0%</td>
                            <td class="text-center">
                                <span class="badge rounded-pill bg-warning text-dark">Sin asistencia</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection