<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Verificación automatizada de las defensas del PASO 6.
 *
 * El error 419 (CSRF) no se puede reproducir aquí porque Laravel desactiva
 * la verificación de token durante las pruebas; se valida manualmente en el
 * navegador según el protocolo del laboratorio.
 */
class ProductFormSecurityTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Cache::flush(); // reinicia el contador de throttle entre pruebas
    }

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
     * PNG 1×1 válido. Se construye desde bytes para no depender de la
     * extensión GD (no siempre está habilitada en los equipos del laboratorio).
     */
    private function fakePng(string $name): UploadedFile
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+ip1sAAAAASUVORK5CYII='
        );

        return UploadedFile::fake()->createWithContent($name, $png);
    }

    public function test_formulario_vacio_devuelve_422_con_mensajes_en_espanol(): void
    {
        $response = $this->from('/products/create')->post('/products', []);

        $response->assertRedirect('/products/create');
        $response->assertSessionHasErrors(['code', 'name', 'price', 'image_proof']);

        $this->assertSame(
            'El código del producto es obligatorio.',
            session('errors')->first('code')
        );
    }

    public function test_respuesta_json_devuelve_estructura_422(): void
    {
        $response = $this->postJson('/products', []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Fallo de validación en los datos ingresados.',
            ])
            ->assertJsonStructure(['errors' => ['code', 'name', 'price', 'image_proof']]);
    }

    public function test_archivo_php_es_rechazado(): void
    {
        $response = $this->from('/products/create')->post('/products', $this->payload([
            'image_proof' => UploadedFile::fake()->create('shell.php', 12, 'application/x-php'),
        ]));

        $response->assertSessionHasErrors('image_proof');
        $this->assertNull(session('product'));
    }

    public function test_imagen_valida_registra_el_producto(): void
    {
        $file = $this->fakePng('comprobante.png');

        $response = $this->from('/products/create')->post('/products', $this->payload([
            'image_proof' => $file,
        ]));

        $response->assertRedirect('/products/create');
        $response->assertSessionHas('success', 'Producto registrado exitosamente');
        $response->assertSessionHas('product.code', 'PRD001');
        $response->assertSessionHas('product.name', 'Audífonos de estudio');

        Storage::disk('public')->assertExists('products/'.$file->hashName());
    }

    public function test_throttle_bloquea_el_septimo_envio_con_429(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            $this->from('/products/create')
                ->post('/products', $this->payload())
                ->assertRedirect('/products/create');
        }

        $this->from('/products/create')
            ->post('/products', $this->payload())
            ->assertStatus(429);
    }
}
