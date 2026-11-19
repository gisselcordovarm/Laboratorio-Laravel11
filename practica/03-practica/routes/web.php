<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| RETO 5 · Límite de frecuencia
|--------------------------------------------------------------------------
| Añade al POST el middleware que permite 6 peticiones por minuto
| por IP y por ruta.
|
| Ojo: los envíos rechazados también consumen el contador.
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/products/create');

Route::get('/products/create', [ProductController::class, 'create'])
    ->name('products.create');

Route::post('/products', [ProductController::class, 'store'])
    // TODO 5 · ->middleware('...')
    ->name('products.store');
