<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreUsuarioRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        // Si esta en false, bloquearía a todos.
        return true;
    }

    /**
     * SANITIZACIÓN DE DATOS
     * Limpia los datos ANTES de validarlos.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            // Quita espacios en blanco extra al inicio/final y convierte a Mayúsculas cada palabra
            'nombre' => Str::title(trim($this->nombre)),
            'ap_pat' => Str::title(trim($this->ap_pat)),
            'ap_mat' => $this->ap_mat ? Str::title(trim($this->ap_mat)) : null,
            // Asegura que el correo siempre esté en minúsculas y sin espacios
            'correo_institucional' => Str::lower(trim($this->correo_institucional)),
        ]);
    }

    /**
     * REGLAS DE VALIDACIÓN
     */
    public function rules(): array
    {
        return [
            'num_empleado'         => 'required|numeric|unique:usuarios,num_empleado',
            'nombre'               => 'required|string|max:255',
            'ap_pat'               => 'required|string|max:255',
            'ap_mat'               => 'nullable|string|max:255',
            // Validación del dominio de la UABC y que no se repita
            'correo_institucional' => ['required', 'email', 'ends_with:@uabc.edu.mx', 'unique:usuarios,correo_institucional'],
            'id_rol'               => 'required|exists:roles,id_rol'
        ];
    }

    /**
     * MENSAJES DE ERROR
     */
    public function messages(): array
    {
        return [
            'num_empleado.required' => 'El número de empleado es obligatorio.',
            'num_empleado.unique'   => 'Este número de empleado ya está registrado en el sistema.',
            'correo_institucional.required' => 'El correo es obligatorio.',
            'correo_institucional.ends_with' => 'El correo debe ser una cuenta institucional válida (@uabc.edu.mx).',
            'correo_institucional.unique'   => 'Este correo electrónico ya está en uso.',
            'id_rol.required'       => 'Debe seleccionar un rol para el usuario.',
        ];
    }
}
