<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUNTO DE PARTIDA · rutas sin protección
|--------------------------------------------------------------------------
| Estas dos rutas funcionan, pero no tienen límite de frecuencia.
| Tu trabajo (Reto 5) es añadir ->middleware('throttle:6,1') al POST.
*/

Route::redirect('/', '/products/create');

Route::get('/products/create', [ProductController::class, 'create'])
    ->name('products.create');

Route::post('/products', [ProductController::class, 'store'])
    ->name('products.store');
