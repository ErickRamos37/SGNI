@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    {{-- ─── Encabezado de página + Buscador ──────────────────────── --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Lista de Asistencia Grupal</h2>
            <p class="text-muted mb-0">Grupo: Sin asignar</p>

            {{-- Botón deshabilitado porque no hay nada que descargar --}}
            <button class="btn btn-outline-dark px-5 fw-semibold rounded-3 mt-3">
                Descargar Lista
            </button>
        </div>
        <div class="w-25">
            <label class="small fw-bold text-muted text-uppercase mb-1">Buscar Estudiante</label>
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Matrícula o nombre..." disabled>
            </div>
        </div>
    </div>

    {{-- ─── Tarjeta contenedora ───────────────────────────────────── --}}
    <div class="card border border-light-subtle shadow-sm rounded-3">

        {{-- Header Verde UABC --}}
        <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
            <h5 class="fw-bold text-uppercase text-white mb-0">Registro de Asistencia Semanal</h5>
        </div>

        {{-- ESTADO VACÍO (Empty State) --}}
        <div class="card-body p-5 text-center">
            <div class="py-5">
                <i class="bi bi-people display-6 d-block mb-2 text-light-subtle"></i>
                <h3 class="fw-bold text-dark">No hay alumnos registrados</h3>
                <p class="text-muted fs-5 mb-0">Aún no se han generado los grupos o asignado estudiantes a esta sección.</p>
            </div>
        </div>

    </div>{{-- /card --}}

</div>
@endsection