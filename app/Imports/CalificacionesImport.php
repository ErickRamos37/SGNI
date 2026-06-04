<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\ResultadosPropedeutico;
use App\Models\Grupo;
use Maatwebsite\Excel\Concerns\ToModel;

class CalificacionesImport implements ToModel
{
    public function model(array $row)
    {
        $matricula = trim($row[0]);
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
        }

        return null;
    }
}
