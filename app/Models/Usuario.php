<?php

namespace App\Models;

// Importamos la clase Authenticatable de Laravel nativo
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $primaryKey = 'num_empleado';
    public $incrementing = false; // El número de empleado se le asigna manualmente

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    protected $fillable = [
        'num_empleado',
        'nombre',
        'ap_pat',
        'ap_mat',
        'correo_institucional',
        'id_rol'
    ];
}