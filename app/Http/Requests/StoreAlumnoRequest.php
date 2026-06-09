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
            'nombre'             => Str::title(trim($this->nombre)),
            'ap_pat'             => Str::title(trim($this->ap_pat)),
            'ap_mat'             => $this->ap_mat ? Str::title(trim($this->ap_mat)) : null,
            'correo_alternativo' => $this->correo_alternativo ? Str::lower(trim($this->correo_alternativo)) : null,
            'telefono'           => trim($this->telefono),
        ]);
    }

    public function rules(): array
    {
        return [
            'matricula'          => 'required|integer|unique:alumno,matricula',
            'nombre'             => 'required|string|max:255',
            'ap_pat'             => 'required|string|max:255',
            'ap_mat'             => 'nullable|string|max:255',
            'correo_alternativo' => 'required|email|unique:alumno,correo_alternativo',
            'telefono'           => 'required|string|max:20|unique:alumno,telefono',
            'puntaje_ingreso'    => 'nullable|integer|min:0|max:1300',
            'id_carrera'         => 'required|exists:carrera,id_carrera',
            'id_grupo_induccion' => 'nullable|exists:grupos,id_grupo',
            'id_grupo_propedeutico' => 'nullable|exists:grupos,id_grupo',
        ];
    }

    public function messages(): array
    {
        return [
            'matricula.unique'            => 'Esta matrícula ya se encuentra registrada en el sistema.',
            'correo_alternativo.required' => 'El correo alternativo es obligatorio.',
            'correo_alternativo.unique'   => 'Este correo alternativo ya está en uso por otro alumno.',
            'telefono.unique'             => 'Este teléfono ya ha sido registrado previamente.',
            'puntaje_ingreso.max'         => 'El puntaje máximo de admisión es 1300 puntos.',
            'id_carrera.exists'           => 'La carrera seleccionada no es válida.',
            'id_grupo_induccion.exists'   => 'El grupo de inducción seleccionado no es válido.',
            'id_grupo_propedeutico.exists'=> 'El grupo propedéutico seleccionado no es válido.',
        ];
    }
}