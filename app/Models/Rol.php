<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    // Le decimos a Laravel cómo se llama tu tabla exactamente
    protected $table = 'roles'; // Cambia esto si tu tabla se llama distinto, ej: 'catalogo_roles'
    
    protected $primaryKey = 'id_rol';
}