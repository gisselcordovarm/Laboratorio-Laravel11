<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * PASO 2 · Capa de validación dedicada (SRP).
 *
 * El controlador no conoce reglas: toda la política de entrada vive aquí.
 * Los mensajes están en español para que la validación visual sea legible
 * durante la demostración en el laboratorio.
 */
class StoreProductRequest extends FormRequest
{
    /**
     * En este laboratorio la ruta es pública; en un módulo real se
     * comprobaría aquí la política de acceso (Gate / Policy).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas estrictas de entrada.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'code'        => ['required', 'string', 'alpha_num', 'max:10'],
            'name'        => ['required', 'string', 'min:3', 'max:100'],
            'price'       => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'image_proof' => ['required', 'file', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Mensajes traducidos al español (UX de validación).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required'        => 'El código del producto es obligatorio.',
            'code.alpha_num'       => 'El código solo debe contener letras y números.',
            'code.max'             => 'El código no debe superar los 10 caracteres.',
            'name.required'        => 'El nombre del producto es obligatorio.',
            'name.min'             => 'El nombre debe tener al menos 3 caracteres.',
            'name.max'             => 'El nombre no debe superar los 100 caracteres.',
            'price.required'       => 'Debe ingresar un precio válido.',
            'price.numeric'        => 'El precio debe ser un valor numérico.',
            'price.min'            => 'El precio debe ser mayor o igual a 0.01.',
            'price.max'            => 'El precio no debe superar los 999.999,99.',
            'image_proof.required' => 'Es obligatorio adjuntar una imagen comprobante.',
            'image_proof.image'    => 'El archivo adjunto debe ser una imagen válida.',
            'image_proof.mimes'    => 'Formato de imagen no permitido. Solo JPEG, PNG o WebP.',
            'image_proof.max'      => 'La imagen no debe pesar más de 5 MB.',
            'image_proof.uploaded' => 'La imagen no pudo subirse. Verifica el tamaño y el formato.',
        ];
    }

    /**
     * Nombres legibles para los mensajes generados por el framework.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'code'        => 'código',
            'name'        => 'nombre',
            'price'       => 'precio',
            'image_proof' => 'imagen comprobante',
        ];
    }

    /**
     * Respuesta defensiva: JSON 422 para consumidores API y recarga con
     * errores para la vista Blade.
     */
    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Fallo de validación en los datos ingresados.',
                'errors'  => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        parent::failedValidation($validator);
    }
}
