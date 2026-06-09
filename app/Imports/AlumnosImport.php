<?php

namespace App\Imports;

use App\Models\Alumno;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlumnosImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // WithHeadingRow convierte encabezados a snake_case minúsculas
        // Orden real del Excel: matricula|Nombre|apellido_paterno|apellido_materno|puntaje|correo|correo_alter|telefono
        return new Alumno([
            'matricula'            => $row['matricula'],
            'nombre'               => $row['nombre'],
            'ap_pat'               => $row['apellido_paterno'],
            'ap_mat'               => !empty($row['apellido_materno']) ? $row['apellido_materno'] : null,
            'puntaje_ingreso'      => !empty($row['puntaje']) ? (int)$row['puntaje'] : null,
            'correo_institucional' => !empty($row['correo']) ? trim($row['correo']) : null,
            'correo_alternativo'   => trim($row['correo_alter']),
            'telefono'             => trim($row['telefono']),
            'id_carrera'           => $row['programaestudios'],
        ]);
    }
}