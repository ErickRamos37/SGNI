<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use App\Models\Curso;
use App\Models\Usuario;
use App\Services\GrupoDistribucionService;
use App\Services\GrupoExcelService;

class GrupoController extends Controller
{
    protected $distribucionService;
    protected $excelService;

    public function __construct(GrupoDistribucionService $distribucionService, GrupoExcelService $excelService)
    {
        $this->distribucionService = $distribucionService;
        $this->excelService = $excelService;
    }

    public function cambiarModoEstado(Request $request, $id_grupo)
    {
        $grupo = Grupo::findOrFail($id_grupo);
        
        $estadoLectura = DB::table('estado_grupo')->whereRaw('LOWER(nombre_estado) = ?', ['lectura'])->first();
        $estadoEditable = DB::table('estado_grupo')->whereRaw('LOWER(nombre_estado) = ?', ['editable'])->first();

        if (!$estadoLectura || !$estadoEditable) {
            return redirect()->back()->withErrors(['Faltan los estados requeridos en el catálogo (Lectura/Editable).']);
        }

        if ($grupo->id_estado == $estadoLectura->id_estado) {
            $grupo->id_estado = $estadoEditable->id_estado;
            $mensaje = 'El grupo ha regresado al modo Editable.';
        } else {
            $grupo->id_estado = $estadoLectura->id_estado;
            $mensaje = 'El grupo ha sido bloqueado (Modo Lectura).';
        }

        $grupo->save();

        return redirect()->back()->with('success', $mensaje);
    }

    public function showListaGrupo($id_grupo)
    {
        $grupo = Grupo::findOrFail($id_grupo);

        $cursoInduccion = Curso::where('nombre_curso', 'LIKE', '%induccion%')->first();
        $isInduccion = $cursoInduccion && $grupo->id_curso == $cursoInduccion->id_curso;

        if ($isInduccion) {
            $grupo->load('alumnosInduccion');
            $grupo->alumnos = $grupo->alumnosInduccion; 
        } else {
            $grupo->load('alumnos');
        }

        return view('groups.crear_grupos_cursos.lista_grupo', compact('grupo'));
    }

    public function descargarLista($id_grupo)
    {
        return $this->excelService->generarListaAsistencia($id_grupo);
    }

    public function guardarProfesores(Request $request)
    {
        $request->validate([
            'docentes' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $estadoLectura = DB::table('estado_grupo')->whereRaw('LOWER(nombre_estado) = ?', ['lectura'])->first();
            $idEstadoLectura = $estadoLectura ? $estadoLectura->id_estado : null;

            foreach ($request->docentes as $id_grupo => $num_empleado) {
                if (!empty($num_empleado)) {
                    $grupo = Grupo::findOrFail($id_grupo);

                    if ($idEstadoLectura && $grupo->id_estado == $idEstadoLectura) {
                        throw new \Exception('El grupo "' . $grupo->nombre_grupo . '" está en Modo Lectura. No se pueden realizar cambios.');
                    }

                    $grupo->id_usuario = $num_empleado;
                    $grupo->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', '¡Docentes asignados correctamente a los grupos!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([$e->getMessage()]);
        }
    }
}