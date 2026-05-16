@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            
            <h1 class="fw-bold mb-1" style="color: #00723F;">Panel de Seguimiento Psicológico</h1>
            <p class="text-muted mb-4">Monitoreo de riesgo académico, asistencias y documentación</p>
            
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    
                    <h5 class="fw-bold text-uppercase text-secondary mb-4" style="font-size: 0.9rem; letter-spacing: 0.05em; color: #F1B500 !important;">
                        TABLA DE SEGUIMIENTO DE RIESGO
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase text-secondary" style="font-size: 0.75rem; font-weight: 700;">
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
                            <tbody style="font-size: 0.87rem; color: #333333;">
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123456</td>
                                    <td>Ana María González López</td>
                                    <td class="text-muted">ana.gonzalez@uabc.edu.mx</td>
                                    <td>Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold text-white" style="font-size: 0.7rem; letter-spacing: 0.03em; background-color: #ff0000;">Riesgo Alto</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-danger" style="background-color: rgba(255, 0, 0, 0.1) !important;">63%</td>
                                    <td class="text-center fw-bold pe-3" style="font-size: 1rem; color: #00723F;">+10</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123457</td>
                                    <td>Carlos Javier Ramírez Torres</td>
                                    <td class="text-muted">carlos.ramirez@uabc.edu.mx</td>
                                    <td>Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold text-white" style="font-size: 0.7rem; letter-spacing: 0.03em; background-color: #ff0000;">Riesgo Alto</span>
                                    </td>
                                    <td class="text-center fw-bold py-3" style="background-color: rgba(241, 181, 0, 0.1) !important; color: #d49e00;">75%</td>
                                    <td class="text-center fw-bold pe-3" style="font-size: 1rem; color: #00723F;">+8</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123458</td>
                                    <td>María Elena Sánchez Cruz</td>
                                    <td class="text-muted">maria.sanchez@uabc.edu.mx</td>
                                    <td>Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold text-dark" style="font-size: 0.7rem; letter-spacing: 0.03em; background-color: #F1B500;">Riesgo Medio</span>
                                    </td>
                                    <td class="text-center fw-bold py-3" style="background-color: rgba(241, 181, 0, 0.1) !important; color: #d49e00;">78%</td>
                                    <td class="text-center fw-bold pe-3" style="font-size: 1rem; color: #00723F;">+8</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123459</td>
                                    <td>José Luis Hernández Pérez</td>
                                    <td class="text-muted">jose.hernandez@uabc.edu.mx</td>
                                    <td>Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold text-dark" style="font-size: 0.7rem; letter-spacing: 0.03em; background-color: #F1B500;">Riesgo Medio</span>
                                    </td>
                                    <td class="text-center py-3">89%</td>
                                    <td class="text-center fw-bold pe-3" style="font-size: 1rem; color: #00723F;">+7</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123460</td>
                                    <td>Laura Patricia Morales García</td>
                                    <td class="text-muted">laura.morales@uabc.edu.mx</td>
                                    <td>Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold text-white" style="font-size: 0.7rem; letter-spacing: 0.03em; background-color: #00ff00;">Regular</span>
                                    </td>
                                    <td class="text-center py-3 fw-bold">100%</td>
                                    <td class="text-center fw-bold pe-3" style="font-size: 1rem; color: #00723F;">+7</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123461</td>
                                    <td>Roberto Carlos Jiménez Vega</td>
                                    <td class="text-muted">roberto.jimenez@uabc.edu.mx</td>
                                    <td>Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold text-white" style="font-size: 0.7rem; letter-spacing: 0.03em; background-color: #00ff00;">Regular</span>
                                    </td>
                                    <td class="text-center py-3 fw-bold">100%</td>
                                    <td class="text-center fw-bold pe-3" style="font-size: 1rem; color: #00723F;">+7</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123462</td>
                                    <td>Gabriela Fernández Ruiz</td>
                                    <td class="text-muted">gabriela.fernandez@uabc.edu.mx</td>
                                    <td>Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold text-white" style="font-size: 0.7rem; letter-spacing: 0.03em; background-color: #ff0000;">Riesgo Alto</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-danger" style="background-color: rgba(255, 0, 0, 0.1) !important;">50%</td>
                                    <td class="text-center fw-bold pe-3" style="font-size: 1rem; color: #00723F;">+8</td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold ps-3 py-3">00123463</td>
                                    <td>Diego Alejandro Castro Mendoza</td>
                                    <td class="text-muted">diego.castro@uabc.edu.mx</td>
                                    <td>Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold text-white" style="font-size: 0.7rem; letter-spacing: 0.03em; background-color: #00ff00;">Regular</span>
                                    </td>
                                    <td class="text-center py-3 fw-bold">100%</td>
                                    <td class="text-center fw-bold pe-3" style="font-size: 1rem; color: #00723F;">+6</td>
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