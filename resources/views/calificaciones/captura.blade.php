@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-1">

        {{-- 1. Header Principal --}}
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-2">
            <div>
                <h1 class="fw-bold text-dark mb-1">Cargar Excel con Calificaciones</h1>
                <p class="text-muted mb-0">Importe el archivo de calificaciones del grupo de manera automática</p>
            </div>
        </div>

        {{-- 2. Tarjeta de Contenido Principal --}}
        <div class="card border-0 shadow-sm rounded-3 mb-1">
            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                <div class="mb-3">
                    {{-- Enlace para descargar la plantilla Excel estandarizada --}}
                    <a href="{{ route('calificaciones.descargarFormatoBase') }}"
                        class="btn btn-outline-dark px-4 fw-semibold rounded-3">
                        Descargar Formato
                    </a>
                </div>

                <div class="d-flex align-items-center text-primary fw-bold">
                    <i class="bi bi-file-earmark-excel-fill text-primary me-2 fs-3"></i>
                    <span class="text-uppercase tracking-wide fs-5">Importar Lista de Calificaciones</span>
                </div>
            </div>

            <div class="card-body p-4">
                <p class="small text-muted mb-4">
                    Suba el archivo Excel (.xlsx) con los resultados de las evaluaciones del grupo seleccionado. El sistema
                    procesará las calificaciones de forma inmediata.
                </p>

                {{-- Formulario con soporte para transferencia de archivos binarios (enctype) --}}
                <form action="{{ route('calificaciones.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Area dropzone interactiva vinculada al input de archivo --}}
                    <label
                        class="border border-3 border-black border-dashed rounded-3 bg-light bg-opacity-25 p-5 text-center mb-4 d-block w-100 position-relative cursor-pointer"
                        id="dropzone-area">

                        <input type="file" name="archivo_excel" id="archivo_excel" accept=".xlsx, .xls" required
                            class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;">

                        <div class="py-3">
                            <i class="bi bi-cloud-arrow-up text-primary display-3 mb-3 d-block"></i>

                            <h5 class="fw-bold text-dark mb-1" id="nombre_archivo">Arrastre el archivo aquí</h5>
                            <p class="text-muted small mb-3" id="file-help-text">o haga clic para seleccionar</p>

                            <div
                                class="d-inline-flex align-items-center badge bg-white text-dark border px-3 py-2 rounded-2 small shadow-sm">
                                <i class="bi bi-filetype-xlsx text-dark me-1 fs-6"></i> Formato: .xlsx (Excel)
                            </div>
                        </div>
                    </label>

                    {{-- Alerta informativa con la estructura obligatoria del archivo --}}
                    <div class="alert bg-info-subtle border border-info-subtle text-dark rounded-3 d-flex align-items-center p-3 mb-4"
                        role="alert">
                        <i class="bi bi-info-circle-fill fs-5 me-3 text-info"></i>
                        <div class="small">
                            <strong>Formato esperado:</strong> El archivo Excel debe contener las columnas:
                            <span class="text-muted fw-semibold">Matricula, Examen Propedeutico Inicial y Examen
                                Propedéutico Final</span>
                        </div>
                    </div>

                    {{-- Botones de accion del formulario --}}
                    <hr class="my-4 border-light-subtle">
                    {{-- Cambiamos justify-content-end por justify-content-between para mandar los bloques a los extremos --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="button" onclick="window.history.back();"
                            class="btn btn-outline-dark px-4 fw-semibold rounded-3 d-inline-flex align-items-center gap-2">
                            <span>Regresar</span>
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" id="btn-cancelar"
                                class="btn btn-outline-dark px-4 fw-semibold rounded-3">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-outline-dark px-4 fw-semibold rounded-3">
                                Subir Calificaciones
                            </button>
                        </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Alertas de SweetAlert2 disparadas por variables de sesion de Laravel --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Carga Exitosa!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#00723F',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorTexto = "{{ $errors->first() }}";

                Swal.fire({
                    title: '¡Inconsistencia Detectada!',
                    html: '<p class="text-muted small text-center mb-0">' + errorTexto + '</p>',
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const archivoInput = document.getElementById('archivo_excel');

            // Muestra dinamicamente el nombre del archivo seleccionado en el contenedor
            archivoInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0] ? e.target.files[0].name : '';
                const helpText = document.getElementById('file-help-text');
                const mainTitle = document.getElementById('nombre_archivo');

                if (fileName) {
                    mainTitle.textContent = fileName;
                    helpText.classList.add('d-none');
                } else {
                    mainTitle.textContent = "Arrastre el archivo aquí";
                    helpText.classList.remove('d-none');
                }
            });

            // Limpia el input de archivo y reinicia el estado visual de la interfaz
            document.getElementById('btn-cancelar').addEventListener('click', function() {
                if (archivoInput.value !== '') {
                    archivoInput.value = '';
                    archivoInput.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>
@endsection
