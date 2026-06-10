<?php

namespace App\Services;

use App\Models\Curso;
use App\Models\Grupo;
use Illuminate\Support\Facades\Response;

class GrupoExcelService
{
    /**
     * Genera y descarga el archivo Excel de la lista de asistencia del grupo.
     *
     * @param int $id_grupo
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function generarListaAsistencia($id_grupo)
    {
        $grupo = Grupo::findOrFail($id_grupo);
        
        $cursoInduccion = Curso::where('nombre_curso', 'LIKE', '%induccion%')->first();
        $isInduccion = $cursoInduccion && $grupo->id_curso == $cursoInduccion->id_curso;

        // El mismo truco para que el Excel sepa a quién exportar
        if ($isInduccion) {
            $grupo->load('alumnosInduccion');
            $grupo->alumnos = $grupo->alumnosInduccion; 
        } else {
            $grupo->load('alumnos');
        }
        
        $docente = \App\Models\Usuario::find($grupo->id_usuario);
        $nombreDocente = $docente ? mb_strtoupper($docente->nombre . ' ' . $docente->ap_pat . ' ' . $docente->ap_mat) : 'SIN ASIGNAR';
        
        $grupo->load('turno');
        $turnoStr = $grupo->turno ? ucfirst($grupo->turno->tipo_turno) : 'SIN ASIGNAR';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- ENCABEZADOS DE LA ESCUELA ---
        $sheet->setCellValue('A1', 'Universidad Autónoma de Baja California');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('A2', 'Facultad de Ingeniería, Arquitectura y Diseño - Curso de nivelación 2026');
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        // --- DATOS DEL GRUPO Y PROFESOR ---
        $sheet->setCellValue('B3', 'Docente:');
        $sheet->setCellValue('C3', $nombreDocente);
        $sheet->getStyle('B3')->getFont()->setBold(true);

        $sheet->setCellValue('B4', 'Turno:');
        $sheet->setCellValue('C4', $turnoStr);
        $sheet->setCellValue('E4', 'Horario:');
        $sheet->setCellValue('F4', '08:00-13:00'); 
        $sheet->setCellValue('K4', 'Salón:');
        $sheet->setCellValue('L4', 'Por definir'); 
        $sheet->getStyle('B4')->getFont()->setBold(true);
        $sheet->getStyle('E4')->getFont()->setBold(true);
        $sheet->getStyle('K4')->getFont()->setBold(true);

        // --- ENCABEZADOS DE LA TABLA ---
        $sheet->setCellValue('H6', 'Calificaciones');
        $sheet->mergeCells('H6:I6');
        $sheet->getStyle('H6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('H6')->getFont()->setBold(true);

        $sheet->setCellValue('J6', 'Asistencias');
        $sheet->mergeCells('J6:N6');
        $sheet->getStyle('J6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('J6')->getFont()->setBold(true);

        $sheet->setCellValue('H7', 'Examen 1');
        $sheet->setCellValue('I7', 'Examen 2');
        $sheet->getStyle('H7:I7')->getFont()->setBold(true);
        $sheet->getStyle('H7:I7')->getAlignment()->setHorizontal('center');

        // Subencabezados de fechas para asistencias
        $sheet->setCellValue('J7', 'Lunes');
        $sheet->setCellValue('K7', 'Martes');
        $sheet->setCellValue('L7', 'Miércoles');
        $sheet->setCellValue('M7', 'Jueves');
        $sheet->setCellValue('N7', 'Viernes');
        $sheet->getStyle('J7:N7')->getFont()->setBold(true);
        $sheet->getStyle('J7:N7')->getAlignment()->setHorizontal('center');

        // Columnas combinadas verticalmente para Nombre, etc.
        $mainHeaders = [
            'A' => '',
            'B' => 'Nombre',
            'C' => 'Apellido Paterno',
            'D' => 'Apellido Materno',
            'E' => 'Matrícula',
            'F' => 'Carrera a cursar',
            'G' => 'Correo'
        ];

        foreach ($mainHeaders as $col => $title) {
            $sheet->setCellValue($col . '6', $title);
            $sheet->mergeCells($col . '6:' . $col . '7');
            $sheet->getStyle($col . '6')->getFont()->setBold(true);
            $sheet->getStyle($col . '6')->getAlignment()->setHorizontal('center');
            $sheet->getStyle($col . '6')->getAlignment()->setVertical('center');
        }

        // --- BORDES PARA ENCABEZADOS ---
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A6:N7')->applyFromArray($styleArray);

        // --- IMPRIMIR ALUMNOS ---
        $row = 8;
        $contador = 1;
        
        // Cargar relación carrera si no viene
        $grupo->alumnos->load('carrera');

        $lunesSemana = \Carbon\Carbon::now()->startOfWeek();
        $fechasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $fechasSemana[] = $lunesSemana->copy()->addDays($i)->toDateString();
        }

        foreach ($grupo->alumnos as $alumno) {
            $sheet->setCellValue('A' . $row, $contador);
            $sheet->setCellValue('B' . $row, mb_strtoupper($alumno->nombre));
            $sheet->setCellValue('C' . $row, mb_strtoupper($alumno->ap_pat));
            $sheet->setCellValue('D' . $row, mb_strtoupper($alumno->ap_mat));
            $sheet->setCellValue('E' . $row, $alumno->matricula);
            $sheet->setCellValue('F' . $row, ''); // Se deja en blanco
            $sheet->setCellValue('G' . $row, ''); // Se deja en blanco
            
            // Cargar las asistencias guardadas para la semana actual
            $alumno->load(['asistencias' => function ($query) use ($fechasSemana, $grupo) {
                $query->where('id_grupo', $grupo->id_grupo)
                      ->whereIn('fecha', $fechasSemana);
            }]);
            
            $asistenciasAlu = $alumno->asistencias->keyBy('fecha');
            $columnasAsis = ['J', 'K', 'L', 'M', 'N'];
            
            foreach ($fechasSemana as $idx => $fecha) {
                $asistencia = $asistenciasAlu->get($fecha);
                $col = $columnasAsis[$idx];
                if ($asistencia) {
                    $sheet->setCellValue($col . $row, $asistencia->asistio ? 'P' : 'A');
                    $sheet->getStyle($col . $row)->getAlignment()->setHorizontal('center');
                } else {
                    $sheet->setCellValue($col . $row, '');
                }
            }
            
            // Bordes para la fila de datos
            $sheet->getStyle('A' . $row . ':N' . $row)->applyFromArray($styleArray);
            
            $row++;
            $contador++;
        }

        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Lista_' . str_replace(' ', '_', $grupo->nombre_grupo) . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Lee y valida un archivo Excel, devolviendo un arreglo de alumnos.
     * Si el tipo es propedeutico, separa en Inge y Arqui.
     * Si el tipo es induccion, devuelve una sola lista general.
     *
     * @param \Illuminate\Http\UploadedFile $archivo
     * @param string $tipoGrupo (propedeutico o induccion)
     * @return array
     * @throws \Exception
     */
    public function leerAlumnosDesdeExcel($archivo, $tipoGrupo)
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo->getRealPath());
        $filas = $spreadsheet->getActiveSheet()->toArray();

        // Leemos los encabezados de la fila 0 y los normalizamos
        $encabezados = array_map(function($col) {
            return strtolower(trim((string)$col));
        }, $filas[0]);

        $mapa = array_flip($encabezados);

        // Validar que vengan las columnas requeridas
        $requeridos = ['matricula', 'nombre', 'apellido_paterno', 'apellido_materno', 'programa_desc', 'correo_alter'];
        $faltantes = [];
        foreach ($requeridos as $req) {
            if (!isset($mapa[$req])) {
                $faltantes[] = $req;
            }
        }

        if (!empty($faltantes)) {
            throw new \Exception('El archivo de Excel no tiene el formato correcto. Faltan las siguientes columnas o están mal escritas: ' . implode(', ', $faltantes));
        }

        $carreras = \App\Models\Carrera::all();

        if ($tipoGrupo === 'propedeutico') {
            $alumnosInge = [];
            $alumnosArqui = [];
        } else {
            $alumnosGenerales = [];
        }
        
        for ($i = 1; $i < count($filas); $i++) {
            $fila = $filas[$i];
            
            $matricula = isset($mapa['matricula']) ? trim((string)$fila[$mapa['matricula']]) : '';
            if (empty($matricula)) continue; 

            $programa = isset($mapa['programa_desc']) ? strtoupper(trim((string)$fila[$mapa['programa_desc']])) : '';
            $telefono = isset($mapa['telefono']) && !empty(trim((string)$fila[$mapa['telefono']])) ? trim((string)$fila[$mapa['telefono']]) : substr($matricula . rand(100, 999), 0, 10);
            $correo_alt = isset($mapa['correo_alter']) && !empty(trim((string)$fila[$mapa['correo_alter']])) ? trim((string)$fila[$mapa['correo_alter']]) : $matricula . '@sin-correo.com';
            $correo_inst = isset($mapa['correo']) && !empty(trim((string)$fila[$mapa['correo']])) ? trim((string)$fila[$mapa['correo']]) : null;
            $puntaje = isset($mapa['puntaje']) && !empty(trim((string)$fila[$mapa['puntaje']])) ? trim((string)$fila[$mapa['puntaje']]) : null;

            // Determinar la carrera dinámicamente buscando en la tabla carrera
            $id_carrera = 1; // Por defecto Ingeniería
            if (str_contains($programa, 'ARQUITECTURA')) {
                $carreraObj = $carreras->first(function($c) {
                    return str_contains(strtoupper($c->nombre_carrera), 'ARQUITECTURA');
                });
                $id_carrera = $carreraObj ? $carreraObj->id_carrera : 2;
            } else {
                $carreraObj = $carreras->first(function($c) {
                    return str_contains(strtoupper($c->nombre_carrera), 'INGENIERIA');
                });
                $id_carrera = $carreraObj ? $carreraObj->id_carrera : 1;
            }

            $datosAlumno = [
                'matricula'             => $matricula,
                'nombre'                => isset($mapa['nombre']) ? substr(trim((string)$fila[$mapa['nombre']]), 0, 45) : '',
                'ap_pat'                => isset($mapa['apellido_paterno']) ? substr(trim((string)$fila[$mapa['apellido_paterno']]), 0, 25) : '',
                'ap_mat'                => isset($mapa['apellido_materno']) ? substr(trim((string)$fila[$mapa['apellido_materno']]), 0, 25) : '',
                'correo_institucional'  => $correo_inst,
                'correo_alternativo'    => substr($correo_alt, 0, 400),
                'telefono'              => $telefono,
                'puntaje_ingreso'       => $puntaje,
                'id_carrera'            => $id_carrera,
            ];

            if ($tipoGrupo === 'propedeutico') {
                if (str_contains($programa, 'ARQUITECTURA')) {
                    $alumnosArqui[] = $datosAlumno;
                } else {
                    $alumnosInge[] = $datosAlumno;
                }
            } else {
                $alumnosGenerales[] = $datosAlumno;
            }
        }

        if ($tipoGrupo === 'propedeutico') {
            return [
                'inge' => $alumnosInge,
                'arqui' => $alumnosArqui
            ];
        } else {
            return [
                'general' => $alumnosGenerales
            ];
        }
    }
}
