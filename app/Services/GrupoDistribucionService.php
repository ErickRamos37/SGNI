<?php

namespace App\Services;

use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Grupo;
use App\Models\Turno;
use Illuminate\Support\Facades\DB;

class GrupoDistribucionService
{
    /**
     * Procesa y distribuye aleatoriamente a los alumnos en grupos equitativos.
     *
     * @param int $gruposManana
     * @param int $gruposTarde
     * @param string $etiqueta
     * @param array $alumnos
     * @param string $tipoGrupo (propedeutico o induccion)
     * @param string $periodo
     * @return array
     */
    public function procesarGrupos($gruposManana, $gruposTarde, $etiqueta, $alumnos, $tipoGrupo, $periodo)
    {
        $totalGrupos = $gruposManana + $gruposTarde;
        
        // Si no hay grupos o alumnos, regresamos 0
        if ($totalGrupos <= 0 || count($alumnos) === 0) {
            return ['nuevos' => 0, 'repetidos' => 0];
        }

        $gruposCreados = [];
        $letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

        // Buscar el curso por nombre
        $nombreCursoBuscado = ($tipoGrupo === 'propedeutico') ? 'prope' : 'induccion';
        $cursoObj = Curso::where('nombre_curso', 'LIKE', '%' . $nombreCursoBuscado . '%')->first();
        $idCurso = $cursoObj ? $cursoObj->id_curso : null;

        // Buscar los turnos por nombre
        $turnoMatutino = Turno::where('tipo_turno', 'matutino')->first();
        $turnoVespertino = Turno::where('tipo_turno', 'vespertino')->first();

        for ($i = 0; $i < $totalGrupos; $i++) {
            $idTurno = ($i < $gruposManana) ? ($turnoMatutino ? $turnoMatutino->id_turno : 1) : ($turnoVespertino ? $turnoVespertino->id_turno : 2); 
            $prefijo = ($tipoGrupo === 'propedeutico') ? 'Prope' : 'Induc';

            $idEstadoActivo = DB::table('estado_grupo')->whereRaw('LOWER(nombre_estado) = ?', ['activo'])->value('id_estado') ?? 4;
            
            // Garantizar que el usuario exista, sino usar el primer administrador disponible
            $idUsuarioActual = auth()->check() ? auth()->user()->id_usuario : null;
            $usuarioExiste = $idUsuarioActual ? \App\Models\Usuario::find($idUsuarioActual) : null;
            
            $idAdmin = $usuarioExiste 
                        ? $idUsuarioActual 
                        : \App\Models\Usuario::whereHas('rol', function($q) {
                            $q->where('nombre_rol', 'LIKE', '%admin%');
                          })->value('id_usuario') ?? \App\Models\Usuario::first()->id_usuario;

            $gruposCreados[] = Grupo::create([
                'nombre_grupo' => $prefijo . ' ' . $etiqueta . ' - Gpo ' . $letras[$i],
                'id_turno'     => $idTurno,
                'id_curso'     => $idCurso,
                'id_usuario'   => $idAdmin,
                'id_estado'    => $idEstadoActivo,
                'periodo'      => $periodo,
            ]);
        }

        // =========================================================
        // Revolvemos a los alumnos de forma aleatoria
        // =========================================================
        shuffle($alumnos);
        
        $nuevos = 0;
        $repetidos = 0;
        $indiceGrupo = 0;

        // Algoritmo Round-Robin: Repartir como baraja para balancear los grupos
        foreach ($alumnos as $data) {
            $grupo = $gruposCreados[$indiceGrupo];
            $columnaGrupo = ($tipoGrupo === 'propedeutico') ? 'id_grupo_propedeutico' : 'id_grupo_induccion';
            
            // Insertamos o actualizamos
            $datosUpdate = [
                'nombre'             => $data['nombre'],
                'ap_pat'             => $data['ap_pat'],
                'ap_mat'             => $data['ap_mat'],
                'correo_alternativo' => $data['correo_alternativo'],
                'telefono'           => $data['telefono'],
                'id_carrera'         => $data['id_carrera'],
                $columnaGrupo        => $grupo->id_grupo,
            ];

            // Solo incluir campos opcionales si vienen con valor
            if (!empty($data['correo_institucional'])) {
                $datosUpdate['correo_institucional'] = $data['correo_institucional'];
            }
            if (!empty($data['puntaje_ingreso'])) {
                $datosUpdate['puntaje_ingreso'] = $data['puntaje_ingreso'];
            }

            $alumno = Alumno::updateOrCreate(
                ['matricula' => $data['matricula']],
                $datosUpdate
            );

            if ($alumno->wasRecentlyCreated) {
                $nuevos++;
            } else {
                $repetidos++;
            }

            // Pasamos al siguiente grupo. Si llegamos al último, volvemos a empezar.
            $indiceGrupo++;
            if ($indiceGrupo >= count($gruposCreados)) {
                $indiceGrupo = 0;
            }
        }

        return ['nuevos' => $nuevos, 'repetidos' => $repetidos];
    }
}
