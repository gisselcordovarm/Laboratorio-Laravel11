<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PASO 4 · Rutas y Rate Limiting
|--------------------------------------------------------------------------
| throttle:6,1 limita a 6 solicitudes por minuto por IP y por ruta.
| En Laravel 11 este middleware se declara aquí o en bootstrap/app.php;
| ya no existe app/Http/Kernel.php.
*/

Route::redirect('/', '/products/create');

Route::get('/products/create', [ProductController::class, 'create'])
    ->name('products.create');

Route::post('/products', [ProductController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('products.store');
