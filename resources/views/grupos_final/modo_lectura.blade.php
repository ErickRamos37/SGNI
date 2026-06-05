@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-4">

        <div class="mb-4">
            <h2 class="fw-bold text-dark mb-1">Modo Lectura</h2>
            <p class="text-muted mb-0">Control de edición de calificaciones para profesores</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-11 col-lg-10 col-xl-10">

                <div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 p-md-5 text-center">

                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-primary bg-success-subtle border border-success border-2"
                        style="width: 85px; height: 85px;">
                        <i class="bi bi-unlock-fill display-5"></i>
                    </div>

                    <span class="small fw-bold text-muted text-uppercase d-block mb-1">
                        Estado Actual
                    </span>

                    <h3 class="fw-bold text-primary text-uppercase mb-2">
                        Editable
                    </h3>

                    <p class="text-muted fw-semibold mb-5">
                        Los profesores PUEDEN modificar calificaciones
                    </p>

                    <hr class="my-4 border-light-subtle">
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <button type="button" onclick="window.history.back();" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                            Guardar
                        </button>
                    </div>

                    <div class="alert alert-info border-0 mb-0 p-4 rounded-3 shadow-sm text-start" role="alert">
                        <p class="mb-0 fs-5 text-dark lh-base">
                            <strong>Nota:</strong> Cuando el modo lectura está activado, los profesores solo podrán
                            visualizar las calificaciones pero no podrán editarlas. Esta función es útil al finalizar el
                            período de captura de calificaciones.
                        </p>
                    </div>

                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection
