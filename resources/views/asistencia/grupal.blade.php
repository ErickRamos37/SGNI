@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-2">
    
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="fw-bold text-dark mb-1">Lista de Asistencia Grupal</h1>
            <p class="text-dark fw-semibold mb-3" style="opacity: 0.7;">Grupo: Ingeniería - Grupo A</p>
            
            <button class="btn text-dark fw-bold px-4 py-2 shadow-sm rounded-3 border-0" style="background-color: #ffc107;">
                <i class="bi bi-download me-2"></i> DESCARGAR LISTA
            </button>
        </div>
        
        <div class="col-md-6 d-flex justify-content-md-end mt-4 mt-md-0">
            <div style="width: 100%; max-width: 350px;">
                <label class="form-label small fw-bold text-dark text-uppercase mb-1" style="opacity: 0.7; font-size: 0.75rem;">Buscar Estudiante</label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-end-0 text-dark" style="opacity: 0.5;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0 text-dark" placeholder="Matrícula o nombre...">
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        <div class="card-header bg-primary text-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Registro de Asistencia Semanal</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark small fw-bold text-uppercase" style="opacity: 0.8;">
                        <tr>
                            <th class="ps-4 py-3 border-bottom-0">Matrícula</th>
                            <th class="py-3 border-bottom-0">Nombre Completo</th>
                            <th class="py-3 border-bottom-0">Correo Institucional</th>
                            <th class="text-center py-3 border-bottom-0">Porcentaje de Asistencia</th>
                        </tr>
                    </thead>
                    
                    <tbody class="border-top-0">
                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123456</td>
                            <td class="fw-bold text-dark">Ana María González López</td>
                            <td class="text-dark" style="opacity: 0.8;">ana.gonzalez@uabc.edu.mx</td>
                            <td class="text-center fw-bold text-dark">100%</td>
                        </tr>

                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123457</td>
                            <td class="fw-bold text-dark">Carlos Eduardo Martínez Torres</td>
                            <td class="text-dark" style="opacity: 0.8;">carlos.martinez@uabc.edu.mx</td>
                            <td class="text-center fw-bold text-dark">80%</td>
                        </tr>
                        
                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123458</td>
                            <td class="fw-bold text-dark">Diana Patricia Rodríguez Cruz</td>
                            <td class="text-dark" style="opacity: 0.8;">diana.rodriguez@uabc.edu.mx</td>
                            <td class="text-center fw-bold text-dark">80%</td>
                        </tr>

                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123459</td>
                            <td class="fw-bold text-dark">Fernando Javier Hernández Pérez</td>
                            <td class="text-dark" style="opacity: 0.8;">fernando.hernandez@uabc.edu.mx</td>
                            <td class="text-center fw-bold text-dark">80%</td>
                        </tr>
                        
                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123460</td>
                            <td class="fw-bold text-dark">Gabriela Alejandra Torres Silva</td>
                            <td class="text-dark" style="opacity: 0.8;">gabriela.torres@uabc.edu.mx</td>
                            <td class="text-center fw-bold text-dark">80%</td>
                        </tr>
                        
                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123461</td>
                            <td class="fw-bold text-dark">Hugo Alberto Ramírez García</td>
                            <td class="text-dark" style="opacity: 0.8;">hugo.ramirez@uabc.edu.mx</td>
                            <td class="text-center fw-bold text-dark">80%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top py-4 px-4">
            <span class="text-dark fw-bold small" style="opacity: 0.7;">8 estudiantes mostrados</span>
        </div>
    </div>
</div>
@endsection