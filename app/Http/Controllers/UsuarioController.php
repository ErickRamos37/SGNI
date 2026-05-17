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
        // 1. Extraemos de la base de datos todos los roles
        $roles = Rol::all();

        // 2. Se abre la vista y le "inyectamos" la variable $roles usando compact()
        return view('usuarios.alta_usuarios', compact('roles'));
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
            'id_rol' => 'required|exists:roles,id_rol'
        ]);

        // GUARDADO
        Usuario::create($request->all());

        // Redirección con mensaje de éxito (usando tus flash messages)
        return redirect()->route('usuarios.index')
                         ->with('success', 'El usuario ha sido registrado exitosamente.');
    }

    // Mostrar la lista de usuarios
    public function index()
    {
        // Traemos todos los usuarios junto con su rol asociado
        $usuarios = Usuario::with('rol')->get();
        
        // Asumiendo que renombraste tu archivo a lista_usuarios.blade.php
        return view('usuarios.lista_usuarios', compact('usuarios')); 
    }
}