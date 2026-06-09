@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-1">
        <div class="row">
            <div class="col-12">

                {{-- Encabezado de la pantalla --}}
                <div class="mb-4">
                    <h1 class="fw-bold text-dark mb-1">Cargar Lista de Alumnos</h1>
                    <p class="text-dark small mb-0">Sube el archivo Excel con los alumnos para aplicar la distribución {{ request('porcentaje_alto', 80) }}/{{ request('porcentaje_bajo', 20) }}</p>
                </div>

                {{-- Alertas de Error --}}
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm rounded-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Tarjeta principal de carga --}}
                <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-dark text-uppercase d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-file-earmark-excel-fill text-success fs-4"></i>
                            <span>Archivo Excel</span>
                        </h5>

                        <p class="text-dark small mb-4">
                            Sube el archivo Excel (<code>.xlsx</code>, <code>.csv</code>) que contiene la lista de estudiantes. 
                            El archivo debe tener las siguientes columnas: <strong>matricula, puntaje, Nombre, apellido_paterno, apellido_materno, correo, correo_alter, telefono</strong>.
                        </p>

                        <form action="{{ route('grupos_finales.generar') }}" method="POST" enctype="multipart/form-data" id="form-subir">
                            @csrf
                            
                            {{-- Parametros heredados del paso anterior --}}
                            <input type="hidden" name="porcentaje_alto" value="{{ request('porcentaje_alto', 80) }}">
                            <input type="hidden" name="porcentaje_bajo" value="{{ request('porcentaje_bajo', 20) }}">

                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="border border-3 border-dark border-dashed rounded-3 bg-light bg-opacity-25 p-5 text-center mb-3 d-block w-100 cursor-pointer">
                                        <input type="file" name="archivo_alumnos" id="archivo_alumnos" class="d-none" accept=".xlsx, .xls, .csv" required>
                                        <div class="py-3">
                                            <i class="bi bi-cloud-arrow-up text-primary display-3 mb-3 d-block"></i>
                                            <h5 class="fw-bold text-dark mb-1" id="nombre_archivo">Arrastre el archivo aquí</h5>
                                            <p class="text-muted small mb-3">o haga clic para seleccionar</p>
                                            <div class="d-inline-flex align-items-center badge bg-white text-dark border px-3 py-2 rounded-2 small shadow-sm">
                                                <i class="bi bi-filetype-xlsx text-dark me-1 fs-6"></i> Formato: .xlsx, .csv (Excel)
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <hr class="my-4 border-light-subtle">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('grupos_finales.criterios') }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                    Cancelar
                                </a>
                                <button type="submit" id="btn-generar" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                    Generar
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Mostrar nombre del archivo al seleccionarlo
            document.getElementById('archivo_alumnos').addEventListener('change', function(e) {
                if(this.files.length > 0) {
                    document.getElementById('nombre_archivo').innerText = this.files[0].name;
                }
            });

            const formSubir = document.getElementById('form-subir');
            const btnGenerar = document.getElementById('btn-generar');

            formSubir.addEventListener('submit', function () {
                if (document.getElementById('archivo_alumnos').files.length > 0) {
                    btnGenerar.disabled = true;
                    btnGenerar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generando...';
                }
            });
        });
    </script>
@endsection
