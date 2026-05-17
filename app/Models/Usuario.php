<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
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
