<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Catálogo de Carreras
        DB::table('carrera')->insertOrIgnore([
            [
                'id_carrera'     => 1,
                'nombre_carrera' => 'TRONCO COMUN (AREA DE INGENIERIA)',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id_carrera'     => 2,
                'nombre_carrera' => 'TRONCO COMUN DE ARQUITECTURA Y DISEÑO',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]
        ]);

        // 2. Catálogo de Cursos (con IDs específicos solicitados)
        DB::table('cursos')->insertOrIgnore([
            [
                'id_curso'     => 1,
                'nombre_curso' => 'induccion',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id_curso'     => 2,
                'nombre_curso' => 'propedeutico',
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'id_curso'     => 3,
                'nombre_curso' => 'primer_semestre',
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        ]);

        // 3. Catálogo de Estados del Grupo
        DB::table('estado_grupo')->insertOrIgnore([
            [
                'id_estado'     => 1,
                'nombre_estado' => 'Editable',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id_estado'     => 2,
                'nombre_estado' => 'Lectura',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'id_estado'     => 3,
                'nombre_estado' => 'activo',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]
        ]);

        // 4. Catálogo de Roles
        DB::table('roles')->insertOrIgnore([
            [
                'id_rol'      => 1,
                'nombre_rol'  => 'Administrador',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_rol'      => 2,
                'nombre_rol'  => 'Docente',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'id_rol'      => 3,
                'nombre_rol'  => 'Psicopedagogico',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        ]);

        // 5. Catálogo de Turnos (con IDs específicos solicitados)
        DB::table('turnos')->insertOrIgnore([
            [
                'id_turno'   => 1,
                'tipo_turno' => 'vespertino',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_turno'   => 2,
                'tipo_turno' => 'matutino',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_turno'   => 3,
                'tipo_turno' => 'intermedio',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 6. Catálogo de Situación del Alumno
        DB::table('situacion_alumno')->insertOrIgnore([
            [
                'id_situacion_alum' => 1,
                'tipo_situacion'    => 'Regular',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'id_situacion_alum' => 2,
                'tipo_situacion'    => 'Condicionado',
                'created_at'        => now(),
                'updated_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'id_situacion_alum' => 3,
                'tipo_situacion'    => 'Baja',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        ]);
    }
}
