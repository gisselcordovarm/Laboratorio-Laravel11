<?php

namespace Tests\Feature;

use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Prueba de humo · la raíz redirige al formulario
|--------------------------------------------------------------------------
| Laravel trae esta prueba apuntando a la portada de bienvenida. En este
| laboratorio "/" redirige a /products/create, así que la expectativa
| correcta es un 302, no un 200.
|
| Si no copias este archivo, `php artisan test` fallará en
| Tests\Feature\ExampleTest con "Expected 200 but received 302".
|--------------------------------------------------------------------------
*/

class ExampleTest extends TestCase
{
    public function test_la_raiz_redirige_al_formulario(): void
    {
        $this->get('/')->assertRedirect('/products/create');
    }
}
