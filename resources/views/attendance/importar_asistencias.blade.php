@extends('layouts.app')

@section('contenido')
    <div class="container py-2">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <h2 class="text-center fw-bold text-dark mb-2 display-6">
                    Cargar Excel con Asistencias
                </h2>
                {{-- Mensaje de subtítulo corregido acorde al nuevo flujo --}}
                <p class="text-center body-color mb-4 fs-5">
                    Importe el archivo Excel para autocompletar el pase de lista semanal del grupo.
                </p>

                <div class="text-center mb-4">
                    <a href="#" class="btn btn-warning text-dark fw-bold btn-sm shadow-sm rounded-3">
                        <i class="bi bi-download me-1"></i> DESCARGAR FORMATO
                    </a>
                </div>

                <div class="card border-0 shadow-sm p-4 p-md-5 rounded-4 bg-white">
                    
                    {{-- ZONA DE DRAG & DROP PARA AJAX --}}
                    <div class="p-5 text-center position-relative mb-4 border border-2 border-warning rounded-4 bg-light"
                        id="dropZone" style="border-style: dashed !important; cursor: pointer; transition: all 0.3s ease;">

                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-dark shadow-sm bg-warning"
                            style="width: 65px; height: 65px;">
                            <i class="bi bi-upload fs-3"></i>
                        </div>

                        <h4 class="fw-bold text-dark mb-2">Arrastre el archivo aquí</h4>
                        <p class="text-muted mb-3 small">o haga clic para seleccionar</p>

                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 bg-white border rounded-3 shadow-sm text-muted small">
                            <i class="bi bi-file-earmark-excel-fill text-success"></i>
                            <span>Formato: .xlsx (Excel)</span>
                        </div>

                        {{-- Input oculto que el JS utilizará --}}
                        <input type="file" id="fileInput" accept=".xlsx" class="d-none">
                    </div>

                    {{-- MENSAJE INFORMATIVO TOTALMENTE CORREGIDO --}}
                    <div class="alert alert-info border-0 mb-0 p-3 rounded-3 shadow-sm text-start" role="alert">
                        <p class="mb-0 fs-6 text-dark">
                            <strong>Autocompletado de asistencia:</strong> El sistema buscará las matrículas del archivo Excel dentro de los alumnos ya inscritos en este grupo y marcará de forma automática los días correspondientes (Lunes a Viernes).
                        </p>
                    </div>

                    {{-- BOTÓN DE SUBIDA AJAX --}}
                    <div class="text-end mt-4">
                        <button id="btnSubirAsistencia" class="btn btn-warning btn-lg fw-bold px-5 py-3 shadow-sm rounded-3 text-dark">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                            Procesar Asistencias
                        </button>
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
                dropZone.style.borderColor = '#ffc107';
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
                
                if(data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.mensaje);
                }
            })
            .catch(async errorResponse => {
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = textoOriginal;
                alert("Ocurrió un error de conexión con el servidor.");
            });
        });
    });
    </script>
@endsection