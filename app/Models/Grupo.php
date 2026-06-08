<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    // CORRECCIÓN CLAVE PARA PREVENIR GRUPOS VACÍOS:
    protected $primaryKey = 'id_grupo'; 
    public $incrementing = true;

    protected $fillable = [
        'nombre_grupo',
        'id_turno',
        'id_curso',
        'num_empleado',
        'id_estado',
    ];

    public function alumnos()
    {
        return $this->hasMany(Alumno::class, 'id_grupo_propedeutico', 'id_grupo');
    }

    public function alumnosInduccion()
    {
        return $this->hasMany(Alumno::class, 'id_grupo_induccion', 'id_grupo');
    }
}