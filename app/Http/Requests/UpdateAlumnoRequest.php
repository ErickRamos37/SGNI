<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateAlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nombre'               => Str::title(trim($this->nombre)),
            'ap_pat'               => Str::title(trim($this->ap_pat)),
            'ap_mat'               => $this->ap_mat ? Str::title(trim($this->ap_mat)) : null,
            'correo_alternativo'   => $this->correo_alternativo ? Str::lower(trim($this->correo_alternativo)) : null,
            'correo_institucional' => $this->correo_institucional ? Str::lower(trim($this->correo_institucional)) : null,
            'telefono'             => trim($this->telefono),
        ]);
    }

    public function rules(): array
    {
        // Obtenemos la matrícula desde la URL para excluirla en las reglas unique
        $matricula = $this->route('alumno');

        return [
            'nombre'               => 'required|string|max:255',
            'ap_pat'               => 'required|string|max:255',
            'ap_mat'               => 'nullable|string|max:255',
            'correo_alternativo'   => 'nullable|email|unique:alumno,correo_alternativo,' . $matricula . ',matricula',
            'correo_institucional' => 'nullable|email|ends_with:@uabc.edu.mx|unique:alumno,correo_institucional,' . $matricula . ',matricula',
            'telefono'             => 'required|string|max:20|unique:alumno,telefono,' . $matricula . ',matricula',
            'puntaje_ingreso'      => 'nullable|integer|min:0|max:1300',
            'id_carrera'           => 'required|exists:carrera,id_carrera',
        ];
    }

    public function messages(): array
    {
        return [
            'correo_alternativo.unique'      => 'Este correo alternativo ya está en uso por otro alumno.',
            'correo_institucional.ends_with' => 'El correo institucional debe terminar en @uabc.edu.mx.',
            'correo_institucional.unique'    => 'Este correo institucional ya está registrado por otro alumno.',
            'telefono.unique'                => 'Este teléfono ya ha sido registrado por otro alumno.',
            'puntaje_ingreso.min'            => 'El puntaje no puede ser negativo.',
            'puntaje_ingreso.max'            => 'El puntaje máximo de admisión es 1300 puntos.',
            'id_carrera.exists'              => 'La carrera seleccionada no es válida.',
        ];
    }
}