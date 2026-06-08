@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Cargar Excel con Asistencias</h2>
                    <p class="text-muted mb-0">Importe el archivo de asistencias del grupo de manera automática</p>
                </div>
                <a href="#" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                    Descargar formato
                </a>
            </div>

            <div class="mb-4">
                <div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 p-md-5">

                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            <i class="bi bi-file-earmark-excel-fill me-2 fs-4"></i>
                            <span>Importar Lista Con Las Asistencias</span>
                        </h5>

                        <p class="small text-muted mb-4">
                            Suba el archivo Excel (.xlsx) con las asistencias del grupo seleccionado. El sistema procesará las asistencias de forma inmediata.
                        </p>

                        {{-- ZONA DE DRAG & DROP PARA AJAX --}}
                        <div class="border border-3 border-black border-dashed rounded-3 bg-light bg-opacity-25 p-5 text-center mb-4 d-block w-100 position-relative cursor-pointer" id="dropZone" style="transition: all 0.3s ease;">

                            <input type="file" id="fileInput" accept=".xlsx"
                                class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;">

                            <div class="py-3">
                                <i class="bi bi-cloud-arrow-up text-primary display-3 mb-3 d-block"></i>

                                <h4 class="fw-bold text-dark mb-1 fs-5">Arrastre el archivo aquí</h4>
                                <p class="text-muted small mb-3">o haga clic para seleccionar</p>

                                <div class="d-inline-flex align-items-center badge bg-white text-dark border px-3 py-2 rounded-2 small shadow-sm">
                                    <i class="bi bi-file-earmark-spreadsheet-fill text-dark me-1"></i>
                                    <span>Formato: .xlsx</span>
                                </div>
                            </div>
                        </div>

                        {{-- MENSAJE INFORMATIVO --}}
                        <div class="alert alert-info border-0 mb-0 p-3 rounded-3 shadow-sm text-start" role="alert">
                            <p class="mb-0 fs-6 text-dark">
                                <strong>Autocompletado de asistencia:</strong> El sistema buscará las matrículas del archivo Excel dentro de los alumnos ya inscritos en este grupo y marcará de forma automática los días correspondientes (Lunes a Viernes).
                            </p>
                        </div>

                        {{-- BOTONES DE ACCIÓN --}}
                        <hr class="my-4 border-light-subtle">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" onclick="window.history.back();" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Cancelar
                            </button>
                            <button id="btnSubirAsistencia" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Guardar
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT ÚNICO PARA MANEJAR EL DRAG & DROP Y LA PETICIÓN AJAX --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const uploadBtn = document.getElementById('btnSubirAsistencia');

        dropZone.addEventListener('click', () => fileInput.click());

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
                dropZone.style.borderColor = 'var(--bs-primary)';
            });
        });

        function validarYActualizarArchivo(file) {
            if (!file.name.toLowerCase().endsWith('.xlsx')) {
                alert("¡Error! Formato inválido. Suba únicamente archivos Excel (.xlsx)");
                fileInput.value = "";
                dropZone.querySelector('h4').innerText = "Arrastre el archivo aquí";
                dropZone.querySelector('p').innerText = "o haga clic para seleccionar";
                return false;
            }
            dropZone.querySelector('h4').innerText = file.name;
            dropZone.querySelector('p').innerText = "Archivo Excel listo para procesar";
            return true;
        }

        dropZone.addEventListener('drop', (e) => {
            if (e.dataTransfer.files.length) {
                let file = e.dataTransfer.files[0];
                if (validarYActualizarArchivo(file)) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                }
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                validarYActualizarArchivo(this.files[0]);
            }
        });

        uploadBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (!fileInput.files.length) {
                alert("Por favor, selecciona un archivo primero.");
                return;
            }

            uploadBtn.disabled = true;
            let textoOriginal = uploadBtn.innerHTML;
            uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';

            let formData = new FormData();
            formData.append('archivo_asistencia', fileInput.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("asistencias.procesar") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) throw response;
                    return response.json();
                })
                .then(data => {
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = textoOriginal;

                    if (data.success) {
                        alert(data.mensaje || "¡Archivo procesado con éxito!");
                        // OPCIÓN A: Redirección automática a la vista anterior (Pase de Lista)
                        window.location.href = "{{ url()->previous() }}";
                    } else {
                        alert("Error del sistema: " + data.mensaje);
                    }
                })
                .catch(async errorResponse => {
                    uploadBtn.disabled = false;
                    uploadBtn.innerHTML = textoOriginal;
                    
                    console.error("Detalles del error en el servidor:", errorResponse);
                    alert("Ocurrió un error al procesar el archivo en el servidor. Verifica que el formato del Excel sea correcto.");
                });
        });
    });
</script>
@endsection