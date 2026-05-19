<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    // 1. Apuntador a la tabla exacta
    protected $table = 'alumno';

    // 2. Indicar que la llave primaria es 'matricula' y no 'id'
    protected $primaryKey = 'matricula';

    // 3. Indica que la matrícula no es autoincrementable
    public $incrementing = false;

    public $timestamps = false;
    // 4. Campos que se permiten llenar desde el Excel
    protected $fillable = [
        'matricula',
        'nombre',
        'ap_pat',
        'ap_mat',
        'correo_alternativo',
        'telefono',
        'id_carrera',
    ];

    public function carrera()
    {
        // belongsTo significa que un Alumno "pertenece a" una Carrera.
        // El primer parámetro es el modelo de Carrera.
        // El segundo es cómo se llama la columna de la llave foránea en tu tabla alumno (ej. 'id_carrera').
        // El tercero es la llave primaria en la tabla carrera (ej. 'id_carrera').
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }
}
