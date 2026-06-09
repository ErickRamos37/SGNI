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
        'id_usuario',
        'id_estado',
        'periodo',
    ];

    // ==========================================
    // RELACIONES
    // ==========================================

    /**
     * Relación con los alumnos (muchos a muchos)
     */
    public function alumnos()
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

    /**
     * Relación con el turno (uno a muchos inverso)
     */
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno', 'id_turno');
    }

    public function alumnosFinales()
    {
        // Asumiendo que la llave foránea en la tabla alumno es 'id_grupo_definitivo'
        return $this->hasMany(Alumno::class, 'id_grupo_definitivo', 'id_grupo');
    }

    /**
     * Relación con el curso (uno a muchos inverso)
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    /**
     * Relación con el usuario/docente asignado (uno a muchos inverso)
     */
    public function docente()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function estado()
    {
        return $this->belongsTo(\App\Models\EstadoGrupo::class, 'id_estado', 'id_estado');
    }
}
