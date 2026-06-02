# Reglas de Desarrollo y Arquitectura: Sistema de Gestión de Nuevo Ingreso (SGNI)

Este documento establece los estándares técnicos de cumplimiento obligatorio para el backend y frontend del proyecto SGNI bajo Laravel 11. Está diseñado como directriz de gobernanza para el equipo y agentes de desarrollo CLI.

## 1. Estándares de Vistas (Blade) y UI/UX
* **Herencia de Layout:** Todas las vistas de la aplicación deben extender obligatoriamente de la plantilla maestra mediante `@extends('layouts.app')` y encapsular su código dentro de `@section('contenido')`.
* **Prohibición de Estilos Locales:** Queda estrictamente prohibido el uso del atributo `style="..."` en etiquetas HTML. Se deben utilizar las clases utilitarias de Bootstrap 5 o variables personalizadas.
* **Cero "Magic Numbers" en RBAC:** Para el control de acceso visual, está prohibido validar roles mediante IDs numéricos (ej. `id_rol == 1`). La validación en Blade debe realizarse siempre mediante el nombre del rol:
  * *Correcto:* `@if(Auth::user()->rol->nombre_rol === 'Administrador')`
* **Interacciones Sobrias:** * Al hacer clic en un botón de envío, este debe pasar a estado `disabled` para prevenir envíos duplicados.
  * Todo formulario de registro o edición debe incluir un botón gris neutro (`btn-secondary`) de "Cancelar" o "Regresar" al lado del botón de acción.

## 2. Controladores y Peticiones (Backend)
* **Obligatoriedad de Form Requests:** Los controladores deben mantenerse limpios y delegados. Toda lógica de validación debe extraerse a archivos FormRequest específicos (ej. `StoreUsuarioRequest`, `UpdateUsuarioRequest`).
* **Sanitización de Datos:** Se debe implementar el método `prepareForValidation()` dentro de los Form Requests para normalizar los datos (ej. formatear nombres o forzar minúsculas en correos) antes de que ocurra la evaluación.
* **Validación de Correo Institucional:** Todas las validaciones de creación o validación de usuarios deben incluir la regla estricta de terminación de dominio: `ends_with:@uabc.edu.mx`.
* **Tratamiento de Errores en Interfaz:** Los mensajes de error de validación deben ser claros y utilizar las clases nativas de Bootstrap (`.invalid-feedback` / `.is-invalid`), ya sea renderizados por Blade (`@error`) o devueltos por respuestas asíncronas.

## 3. Manejo de Archivos y Exportación (Laravel Excel)
* **Implementación de Exportaciones:** Se debe utilizar exclusivamente la librería `maatwebsite/excel` para la generación de documentos oficiales (Asistencias y Calificaciones).
* **Uso de FromView:** Para garantizar que los archivos Excel respeten fielmente el formato institucional de la UABC FIAD (encabezados, logos de la facultad, títulos), las clases de exportación deben implementar la interfaz `FromView` vinculada a una vista Blade limpia (`<table>`, `<tr>`, `<td>`).
* **Estandarización de Campos:** Los archivos generados deben seguir estrictamente el orden institucional de datos: `matricula`, `apellido_paterno`, `apellido_materno`, `nombre`.

## 4. Gestión de Autenticación y Sesiones
* **Autenticación Única (SSO):** El sistema depende exclusivamente de Google Socialite. Queda prohibido el almacenamiento de contraseñas en la base de datos. El modelo `Usuario` debe implementar la interfaz `Authenticatable` de Laravel pero carecer del campo `password`.
* **Middleware de Rol:** Las rutas protegidas en `web.php` deben utilizar el middleware personalizado registrado con el alias `rol:NombreDelRol`.
* **Variables de Entorno:** Las URLs de redirección de Google OAuth2 (`GOOGLE_REDIRECT_URI`) deben coincidir exactamente con el entorno configurado (sea el Apache local de XAMPP o el servidor de desarrollo), evitando disparidades entre `localhost` y `127.0.0.1`.