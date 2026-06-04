# Guía de Estilos UI/UX: Sistema de Gestión de Nuevo Ingreso (SGNI)

Como directriz obligatoria para el equipo de desarrollo y herramientas CLI, este documento establece la identidad visual institucional de la UABC FIAD, priorizando la sobriedad, la legibilidad y el uso de lenguaje natural.

## 1. Paleta de Colores y Variables de Sistema
Se prohíbe el uso de estilos en línea (CSS quemado). Todo debe basarse en la siguiente paleta sobria, utilizando las variables de Bootstrap 5.

| Uso | Color HEX | Regla Estricta |
| :--- | :--- | :--- |
| **Fondo de Página** | `#FFFFFF` | **Fondo 100% blanco.** Quedan estrictamente prohibidos los fondos grises para el contenedor principal. |
| **Primario (UABC)** | `#00723F` | Uso exclusivo en Sidebar, Navbar institucional o acentos muy específicos. No usar para botones comunes. |
| **Acento (Ocre)** | `#F1B500` | Prohibido para texto. Solo para indicadores de advertencia o bordes activos. |
| **Texto Principal** | `#212529` | Color por defecto para toda la lectura de datos. Prohibido usar letras amarillas o sin contraste. |
| **Botones Normales** | `#6C757D` | Los botones de acción estándar (como guardar o editar) deben ser grises y sobrios. Cero colores chillantes. |

## 2. Lenguaje Simple y Directo (Copywriting UX)
* **Cero Jerga Técnica:** La interfaz está diseñada para docentes y directivos. Se prohíben frases de base de datos como "Registrar al usuario en el sistema" o "Insertar registro". 
* **Acción Humana:** Utilizar frases directas y orientadas a la acción: "Registrar usuario", "Guardar cambios", "Descargar lista".
* **Estandarización de Textos:** Usar formato tipo oración para botones y menús (ej. "Guardar cambios", no "GUARDAR CAMBIOS"). Los errores deben ser claros (ej. "El correo es obligatorio", no "Error SQL: Constraint violation").

## 3. Estandarización de Componentes

### A. Formularios y Campos
* **Alineación Lógica y Única:** Queda estrictamente prohibido aislar campos (como el "Rol") en "cajas especiales" o separadas. Todos los campos de un formulario pertenecen al mismo contenedor y flujo visual. No trates ningún dato como si no perteneciera al formulario original.
* **Uso del Espacio:** Priorizar el crecimiento vertical. No forzar múltiples columnas que compriman la información y dificulten la lectura.

### B. Botones y Acciones
* **Sobriedad Visual:** Atendiendo a las observaciones docentes, se prohíbe el uso de botones amarillos o verdes brillantes para acciones cotidianas. Utilizar botones grises estándar (`btn-secondary` o `btn-outline-secondary`).
* **Botón Cancelar:** Es obligatorio incluir un botón neutral de "Cancelar" o "Regresar" junto a las acciones de guardado.
* **Prevención de Errores:** Al hacer clic en enviar, el botón debe desactivarse visualmente para evitar envíos dobles. Las acciones destructivas (Eliminar) requieren confirmación.

### C. Tarjetas (Cards) e Interfaces
* **Delimitación Sutil:** Dado que el fondo general es blanco, si se usan tarjetas (Cards) para agrupar contenido, deben delimitarse únicamente con un borde fino (`border: 1px solid #dee2e6`) y sin sombras exageradas para mantener un diseño corporativo plano.

## 4. Reglas de Implementación Frontend (Strict Mode)
* **Cero CSS en línea:** Prohibido el atributo `style="..."` (salvo excepciones justificadas en exportaciones PDF/Excel).
* **Layout Maestro:** Todas las vistas deben extender de `layouts.app` y usar la sección `@section('contenido')`.