<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResultadosPropedeutico;
use App\Models\Grupo; // <-- Importación que ya tenían ellos

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
        'id_grupo_propedeutico',
        'id_resultados_propedeutico',
        'correo_alternativo',  
        'telefono',            
        'id_carrera',          
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
}