<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;

class UsuarioController extends Controller
{
    // 1. READ: Mostrar la lista de usuarios (Yajra DataTables Server-Side)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Usuario::with('rol')->select('usuarios.*');

            return DataTables::of($data)
                ->addColumn('apellidos', function ($row) {
                    return $row->ap_pat . ' ' . $row->ap_mat;
                })
                ->addColumn('rol_nombre', function ($row) {
                    return $row->rol ? $row->rol->nombre_rol : 'Sin rol asignado';
                })
                ->addColumn('acciones', function ($row) {
                    // 1. Generamos ambas URLs asegurándonos de que estén dentro de esta función
                    $urlEditar = route('usuarios.edit', $row->id_usuario);
                    $urlEliminar = route('usuarios.destroy', $row->id_usuario);

                    // 2. Creamos los botones
                    $btnEditar = '<a href="' . $urlEditar . '" class="btn btn-sm btn-outline-dark d-inline-flex align-items-center justify-content-center me-2" title="Editar Usuario">
                    <i class="bi bi-pencil-square"></i>
                  </a>';

                    $btnEliminar = '<button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center btn-eliminar" data-url="' . $urlEliminar . '" title="Eliminar Usuario">
                        <i class="bi bi-trash"></i>
                    </button>';

                    // 3. Retornamos el HTML
                    return '<div class="d-flex justify-content-center align-items-center">' . $btnEditar . $btnEliminar . '</div>';
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('usuarios.index');
    }

    // 2. CREATE: Mostrar el formulario de alta
    public function create()
    {
        $roles = Rol::all();
        // Nota: Asegúrate de que tu archivo ahora se llame create.blade.php
        return view('usuarios.create', compact('roles'));
    }

    // 3. STORE: Procesar el registro (Vía AJAX/Fetch)
    public function store(StoreUsuarioRequest $request)
    {
        // Se usa $request->validated() porque ya pasó por tu StoreUsuarioRequest blindado
        $usuario = Usuario::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'El usuario ha sido registrado exitosamente.',
            'data' => $usuario
        ]);
    }

    // 4. EDIT: Mostrar formulario de edición
    public function edit(Usuario $usuario)
    {
        $roles = Rol::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    // 5. UPDATE: Actualizar en BD (Vía AJAX/Fetch)
    // 5. UPDATE: Actualizar en BD (Vía AJAX/Fetch)
    public function update(UpdateUsuarioRequest $request, $id)
    {
        // 1. Encontrar el usuario
        $usuario = Usuario::findOrFail($id);
        $numEmpleadoOriginal = $usuario->num_empleado;

        // 2. Actualizar la cuenta actual con los datos validados
        $usuario->update($request->validated());

        // 3. Sincronizar nombres si conservó su número de empleado
        // Si no cambió el número de empleado, actualizamos el nombre/apellidos 
        // en TODAS sus otras cuentas para mantener consistencia.
        if ($request->num_empleado == $numEmpleadoOriginal) {
            Usuario::where('num_empleado', $request->num_empleado)
                ->where('id_usuario', '!=', $usuario->id_usuario)
                ->update([
                    'nombre' => $request->nombre,
                    'ap_pat' => $request->ap_pat,
                    'ap_mat' => $request->ap_mat,
                ]);
        }

        return response()->json([
            'message' => 'Usuario actualizado correctamente.'
        ]);
    }

    // 6. DELETE: Eliminar registro (Vía AJAX/Fetch)
    public function destroy(Usuario $usuario)
    {
        try {
            $usuario->delete();
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado permanentemente.'
            ]);
        } catch (QueryException $e) {
            // Protección contra integridad referencial (Grupos asignados, etc)
            if ($e->getCode() == 23000) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar este usuario porque tiene grupos u otros registros vinculados.'
                ], 422); // 422 = Unprocessable Entity
            }

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en la base de datos al intentar eliminar.'
            ], 500); // 500 = Internal Server Error
        }
    }
}
