# Guía Definitiva de Estandarización Arquitectónica y Visual (UI/UX)

**Contexto y Objetivo (Instrucciones del Sistema para IAs y Programadores):**
Este documento es la fuente de la verdad para la creación y modificación de vistas en este proyecto Laravel 11. El stack tecnológico estricto es **Laravel (Blade), Bootstrap 5, Vanilla JS (Fetch API) y Yajra DataTables (Server-Side)**. 

Queda estrictamente prohibido:
1. Inventar clases CSS o usar estilos en línea (`style="..."`), salvo para dimensiones críticas fijas especificadas aquí.
2. Usar jQuery para peticiones HTTP (obligatorio usar `fetch`). jQuery solo se permite para inicializar DataTables.
3. Desviar los esquemas de color: `$primary` es verde institucional, los botones de acción usan `outline-dark`, y los fondos usan `bg-white` o `table-light`.

---

## 1. Estructura de Contenedores Base (Grid System)

Toda vista (sea tabla, formulario o panel) debe obedecer esta jerarquía estructural inquebrantable:

```html
@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- El contenido de la vista va aquí -->
        </div>
    </div>
</div>
@endsection
```

---

## 2. Encabezado de Página y Pestañas de Navegación (Tabs)

### A. Encabezados (Títulos)
Se utiliza flexbox para alinear el título y subtítulo de forma limpia y consistente.

```html
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Módulo - Acción</h2>
        <p class="text-muted mb-0">Descripción clara de lo que hace esta vista.</p>
    </div>
</div>
```

### B. Pestañas (Tabs) estilo Píldora
Para vistas que alternan contextos (ej. Propedéutico vs Inducción), usar este contenedor superior.

* **Contenedor:** `d-inline-flex rounded-pill border bg-white shadow-sm p-1 gap-1 mb-4`
* **Botón Activo:** `btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-outline-dark text-decoration-none active`
* **Botón Inactivo:** `btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-outline-dark border-0 text-decoration-none`

---

## 3. Tarjetas Contenedoras (Cards)

Todo contenido principal va dentro de una Card. El diseño de la cabecera (Header) cambia según el tipo de vista.

* **Contenedor Principal:** `<div class="card border border-light-subtle shadow-sm rounded-3">`

### A. Para Tablas de Listado (DataTables)
El Header lleva fondo verde primario y texto blanco. El Body contiene la tabla responsiva con un padding controlado.

```html
<div class="card border border-light-subtle shadow-sm rounded-3">
    <div class="card-header bg-primary p-4 border-bottom">
        <h5 class="fw-bold text-uppercase text-white mb-0">Lista de Registros</h5>
    </div>
    <div class="card-body p-4"> 
        <div class="table-responsive">
            <!-- Tabla física va aquí -->
        </div>
    </div>
</div>
```

### B. Para Formularios (Alta/Edición)
El Header desaparece como tal; el título va dentro del Body con un icono y texto en color verde primario.

```html
<div class="card border border-light-subtle shadow-sm rounded-3">
    <div class="card-body p-4 p-md-5">
        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
            <i class="bi bi-person-plus me-2 fs-4"></i>
            <span>Registrar elemento</span>
        </h5>
        <!-- Campos del formulario van aquí -->
    </div>
</div>
```

---

## 4. Estándar para Tablas Dinámicas (Yajra DataTables Server-Side)

### A. Estructura HTML
* **Tabla:** `<table id="tabla-unica" class="table table-hover align-middle mb-0" style="width:100%">`
* **Cabecera:** `py-3` para todos los `<th>`. La primera columna (identificador) suele llevar `px-2` o `text-start`.
* **Cuerpo:** `<tbody class="small"></tbody>` (Debe mantenerse completamente vacío en el HTML).

### B. Inicialización JavaScript (Blade)
* **Atención IAs:** No agregar propiedades `language` ni `processing`. Estas son globales y residen en `app.js`.

```html
<script type="module">
    $(document).ready(function() {
        $('#tabla-unica').DataTable({
            serverSide: true,
            ajax: "{{ route('modelo.index') }}",
            columns: [
                { data: 'identificador', name: 'identificador', type: 'string', className: 'text-start fw-bold text-dark' },
                { data: 'campo_normal', name: 'campo_normal' },
                { data: 'campo_secundario', name: 'campo_secundario', className: 'text-muted' },
                { data: 'acciones', name: 'acciones', orderable: false, searchable: false }
            ]
        });
    });
</script>
```

### C. Columna de Acciones (Backend PHP - Controlador)
Los botones CRUD se generan desde el controlador de Laravel. Deben medir 32x32px exactos, usar iconos de Bootstrap y estar centrados.

```php
->addColumn('acciones', function($row){
    $btnEditar = '<a href="..." class="btn btn-sm btn-outline-dark d-inline-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;" title="Editar"><i class="bi bi-pencil-square"></i></a>';
    $btnEliminar = '<button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Eliminar"><i class="bi bi-trash"></i></button>';
    
    return '<div class="d-flex justify-content-center align-items-center">' . $btnEditar . $btnEliminar . '</div>';
})
->rawColumns(['acciones'])
```

---

## 5. Formularios y Peticiones AJAX (Fetch API)

Todos los formularios de creación/edición deben enviarse vía AJAX para no recargar la página.

### A. Diseño de Inputs
* Las etiquetas (`<label>`) deben llevar `form-label text-dark fw-semibold`. Los campos requeridos llevan `<span class="text-danger">*</span>`.
* Los inputs (`<input>`, `<select>`) deben llevar `form-control shadow-sm`.
* Todo input debe tener un `div` hermano para el error: `<div class="invalid-feedback" id="error-nombre_campo"></div>`.
* **Agrupación en Grid:** Usar `<div class="row g-4 mb-4">` para estructurar y espaciar los campos correctamente.

### B. Botones de Acción (Footer del Formulario)
* Separador: `<hr class="my-4 border-light-subtle">`
* Alineación a la derecha:

```html
<div class="d-flex justify-content-end gap-2">
    <a href="..." class="btn btn-outline-dark px-5 fw-semibold rounded-3">Cancelar</a>
    <button type="submit" id="btnGuardar" class="btn btn-outline-dark px-5 fw-semibold rounded-3">Guardar</button>
</div>
```

### C. Script Estándar de Envío (Vanilla JS)
El script debe interceptar el submit, deshabilitar la acción para mitigar doble clic, limpiar los estados de error previos e inyectar las validaciones del backend.

```javascript
document.getElementById('miFormulario').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const btnGuardar = document.getElementById('btnGuardar');
    const originalText = btnGuardar.innerHTML;
    
    // UX: Estado de carga
    btnGuardar.disabled = true;
    btnGuardar.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Guardando...`;
    
    // Limpieza de errores previos
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    ->then(response => response.json().then(data => ({ status: response.status, body: data })))
    ->then(res => {
        if (res.status === 422) {
            // Manejo de errores de validación Laravel
            Object.keys(res.body.errors).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                const errorDiv = form.querySelector(`#error-${key}`);
                if (input) input.classList.add('is-invalid');
                if (errorDiv) errorDiv.textContent = res.body.errors[key][0];
            });
        } else if (res.status === 200 || res.status === 201) {
            // Éxito: recargar o redirigir
            window.location.reload();
        }
    })
    catch(error => console.error('Error:', error))
    ->finally(() => {
        // Restaurar botón
        btnGuardar.disabled = false;
        btnGuardar.innerHTML = originalText;
    });
});
```

---

## 6. Componentes Especiales

### Cajas de Drag & Drop (Ej. Subida de Excel)
Para vistas que requieran importación masiva o subida de archivos (como `curso_prope.blade.php`), el diseño de la zona de arrastre debe ser:

* **Contenedor:** Border dashed, fondo claro, transiciones suaves.
* **Clases recomendadas:** `border border-2 border-dashed border-secondary rounded-4 bg-light p-5 text-center position-relative hover-shadow transition-all`.
* **Icono:** Un icono grande en el centro (ej. `bi bi-cloud-arrow-up display-4 text-primary mb-3`).
* **Input File:** Debe estar oculto (`d-none`) pero accesible al hacer clic en el contenedor mediante JS.

---

## 7. Instrucciones Críticas para IAs Autónomas (Agents)

Si eres una IA leyendo este archivo para crear o modificar una vista en este proyecto, **DEBES CUMPLIR ESTO:**

1. **Análisis de BD:** Lee detenidamente el esquema de la base de datos antes de mapear la propiedad `columns:` de DataTables.
