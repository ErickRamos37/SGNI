@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-4">

        <div class="text-center mb-5">
            <div class="text-primary mb-2">
                <i class="bi bi-shield-check display-4"></i>
            </div>
            <h1 class="fw-bolder text-dark mb-1 display-5">Modo Lectura</h1>
            <p class="text-muted fs-5">Control de edición de calificaciones para profesores</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-11 col-lg-10 col-xl-10">

                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 text-center">

                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-primary bg-success-subtle border border-success border-2"
                        style="width: 85px; height: 85px;">
                        <i class="bi bi-unlock-fill display-5"></i>
                    </div>

                    <span class="small fw-bold text-muted text-uppercase tracking-wider d-block mb-1 fs-6">
                        Estado Actual
                    </span>

                    <h1 class="fw-black text-primary text-uppercase mb-2 display-4" style="font-weight: 900;">
                        Editable
                    </h1>

                    <p class="text-muted fw-medium fs-5 mb-5">
                        Los profesores PUEDEN modificar calificaciones
                    </p>

                    <div class="mb-5">
                        <button type="button"
                            class="btn btn-danger btn-lg text-white fw-bolder px-5 py-3 text-uppercase shadow rounded-3 tracking-wider fs-5 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-lock-fill"></i> Activar Modo Lectura
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
@endsection
