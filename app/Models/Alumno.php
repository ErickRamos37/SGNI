<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Asistencia;
use App\Models\SeguimientoAcademico;
use App\Models\ResultadoPropedeutico;

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
    // Relación con Asistencias (Un alumno tiene muchas asistencias)
    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'matricula', 'matricula');
    }

    // Relación con el Seguimiento Académico
    public function seguimientoAcademico()
    {
        return $this->hasOne(SeguimientoAcademico::class, 'matricula', 'matricula');
    }

    // Relación con Resultados (para el cálculo de mejoría)
    public function resultadosPropedeutico()
    {
        return $this->belongsTo(ResultadoPropedeutico::class, 'id_resultados_propedeutico');
    }

    /**
     * ACCESSORS: Campos dinámicos calculados en tiempo de ejecución
     */

    // Nombre Completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->ap_pat} {$this->ap_mat}";
    }

    // Programa Dinámico
    // 1. El Accessor de Programa ahora es más descriptivo basado en tu flujo real
public function getProgramaAttribute()
{
    // Si tiene registros en exámenes, pasó por Propedéutico; si no, entró directo a Inducción
    return $this->resultadosPropedeutico ? 'Propedéutico' : 'Inducción';
}

// 2. Porcentaje exclusivo para Propedéutico (id_grupo = 1)
public function getPorcentajeAsistenciaPropedeuticoAttribute()
{
    $total = $this->asistencias()->where('id_grupo', 1)->count();
    if ($total === 0) return 0;
    
    $asistidas = $this->asistencias()->where('id_grupo', 1)->where('asistio', 1)->count();
    return round(($asistidas / $total) * 100);
}

// 3. Porcentaje exclusivo para Inducción (id_grupo = 2)
public function getPorcentajeAsistenciaInduccionAttribute()
{
    $total = $this->asistencias()->where('id_grupo', 2)->count();
    if ($total === 0) return 0;
    
    $asistidas = $this->asistencias()->where('id_grupo', 2)->where('asistio', 1)->count();
    return round(($asistidas / $total) * 100);
}

    // Calcular Mejoría basada en tus tablas de exámenes
    public function getMejoriaAttribute()
    {
        $resultados = $this->resultadosPropedeutico;
        
        if ($resultados && isset($resultados->examen_final) && isset($resultados->examen_inicial)) {
            $diferencia = $resultados->examen_final - $resultados->examen_inicial;
            return $diferencia >= 0 ? "+{$diferencia}" : $diferencia;
        }
        
        return 'N/A';
    }
}