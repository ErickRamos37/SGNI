# Guía de Estandarización de Vistas de Lista (Tablas)

**Contexto y Objetivo:**
Quiero que generes una vista de listado (tabla) en Laravel (Blade) utilizando Bootstrap 5. Debes apegarte estrictamente al siguiente estándar de diseño, estructura y funcionalidad para garantizar que todas las vistas del sistema sean idénticas y armoniosas. No inventes clases, no uses estilos en línea (`style="..."`) ni cambies la estructura; usa exactamente las indicaciones a continuación.

## 1. Estructura de Contenedores Base
* Toda la vista debe estar envuelta en un `<div class="container-fluid">`.
* Dentro de este, debe ir un `<div class="row">` y luego un `<div class="col-12">`.

## 2. Encabezado de la Página y Buscador
* **Contenedor Principal:** Un `<div class="d-flex justify-content-between align-items-end mb-4">`.
* **Títulos (Izquierda):** Un `<h2>` con las clases `fw-bold text-dark mb-1` para el título principal, y un `<p>` con `text-muted mb-0` para el subtítulo.
* **Buscador (Derecha):** * Un `<div class="w-25">`. 
  * Debe contener un `<label>` con las clases `small fw-bold text-muted text-uppercase mb-1`. 
  * El input debe estar dentro de un `<div class="input-group shadow-sm">`. 
  * El icono de lupa debe ir en un `<span class="input-group-text bg-white border-end-0">` usando `bi bi-search text-muted`. 
  * El `<input>` debe tener las clases `form-control border-start-0 ps-0`, un `placeholder` descriptivo y un **ID único** (ej. `id="search-registro"`) para el script.

## 3. Tarjeta Contenedora (Card)
La tabla no debe ir suelta; debe estar dentro de una tarjeta con las clases: `card border border-light-subtle shadow-sm rounded-3`.
* **Card Header:** Debe usar `card-header bg-primary p-4 border-bottom border-light-subtle`. El texto interior debe ser un `<h5>` con `fw-bold text-uppercase text-white mb-0`. (Nota: En este sistema, el color primary ya está configurado como el verde institucional).
* **Card Body:** Debe usar `card-body p-0` para que la tabla toque los bordes laterales. Dentro, colocar un `<div class="table-responsive">`.

## 4. Estilo de la Tabla
* **Tabla Principal:** `<table class="table table-hover align-middle mb-0">`.
* **Cabecera (Thead):** `<thead class="table-light text-muted small text-uppercase">`.
* **Celdas de Cabecera (Th):** El primer `<th>` debe llevar `px-4 py-3`. Los demás solo `py-3`.
* **Cuerpo (Tbody):** `<tbody class="small">`.
* **Filas de Datos (Tr):** Cada fila generada (por `@foreach` o `@forelse`) debe tener una clase identificadora (ej. `registro-row`) y un atributo **`data-search`** que contenga todos los valores de esa fila en minúsculas (ej. `data-search="{{ strtolower($item->campo1 . ' ' . $item->campo2) }}"`). Esto es obligatorio.
* **Celdas de Datos (Td):** La primera celda debe resaltar con `px-4 fw-bold text-dark`. Las celdas normales usarán `text-dark`, y los datos secundarios (como correos o fechas) usarán `text-muted`.

## 5. Estado Vacío (Empty State)
Si no hay registros (usando `@empty`), mostrar un `<tr>` con un `<td>` que abarque todas las columnas (`colspan="X"`).
* Las clases del `<td>` deben ser: `text-center py-5 text-muted`.
* Debe incluir un icono grande: `<i class="bi bi-inbox display-6 d-block mb-2 text-light-subtle"></i>` seguido del mensaje "No hay registros en el sistema".

## 6. Paginación de Laravel
Si la variable tiene el método `hasPages`, la paginación debe ir en un Footer de la tarjeta.
* **Contenedor:** `<div class="card-footer bg-white border-top border-light-subtle p-3 d-flex justify-content-center">`.
* El renderizado debe usar explícitamente el paginador de Bootstrap 4: `{{ $variable->links('pagination::simple-bootstrap-4') }}`.

## 7. Script de Búsqueda Dinámica (Vanilla JS)
Al final del archivo, incluir un script que escuche el evento `input` del ID del buscador.
* Debe capturar el valor, pasarlo a minúsculas, limpiar espacios (`trim()`), y recorrer todas las filas (`querySelectorAll('.registro-row')`).
* Debe leer el atributo `data-search` de cada fila. Si coincide con la búsqueda, se remueve la clase `d-none`; si no coincide, se agrega `classList.add('d-none')`.

## 8.Pestañas de Navegación y Filtros Superiores (Tabs)

Para la navegación entre diferentes vistas de datos (como alternar entre listas de "Propedéutico" e "Inducción") situadas en la parte superior de las tablas, se utilizará un contenedor tipo "píldora" con botones en estilo **Outline Dark**. Este diseño proporciona un efecto *hover* elegante y mantiene la jerarquía visual limpia.

#### Clases y Reglas Clave:
* **Contenedor Principal:** Debe utilizar `d-inline-flex rounded-pill border bg-white shadow-sm p-1 gap-1`.
* **Botones (General):** Usar siempre `btn btn-sm rounded-pill px-4 py-2 fw-semibold text-decoration-none`.
* **Botón Activo:** Lleva la clase `btn-outline-dark active` para mantenerse relleno.
* **Botón Inactivo:** Lleva la clase `btn-outline-dark border-0` para mantener el fondo transparente y sin bordes dobles, activando el relleno oscuro únicamente al pasar el cursor (hover).

#### Estructura HTML de Referencia:

```html
<div class="mb-4">
    <div class="d-inline-flex rounded-pill border bg-white shadow-sm p-1 gap-1">  
        <!-- Pestaña Activa -->
        <a href="{{ route('ruta_activa') }}"
            class="btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-outline-dark text-decoration-none active">
            Vista Actual
        </a>
        <!-- Pestaña Inactiva -->
        <a href="{{ route('ruta_inactiva') }}"
            class="btn btn-sm rounded-pill px-4 py-2 fw-semibold btn-outline-dark border-0 text-decoration-none">
            Otra Vista
        </a>
    </div>
</div>