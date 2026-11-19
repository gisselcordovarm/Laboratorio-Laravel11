# Guía del alumno · Seguridad y validación en Laravel 11

Todo lo que necesitas hacer en clase son estos tres pasos. Nada más.

## Paso 1 · Abrir la consola

Doble clic en **`ABRIR CONSOLA.bat`**.

Se abrirá una ventana negra con PHP 8.3 y Composer ya listos. No instales nada y no escribas comandos de instalación.

## Paso 2 · Levantar el servidor

En esa misma ventana escribe:

```
php artisan serve
```

Luego abre el navegador en:

```
http://127.0.0.1:8000/products/create
```

Debe verse el formulario del producto (con estilos). Si no, avisa al facilitador.

## Paso 3 · Resolver los 8 retos

Los archivos que debes editar están marcados con `// TODO` o los retos en los comentarios de cada archivo:

| Reto | Qué hacer | Archivo |
|------|-----------|---------|
| 1 | Reglas de validación (`code`, `price`, `image_proof`) | `app/Http/Requests/StoreProductRequest.php` |
| 2 | Mensajes de error en español | `app/Http/Requests/StoreProductRequest.php` |
| 3 | Respuesta 422 en JSON para APIs | `app/Http/Requests/StoreProductRequest.php` |
| 4 | Controlador que use `StoreProductRequest` y `validated()` | `app/Http/Controllers/ProductController.php` |
| 5 | Límite de frecuencia `throttle:6,1` en el POST | `routes/web.php` |
| 6 | Token `@csrf` en el formulario | `resources/views/products/create.blade.php` |
| 7 | `old()`, `@error`, `aria-invalid` y resumen de errores | `resources/views/products/create.blade.php` |
| 8 | Escribir las 5 pruebas | `tests/Feature/ProductFormSecurityTest.php` |

Después de editar, guarda el archivo y vuelve a cargar la página del navegador. Para probar las pruebas automáticas:

```
php artisan test
```

## Paso 3b · Diseñar tus propias vistas de error (opcional)

Las vistas de error (`419`, `429`, `404`, `500`) ya tienen la estructura Blade lista en `resources/views/errors/`, pero vienen vacías: sin título, sin mensaje ni sin pista. Completa los espacios con tus propias palabras. No copies el estilo del facilitador; escríbelo a tu manera.

## Paso 4 · Cierre

Cuando termine la clase, detén el servidor con **Ctrl + C** en la consola y cierra la ventana.

---

> No uses `composer install`, `npm install` ni `php artisan storage:link`. Todo eso ya está hecho en `lab-formularios`.
