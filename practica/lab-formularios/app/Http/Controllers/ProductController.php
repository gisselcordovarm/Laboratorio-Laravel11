<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/*
|--------------------------------------------------------------------------
| PUNTO DE PARTIDA · controlador sin validación
|--------------------------------------------------------------------------
| Recibe un Request genérico y guarda lo que llegue, sin comprobar nada.
| Sirve para demostrar el problema antes de escribir la defensa.
|
| Tu trabajo (Retos 3 y 4) es sustituirlo por la versión con
| StoreProductRequest y ->validated().
*/

class ProductController extends Controller
{
    public function create(): View
    {
        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Sin reglas, sin lista blanca, sin comprobación de tipo de archivo.
        $imagePath = $request->file('image_proof')?->store('products', 'public');

        return redirect()
            ->route('products.create')
            ->with('success', 'Producto registrado sin validar')
            ->with('product', [
                'code'       => $request->input('code'),
                'name'       => $request->input('name'),
                'price'      => $request->input('price'),
                'image_path' => $imagePath,
            ]);
    }
}
