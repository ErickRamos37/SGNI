# Reglas de Negocio y Restricciones del Sistema: SGNI

Este documento define la lógica operativa, los flujos por rol y las restricciones críticas del Sistema de Gestión de Nuevo Ingreso (SGNI). Es de cumplimiento obligatorio para el desarrollo de módulos y la automatización mediante agentes CLI.

## 1. Matriz de Roles Autenticados (RBAC)
El sistema opera bajo un control de acceso estricto limitado únicamente a tres perfiles. El acceso para estudiantes está fuera del alcance del sistema.

### A. Administrador
* **Configuración Inicial:** Controla los parámetros del sistema, incluyendo los pesos de evaluación y los porcentajes del algoritmo de distribución de grupos.
* **Gestión de Matrícula:** Realiza la carga masiva inicial de aspirantes mediante archivos Excel y gestiona las incorporaciones manuales (altas tardías).
* **Procesamiento:** Ejecuta el algoritmo de asignación y generación de grupos.
* **Gobernanza:** Gestiona los usuarios del personal y asigna los roles correspondientes.

### B. Docente
* **Seguimiento diario:** Registra las asistencias de los alumnos en sus grupos asignados.
* **Evaluación:** Registra las calificaciones de los exámenes diagnósticos.
* **Descargas:** Exporta las listas oficiales de asistencia y de calificaciones de sus respectivos grupos en formato Excel.

### C. Psicopedagógico
* **Monitoreo de Riesgo:** Consulta los indicadores visuales (semáforos de asistencia y rendimiento) para identificar alumnos con irregularidades.
* **Seguimiento:** Modifica el estado o etiqueta de seguimiento de los alumnos en situación vulnerable para llevar un registro de casos.

## 2. Reglas del Algoritmo de Distribución de Grupos
* **Configuración del Administrador:** El algoritmo de asignación de grupos toma como base inicial la regla de promedios **80/20** (80% promedios más altos, 20% más bajos) para equilibrar los salones.
* **Parámetro Variable:** Esta proporción **no debe estar fija en el código**. El Administrador tiene la facultad de editar y configurar estos porcentajes desde su interfaz antes de ejecutar la asignación.
* **Prioridad de Turnos:** La asignación debe estructurarse ordenando las listas de alumnos asignados priorizando en primera instancia el turno matutino, seguido del intermedio y concluyendo con el vespertino.

## 3. Requerimientos de Exportación y Tratamiento de Archivos
* **Formatos de Lista:** El sistema debe generar las listas oficiales en formato de hoja de cálculo Excel (y vistas preparadas para impresión).
* **Estructura de Columnas:** Las listas de asistencia y calificaciones deben mantener el orden estricto de campos: `Matrícula`, `Apellido Paterno`, `Apellido Materno`, `Nombre(s)`.
* **Protección de Matrículas:** Al generar el archivo Excel, la columna de matrículas debe configurarse explícitamente con formato de texto para evitar que el software externo elimine los ceros a la izquierda o altere el dato.

## 4. Restricciones Críticas del Sistema
* **Exclusividad de Google SSO:** Queda prohibido el uso de credenciales o contraseñas locales. El acceso es único a través de cuentas institucionales `@uabc.edu.mx`.
* **Lista Blanca de Acceso:** Si un correo institucional intenta iniciar sesión mediante Google pero no fue registrado previamente en la base de datos por un Administrador, el sistema debe denegar el acceso inmediatamente.
* **Modo de Operación Cierre:** Al finalizar el periodo de captura regular, el sistema debe permitir al Administrador congelar las listas, pasando los módulos de los docentes a un Modo Consulta (Solo Lectura) para evitar alteraciones accidentales.