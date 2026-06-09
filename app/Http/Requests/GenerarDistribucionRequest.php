<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerarDistribucionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permitir a los usuarios autenticados
    }

    public function rules()
    {
        return [
            'porcentaje_alto' => 'required|integer|min:1|max:99',
            'porcentaje_bajo' => 'required|integer|min:1|max:99',
        ];
    }

    public function withValidator($validator)
    {
        // Validar que la suma sea exactamente 100%
        $validator->after(function ($validator) {
            $alto = $this->input('porcentaje_alto');
            $bajo = $this->input('porcentaje_bajo');

            if (($alto + $bajo) !== 100) {
                $validator->errors()->add('porcentajes', 'La suma de los porcentajes debe ser exactamente 100.');
            }
        });
    }
}