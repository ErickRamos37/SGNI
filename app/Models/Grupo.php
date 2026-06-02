<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $table = 'grupos';

    protected $primaryKey = 'id_grupo';

    protected $fillable = [
        'nombre_grupo',
        'id_curso',
        'id_turno',      
        'num_empleado', 
        'id_estado'      
    ];

    // La función intacta de tus compañeros
    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'id_grupo_propedeutico', 'id_grupo');
    }
    
    public function alumnosInduccion()
    {
        return $this->hasMany(Alumno::class, 'id_grupo_induccion', 'id_grupo');
    }
}