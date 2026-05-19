<?php

namespace App\Exports;

use App\Models\Alumno;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GrupoCalificacionesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $id_grupo;

    public function __classComponent($id_grupo)
    {
        $this->id_grupo = $id_grupo;
    }
    public function __construct($id_grupo)
    {
        $this->id_grupo = $id_grupo;
    }

    public function collection()
    {
        return Alumno::where('id_grupo_propedeutico', $this->id_grupo)
            ->with('resultadosPropedeutico')
            ->get();
    }
    public function headings(): array
    {
        return [
            'Matrícula',
            'Nombre Completo',
            'Examen Diagnóstico',
            'Examen Final Propedéutico'
        ];
    }
    public function map($alumno): array
    {
        return [
            $alumno->matricula,
            $alumno->nombre . ' ' . $alumno->ap_pat . ' ' . $alumno->ap_mat,
            $alumno->resultadosPropedeutico->examen_inicial ?? '0',
            $alumno->resultadosPropedeutico->examen_final ?? '0',
        ];
    }
}
