<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    // Tu tabla en la BD está en plural
    protected $table = 'asistencias'; 
    protected $primaryKey = 'id_asistencia';
}