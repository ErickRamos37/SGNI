# Estándar: Formularios Maestros SGNI (Bootstrap 5)

Este documento define la estructura HTML, las clases exactas de Bootstrap 5 y la lógica JavaScript que TODO formulario dentro del sistema SGNI debe implementar estrictamente. Queda prohibida la desviación de este diseño.

## 1. Contenedores y Tarjetas (Layout Estructural)
Todo formulario debe envolverse en la siguiente estructura fluida y tarjeta estilizada:
* **Contenedor Principal:** `<div class="container-fluid">`
* **Encabezado de Página:** * Título: `<h2 class="fw-bold text-dark">Título</h2>`
  * Subtítulo: `<p class="text-muted">Descripción</p>`
* **Tarjeta del Formulario:** `<div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100">`
* **Cuerpo de la Tarjeta:** `<div class="card-body p-4 p-md-5">`
* **Título Interno (con ícono):** `<h5 class="fw-bold text-primary mb-4 d-flex align-items-center">`

## 2. Elementos del Formulario (Inputs y Grids)
* **Filas:** Los campos deben agruparse en `<div class="row g-4 mb-4">` para mantener un espaciado vertical y horizontal uniforme.
* **Labels:** Deben usar las clases `<label class="form-label text-dark fw-semibold">`.
* **Campos Obligatorios:** Deben incluir un asterisco rojo: `<span class="text-danger">*</span>`.
* **Inputs y Selects:** * Clases obligatorias: `form-control shadow-sm` o `form-select shadow-sm`.
  * Nunca deben estirarse al 100% en pantallas grandes si están solos. Deben limitarse usando clases como `col-md-8 col-xl-6`.
* **Div de Error (Feedback):** Cada input debe estar acompañado inmediatamente debajo por su contenedor de error: `<div class="invalid-feedback" id="error-[nombre_campo]"></div>`.

## 3. Botones y Separadores
* **Separador:** Antes de la botonera, debe existir una línea divisoria: `<hr class="my-4 border-light-subtle">`.
* **Contenedor de Botones:** `<div class="d-flex justify-content-end gap-2">`
* **Estilo de Botones (Acción y Cancelar):** Ambos botones deben mantener un perfil sobrio e institucional usando: `class="btn btn-outline-dark px-5 fw-semibold rounded-3"`.

## 4. Estándar JavaScript (UX y Fetch API)
Todo formulario debe ser procesado de forma asíncrona y cumplir con este flujo exacto:
1. **Mensaje de Éxito Oculto:** Al inicio del formulario debe existir un `<div id="mensajeExito" class="alert alert-success d-none mb-4 rounded-3 shadow-sm"></div>`.
2. **Real-time Validation Clearing (Delegación de eventos):** El `<form>` debe escuchar los eventos `input` y `change`. Si el usuario interactúa con un elemento que tiene la clase `.is-invalid`, el script debe remover esa clase instantáneamente y vaciar el texto del div `.invalid-feedback`.
3. **Bloqueo Anti-Doble Clic:** Al disparar el `submit`, el botón de guardar debe pasar a `disabled = true` y reemplazar su texto por un `<span class="spinner-border spinner-border-sm"></span> Guardando...`.
4. **Reseteo de Errores Previo:** Antes de enviar el `fetch`, se deben remover todas las clases `.is-invalid` y limpiar los textos de error.
5. **Manejo del Error 422:** Si Laravel responde con un 422 (Unprocessable Entity), el script iterará sobre el JSON de errores, agregará la clase `.is-invalid` al input correspondiente y pintará el mensaje rojo en su `.invalid-feedback`.