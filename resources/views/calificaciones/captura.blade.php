@extends('layouts.app')

@section('contenido')
    <div class="container py-2">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <h2 class="text-center fw-bold text-dark mb-2 display-6">
                    Cargar Excel con Calificaciones
                </h2>
                <p class="text-center body-color mb-4 fs-5">
                    Importe el archivo de calificaciones del grupo.
                </p>

                <div class="text-center mb-4">
                    <a href="{{ route('calificaciones.exportar', $grupo->id_grupo ?? ($grupo->id ?? 1)) }}"
                        class="btn btn-warning text-white fw-bold btn-sm shadow-sm rounded-3">
                        <i class="bi bi-download me-1"></i> DESCARGAR FORMATO
                    </a>
                </div>

                <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>¡Revisa el archivo!</strong>
                            <ul class="mb-0 mt-1 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('calificaciones.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="p-5 text-center position-relative mb-4 border border-2 border-dashed border-warning rounded-4 bg-light"
                            id="dropzone-area">

                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-white shadow-sm bg-warning"
                                style="width: 65px; height: 65px;">
                                <i class="bi bi-upload fs-3"></i>
                            </div>

                            <h5 class="fw-bold text-dark mb-1">Haga clic aqui para seleccionar
                                <div id="file-name-badge" class="d-none mb-3">
                                    <span class="badge bg-warning text-dark p-2 fs-6 rounded-3 shadow-sm fw-semibold">
                                        <i class="bi bi-file-earmark-check-fill me-2"></i>
                                        <span id="file-name-text"></span>
                                    </span>
                                </div>

                                <div
                                    class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-white border rounded-3 shadow-sm text-muted small">
                                    <i class="bi bi-file-earmark-spreadsheet-fill text-warning"></i>
                                    <span>Formato: .xlsx o .xls (Excel)</span>
                                </div>

                                <input type="file" name="archivo_excel" id="archivo_excel" accept=".xlsx, .xls" required
                                    class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;">
                        </div>

                        <div class="alert alert-info border-0 mb-0 p-3 rounded-3 shadow-sm text-start" role="alert">
                            <p class="mb-0 fs-6 text-dark">
                                <strong>Formato esperado:</strong> El archivo Excel debe contener las columnas:
                                <span class="text-muted fw-semibold">Matrícula, Nombre, Examen Diagnóstico, Examen
                                    Propedéutico Final</span>
                            </p>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold px-5 py-3 shadow-sm rounded-3">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                                Subir Calificaciones
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('archivo_excel').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : '';
            const badge = document.getElementById('file-name-badge');
            const helpText = document.getElementById('file-help-text');
            const textSpan = document.getElementById('file-name-text');
            if (fileName) {
                textSpan.textContent = fileName;
                badge.classList.remove('d-none');
                helpText.classList.add('d-none');
            } else {
                badge.classList.add('d-none');
                helpText.classList.remove('d-none');
            }
        });
    </script>
@endsection
