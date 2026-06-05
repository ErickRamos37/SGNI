@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">Cargar Excel con Calificaciones</h2>
                    <p class="text-muted mb-0">Importe el archivo de calificaciones del grupo de manera automática</p>
                </div>
                <a href="{{ route('calificaciones.descargarFormatoBase') }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                    Descargar formato
                </a>
            </div>

            <div class="mb-4">
                <div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 p-md-5">

                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            <i class="bi bi-file-earmark-excel-fill me-2 fs-4"></i>
                            <span>Importar Lista de Calificaciones</span>
                        </h5>

                <p class="small text-muted mb-4">
                    Suba el archivo Excel (.xlsx) con los resultados de las evaluaciones del grupo seleccionado. El sistema procesará las calificaciones de forma inmediata.
                </p>

                {{-- Formulario Operativo --}}
                <form action="{{ route('calificaciones.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Área Dropzone Interactiva --}}
                    <label class="border border-3 border-black border-dashed rounded-3 bg-light bg-opacity-25 p-5 text-center mb-4 d-block w-100 position-relative cursor-pointer" id="dropzone-area">

                        <input type="file" name="archivo_excel" id="archivo_excel" accept=".xlsx, .xls" required
                            class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;">

                        <div class="py-3">
                            <i class="bi bi-cloud-arrow-up text-primary display-3 mb-3 d-block"></i>

                            <h5 class="fw-bold text-dark mb-1" id="nombre_archivo">Arrastre el archivo aquí</h5>
                            <p class="text-muted small mb-3" id="file-help-text">o haga clic para seleccionar</p>

                            <div id="file-name-badge" class="d-none mb-3">
                                <span class="badge bg-warning text-dark p-2 fs-6 rounded-3 shadow-sm fw-semibold">
                                    <i class="bi bi-file-earmark-check-fill me-2"></i>
                                    <span id="file-name-text"></span>
                                </span>
                            </div>

                            <div class="d-inline-flex align-items-center badge bg-white text-dark border px-3 py-2 rounded-2 small shadow-sm">
                                <i class="bi bi-file-earmark-spreadsheet-fill text-dark me-1"></i>
                                <span>Formato: .xlsx / .xls</span>
                            </div>
                        </div>
                    </label>

                    {{-- Recuadro Informativo de Formato --}}
                    <div class="alert bg-info-subtle border border-info-subtle text-dark rounded-3 d-flex align-items-center p-3 mb-4" role="alert">
                        <i class="bi bi-info-circle-fill fs-5 me-3 text-info"></i>
                        <div class="small">
                            <strong>Formato esperado:</strong> El archivo Excel debe contener las columnas:
                            <span class="text-muted fw-semibold">Matrícula, Nombre, Examen Diagnóstico, Examen Propedéutico Final</span>
                        </div>
                    </div>

                    {{-- Botón de Acción Único Inferior Derecho --}}
                    <hr class="my-4 border-light-subtle">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" onclick="window.history.back();" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                            Guardar
                        </button>
                    </div>

                </form>
            </div>
        </div>
        </div>

        </div>
    </div>
</div>

    {{-- Importación de la librería de SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- LÓGICA DE ALERTAS EMERGENTES --}}
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
                let listadoErrores = '';

                @foreach ($errors->all() as $error)
                    let errorTexto = "{{ $error }}";

                    if (!errorTexto.includes('Fila')) {
                        errorTexto = "El contenido o la estructura interna del archivo es incorrecto. Verifique las columnas del formato.";
                    }

                    listadoErrores += '<div class="d-flex align-items-start mb-2 text-dark">' +
                                        '<i class="bi bi-x-circle-fill text-danger me-2 mt-1"></i>' +
                                        '<span>' + errorTexto + '</span>' +
                                      '</div>';
                @endforeach

                Swal.fire({
                    title: '¡Contenido del Archivo Incorrecto!',
                    html: '<p class="text-muted small text-start mb-3">El sistema detectó inconsistencias al intentar leer el documento:</p>' +
                           '<div class="bg-light p-3 rounded-3 border text-start lh-base style-scroll" style="max-height: 200px; overflow-y: auto;">' +
                               listadoErrores +
                           '</div>',
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    {{-- Script nativo de control de UI para el Dropzone --}}
    <script>
        document.getElementById('archivo_excel').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            const badge = document.getElementById('file-name-badge');
            const helpText = document.getElementById('file-help-text');
            const textSpan = document.getElementById('file-name-text');
            const mainTitle = document.getElementById('nombre_archivo');

            if (fileName) {
                textSpan.textContent = fileName;
                badge.classList.remove('d-none');
                helpText.classList.add('d-none');
                mainTitle.innerText = "¡Archivo Seleccionado!";
            } else {
                badge.classList.add('d-none');
                helpText.classList.remove('d-none');
                mainTitle.innerText = "Arrastre el archivo aquí";
            }
        });
    </script>
@endsection
