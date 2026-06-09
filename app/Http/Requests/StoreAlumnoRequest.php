<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Models\Grupo; // <-- Importamos el modelo Grupo

class StoreAlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // 1. Atrapamos el "id_grupo" genérico que manda tu vista HTML
        $idGrupo = $this->input('id_grupo');
        
        $idPrope = null;
        $idInduc = null;

        // 2. Si seleccionaron un grupo, investigamos de qué tipo es
        if (!empty($idGrupo)) {
            $grupo = Grupo::with('curso')->find($idGrupo);
            
            if ($grupo && $grupo->curso) {
                $nombreCurso = strtolower($grupo->curso->nombre_curso);
                
                // Si el nombre del curso dice "induc", va a la columna de inducción
                if (str_contains($nombreCurso, 'induc')) {
                    $idInduc = $idGrupo;
                } else {
                    // Si no, va a la columna de propedéutico
                    $idPrope = $idGrupo;
                }
            } else {
                // Por defecto, si algo falla, lo mandamos a propedéutico
                $idPrope = $idGrupo; 
            }
        }

        // 3. Mezclamos y preparamos los datos para que coincidan con tu BD
        $this->merge([
            'nombre'                => Str::title(trim($this->nombre)),
            'ap_pat'                => Str::title(trim($this->ap_pat)),
            'ap_mat'                => $this->ap_mat ? Str::title(trim($this->ap_mat)) : null,
            'correo_alternativo'    => $this->correo_alternativo ? Str::lower(trim($this->correo_alternativo)) : null,
            'telefono'              => trim($this->telefono),
            'id_grupo_propedeutico' => $idPrope, // <-- Se asigna al correcto
            'id_grupo_induccion'    => $idInduc, // <-- Se asigna al correcto
        ]);
    }

    public function rules(): array
    {
        return [
            'matricula'             => 'required|integer|unique:alumno,matricula',
            'nombre'                => 'required|string|max:255',
            'ap_pat'                => 'required|string|max:255',
            'ap_mat'                => 'nullable|string|max:255',
            'correo_alternativo'    => 'required|email|unique:alumno,correo_alternativo',
            'telefono'              => 'required|string|max:20|unique:alumno,telefono',
            'puntaje_ingreso'       => 'nullable|integer|min:0|max:1300',
            'id_carrera'            => 'required|exists:carrera,id_carrera',
            // Validamos que el grupo exista si es que fue asignado a alguno
            'id_grupo_induccion'    => 'nullable|exists:grupos,id_grupo',
            'id_grupo_propedeutico' => 'nullable|exists:grupos,id_grupo',
        ];
    }

    public function messages(): array
    {
        return [
            'matricula.unique'             => 'Esta matrícula ya se encuentra registrada en el sistema.',
            'correo_alternativo.required'  => 'El correo alternativo es obligatorio.',
            'correo_alternativo.unique'    => 'Este correo alternativo ya está en uso por otro alumno.',
            'telefono.unique'              => 'Este teléfono ya ha sido registrado previamente.',
            'puntaje_ingreso.max'          => 'El puntaje máximo de admisión es 1300 puntos.',
            'id_carrera.exists'            => 'La carrera seleccionada no es válida.',
            'id_grupo_induccion.exists'    => 'El grupo de inducción seleccionado no es válido.',
            'id_grupo_propedeutico.exists' => 'El grupo propedéutico seleccionado no es válido.',
        ];
    }
}
