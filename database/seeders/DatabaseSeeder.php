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
          DB::table('carrera')->insertOrIgnore([
        'id_carrera'     => 1,
        'nombre_carrera' => 'Tronco Común',
        'created_at'     => now(),
        'updated_at'     => now(),
    ]);

    }
}
