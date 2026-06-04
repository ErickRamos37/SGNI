<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*DB::table('carrera')->insertOrIgnore([
            'id_carrera' => 1,
            'nombre_carrera' => 'Tronco Común', // O el nombre de la columna que tu equipo haya elegido
        ]);

        DB::table('grupos')->insertOrIgnore([
            'id_grupo' => 1,
            'nombre_grupo' => 'Grupo Ficticio Inicio', // Cambia 'nombre' por la columna real de tu tabla grupos si marca error
        ]);

        // 3. Insertamos el resultado de propedéutico base
        DB::table('resultados_propedeutico')->insertOrIgnore([
            'id_resultados_propedeutico' => 1,// Cambia 'nombre' por la columna real si es necesario
        ]);*/
    }
}
