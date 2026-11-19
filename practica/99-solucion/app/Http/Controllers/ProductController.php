<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * PASO 3 · Controlador delgado (Skinny Controller).
 *
 * No contiene reglas de validación ni decisiones de seguridad: recibe
 * datos ya validados y delega el almacenamiento del archivo al disco
 * público. El uso de validated() evita la asignación masiva.
 */
class ProductController extends Controller
{
    public function create(): View
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        // Solo los campos declarados en el Form Request llegan aquí.
        $data = $request->validated();

        $imagePath = null;

        if ($request->hasFile('image_proof')) {
            // Nombre aleatorio + carpeta products/ en el disco public.
            $imagePath = $request->file('image_proof')->store('products', 'public');
        }

        return redirect()
            ->route('products.create')
            ->with('success', 'Producto registrado exitosamente')
            ->with('product', [
                'code'       => $data['code'],
                'name'       => $data['name'],
                'price'      => $data['price'],
                'image_path' => $imagePath,
            ]);
    }
}
