<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Http\Requests\StoreUsuarioRequest;

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
    public function store(StoreUsuarioRequest $request)
    {
        $usuario = Usuario::create($request->all());

        // Respondemos con un JSON (ideal para nuestra petición Fetch/AJAX del frontend)
        return response()->json([
            'success' => true,
            'message' => 'El usuario ha sido registrado exitosamente.',
            'data' => $usuario
        ]);
    }

    // Mostrar la lista de usuarios
    public function index()
    {
        // Traemos todos los usuarios junto con su rol asociado
        $usuarios = Usuario::with('rol')->get();
        
        return view('usuarios.lista_usuarios', compact('usuarios')); 
    }
}