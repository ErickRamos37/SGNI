<?php

namespace App\Models;

// Importamos la clase Authenticatable de Laravel nativo
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    // Le indicamos que sigue siendo autoincremental (true por defecto, pero es buena práctica declararlo)
    public $incrementing = true;

    protected $fillable = [
        'num_empleado',
        'nombre',
        'ap_pat',
        'ap_mat',
        'correo_institucional',
        'id_rol'
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}
