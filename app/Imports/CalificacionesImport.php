<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\ResultadosPropedeutico;
use App\Models\Grupo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;

class CalificacionesImport implements ToModel, WithEvents
{
    private $rowsProcessed = 0;
    private $importedCount = 0;

    public function model(array $row)
    {
        $this->rowsProcessed++;

        // Candado 1: Validar físicamente que el archivo tenga el mínimo de columnas requeridas
        if ($this->rowsProcessed === 1) {
            if (!array_key_exists(0, $row) || !array_key_exists(2, $row) || !array_key_exists(3, $row)) {
                throw new \Exception('Estructura de columnas incompleta.');
            }
        }

        $matricula = trim($row[0] ?? '');
        if (!is_numeric($matricula)) {
            return null;
        }

        $alumno = Alumno::where('matricula', $matricula)->first();

        if ($alumno) {
            $examenInicial = $row[2];
            $examenFinal   = $row[3];

            if ($alumno->id_resultados_propedeutico) {
                ResultadosPropedeutico::where('id_resultados_propedeutico', $alumno->id_resultados_propedeutico)
                    ->update([
                        'examen_inicial' => $examenInicial,
                        'examen_final'   => $examenFinal,
                    ]);
            }
            else {
                $idCursoDefecto = 1;

                if ($alumno->id_grupo_propedeutico) {
                    $grupo = Grupo::find($alumno->id_grupo_propedeutico);
                    if ($grupo && $grupo->id_curso) {
                        $idCursoDefecto = $grupo->id_curso;
                    }
                }

                $nuevasNotas = ResultadosPropedeutico::create([
                    'examen_inicial' => $examenInicial,
                    'examen_final'   => $examenFinal,
                    'id_curso'       => $idCursoDefecto
                ]);
                $alumno->id_resultados_propedeutico = $nuevasNotas->id_resultados_propedeutico ?? $nuevasNotas->id;
                $alumno->save();
            }

            $this->importedCount++;
        }

        return null;
    }

    // Evento que se ejecuta al terminar de escanear todo el documento Excel
    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                // Candado 2: Si terminó el proceso y no modificó a nadie, el contenido está mal
                if ($this->importedCount === 0) {
                    throw new \Exception('El archivo no contiene registros válidos para este módulo.');
                }
            },
        ];
    }
}
