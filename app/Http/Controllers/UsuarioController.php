<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Http\Requests\StoreUsuarioRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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

    // Mostrar la lista de usuarios (Server-Side DataTables)
    public function index(Request $request)
    {
        // 1. Verificamos si la petición viene de DataTables (AJAX)
        if ($request->ajax()) {
            // Preparamos la consulta
            $data = Usuario::with('rol')->select('usuarios.*');

            // Devolvemos el motor de DataTables
            return DataTables::of($data)
                // Columna virtual para juntar los apellidos
                ->addColumn('apellidos', function ($row) {
                    return $row->ap_pat . ' ' . $row->ap_mat;
                })
                // Columna virtual para extraer el nombre del rol
                ->addColumn('rol_nombre', function ($row) {
                    return $row->rol ? $row->rol->nombre_rol : 'Sin rol asignado';
                })
                // 1. Columna de acciones con botones HTML
                ->addColumn('acciones', function ($row) {
                    // Botón Editar: Pequeño, bordes redondeados suaves, outline-dark
                    $btnEditar = '<a href="#" class="btn btn-sm btn-outline-dark d-inline-flex align-items-center justify-content-center me-2" title="Editar Usuario">
                                    <i class="bi bi-pencil-square"></i>
                                  </a>';

                    // Botón Eliminar: Pequeño, bordes redondeados suaves, outline-danger para alertas
                    $btnEliminar = '<button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center" title="Eliminar Usuario">
                                        <i class="bi bi-trash"></i>
                                    </button>';

                    return '<div class="d-flex justify-content-center align-items-center">' . $btnEditar . $btnEliminar . '</div>';
                })
                // Se declarar que 'acciones' contiene HTML puro
                ->rawColumns(['acciones'])
                // Esto es necesario para que busque correctamente
                ->make(true);
        }

        // 2. Si no es AJAX, simplemente cargamos la vista vacía
        return view('usuarios.lista_usuarios');
    }
}
