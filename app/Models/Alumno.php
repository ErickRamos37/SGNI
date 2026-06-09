<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResultadosPropedeutico;
use App\Models\Grupo; // <-- Importación que ya tenían ellos

use App\Models\Asistencia;
use App\Models\SeguimientoAcademico;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumno';
    protected $primaryKey = 'matricula';
    public $incrementing = false;

    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'matricula',
        'nombre',
        'ap_pat',
        'ap_mat',
        'correo_institucional', 
        'correo_alternativo', 
        'telefono', 
        'id_carrera', 
        'id_grupo_propedeutico',
        'id_grupo_induccion'
    ];


    public function carrera()
    {
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

    // =========================================================
    // RELACIONES CONSUMIDAS POR LA VISTA DEL PSICÓLOGO
    // =========================================================

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'matricula', 'matricula');
    }

    public function seguimientoAcademico()
    {
        return $this->hasOne(SeguimientoAcademico::class, 'matricula', 'matricula');
    }


    // =========================================================
    // ACCESSORS (CAMPOS DINÁMICOS) IMPRESOS EN LA VISTA
    // =========================================================

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->ap_pat} {$this->ap_mat}";
    }

    public function getPorcentajeAsistenciaPropedeuticoAttribute()
    {
        $total = $this->asistencias()->where('id_grupo', 1)->count();
        if ($total === 0) return 0;
        
        $asistidas = $this->asistencias()->where('id_grupo', 1)->where('asistio', 1)->count();
        return round(($asistidas / $total) * 100);
    }

    public function getPorcentajeAsistenciaInduccionAttribute()
    {
        $total = $this->asistencias()->where('id_grupo', 2)->count();
        if ($total === 0) return 0;
        
        $asistidas = $this->asistencias()->where('id_grupo', 2)->where('asistio', 1)->count();
        return round(($asistidas / $total) * 100);
    }

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
