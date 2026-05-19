<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResultadosPropedeutico;
use App\Models\Grupo;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumno';
    protected $primaryKey = 'matricula';
    public $incrementing = false;

    public $timestamps = false;
    // 4. Campos que se permiten llenar desde el Excel
    protected $keyType = 'string';
    protected $fillable = [
        'matricula',
        'nombre',
        'ap_pat',
        'ap_mat',
        'id_grupo_propedeutico',
        'id_resultados_propedeutico'
    ];

    public function carrera()
    {
        // belongsTo significa que un Alumno "pertenece a" una Carrera.
        // El primer parámetro es el modelo de Carrera.
        // El segundo es cómo se llama la columna de la llave foránea en tu tabla alumno (ej. 'id_carrera').
        // El tercero es la llave primaria en la tabla carrera (ej. 'id_carrera').
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }
        public function resultadosPropedeutico()
    {
        return $this->belongsTo(ResultadosPropedeutico::class, 'id_resultados_propedeutico');
    }

    public function grupoPropedeutico()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo_propedeutico');
    }
}
