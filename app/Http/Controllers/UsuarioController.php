<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // 1. Mostrar el formulario de alta
    public function create()
    {
        // Traemos todos los roles para llenar el <select> dinámicamente
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    // 2. Procesar el registro
    public function store(Request $request)
    {
        // VALIDACIÓN: Fundamental para la integridad de los datos
        $request->validate([
            'num_empleado' => 'required|numeric|unique:usuarios,num_empleado',
            'nombre' => 'required|string|max:255',
            'ap_pat' => 'required|string|max:255',
            'correo_institucional' => 'required|email|unique:usuarios,correo_institucional',
            'id_rol' => 'required|exists:roles,id' // Verifica que el rol exista en la tabla roles
        ]);

        // GUARDADO
        Usuario::create($request->all());

        // Redirección con mensaje de éxito (usando tus flash messages)
        return redirect()->route('usuarios.index')
                         ->with('success', 'El usuario ha sido registrado exitosamente.');
    }
}