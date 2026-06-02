<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGruposFinalesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo el Administrador puede ejecutar esto
        return auth()->user()->rol->nombre_rol === 'Administrador';
    }

    protected function prepareForValidation(): void
    {
        // Sanitizamos los porcentajes asegurando que sean números enteros
        $this->merge([
            'porcentaje_altos' => (int) $this->input('porcentaje_altos', 80),
            'porcentaje_bajos' => (int) $this->input('porcentaje_bajos', 20),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'porcentaje_altos' => 'required|integer|min:0|max:100',
            'porcentaje_bajos' => 'required|integer|min:0|max:100',
            'criterios' => 'required|array|min:1', // Al menos un criterio activo
            'criterios.*.tipo' => 'required|string',
            'criterios.*.minimo' => 'required|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Regla de negocio: Ambos porcentajes deben equilibrar al 100%
            $suma = $this->porcentaje_altos + $this->porcentaje_bajos;
            if ($suma !== 100) {
                $validator->errors()->add('porcentaje_altos', 'La suma de porcentajes altos y bajos debe ser exactamente 100%.');
            }
        });
    }
}
