<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportarAlumnosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cambiar a true para permitir el flujo
    }

    public function rules(): array
    {
        return [
            'curso' => 'required|string',
            'archivo_excel' => 'required|mimes:xlsx,xls' // Solo se permiten archivos Excel
        ];
    }

    public function messages(): array
    {
        return [
            'curso.required' => 'El curso es un campo obligatorio.',
            'archivo_excel.required' => 'Es necesario adjuntar un archivo de Excel.',
            'archivo_excel.mimes' => 'El formato del archivo debe ser estrictamente .xlsx o .xls.'
        ];
    }
}