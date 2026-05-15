<?php

namespace App\Imports;

use App\Models\Alumno;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AlumnosImport implements ToModel, WithHeadingRow
{
    /**
     * Este método se ejecuta por CADA FILA del Excel que tenga datos.
     * La variable $row es un arreglo asociativo con los datos de esa fila.
     */
    public function model(array $row)
    {
        // Retornamos una nueva instancia de tu modelo Alumno.
        // Lo que está en comillas simples en el $row['...'] DEBE coincidir 
        // exactamente con el encabezado de tu columna en el archivo de Excel (en minúsculas).

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