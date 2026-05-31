<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Alumno;
use App\Models\Asistencia;
use Carbon\Carbon;

class AsistenciaController extends Controller
{

    public function paselista()
    {
        // 1. Traemos a los alumnos REALES de la base de datos
        // (Aquí asumo que traes a todos, o le pones el where de tu grupo: Alumno::where('id_grupo', 1)->get())
        $alumnos = Alumno::all(); 

        // 2. Revisamos si el profe acaba de subir un Excel (atajo de autocompletado)
        $asistenciasExcel = session('alumnos_importados', []);

        // 3. Borramos la memoria temporal para que no se queden marcados por error si recarga la página
        session()->forget('alumnos_importados');

        return view('asistencia.paselista', compact('alumnos', 'asistenciasExcel'));
    }

    public function grupal()
    {
        return view('asistencia.grupal');
    }

    public function procesar(Request $request)
    {
        $request->validate([
            'archivo_asistencia' => 'required|file|mimes:xlsx,xls|max:5120'
        ]);

        try {
            $archivo = $request->file('archivo_asistencia');
            $rutaTemporal = $archivo->getRealPath();

            $spreadsheet = IOFactory::load($rutaTemporal);
            $hoja = $spreadsheet->getActiveSheet();
            $filas = $hoja->toArray();

            $alumnos = [];
            
            // Asumimos que la fila 0 es el encabezado
            for ($i = 4; $i < count($filas); $i++) {
                $fila = $filas[$i];
                
                if (empty($fila[0])) continue;

                $alumnos[] = [
                    'matricula' => $fila[0],
                    'nombre'    => $fila[1],
                    'lunes'     => $this->evaluarAsistencia($fila[2] ?? null),
                    'martes'    => $this->evaluarAsistencia($fila[3] ?? null),
                    'miercoles' => $this->evaluarAsistencia($fila[4] ?? null),
                    'jueves'    => $this->evaluarAsistencia($fila[5] ?? null),
                    'viernes'   => $this->evaluarAsistencia($fila[6] ?? null),
                ];
            }

            return response()->json([
                'success' => true,
                'mensaje' => 'Archivo procesado correctamente',
                'datos'   => $alumnos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error al leer el archivo Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    // Función auxiliar para leer los "Presente" o "1" del Excel
    private function evaluarAsistencia($valorCelda)
    {
        if (!$valorCelda) return false;
        
        $valor = strtolower(trim($valorCelda));
        return in_array($valor, ['presente', 'p', '1', 'sí', 'si', 'x', 'asistencia']);
    }

    public function guardarMasivo(Request $request)
    {
        $request->validate([
            'id_grupo' => 'required|integer',
            'asistencias' => 'required|array',
            'asistencias.*.matricula' => 'required|string',
        ]);

        $idGrupo = $request->id_grupo;
        $lunesDeEstaSemana = Carbon::now()->startOfWeek(); 

        foreach ($request->asistencias as $data) {
            
            $alumno = Alumno::where('matricula_alumno', $data['matricula'])->first();
            if (!$alumno) continue; 

            $dias = [
                0 => $data['lunes'] ?? false,
                1 => $data['martes'] ?? false,
                2 => $data['miercoles'] ?? false,
                3 => $data['jueves'] ?? false,
                4 => $data['viernes'] ?? false,
            ];

            foreach ($dias as $diasAgregados => $asistio) {
                $fechaExacta = $lunesDeEstaSemana->copy()->addDays($diasAgregados)->format('Y-m-d');
                $estadoEnum = $asistio ? 'Presente' : 'Ausente';

                Asistencia::updateOrCreate(
                    [
                        'id_alumno' => $alumno->id_alumno,
                        'id_grupo'  => $idGrupo,
                        'fecha_asistencia' => $fechaExacta,
                    ],
                    [
                        'estado_asistencia' => $estadoEnum
                    ]
                );
            }
        }

        // Modifica el return para que quede así:
            return response()->json([
                'success' => true,
                'mensaje' => 'Archivo procesado correctamente',
                'datos'   => $alumnos,
                'crudo'   => $filas // <--- ESTO NOS VA A SALVAR LA VIDA
            ]);
    }
}