<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/*
|--------------------------------------------------------------------------
| RETOS 1, 2 y 3 · Capa de validación dedicada
|--------------------------------------------------------------------------
| Genera el archivo con:
|
|     php artisan make:request StoreProductRequest
|
| y sustituye su contenido por este. Después completa los TODO.
|
| Documentación: https://laravel.com/docs/11.x/validation#form-request-validation
|--------------------------------------------------------------------------
*/

class StoreProductRequest extends FormRequest
{
    /**
     * ¿Quién puede hacer esta petición? En el laboratorio la ruta es
     * pública, así que devolvemos true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * RETO 1 · Reglas de entrada.
     *
     * Declara los cuatro campos: code, name, price e image_proof.
     * Piensa en: obligatorio / tipo / longitud / formato / peso máximo.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            // EJEMPLO ya resuelto, úsalo como modelo:
            'name' => ['required', 'string', 'min:3', 'max:100'],

            // TODO 1.1 · code: obligatorio, texto, solo letras y números, máx. 10.
            // 'code' => [ ... ],

            // TODO 1.2 · price: obligatorio, numérico, mínimo 0.01, máximo 999999.99.
            // 'price' => [ ... ],

            // TODO 1.3 · image_proof: obligatorio, archivo, imagen real,
            //            solo jpeg/png/webp, máximo 5120 KB (5 MB).
            // 'image_proof' => [ ... ],
        ];
    }

    /**
     * RETO 2 · Mensajes en español.
     *
     * La clave es "campo.regla": por ejemplo 'name.required'.
     * Escribe al menos un mensaje por cada regla que añadas arriba.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // EJEMPLO ya resuelto:
            'name.required' => 'El nombre del producto es obligatorio.',

            // TODO 2 · mensajes de code, price e image_proof.
        ];
    }

    /**
     * Nombres legibles para los mensajes que genera el framework.
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
     * RETO 3 · Respuesta defensiva.
     *
     * Si la petición espera JSON, responde 422 con esta estructura:
     *   { success: false, message: "...", errors: { ... } }
     * Si no la espera, deja que Laravel recargue la vista con los errores
     * usando parent::failedValidation($validator).
     *
     * Pista: necesitas HttpResponseException y Symfony\Component\HttpFoundation\Response.
     */
    // protected function failedValidation(Validator $validator): void
    // {
    //     ...
    // }
}
