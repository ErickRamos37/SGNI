<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use App\Models\Usuario;
use App\Services\GrupoDistribucionService;
use App\Services\GrupoExcelService;

class GrupoPropedeuticoController extends Controller
{
    protected $distribucionService;
    protected $excelService;

    public function __construct(GrupoDistribucionService $distribucionService, GrupoExcelService $excelService)
    {
        $this->distribucionService = $distribucionService;
        $this->excelService = $excelService;
    }

    public function showCursoPrope()
    {
        // Obtener todos los periodos disponibles de grupos Prope
        $periodos = Grupo::where('nombre_grupo', 'LIKE', '%Prope%')
            ->whereNotNull('periodo')
            ->select('periodo')
            ->distinct()
            ->orderBy('periodo', 'desc')
            ->pluck('periodo');

        // Tomar el periodo seleccionado por URL o el más reciente
        $periodoActual = request('periodo', $periodos->first());

        // Filtrar grupos por periodo
        $gruposInge = Grupo::where('nombre_grupo', 'LIKE', '%Prope Inge%')
            ->where('periodo', $periodoActual)
            ->get();
        $gruposArqui = Grupo::where('nombre_grupo', 'LIKE', '%Prope Arqui%')
            ->where('periodo', $periodoActual)
            ->get();

        // Traemos a los docentes
        $docentes = Usuario::whereHas('rol', function($q) {
            $q->where('nombre_rol', 'docente');
        })->get();

        $estadoLectura = DB::table('estado_grupo')->whereRaw('LOWER(nombre_estado) = ?', ['lectura'])->first();
        $idEstadoLectura = $estadoLectura ? $estadoLectura->id_estado : null;

        return view('groups.crear_grupos_cursos.curso_prope', compact('gruposInge', 'gruposArqui', 'docentes', 'periodos', 'periodoActual', 'idEstadoLectura'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grupos_manana_inge'  => 'required|integer|min:0',
            'grupos_tarde_inge'   => 'required|integer|min:0',
            'grupos_manana_arqui' => 'required|integer|min:0',
            'grupos_tarde_arqui'  => 'required|integer|min:0',
            'archivo_alumnos'     => 'required|mimes:xlsx,xls,csv',
            'tipo_grupo'          => 'required|string',
            'periodo'             => 'required|string|max:10'
        ]);

        $gruposExistentes = Grupo::where('nombre_grupo', 'LIKE', '%Prope%')
            ->where('periodo', $request->periodo)
            ->exists();

        if ($gruposExistentes) {
            return redirect()->back()->with('error_grupos_existentes', 'Los grupos para el periodo ' . $request->periodo . ' ya fueron generados. Si deseas inscribir a más estudiantes, utiliza el módulo de Alumnos Tardíos.');
        }

        try {
            DB::beginTransaction();

            $alumnos = $this->excelService->leerAlumnosDesdeExcel($request->file('archivo_alumnos'), 'propedeutico');

            $statsInge = $this->distribucionService->procesarGrupos($request->grupos_manana_inge, $request->grupos_tarde_inge, 'Inge', $alumnos['inge'], $request->tipo_grupo, $request->periodo);
            $statsArqui = $this->distribucionService->procesarGrupos($request->grupos_manana_arqui, $request->grupos_tarde_arqui, 'Arqui', $alumnos['arqui'], $request->tipo_grupo, $request->periodo);

            $totalNuevos = $statsInge['nuevos'] + $statsArqui['nuevos'];
            $totalRepetidos = $statsInge['repetidos'] + $statsArqui['repetidos'];

            DB::commit();
            
            return redirect()->back()->with('import_stats', [
                'nuevos' => $totalNuevos,
                'repetidos' => $totalRepetidos
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Error: ' . $e->getMessage()]);
        }
    }

    public function showPropeCreado()
    {
        $periodos = Grupo::where('nombre_grupo', 'LIKE', '%Prope%')
                         ->select('periodo')
                         ->distinct()
                         ->whereNotNull('periodo')
                         ->orderBy('periodo', 'desc')
                         ->pluck('periodo');

        $periodoActual = request('periodo', $periodos->first());

        $queryInge = Grupo::withCount('alumnos')->where('nombre_grupo', 'LIKE', '%Prope Inge%');
        if ($periodoActual) { $queryInge->where('periodo', $periodoActual); }
        $gruposInge = $queryInge->get();

        $queryArqui = Grupo::withCount('alumnos')->where('nombre_grupo', 'LIKE', '%Prope Arqui%');
        if ($periodoActual) { $queryArqui->where('periodo', $periodoActual); }
        $gruposArqui = $queryArqui->get();

        return view('groups.crear_grupos_cursos.curso_prope_creado', compact('gruposInge', 'gruposArqui', 'periodos', 'periodoActual'));
    }
}
