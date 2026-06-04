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
    protected $contadorFilas = 1;

    protected $filasConDatos = 0;

    public function model(array $row)
    {
        $this->contadorFilas++;

        $matricula = isset($row[0]) ? trim($row[0]) : '';

        if (empty($matricula)) {
            return null;
        }

        if (!is_numeric($matricula)) {
            return null;
        }

        $alumno = Alumno::where('matricula', $matricula)->first();

        if (!$alumno) {
            throw new \Exception("Fila {$this->contadorFilas}: La matrícula '{$matricula}' no pertenece a ningún alumno registrado en el sistema.");
        }

        if ($alumno->id_grupo_propedeutico === null || empty($alumno->id_grupo_propedeutico)) {
            throw new \Exception("Fila {$this->contadorFilas}: El alumno '{$alumno->nombre}' (Matrícula: {$matricula}) existe, pero NO tiene ningún grupo propedéutico asignado.");
        }

        // CORRECCIÓN 3: Si pasó los candados, sumamos 1 a las filas con datos reales procesados
        $this->filasConDatos++;

        $examenInicial = $row[2];
        $examenFinal   = $row[3];

        if ($alumno->id_resultados_propedeutico) {
            ResultadosPropedeutico::where('id_resultados_propedeutico', $alumno->id_resultados_propedeutico)
                ->update([
                    'examen_inicial' => $examenInicial,
                    'examen_final'   => $examenFinal,
                ]);
        } else {
            $idCursoDefecto = 1;

            $grupo = Grupo::find($alumno->id_grupo_propedeutico);
            if ($grupo && $grupo->id_curso) {
                $idCursoDefecto = $grupo->id_curso;
            }

            $nuevasNotas = ResultadosPropedeutico::create([
                'examen_inicial' => $examenInicial,
                'examen_final'   => $examenFinal,
                'id_curso'       => $idCursoDefecto
            ]);

            $alumno->id_resultados_propedeutico = $nuevasNotas->id_resultados_propedeutico ?? $nuevasNotas->id;
            $alumno->save();
        }

        return null;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function(AfterImport $event) {
                // Si terminó todo el Excel y nunca se procesó ningún dato numérico real:
                if ($this->filasConDatos === 0) {
                    throw new \Exception("Fila de datos inexistente: El archivo Excel está vacío o no contiene ningún registro de alumnos válido.");
                }
            },
        ];
    }
}
