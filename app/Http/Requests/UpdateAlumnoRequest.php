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
            // Los IDs de los grupos no requieren procesamiento de strings aquí
        ]);
    }

    public function rules(): array
    {
        $matricula = $this->route('alumno');

        return [
            'nombre'               => 'required|string|max:50',
            'ap_pat'               => 'required|string|max:50',
            'ap_mat'               => 'nullable|string|max:50',
            'correo_alternativo'   => 'nullable|email|max:50|unique:alumno,correo_alternativo,' . $matricula . ',matricula',
            'correo_institucional' => 'nullable|email|max:50|ends_with:@uabc.edu.mx|unique:alumno,correo_institucional,' . $matricula . ',matricula',
            'telefono'             => 'required|string|max:15|unique:alumno,telefono,' . $matricula . ',matricula',
            'puntaje_ingreso'      => 'nullable|integer|min:0|max:1300',
            'id_carrera'           => 'required|exists:carrera,id_carrera',
            
            // Un solo grupo que viene de la vista
            'id_grupo_definitivo'  => 'nullable|integer|exists:grupos,id_grupo', 
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
            // Mensaje actualizado para el grupo
            'id_grupo_definitivo.exists'     => 'El grupo asignado seleccionado no es válido.',
        ];
    }
}