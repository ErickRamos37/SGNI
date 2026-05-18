@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            
            <h1 class="fw-bold mb-1 text-primary">Panel de Seguimiento Psicológico</h1>
            <p class="text-muted mb-4">Monitoreo de riesgo académico, asistencias y documentación</p>
            
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <h5 class="fw-bold text-uppercase text-secondary mb-4 small tracking-wide">
                        TABLA DE SEGUIMIENTO DE RIESGO
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase text-secondary small fw-bold">
                                <tr>
                                    <th class="ps-3 py-3">Matrícula</th>
                                    <th class="py-3">Nombre del Alumno</th>
                                    <th class="py-3">Correo Institucional</th>
                                    <th class="py-3">Programa</th>
                                    <th class="py-3 text-center">Estatus de Riesgo</th>
                                    <th class="py-3 text-center">% Asistencias</th>
                                    <th class="py-3 text-center pe-3">Mejoría</th>
                                </tr>
                            </thead>
                            <tbody class="small text-dark">
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123456</td>
                                    <td>Ana María González López</td>
                                    <td class="text-muted">ana.gonzalez@uabc.edu.mx</td>
                                    <td>Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-danger px-3 py-2 text-uppercase fw-bold text-white tracking-wider">Riesgo Alto</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-danger bg-danger bg-opacity-10">63%</td>
                                    <td class="text-center fw-bold text-primary pe-3 fs-5">+10</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123457</td>
                                    <td>Carlos Javier Ramírez Torres</td>
                                    <td class="text-muted">carlos.ramirez@uabc.edu.mx</td>
                                    <td>Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-danger px-3 py-2 text-uppercase fw-bold text-white tracking-wider">Riesgo Alto</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-warning bg-warning bg-opacity-10">75%</td>
                                    <td class="text-center fw-bold text-primary pe-3 fs-5">+8</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123458</td>
                                    <td>María Elena Sánchez Cruz</td>
                                    <td class="text-muted">maria.sanchez@uabc.edu.mx</td>
                                    <td>Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-secondary px-3 py-2 text-uppercase fw-bold text-dark tracking-wider">Riesgo Medio</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-warning bg-warning bg-opacity-10">78%</td>
                                    <td class="text-center fw-bold text-primary pe-3 fs-5">+8</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123459</td>
                                    <td>José Luis Hernández Pérez</td>
                                    <td class="text-muted">jose.hernandez@uabc.edu.mx</td>
                                    <td>Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-secondary px-3 py-2 text-uppercase fw-bold text-dark tracking-wider">Riesgo Medio</span>
                                    </td>
                                    <td class="text-center py-3">89%</td>
                                    <td class="text-center fw-bold text-primary pe-3 fs-5">+7</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123460</td>
                                    <td>Laura Patricia Morales García</td>
                                    <td class="text-muted">laura.morales@uabc.edu.mx</td>
                                    <td>Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-success px-3 py-2 text-uppercase fw-bold text-white tracking-wider">Regular</span>
                                    </td>
                                    <td class="text-center py-3 fw-bold">100%</td>
                                    <td class="text-center fw-bold text-primary pe-3 fs-5">+7</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123461</td>
                                    <td>Roberto Carlos Jiménez Vega</td>
                                    <td class="text-muted">roberto.jimenez@uabc.edu.mx</td>
                                    <td>Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-success px-3 py-2 text-uppercase fw-bold text-white tracking-wider">Regular</span>
                                    </td>
                                    <td class="text-center py-3 fw-bold">100%</td>
                                    <td class="text-center fw-bold text-primary pe-3 fs-5">+7</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123462</td>
                                    <td>Gabriela Fernández Ruiz</td>
                                    <td class="text-muted">gabriela.fernandez@uabc.edu.mx</td>
                                    <td>Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-danger px-3 py-2 text-uppercase fw-bold text-white tracking-wider">Riesgo Alto</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-danger bg-danger bg-opacity-10">50%</td>
                                    <td class="text-center fw-bold text-primary pe-3 fs-5">+8</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123463</td>
                                    <td>Diego Alejandro Castro Mendoza</td>
                                    <td class="text-muted">diego.castro@uabc.edu.mx</td>
                                    <td>Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-success px-3 py-2 text-uppercase fw-bold text-white tracking-wider">Regular</span>
                                    </td>
                                    <td class="text-center py-3 fw-bold">100%</td>
                                    <td class="text-center fw-bold text-primary pe-3 fs-5">+6</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection