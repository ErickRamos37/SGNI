<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'num_empleado';
    public $incrementing = false; // El número de empleado se le asigna manualmente

    protected $fillable = [
        'num_empleado',
        'nombre',
        'ap_pat',
        'ap_mat',
        'correo_institucional',
        'id_rol'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function rol()
    {
        // El segundo parámetro es la FK en usuarios, el tercero es la PK en roles
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }
}
