@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-1">

        {{-- 1. Header Principal --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Cargar Excel con Calificaciones</h2>
                <p class="text-muted mb-0">Importe el archivo de calificaciones del grupo de manera automática</p>
            </div>
            <a href="{{ route('calificaciones.descargarFormatoBase') }}"
                class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                Descargar formato
            </a>
        </div>

        {{-- 2. Tarjeta de Contenido Principal --}}
        <div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100 mb-4">
            <div class="card-body p-4 p-md-5">

                <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                    <i class="bi bi-file-earmark-excel-fill me-2 fs-4"></i>
                    <span>Importar Lista de Calificaciones</span>
                </h5>

                <p class="small text-muted mb-4">
                    Suba el archivo Excel con los resultados de las evaluaciones del grupo seleccionado. El sistema
                    procesará las calificaciones de forma inmediata.
                </p>

                {{-- Formulario con soporte para transferencia de archivos binarios (enctype) --}}
                <form action="{{ route('calificaciones.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Area dropzone interactiva vinculada al input de archivo --}}
                    <div class="border border-3 border-black border-dashed rounded-3 bg-light bg-opacity-25 p-5 text-center mb-4 d-block w-100 position-relative cursor-pointer"
                        id="dropZone" style="transition: all 0.3s ease;">

                        <input type="file" name="archivo_excel" id="archivo_excel" accept=".xlsx, .xls" required
                            class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;">

                        <div class="py-3">
                            <i class="bi bi-cloud-arrow-up text-primary display-3 mb-3 d-block"></i>

                            <h4 class="fw-bold text-dark mb-1 fs-5" id="nombre_archivo">Arrastre el archivo aquí</h4>
                            <p class="text-muted small mb-3" id="file-help-text">o haga clic para seleccionar</p>

                            <div
                                class="d-inline-flex align-items-center badge bg-white text-dark border px-3 py-2 rounded-2 small shadow-sm">
                                <i class="bi bi-filetype-xlsx text-dark me-1 fs-6"></i> Formato: .xlsx (Excel)
                            </div>
                        </div>
                    </div>

                    {{-- Alerta informativa con la estructura obligatoria del archivo --}}
                    <div class="alert bg-info-subtle border border-info-subtle text-dark rounded-3 d-flex align-items-center p-3 mb-4"
                        role="alert">
                        <i class="bi bi-info-circle-fill fs-5 me-3 text-info"></i>
                        <div class="small">
                            <strong>Formato esperado:</strong> El archivo Excel debe contener las columnas:
                            <span class="text-muted fw-semibold">Matricula, Examen1 y Examen2</span>
                        </div>
                    </div>

                    {{-- Botones de accion del formulario --}}
                    <hr class="my-4 border-light-subtle">
                    <div class="d-flex justify-content-end align-items-right mt-4">
                        <div class="d-flex gap-2">
                            <button type="button" id="btn-cancelar"
                                class="btn btn-outline-dark px-4 fw-semibold rounded-3">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Subir Calificaciones
                            </button>
                        </div>
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
                    confirmButtonColor: 'var(--bs-primary)',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let errorTexto = "{{ $errors->first() }}";
                let titulo = "¡Inconsistencia Detectada!";

                if (errorTexto.toLowerCase().includes('lectura') || errorTexto.toLowerCase().includes('bloqueado')) {
                    titulo = "¡Acción Denegada!";
                }

                Swal.fire({
                    title: titulo,
                    html: '<p class="text-muted small text-center mb-0">' + errorTexto + '</p>',
                    icon: 'error',
                    confirmButtonColor: 'var(--bs-primary)',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('dropZone');
            const archivoInput = document.getElementById('archivo_excel');

            // Eventos de Drag & Drop para feedback visual
            ['dragover', 'dragenter'].forEach(evento => {
                dropZone.addEventListener(evento, (e) => {
                    e.preventDefault();
                    dropZone.style.backgroundColor = '#e8f5e9';
                    dropZone.style.borderColor = '#00723F';
                });
            });

            ['dragleave', 'dragend', 'drop'].forEach(evento => {
                dropZone.addEventListener(evento, (e) => {
                    e.preventDefault();
                    dropZone.style.backgroundColor = '#f8f9fa';
                    dropZone.style.borderColor = '#000000';
                });
            });

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
