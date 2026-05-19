<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuscarAlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    // Aquí se limpia el dato antes de validar (Regla A2)
    protected function prepareForValidation()
    {
        if ($this->has('matricula')) {
            $this->merge([
                'matricula' => trim($this->matricula)
            ]);
        }
    }

    // Prohibido validar en controladores (Regla A1)
    public function rules(): array
    {
        return [
            'matricula' => 'required|integer|min:1'
        ];
    }

    public function messages()
    {
        return [
            'matricula.required' => 'La matrícula es obligatoria.',
            'matricula.integer' => 'La matrícula debe ser un número válido.'
        ];
    }
}