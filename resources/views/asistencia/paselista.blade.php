@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-2">
    
    <div class="mb-4">
        <h1 class="fw-bold text-dark mb-1">Pase de Lista</h1>
        <p class="text-dark fw-semibold" style="opacity: 0.7;">Grupo: Ingeniería - Grupo A</p>
        
        <a href="{{ route('asistencias.importar') }}" class="btn btn-primary text-white fw-bold px-4 py-2 mt-2 shadow-sm rounded-3 border-0">
            {{-- Usamos text-secondary para el amarillo UABC, cero estilos en línea --}}
            <i class="bi bi-file-earmark-excel me-2 text-secondary fs-5"></i> IMPORTAR LISTA EXCEL
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        <div class="card-header bg-primary text-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Lista de Asistencia Semanal</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light text-dark small fw-bold text-uppercase" style="opacity: 0.8;">
                        <tr>
                            <th class="ps-4 py-3 border-bottom-0">Matrícula</th>
                            <th class="py-3 border-bottom-0">Nombre Completo</th>
                            <th class="text-center py-3 border-bottom-0">Lunes</th>
                            <th class="text-center py-3 border-bottom-0">Martes</th>
                            <th class="text-center py-3 border-bottom-0">Miércoles</th>
                            <th class="text-center py-3 border-bottom-0">Jueves</th>
                            <th class="text-center py-3 border-bottom-0">Viernes</th>
                        </tr>
                    </thead>
                    
                    <tbody class="border-top-0">
                        {{-- Fila 1 --}}
                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123456</td>
                            <td class="fw-bold text-dark">Ana María González López</td>
                            {{-- Checkboxes limpios. Bootstrap los pintará del verde $primary al marcarlos --}}
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                        </tr>

                        {{-- Fila 2 --}}
                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123457</td>
                            <td class="fw-bold text-dark">Carlos Eduardo Martínez Torres</td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                        </tr>
                        
                        {{-- Fila 3 --}}
                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123458</td>
                            <td class="fw-bold text-dark">Diana Patricia Rodríguez Cruz</td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                        </tr>

                        {{-- Fila 4 --}}
                        <tr>
                            <td class="ps-4 text-dark fw-bold py-3">00123459</td>
                            <td class="fw-bold text-dark">Fernando Javier Hernández Pérez</td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                            <td class="text-center"><input class="form-check-input fs-4 shadow-sm" type="checkbox" checked></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top py-4 px-4 d-flex justify-content-between align-items-center">
            <span class="text-dark fw-bold small" style="opacity: 0.7;">8 estudiantes registrados</span>
            
            <button class="btn btn-secondary text-dark fw-bold px-5 py-2 rounded-3 shadow-sm border-0">
                GUARDAR
            </button>
        </div>
    </div>
</div>
@endsection