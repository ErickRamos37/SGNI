<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    // 1. Le decimos exactamente cómo se llama la tabla en la base de datos
    // (Si en tu BD se llama 'carreras' en plural, ponle la 's' al final)
    protected $table = 'carrera'; 

    // 2. Le decimos cuál es la llave primaria de esa tabla
    protected $primaryKey = 'id_carrera';

    // 3. (Opcional) Si tu tabla de carreras NO tiene las columnas 'created_at' y 'updated_at',
    // descomenta la siguiente línea quitando las dos diagonales para evitar errores:
    // public $timestamps = false;
}