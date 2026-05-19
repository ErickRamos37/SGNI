<?php

namespace App\Imports;

use App\Models\Alumno;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlumnosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Alumno([
            'matricula'          => $row['matricula'],
            'nombre'             => $row['nombre'],
            'ap_pat'             => $row['apellido_paterno'],
            'ap_mat'             => $row['apellido_materno'],
            'correo_alternativo' => $row['correo_alter'],
            'correo_institucional' => $row['correo'],
            'telefono'           => $row['telefono'],
            'id_carrera'         => $row['programaestudios'],
        ]);
    }
}
