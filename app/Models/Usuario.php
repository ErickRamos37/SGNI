<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    // IMPORTANTE: La PK es 'num_empleado'
    protected $primaryKey = 'num_empleado';

    // El número de empleado no es autoincrementable en la BD
    public $incrementing = false;

    protected $fillable = [
        'num_empleado',
        'nombre',
        'ap_pat',
        'ap_mat',
        'correo_institucional',
        'id_rol'
    ];

    // Relación: Un usuario pertenece a un Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
