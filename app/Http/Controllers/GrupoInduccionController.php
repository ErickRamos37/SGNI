<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use App\Models\Usuario;
use App\Services\GrupoDistribucionService;
use App\Services\GrupoExcelService;

class GrupoInduccionController extends Controller
{
    protected $distribucionService;
    protected $excelService;

    public function __construct(GrupoDistribucionService $distribucionService, GrupoExcelService $excelService)
    {
        $this->distribucionService = $distribucionService;
        $this->excelService = $excelService;
    }

    public function showCursoInduc()
    {
        $periodos = Grupo::where('nombre_grupo', 'LIKE', '%Induc%')
            ->whereNotNull('periodo')
            ->select('periodo')
            ->distinct()
            ->orderBy('periodo', 'desc')
            ->pluck('periodo');

        $periodoActual = request('periodo', $periodos->first());

        $grupos = Grupo::where('nombre_grupo', 'LIKE', '%Induc%')
            ->where('periodo', $periodoActual)
            ->get();
        
        $docentes = Usuario::whereHas('rol', function($q) {
            $q->where('nombre_rol', 'docente');
        })->get();

        $estadoLectura = DB::table('estado_grupo')->whereRaw('LOWER(nombre_estado) = ?', ['lectura'])->first();
        $idEstadoLectura = $estadoLectura ? $estadoLectura->id_estado : null;

        return view('groups.crear_grupos_cursos.curso_induc', compact('grupos', 'docentes', 'periodos', 'periodoActual', 'idEstadoLectura'));
    }

    public function storeInduc(Request $request)
    {
        $request->validate([
            'grupos_manana'   => 'required|integer|min:0',
            'grupos_tarde'    => 'required|integer|min:0',
            'archivo_alumnos' => 'required|mimes:xlsx,xls,csv',
            'tipo_grupo'      => 'required|string',
            'periodo'         => 'required|string|max:10'
        ]);

        $gruposExistentes = Grupo::where('nombre_grupo', 'LIKE', '%Induc%')
            ->where('periodo', $request->periodo)
            ->exists();

        if ($gruposExistentes) {
            return redirect()->back()->with('error_grupos_existentes', 'Los grupos de Inducción para el periodo ' . $request->periodo . ' ya fueron generados. Para agregar más estudiantes, utiliza el módulo de Alumnos Tardíos.');
        }

        try {
            DB::beginTransaction();

            $alumnos = $this->excelService->leerAlumnosDesdeExcel($request->file('archivo_alumnos'), 'induccion');

            $stats = $this->distribucionService->procesarGrupos(
                $request->grupos_manana, 
                $request->grupos_tarde, 
                'Gral', 
                $alumnos['general'], 
                $request->tipo_grupo,
                $request->periodo
            );

            DB::commit();
            
            return redirect()->back()->with('import_stats', [
                'nuevos' => $stats['nuevos'],
                'repetidos' => $stats['repetidos']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Error: ' . $e->getMessage()]);
        }
    }

    public function showInducCreado()
    {
        $periodos = Grupo::where('nombre_grupo', 'LIKE', '%Induc%')
                         ->select('periodo')
                         ->distinct()
                         ->whereNotNull('periodo')
                         ->orderBy('periodo', 'desc')
                         ->pluck('periodo');

        $periodoActual = request('periodo', $periodos->first());

        $queryInduc = Grupo::withCount('alumnosInduccion as alumnos_count')
                           ->where('nombre_grupo', 'LIKE', '%Induc%');
        if ($periodoActual) { $queryInduc->where('periodo', $periodoActual); }
        $gruposInduc = $queryInduc->get();

        return view('groups.crear_grupos_cursos.curso_induc_creado', compact('gruposInduc', 'periodos', 'periodoActual'));
    }
}
