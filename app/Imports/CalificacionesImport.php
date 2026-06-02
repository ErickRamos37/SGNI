<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\ResultadosPropedeutico;
use App\Models\Grupo;
use Maatwebsite\Excel\Concerns\ToModel;

class CalificacionesImport implements ToModel
{
    protected $contadorFilas = 1;

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
}
