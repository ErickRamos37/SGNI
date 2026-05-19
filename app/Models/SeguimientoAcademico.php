<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoAcademico extends Model
{
    protected $table = 'seguimiento_academico';
    protected $primaryKey = 'id_seguimiento';

    public function situacionAlum()
    {
        return $this->belongsTo(SituacionAlum::class, 'id_situacion_alum', 'id_situacion_alum');
    }
}