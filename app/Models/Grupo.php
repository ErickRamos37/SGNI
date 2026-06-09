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
        'id_usuario',
        'id_estado',
        'periodo'
    ];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'id_grupo_propedeutico', 'id_grupo');
    }
    
    public function alumnosInduccion()
    {
        return $this->hasMany(Alumno::class, 'id_grupo_induccion', 'id_grupo');
    }

    public function alumnosPropedeutico()
    {
        return $this->hasMany(Alumno::class, 'id_grupo_propedeutico', 'id_grupo');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno', 'id_turno');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function docente()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'num_empleado');
    }

    public function estado()
    {
        return $this->belongsTo(\App\Models\EstadoGrupo::class, 'id_estado', 'id_estado');
    }


}