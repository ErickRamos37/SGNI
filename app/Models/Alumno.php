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
}
