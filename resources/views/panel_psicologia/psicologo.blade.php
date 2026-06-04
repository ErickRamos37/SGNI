@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            {{---Encabezado de página + Buscador ---}}
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Seguimiento del Alumno</h2>
                    <p class="text-muted mb-0">Monitoreo de riesgo académico y asistencias</p>
                </div>
                <div class="w-25">
                    <label class="small fw-bold text-muted text-uppercase mb-1">Buscar Alumno</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="search-seguimiento" class="form-control border-start-0 ps-0" placeholder="Matrícula, nombre, correo...">
                    </div>
                </div>
            </div>

            {{--- Tarjeta contenedora ---}}
            <div class="card border border-light-subtle shadow-sm rounded-3">

                <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-uppercase text-white mb-0">TABLA DE SEGUIMIENTO DE RIESGO</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3">Matrícula</th>
                                    <th class="py-3">Nombre del Alumno</th>
                                    <th class="py-3">Correo Institucional</th>
                                    <th class="py-3">Programa</th>
                                    <th class="py-3 text-center">Estatus de Riesgo</th>
                                    <th class="py-3 text-center">Asistencias</th>
                                    <th class="py-3 text-center">Mejoría</th>
                                </tr>
                            </thead>

                            <tbody class="small">

                                <tr class="seguimiento-row" data-search="00123456 ana maría gonzález lópez ana.gonzalez@uabc.edu.mx propedéutico riesgo alto">
                                    <td class="px-4 fw-bold text-dark">00123456</td>
                                    <td class="text-dark">Ana María González López</td>
                                    <td class="text-muted">ana.gonzalez@uabc.edu.mx</td>
                                    <td class="text-dark">Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-danger px-3 py-2 text-uppercase fw-bold text-white">Riesgo Alto</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-danger bg-danger bg-opacity-10">63%</td>
                                    <td class="text-center fw-bold text-primary fs-5">+10</td>
                                </tr>

                                <tr class="seguimiento-row" data-search="00123457 carlos javier ramírez torres carlos.ramirez@uabc.edu.mx propedéutico riesgo alto">
                                    <td class="px-4 fw-bold text-dark">00123457</td>
                                    <td class="text-dark">Carlos Javier Ramírez Torres</td>
                                    <td class="text-muted">carlos.ramirez@uabc.edu.mx</td>
                                    <td class="text-dark">Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-danger px-3 py-2 text-uppercase fw-bold text-white">Riesgo Alto</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-warning bg-warning bg-opacity-10">75%</td>
                                    <td class="text-center fw-bold text-primary fs-5">+8</td>
                                </tr>

                                <tr class="seguimiento-row" data-search="00123458 maría elena sánchez cruz maria.sanchez@uabc.edu.mx inducción riesgo medio">
                                    <td class="px-4 fw-bold text-dark">00123458</td>
                                    <td class="text-dark">María Elena Sánchez Cruz</td>
                                    <td class="text-muted">maria.sanchez@uabc.edu.mx</td>
                                    <td class="text-dark">Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-secondary px-3 py-2 text-uppercase fw-bold text-dark">Riesgo Medio</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-warning bg-warning bg-opacity-10">78%</td>
                                    <td class="text-center fw-bold text-primary fs-5">+8</td>
                                </tr>

                                <tr class="seguimiento-row" data-search="00123459 josé luis hernández pérez jose.hernandez@uabc.edu.mx propedéutico riesgo medio">
                                    <td class="px-4 fw-bold text-dark">00123459</td>
                                    <td class="text-dark">José Luis Hernández Pérez</td>
                                    <td class="text-muted">jose.hernandez@uabc.edu.mx</td>
                                    <td class="text-dark">Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-secondary px-3 py-2 text-uppercase fw-bold text-dark">Riesgo Medio</span>
                                    </td>
                                    <td class="text-center fw-bold py-3">89%</td>
                                    <td class="text-center fw-bold text-primary fs-5">+7</td>
                                </tr>

                                <tr class="seguimiento-row" data-search="00123460 laura patricia morales garcía laura.morales@uabc.edu.mx inducción regular">
                                    <td class="px-4 fw-bold text-dark">00123460</td>
                                    <td class="text-dark">Laura Patricia Morales García</td>
                                    <td class="text-muted">laura.morales@uabc.edu.mx</td>
                                    <td class="text-dark">Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-primary px-3 py-2 text-uppercase fw-bold text-white">Regular</span>
                                    </td>
                                    <td class="text-center fw-bold py-3">100%</td>
                                    <td class="text-center fw-bold text-primary fs-5">+7</td>
                                </tr>

                                <tr class="seguimiento-row" data-search="00123461 roberto carlos jiménez vega roberto.jimenez@uabc.edu.mx propedéutico regular">
                                    <td class="px-4 fw-bold text-dark">00123461</td>
                                    <td class="text-dark">Roberto Carlos Jiménez Vega</td>
                                    <td class="text-muted">roberto.jimenez@uabc.edu.mx</td>
                                    <td class="text-dark">Propedéutico</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-primary px-3 py-2 text-uppercase fw-bold text-white">Regular</span>
                                    </td>
                                    <td class="text-center fw-bold py-3">100%</td>
                                    <td class="text-center fw-bold text-primary fs-5">+7</td>
                                </tr>

                                <tr class="seguimiento-row" data-search="00123462 gabriela fernández ruiz gabriela.fernandez@uabc.edu.mx inducción riesgo alto">
                                    <td class="px-4 fw-bold text-dark">00123462</td>
                                    <td class="text-dark">Gabriela Fernández Ruiz</td>
                                    <td class="text-muted">gabriela.fernandez@uabc.edu.mx</td>
                                    <td class="text-dark">Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-danger px-3 py-2 text-uppercase fw-bold text-white">Riesgo Alto</span>
                                    </td>
                                    <td class="text-center fw-bold py-3 text-danger bg-danger bg-opacity-10">50%</td>
                                    <td class="text-center fw-bold text-primary fs-5">+8</td>
                                </tr>

                                <tr class="seguimiento-row" data-search="00123463 diego alejandro castro mendoza diego.castro@uabc.edu.mx inducción regular">
                                    <td class="px-4 fw-bold text-dark">00123463</td>
                                    <td class="text-dark">Diego Alejandro Castro Mendoza</td>
                                    <td class="text-muted">diego.castro@uabc.edu.mx</td>
                                    <td class="text-dark">Inducción</td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-primary px-3 py-2 text-uppercase fw-bold text-white">Regular</span>
                                    </td>
                                    <td class="text-center fw-bold py-3">100%</td>
                                    <td class="text-center fw-bold text-primary fs-5">+6</td>
                                </tr>

                            </tbody>

                        </table>
                    </div>
                </div>

            </div>{{-- /card --}}

        </div>
    </div>
</div>

@endsection