<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\ResultadosPropedeutico;
use App\Models\Grupo;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

class CalificacionesImport implements ToCollection
{
    // Contador para verificar que el archivo no venga sin filas de alumnos
    protected $filasConDatos = 0;

    public function collection(Collection $rows)
    {
        // Variables para almacenar los indices de todas las columnas de forma dinamica
        $columnaMatricula = null;
        $columnaNombre = null;
        $columnaApPat = null;
        $columnaApMat = null;
        $columnaExamen1 = null;
        $columnaExamen2 = null;

        // Extraemos las filas de las cabeceras (Fila 6 es indice 5, Fila 7 es indice 6)
        $fila6 = $rows[5] ?? null;
        $fila7 = $rows[6] ?? null;

        // Buscamos los indices recorriendo ambas filas de cabecera
        foreach ([$fila6, $fila7] as $fila) {
            if (!$fila) continue;

            foreach ($fila as $index => $celda) {
                if (is_null($celda)) continue;

                // Transformamos a minusculas y removemos espacios en los extremos
                $texto = strtolower(trim($celda));

                // PARCHE CRITICO: PHP es sensible a acentos. Los removemos manualmente para la comparacion
                $texto = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $texto);

                // Eliminamos cualquier tipo de espacio intermedio (incluyendo espacios duros de Excel)
                $textoLimpio = preg_replace('/\s+/', '', $texto);

                // Busqueda de Matricula (Ahora hara match con 'matricula' o 'matrícula')
                if (str_contains($texto, 'matricula')) {
                    $columnaMatricula = $index;
                }
                // Busqueda de Nombre
                if ($texto === 'nombre') {
                    $columnaNombre = $index;
                }
                // Busqueda de Apellido Paterno
                if (str_contains($texto, 'paterno')) {
                    $columnaApPat = $index;
                }
                // Busqueda de Apellido Materno
                if (str_contains($texto, 'materno')) {
                    $columnaApMat = $index;
                }
                // Busqueda de Examen 1
                if (str_contains($textoLimpio, 'examen1') || str_contains($texto, 'inicial')) {
                    $columnaExamen1 = $index;
                }
                // Busqueda de Examen 2
                if (str_contains($textoLimpio, 'examen2') || str_contains($texto, 'final')) {
                    $columnaExamen2 = $index;
                }
            }
        }

        // Validamos que las columnas minimas operacionales existan
        if (is_null($columnaMatricula) || is_null($columnaExamen1) || is_null($columnaExamen2)) {
            throw new \Exception("Estructura invalida: No se pudieron localizar las columnas obligatorias de 'Matricula', 'Examen 1' o 'Examen 2'. Asegurese de que los encabezados esten en las filas 6 y 7.");
        }

        $datosParaImportar = [];

        // 1. Fase de Validación de todas las filas
        foreach ($rows as $numFila => $row) {
            $filaExcel = $numFila + 1;

            if ($numFila < 7) {
                continue;
            }

            // Extraemos la matricula usando el indice dinamico encontrado
            $matriculaRaw = isset($row[$columnaMatricula]) ? $row[$columnaMatricula] : null;
            $matricula = !is_null($matriculaRaw) ? trim($matriculaRaw) : '';

            // Si la celda esta vacia o no es numerica, la saltamos por seguridad
            if (empty($matricula) || !is_numeric($matricula)) {
                continue;
            }

            // Extraemos de forma segura los textos del nombre para usarlos en reportes de error
            $nombreExcel = !is_null($columnaNombre) && isset($row[$columnaNombre]) ? trim($row[$columnaNombre]) : '';
            $apPatExcel = !is_null($columnaApPat) && isset($row[$columnaApPat]) ? trim($row[$columnaApPat]) : '';
            $apMatExcel = !is_null($columnaApMat) && isset($row[$columnaApMat]) ? trim($row[$columnaApMat]) : '';
            $nombreCompletoExcel = trim("{$nombreExcel} {$apPatExcel} {$apMatExcel}");

            // Buscamos al alumno en la base de datos
            $alumno = Alumno::where('matricula', $matricula)->first();

            if (!$alumno) {
                $identificador = !empty($nombreCompletoExcel) ? "'{$nombreCompletoExcel}' " : "";
                throw new \Exception("Fila {$filaExcel}: El alumno {$identificador}con matricula '{$matricula}' no pertenece a ningun estudiante registrado en el sistema.");
            }

            if ($alumno->id_grupo_propedeutico === null || empty($alumno->id_grupo_propedeutico)) {
                throw new \Exception("Fila {$filaExcel}: El alumno '{$alumno->nombre}' (Matricula: {$matricula}) existe, pero NO tiene ningun grupo propedeutico asignado.");
            }

            // SEGURIDAD: Verificar si el grupo al que pertenece está en Modo Lectura
            $grupo = Grupo::find($alumno->id_grupo_propedeutico);
            if ($grupo) {
                $estadoLectura = \DB::table('estado_grupo')->whereRaw('LOWER(nombre_estado) = ?', ['lectura'])->first();
                if ($estadoLectura && $grupo->id_estado == $estadoLectura->id_estado) {
                    throw new \Exception("El grupo '{$grupo->nombre_grupo}' se encuentra en modo lectura (Bloqueado) y no puede ser editado actualmente.");
                }
            }

            // Extraemos las notas utilizando las columnas encontradas dinamicamente
            $examen1 = isset($row[$columnaExamen1]) ? $row[$columnaExamen1] : null;
            $examen2 = isset($row[$columnaExamen2]) ? $row[$columnaExamen2] : null;

            if (
                ($examen1 !== null && ($examen1 < 0 || $examen1 > 100)) ||
                ($examen2 !== null && ($examen2 < 0 || $examen2 > 100))
            ) {
                throw new \Exception("Fila {$filaExcel}: Las calificaciones de Examen 1 y Examen 2 deben ser valores numericos entre 0 y 100.");
            }

            $this->filasConDatos++;

            $datosParaImportar[] = [
                'alumno' => $alumno,
                'grupo' => $grupo,
                'examen1' => $examen1,
                'examen2' => $examen2
            ];
        }

        if ($this->filasConDatos === 0) {
            throw new \Exception("Fila de datos inexistente: El archivo Excel esta vacio o no contiene ningun registro de alumnos valido a partir de la fila 8.");
        }

        // 2. Fase de Escritura en Base de Datos (solo si todas las validaciones pasaron)
        foreach ($datosParaImportar as $item) {
            $alumno = $item['alumno'];
            $grupo = $item['grupo'];
            $examen1 = $item['examen1'];
            $examen2 = $item['examen2'];

            // Si el alumno ya tiene registro de notas asignado, se actualiza
            if ($alumno->id_resultados_propedeutico) {
                ResultadosPropedeutico::where('id_resultados_propedeutico', $alumno->id_resultados_propedeutico)
                    ->update([
                        'examen_inicial' => $examen1,
                        'examen_final' => $examen2,
                    ]);
            } else {
                $idCursoDefecto = 1;
                if ($grupo && $grupo->id_curso) {
                    $idCursoDefecto = $grupo->id_curso;
                }

                $nuevasNotas = ResultadosPropedeutico::create([
                    'examen_inicial' => $examen1,
                    'examen_final' => $examen2,
                    'id_curso' => $idCursoDefecto
                ]);

                $alumno->id_resultados_propedeutico = $nuevasNotas->id_resultados_propedeutico ?? $nuevasNotas->id;
                $alumno->save();
            }
        }
    }
}
