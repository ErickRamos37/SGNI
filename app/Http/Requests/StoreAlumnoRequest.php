<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreAlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nombre' => Str::title(trim($this->nombre)),
            'ap_pat' => Str::title(trim($this->ap_pat)),
            'ap_mat' => $this->ap_mat ? Str::title(trim($this->ap_mat)) : null,
            'correo_alternativo' => $this->correo_alternativo ? Str::lower(trim($this->correo_alternativo)) : null,
            'telefono' => trim($this->telefono),
        ]);
    }

    public function rules(): array
    {
        return [
            'matricula' => 'required|integer|unique:alumno,matricula',
            'nombre' => 'required|string|max:255',
            'ap_pat' => 'required|string|max:255',
            'ap_mat' => 'nullable|string|max:255',
            'correo_alternativo' => 'nullable|email|unique:alumno,correo_alternativo',
            'telefono' => 'required|string|max:20|unique:alumno,telefono',
            'id_carrera' => 'required', 
        ];
    }

    public function messages(): array
    {
        return [
            'matricula.unique' => 'Esta matrícula ya se encuentra registrada en el sistema.',
            'correo_alternativo.unique' => 'Este correo alternativo ya está en uso por otro alumno.',
            'telefono.unique' => 'Este teléfono ya ha sido registrado previamente.',
        ];
    }
}