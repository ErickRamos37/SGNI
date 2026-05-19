<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResultadosPropedeutico extends Model
{
    protected $table = 'resultados_propedeutico';

    protected $primaryKey = 'id_resultados_propedeutico';

    protected $fillable = [
        'examen_inicial',
        'examen_final',
        'id_curso',
    ];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class, 'id_resultados_propedeutico', 'id_resultados_propedeutico');
    }
}