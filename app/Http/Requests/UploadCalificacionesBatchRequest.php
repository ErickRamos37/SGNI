<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCalificacionesBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'calificaciones'                  => 'required|array',
            'calificaciones.*.examen_inicial' => 'nullable|numeric|min:0|max:100',
            'calificaciones.*.examen_final'   => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'calificaciones.*.examen_inicial.max' => 'La calificación no puede ser mayor a 100.',
            'calificaciones.*.examen_final.max'   => 'La calificación no puede ser mayor a 100.',
        ];
    }
}
