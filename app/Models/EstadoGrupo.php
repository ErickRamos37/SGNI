<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoGrupo extends Model
{
    protected $table = 'estado_grupo';
    protected $primaryKey = 'id_estado';

    protected $fillable = [
        'nombre_estado',
    ];
}
