<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| RETO 8 · Pruebas defensivas
|--------------------------------------------------------------------------
| El andamiaje (setUp, payload, fakePng) ya está resuelto: no lo toques.
| Tu trabajo es escribir las aserciones de cada prueba.
|
| Ejecuta con doble clic en PROBAR.bat (o: php artisan test --compact)
|
| Al principio las cinco pruebas fallan a propósito: cada una tiene un
| $this->fail() que te recuerda el TODO. Cuando termines el reto, la
| línea se sustituye por las aserciones y la prueba pasa a verde.
|
| Nota: el 419 (CSRF) NO se puede probar aquí, porque Laravel desactiva
| la verificación de token durante las pruebas. Se demuestra en el
| navegador, en el protocolo del Paso 6.
|--------------------------------------------------------------------------
*/

class ProductFormSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Cache::flush(); // reinicia el contador del throttle entre pruebas
    }

    /** Datos válidos reutilizables; cambia solo lo que necesites. */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'code'        => 'PRD001',
            'name'        => 'Audífonos de estudio',
            'price'       => '129.99',
            'image_proof' => $this->fakePng('comprobante.png'),
        ], $overrides);
    }

    /**
     * PNG 1×1 válido construido desde bytes, para no depender de la
     * extensión GD (no siempre está habilitada en los equipos del aula).
     */
    private function fakePng(string $name): UploadedFile
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+ip1sAAAAASUVORK5CYII='
        );

        return UploadedFile::fake()->createWithContent($name, $png);
    }

    /**
     * TODO 8.1 · Formulario vacío.
     *
     * Envía un POST a /products sin datos (from('/products/create')).
     * Comprueba que:
     *   - redirige de vuelta a /products/create
     *   - la sesión tiene errores en code, name, price e image_proof
     *   - el mensaje de 'code' es exactamente
     *     "El código del producto es obligatorio."
     */
    public function test_formulario_vacio_devuelve_422_con_mensajes_en_espanol(): void
    {
        $this->fail('TODO 8.1 pendiente: escribe las aserciones de esta prueba.');
    }

    /**
     * TODO 8.2 · Consumidor API.
     *
     * Usa postJson('/products', []). Comprueba que responde 422 y que el
     * JSON trae success=false, el message del Form Request y la clave
     * errors con los cuatro campos.
     */
    public function test_respuesta_json_devuelve_estructura_422(): void
    {
        $this->fail('TODO 8.2 pendiente: escribe las aserciones de esta prueba.');
    }

    /**
     * TODO 8.3 · Archivo no permitido.
     *
     * Envía un payload válido pero con image_proof reemplazado por
     * UploadedFile::fake()->create('shell.php', 12, 'application/x-php').
     * Comprueba que hay error en image_proof y que no se registró nada.
     */
    public function test_archivo_php_es_rechazado(): void
    {
        $this->fail('TODO 8.3 pendiente: escribe las aserciones de esta prueba.');
    }

    /**
     * TODO 8.4 · Camino feliz.
     *
     * Envía el payload válido. Comprueba el redirect, el flash 'success',
     * los datos en 'product' y que el archivo existe en el disco public.
     */
    public function test_imagen_valida_registra_el_producto(): void
    {
        $this->fail('TODO 8.4 pendiente: escribe las aserciones de esta prueba.');
    }

    /**
     * TODO 8.5 · Saturación.
     *
     * Envía 6 veces el payload válido (deben pasar) y una séptima
     * (debe responder 429).
     */
    public function test_throttle_bloquea_el_septimo_envio_con_429(): void
    {
        $this->fail('TODO 8.5 pendiente: escribe las aserciones de esta prueba.');
    }
}
