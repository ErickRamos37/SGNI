@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4 d-flex justify-content-center">
    
    {{-- Contenedor principal centrado (ancho máximo para mantener el diseño) --}}
    <div style="max-width: 800px; width: 100%;">
        
        {{-- Encabezados --}}
        <div class="text-center mb-4">
            <h1 class="fw-bold fs-2 mb-2">Cargar Excel con Asistencias</h1>
            <p class="text-muted fs-5">Importe el archivo de asistencias del grupo.</p>
        </div>

        {{-- Botón de Descargar Formato --}}
        <div class="text-center mb-5">
            <button class="btn btn-secondary text-dark fw-bold rounded-pill px-4 py-2 shadow-sm">
                <i class="bi bi-download me-2"></i> DESCARGAR FORMATO DE LISTA
            </button>
        </div>

        {{-- Tarjeta Principal de Carga --}}
        <div class="card border-0 shadow-sm rounded-4 p-4">
            
            {{-- Zona de Drag & Drop (Usando colores secundarios para el borde punteado) --}}
            <div class="border border-2 border-secondary rounded-4 p-5 text-center mb-4" 
                 style="border-style: dashed !important; background-color: #fcfcfc; cursor: pointer;">
                
                {{-- Ícono de Subida --}}
                <div class="d-inline-flex align-items-center justify-content-center bg-secondary text-dark rounded-circle mb-3 shadow-sm" 
                     style="width: 60px; height: 60px;">
                    <i class="bi bi-upload fs-4"></i>
                </div>
                
                {{-- Textos de la Zona de Carga --}}
                <h4 class="fw-bold mb-2">Arrastre el archivo aquí</h4>
                <p class="text-muted mb-3 small">o haga clic para seleccionar</p>
                
                {{-- Etiqueta de Formato --}}
                <span class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                    <i class="bi bi-file-earmark-excel text-success me-1"></i> Formato: .xlsx o .xls (Excel)
                </span>
            </div>

            {{-- Caja de Información (Formato Esperado) --}}
            <div class="bg-primary bg-opacity-10 rounded-3 p-3 mb-4">
                <p class="mb-0 text-dark small">
                    <span class="fw-bold text-primary">Formato esperado:</span> El archivo Excel debe contener las columnas: Matrícula, Nombre, Lunes, Martes, Miércoles, Jueves, Viernes (valores: Presente/Ausente).
                </p>
            </div>

            {{-- Botón Final de Subir --}}
            <div class="text-end">
                <button class="btn btn-secondary text-dark fw-bold rounded-pill px-5 py-2 shadow-sm">
                    <i class="bi bi-cloud-arrow-up me-2"></i> Subir Asistencias
                </button>
            </div>

        </div>
    </div>
</div>

{{-- 
    NOTA PARA EL DESARROLLADOR FRONTEND:
    La lógica de Drag & Drop y la apertura del selector de archivos al hacer clic en el div punteado, 
    debe manejarse con Vanilla JavaScript en tu archivo app.js, respetando la arquitectura AJAX.
--}}

@endsection
