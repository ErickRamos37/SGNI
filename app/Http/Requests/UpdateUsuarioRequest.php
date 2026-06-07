<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UpdateUsuarioRequest extends FormRequest
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
            'correo_institucional' => Str::lower(trim($this->correo_institucional)),
        ]);
    }

    public function rules(): array
    {
        // Obtener el parámetro de la ruta (puede ser el Modelo o solo el ID en texto)
        $usuarioParam = $this->route('usuario');

        // Operador ternario para validar si es un objeto o un simple string/entero
        $idUsuario = is_object($usuarioParam) ? ($usuarioParam->id_usuario ?? $usuarioParam->id) : $usuarioParam;

        return [
            // Solo acepta números (sin letras, espacios ni decimales)
            'num_empleado' => 'required|regex:/^[0-9]+$/',

            // Solo acepta letras y espacios (\pL incluye acentos y la ñ)
            'nombre'       => 'required|string|max:45|regex:/^[\pL\s]+$/u',
            'ap_pat'       => 'required|string|max:25|regex:/^[\pL\s]+$/u',
            'ap_mat'       => 'nullable|string|max:25|regex:/^[\pL\s]+$/u',

            'correo_institucional' => [
                'required',
                'email',
                'ends_with:@uabc.edu.mx',
                Rule::unique('usuarios', 'correo_institucional')->ignore($idUsuario, 'id_usuario')
            ],
            'id_rol' => [
                'required',
                'exists:roles,id_rol',
                // Evita que este mismo numero de empleado tenga el mismo rol en otra cuenta
                Rule::unique('usuarios', 'id_rol')
                    ->where(function ($query) {
                        return $query->where('num_empleado', $this->num_empleado);
                    })
                    ->ignore($idUsuario, 'id_usuario')
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'num_empleado.required'          => 'El número de empleado es obligatorio.',
            'correo_institucional.required'  => 'El correo es obligatorio.',
            'correo_institucional.ends_with' => 'El correo debe ser una cuenta institucional válida (@uabc.edu.mx).',
            'correo_institucional.unique'    => 'Este correo electrónico ya está registrado en otra cuenta.',
            'id_rol.required'                => 'Debe seleccionar un rol para el usuario.',
            'id_rol.unique'                  => 'Este número de empleado ya tiene una cuenta registrada con este rol.',

            // --- MENSAJES PARA REGEX ---
            'num_empleado.regex' => 'El número de empleado solo debe contener números (sin letras ni espacios).',
            'nombre.regex'       => 'El nombre solo debe contener letras.',
            'ap_pat.regex'       => 'El apellido paterno solo debe contener letras.',
            'ap_mat.regex'       => 'El apellido materno solo debe contener letras.',
        ];
    }

    /**
     * Validaciones complejas adicionales después de las reglas básicas.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // Obtenemos el parámetro de la ruta de forma segura
            $usuarioParam = $this->route('usuario');
            $idUsuario = is_object($usuarioParam) ? ($usuarioParam->id_usuario ?? $usuarioParam->id) : $usuarioParam;

            // 1. Obtener los datos actuales del usuario ANTES de que se editen
            $usuarioActual = DB::table('usuarios')->where('id_usuario', $idUsuario)->first();

            // Verificamos que realmente se encontró al usuario para evitar otros errores
            if (!$usuarioActual) {
                return;
            }

            // 2. ¿El usuario está intentando CAMBIAR a un número de empleado distinto al suyo?
            if ($this->num_empleado != $usuarioActual->num_empleado) {

                // Buscar si el NUEVO número de empleado ya le pertenece a alguien más
                $dueñoNuevoNumero = DB::table('usuarios')
                    ->where('num_empleado', $this->num_empleado)
                    ->first();

                if ($dueñoNuevoNumero) {
                    // Si alguien ya tiene ese número, exigir que los nombres coincidan exactamente
                    if (
                        $this->nombre !== $dueñoNuevoNumero->nombre ||
                        $this->ap_pat !== $dueñoNuevoNumero->ap_pat ||
                        $this->ap_mat !== $dueñoNuevoNumero->ap_mat
                    ) {
                        $nombreCompletoExistente = trim("{$dueñoNuevoNumero->nombre} {$dueñoNuevoNumero->ap_pat} {$dueñoNuevoNumero->ap_mat}");

                        $validator->errors()->add(
                            'num_empleado',
                            "El número de empleado {$this->num_empleado} ya pertenece a '{$nombreCompletoExistente}'. Los nombres y apellidos deben coincidir exactamente para asociarle esta cuenta."
                        );
                    }
                }
            }
            // Si el num_empleado es el MISMO, no hacemos la validación de bloqueo. 
            // Dejamos que edite su nombre libremente.
        });
    }
}
