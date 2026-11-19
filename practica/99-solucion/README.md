# 99-solucion · solo para el facilitador

Aquí está la versión terminada de los cinco archivos que los alumnos escriben.

| Archivo | Retos que resuelve |
| --- | --- |
| `app/Http/Requests/StoreProductRequest.php` | 1, 2, 3 |
| `app/Http/Controllers/ProductController.php` | 4 |
| `routes/web.php` | 5 |
| `resources/views/products/create.blade.php` | 6, 7 |
| `tests/Feature/ProductFormSecurityTest.php` | 8 |

## Cómo usarla en clase

- **No la compartas al inicio.** El valor de la práctica está en escribir.
- Para desatascar a alguien: compara **solo la función** que le falla,
  no el archivo entero.
- Para cerrar la sesión: proyecta el diff entre `02-arranque-inseguro`
  y esta carpeta; es el resumen visual de todo lo aprendido.

> Laravel 11 no crea `app/Http/Requests/`. Si copias estos archivos a mano,
> ejecuta antes `php artisan make:request StoreProductRequest` o crea la
> carpeta: `mkdir app/Http/Requests`.

## Diferencias esperadas con el arranque

```
routes/web.php            + ->middleware('throttle:6,1')
ProductController         Request → StoreProductRequest
                          + validated()
                          + store('products','public')
StoreProductRequest       nuevo: reglas + mensajes + 422 JSON
create.blade.php          + @csrf, old(), @error, aria-invalid
                          + resumen de errores
                          + protocolo de pruebas
tests/                    nuevo: 5 pruebas
```

## Nota sobre el 419

No hay prueba automatizada de 419: Laravel desactiva `ValidateCsrfToken`
durante los tests. La defensa se demuestra en el navegador.
