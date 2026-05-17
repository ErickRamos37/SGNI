# Contexto de Arquitectura y Buenas Prácticas - SGNI FIAD UABC

## 1. Tecnológias
* **Backend:** Laravel 11 (PHP).
* **Frontend:** Blade, Bootstrap 5 (SCSS personalizado), Vanilla JavaScript (Sin jQuery).
* **Arquitectura:** Patrón MVC (Model-View-Controller) con enfoque en SPA parcial para formularios (AJAX).

## 2. Reglas de Desarrollo Implementadas

### A. Validaciones y Seguridad (Backend)
* **Prohibido validar en los Controladores:** Todas las validaciones deben extraerse a un archivo `FormRequest` (ej. `StoreUsuarioRequest`). El controlador solo debe recibir datos limpios.
* **Sanitización de Datos:** Se utiliza el método `prepareForValidation()` dentro de los Form Requests para limpiar datos antes de evaluarlos (ej. aplicar `Str::title()` para nombres, o `Str::lower()` para correos).
* **Reglas de Negocio:**
  * Los correos deben validarse estrictamente con la regla `ends_with:@uabc.edu.mx`.
  * Se debe usar la regla `unique:tabla,columna` para evitar registros duplicados de llaves primarias lógicas (ej. `num_empleado` o `correo_institucional`).
* **Respuestas del Servidor:** Al usar validación asíncrona, los controladores no deben usar `redirect()`. Deben retornar un `response()->json()` para éxito, o dejar que Laravel maneje automáticamente el error 422 (Unprocessable Entity) para fallos de validación.

### B. Experiencia de Usuario (Frontend / Interfaz)
* **Envío Asíncrono (AJAX/Fetch API):** Los formularios de registro y edición no deben recargar la página. Se intercepta el evento `submit` con Vanilla JS, se usa `e.preventDefault()`, y se envía la data mediante `fetch()` configurando los headers `Accept: application/json` y `X-Requested-With: XMLHttpRequest`.
* **Prevención de Doble Envío:** Al momento de hacer clic en "Guardar", el botón debe deshabilitarse (`disabled = true`) y mostrar un *spinner* de carga para evitar que el usuario inserte registros duplicados por error.
* **Manejo de Errores Visuales:** Si el backend responde con un código `422`, el JavaScript debe leer el objeto `errors` del JSON y pintar las alertas utilizando las clases nativas de Bootstrap 5 (`is-invalid` en el input, e inyectar el texto en el div `.invalid-feedback`).

### 3. Ejemplo de Flujo de Trabajo (Módulo de Usuarios)
1. **Frontend (`create.blade.php`):** El usuario llena el formulario. JS intercepta el clic, bloquea el botón y envía un `FormData` vía `fetch()`.
2. **Middleware (`StoreUsuarioRequest`):** Recibe los datos, los limpia, verifica que el correo termine en `@uabc.edu.mx` y no exista en la BD. Si falla, aborta y devuelve un JSON con código 422.
3. **Controlador (`UsuarioController@store`):** Guarda en la base de datos usando Eloquent (`Usuario::create()`) y devuelve un JSON con código 200/201 indicando éxito.
4. **Frontend:** JS recibe el 200, limpia el formulario (`form.reset()`), habilita el botón y muestra una alerta verde de éxito sin recargar la página.

>[!NOTE]
>**Para la IA:** Al generar código nuevo para este proyecto, debes apegarte estrictamente a este flujo: usar Form Requests, respuestas JSON, y Vanilla JS con Fetch para la manipulación del DOM y peticiones.