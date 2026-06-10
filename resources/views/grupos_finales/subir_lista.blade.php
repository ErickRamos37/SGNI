@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    {{-- 1. Header Principal --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Crear Grupos</h2>
            <p class="text-muted mb-0">Seleccione el programa y configure los grupos</p>
        </div>
    </div>

    {{-- 2. Banner: Contexto del Proceso (Idéntico al banner azul de la segunda vista) --}}
    <div class="card bg-primary text-white border border-light-subtle shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <small class="text-white-50 text-uppercase fw-bold d-block mb-1">
                Distribución Configurada
            </small>
            <h3 class="fw-bold mb-0">Primer Semestre - {{ request('porcentaje_alto', 80) }}% / {{ request('porcentaje_bajo', 20) }}%</h3>
        </div>
    </div>


    {{-- Alertas de Error optimizadas con SweetAlert2 para mantener la limpieza visual --}}
    @if($errors->any())
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error de Validación',
                    html: `{!! implode('<br><br>', $errors->all()) !!}`,
                    icon: 'error',
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false,
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    {{-- 4. Tarjeta Principal de Carga --}}
    <form action="{{ route('grupos_finales.generar') }}" method="POST" enctype="multipart/form-data" id="form-subir">
        @csrf
        
        {{-- Parámetros heredados lógicos (Intactos) --}}
        <input type="hidden" name="porcentaje_alto" value="{{ request('porcentaje_alto', 80) }}">
        <input type="hidden" name="porcentaje_bajo" value="{{ request('porcentaje_bajo', 20) }}">

        <div class="card border border-light-subtle shadow-sm rounded-3 mb-4">
            <div class="card-body p-4 p-md-5">
                
                {{-- Título interno con color primario e icono unificado --}}
                <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                    <i class="bi bi-file-earmark-excel-fill text-primary me-2 fs-3"></i>
                    Paso 2: Importar Lista de Estudiantes
                    <span class="text-danger ms-1">*</span>
                </h5>

                <p class="small text-muted mb-4">
                    Sube el archivo Excel (.xlsx) que contiene la lista de estudiantes. El sistema procesará los registros para aplicar la distribución correspondiente.
                </p>

                {{-- Zona de arrastre (Dropzone) --}}
                <label class="border border-3 border-dark border-dashed rounded-3 bg-light bg-opacity-25 p-5 text-center mb-4 d-block w-100 cursor-pointer">
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

                {{-- Caja de información (Idéntica a la de la segunda vista) --}}
                <div class="alert bg-info-subtle border border-info-subtle text-dark rounded-3 d-flex align-items-center p-3 mb-0" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 me-3 text-info"></i>
                    <div class="small">
                        <strong>Formato esperado:</strong> El archivo debe contener los encabezados exactos: matricula, puntaje, Nombre, apellido_paterno, apellido_materno, correo, correo_alter, telefono.
                    </div>
                </div>

            </div>
        </div>

        {{-- 5. Barra de Acciones Inferior --}}
        <div class="d-flex justify-content-end align-items-center gap-2 mb-4">
            <a href="{{ route('grupos_finales.criterios') }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                Cancelar
            </a>
            <button type="submit" id="btn-generar" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                Generar
            </button>
        </div>
    </form>

</div>

{{-- Scripts lógicos originales (Intactos) --}}
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