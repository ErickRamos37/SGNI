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
    protected $keyType = 'string';
    protected $fillable = [
        'matricula',
        'nombre',
        'ap_pat',
        'ap_mat',
        'id_grupo_propedeutico',
        'id_resultados_propedeutico'
    ];

    public function resultadosPropedeutico()
    {
        return $this->belongsTo(ResultadosPropedeutico::class, 'id_resultados_propedeutico');
    }

    public function grupoPropedeutico()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo_propedeutico');
    }
}
