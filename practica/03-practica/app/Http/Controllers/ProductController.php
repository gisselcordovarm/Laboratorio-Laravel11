<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/*
|--------------------------------------------------------------------------
| RETO 4 · Controlador delgado
|--------------------------------------------------------------------------
| Cambia el Request genérico por StoreProductRequest. Si la entrada no
| cumple las reglas, Laravel corta la petición ANTES de entrar aquí.
|
| El controlador no valida: solo orquesta datos ya validados.
|--------------------------------------------------------------------------
*/

class ProductController extends Controller
{
    public function create(): View
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        // TODO 4.1 · Obtén únicamente los campos declarados en el Form Request.
        //            Así se bloquea el mass assignment: un campo extra como
        //            is_admin nunca llega al flujo.
        // $data = ...

        // TODO 4.2 · Si viene archivo, guárdalo en el disco 'public',
        //            dentro de la carpeta 'products'.
        //            Laravel le asigna un nombre aleatorio.
        // $imagePath = ...

        // TODO 4.3 · Redirige a la ruta products.create (patrón PRG)
        //            con un flash 'success' y otro 'product' que contenga
        //            code, name, price e image_path.
        // return ...

        // Si llegaste aquí es que el reto sigue sin resolver.
        throw new \LogicException('Reto 4 pendiente: completa el método store().');
    }
}
