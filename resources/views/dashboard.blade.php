@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">

    <h1 class="mb-2 fw-bold text-success">Dashboard</h1>
    <p class="text-muted mb-4">Semana Inicial de Matemáticas</p>

    {{-- GRUPO A --}}
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-link text-success p-0 me-2 toggle-btn">▼</button>
                <strong>Grupo A - Ingeniería</strong>
            </div>
            <div>
                <span class="text-muted me-3">4/4 completados</span>
                <span class="badge rounded-pill border border-success text-success">4 estudiantes</span>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Carrera</th>
                        <th>Calificación</th>
                        <th>Asistencia</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">202301</td>
                        <td>Ana García Martínez</td>
                        <td>Ingeniería Civil</td>
                        <td class="fw-bold">95</td>
                        <td>100%</td>
                        <td><span class="badge bg-success rounded-pill">Completada</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">202302</td>
                        <td>Carlos Rodríguez López</td>
                        <td>Ingeniería Mecánica</td>
                        <td class="fw-bold">78</td>
                        <td>85%</td>
                        <td><span class="badge bg-success rounded-pill">Completada</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">202305</td>
                        <td>Sofía González Ruiz</td>
                        <td>Ingeniería Civil</td>
                        <td class="fw-bold">92</td>
                        <td>100%</td>
                        <td><span class="badge bg-success rounded-pill">Completada</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">202307</td>
                        <td>Laura Martínez Hernández</td>
                        <td>Ingeniería Mecánica</td>
                        <td class="fw-bold">82</td>
                        <td>90%</td>
                        <td><span class="badge bg-success rounded-pill">Completada</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- GRUPO B --}}
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-link text-success p-0 me-2 toggle-btn">▼</button>
                <strong>Grupo B - Diseño y Arquitectura</strong>
            </div>
            <div>
                <span class="text-muted me-3">1/4 completados</span>
                <span class="badge rounded-pill border border-success text-success">4 estudiantes</span>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table mb-0 align-middle">
                <tbody>
                    <tr>
                        <td class="fw-bold">202303</td>
                        <td>María Fernández Silva</td>
                        <td>Arquitectura</td>
                        <td class="fw-bold">45</td>
                        <td>60%</td>
                        <td><span class="badge bg-danger rounded-pill">Incompleta</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">202304</td>
                        <td>Juan Pérez Morales</td>
                        <td>Diseño Gráfico</td>
                        <td class="fw-bold">88</td>
                        <td>95%</td>
                        <td><span class="badge bg-success rounded-pill">Completada</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">202306</td>
                        <td>Diego Sánchez Torres</td>
                        <td>Arquitectura</td>
                        <td class="fw-bold">35</td>
                        <td>50%</td>
                        <td><span class="badge bg-danger rounded-pill">Incompleta</span></td>
                    </tr>
                    <tr>
                        <td class="fw-bold">202308</td>
                        <td>Miguel Ángel Ramírez</td>
                        <td>Diseño Industrial</td>
                        <td class="fw-bold">--</td>
                        <td>--</td>
                        <td><span class="badge bg-danger rounded-pill">Incompleta</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsectionphp artisan serve
