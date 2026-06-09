<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use App\Models\Usuario;

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
            // Solo acepta números (sin letras, espacios ni decimales)
            'num_empleado' => 'required|regex:/^[0-9]+$/',
            
            // Solo acepta letras y espacios (\pL incluye acentos y la ñ)
            'nombre'       => 'required|string|max:45|regex:/^[\pL\s]+$/u',
            'ap_pat'       => 'required|string|max:25|regex:/^[\pL\s]+$/u',
            'ap_mat'       => 'nullable|string|max:25|regex:/^[\pL\s]+$/u',
            'correo_institucional' => ['required', 'email', 'ends_with:@uabc.edu.mx', 'unique:usuarios,correo_institucional'],
            'id_rol'               => 'required|exists:roles,id_rol'
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $numEmpleado = $this->input('num_empleado');
                $idRol = $this->input('id_rol');

                // Si falta el número de empleado o el rol, dejamos que las reglas base manejen el error
                if (!$numEmpleado || !$idRol) return;

                // 1. VALIDACIÓN DE ROL DUPLICADO PARA EL MISMO EMPLEADO
                $rolExistente = Usuario::where('num_empleado', $numEmpleado)
                    ->where('id_rol', $idRol)
                    ->exists();

                if ($rolExistente) {
                    $validator->errors()->add(
                        'id_rol',
                        "El empleado con número {$numEmpleado} ya tiene una cuenta activa con este rol. No se puede duplicar."
                    );
                }

                // 2. VALIDACIÓN DE CONSISTENCIA DE DATOS PERSONALES
                // Buscamos cualquier registro existente de este empleado para comparar nombres
                $usuarioExistente = Usuario::where('num_empleado', $numEmpleado)->first();

                if ($usuarioExistente) {
                    $nombreCoincide = Str::lower($usuarioExistente->nombre) === Str::lower($this->input('nombre'));
                    $apPatCoincide = Str::lower($usuarioExistente->ap_pat) === Str::lower($this->input('ap_pat'));
                    $apMatCoincide = Str::lower($usuarioExistente->ap_mat ?? '') === Str::lower($this->input('ap_mat') ?? '');

                    // Si alguno no coincide, avisamos al usuario
                    if (!$nombreCoincide || !$apPatCoincide || !$apMatCoincide) {
                        $validator->errors()->add(
                            'num_empleado',
                            "El número de empleado {$numEmpleado} pertenece a '{$usuarioExistente->nombre} {$usuarioExistente->ap_pat}'. Los datos ingresados no coinciden."
                        );
                    }
                }
            }
        ];
    }

    /**
     * MENSAJES DE ERROR
     */
    public function messages(): array
    {
        return [
            'num_empleado.required'          => 'El número de empleado es obligatorio.',
            'correo_institucional.required'  => 'El correo es obligatorio.',
            'correo_institucional.ends_with' => 'El correo debe ser una cuenta institucional válida (@uabc.edu.mx).',
            'correo_institucional.unique'    => 'Este correo electrónico ya está registrado con otro rol/usuario.',
            'id_rol.required'                => 'Debe seleccionar un rol para el usuario.',

            // --- MENSAJES PARA REGEX ---
            'num_empleado.regex' => 'El número de empleado solo debe contener números (sin letras ni espacios).',
            'nombre.regex'       => 'El nombre solo debe contener letras.',
            'ap_pat.regex'       => 'El apellido paterno solo debe contener letras.',
            'ap_mat.regex'       => 'El apellido materno solo debe contener letras.',
        ];
    }
}
