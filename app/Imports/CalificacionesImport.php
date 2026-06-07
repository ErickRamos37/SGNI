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
        // Coordenadas numericas exactas basadas en la imagen (A=0, B=1, C=2...)
        $columnaMatricula = 4; // Columna E (Matricula)
        $columnaInicial = 7;   // Columna H (Examen Propedeutico Inicial)
        $columnaFinal = 8;     // Columna I (Examen Propedeutico Final)

        foreach ($rows as $numFila => $row) {
            // Calculamos el numero de fila real en Excel (los indices inician en 0)
            $filaExcel = $numFila + 1;

            // Saltamos las filas 1 a la 7 (indices 0 al 6) que contienen datos del docente y cabeceras
            if ($numFila < 7) {
                continue;
            }

            // Extraemos la matricula de la columna E
            $matriculaRaw = isset($row[$columnaMatricula]) ? $row[$columnaMatricula] : null;
            $matricula = !is_null($matriculaRaw) ? trim($matriculaRaw) : '';

            // Si la celda esta completamente vacia, la saltamos (comun al final del archivo)
            if (empty($matricula)) {
                continue;
            }

            // Si la celda tiene texto pero no es numerica, la saltamos por seguridad
            if (!is_numeric($matricula)) {
                continue;
            }

            // Buscamos al alumno en la base de datos
            $alumno = Alumno::where('matricula', $matricula)->first();

            // validacion: Si la matricula no existe, detenemos todo y mandamos el error al SweetAlert
            if (!$alumno) {
                throw new \Exception("Fila {$filaExcel}: La matricula '{$matricula}' no pertenece a ningun alumno registrado en el sistema.");
            }

            // validacion: Si el alumno existe pero no tiene grupo asignado, detenemos la operacion
            if ($alumno->id_grupo_propedeutico === null || empty($alumno->id_grupo_propedeutico)) {
                throw new \Exception("Fila {$filaExcel}: El alumno '{$alumno->nombre}' (Matricula: {$matricula}) existe, pero NO tiene ningun grupo propedeutico asignado.");
            }

            // Si el registro es valido, incrementamos nuestro contador de exito
            $this->filasConDatos++;

            // Extraemos las notas de las columnas H e I
            $examenInicial = isset($row[$columnaInicial]) ? $row[$columnaInicial] : null;
            $examenFinal = isset($row[$columnaFinal]) ? $row[$columnaFinal] : null;

            if (
                ($examenInicial !== null && ($examenInicial < 0 || $examenInicial > 100)) ||
                ($examenFinal !== null && ($examenFinal < 0 || $examenFinal > 100))
            ) {
                throw new \Exception("Fila {$filaExcel}: Las calificaciones deben ser valores numericos entre 0 y 100.");
            }

            // Si el alumno ya tiene registro de notas asignado, se actualiza
            if ($alumno->id_resultados_propedeutico) {
                ResultadosPropedeutico::where('id_resultados_propedeutico', $alumno->id_resultados_propedeutico)
                    ->update([
                        'examen_inicial' => $examenInicial,
                        'examen_final' => $examenFinal,
                    ]);
            } else {
                // Busqueda dinamica del curso segun el grupo del alumno (Evita ID fijo)
                $idCursoDefecto = 1;
                $grupo = Grupo::find($alumno->id_grupo_propedeutico);
                if ($grupo && $grupo->id_curso) {
                    $idCursoDefecto = $grupo->id_curso;
                }

                // Si no tiene registro de notas, se crea uno nuevo con el curso correcto
                $nuevasNotas = ResultadosPropedeutico::create([
                    'examen_inicial' => $examenInicial,
                    'examen_final' => $examenFinal,
                    'id_curso' => $idCursoDefecto
                ]);

                // Vinculamos las nuevas notas al perfil del alumno
                $alumno->id_resultados_propedeutico = $nuevasNotas->id_resultados_propedeutico ?? $nuevasNotas->id;
                $alumno->save();
            }
        }

        // validacion: Si el ciclo termino y nunca sumamos ningun registro, el archivo no tenia alumnos
        if ($this->filasConDatos === 0) {
            throw new \Exception("Fila de datos inexistente: El archivo Excel esta vacio o no contiene ningun registro de alumnos valido a partir de la fila 8.");
        }
    }
}
