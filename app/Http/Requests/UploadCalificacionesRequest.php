<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadCalificacionesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'archivo_excel' => 'required|file|mimes:xlsx,xls|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'archivo_excel.required' => 'Debes seleccionar o arrastrar un archivo Excel.',
            'archivo_excel.mimes'    => 'El archivo debe ser un formato de Excel válido (.xlsx).',
            'archivo_excel.max'      => 'El archivo no debe pesar más de 10 MB.',
        ];
    }
}
