# Retos 6 y 7 · Convertir el formulario en uno defensivo

Partes de `02-arranque-inseguro/resources/views/products/create.blade.php`.
Todo lo que sigue se hace **en ese mismo archivo**, sin reescribirlo entero.

Son cuatro cambios pequeños, cada uno con su comprobación.

---

## Reto 6 · Añadir el token CSRF

**Síntoma si falta:** al enviar el formulario, el navegador muestra
`419 | Page Expired`. Es la defensa anti-CSRF de Laravel funcionando.

**Dónde:** justo después de la etiqueta `<form ...>`.

```blade
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
      class="card p-6 sm:p-8" data-product-form novalidate>

    @csrf   {{-- ← añade esta línea --}}
```

`@csrf` genera un `<input type="hidden" name="_token">` con un valor único
por sesión. El middleware `ValidateCsrfToken` (incluido en el grupo `web`)
lo compara en cada POST.

**Comprobación:** envía el formulario vacío. Ya no debe aparecer el 419.

---

## Reto 7 · Devolver los errores a la persona que escribe

Cuatro piezas, todas sobre el mismo formulario:

| Pieza | Para qué sirve |
| --- | --- |
| `old('campo')` | conserva lo escrito cuando la petición vuelve con errores |
| `@error('campo')` | pinta el mensaje en español debajo del campo |
| `@class([...])` | añade `input-invalid` solo al campo que falló |
| `aria-invalid` / `aria-describedby` | el error también existe para lectores de pantalla |

### 7.1 · Resumen de errores (arriba del formulario)

Colócalo antes de `<form>`:

```blade
@if ($errors->any())
    <div class="mb-6 rounded-3xl border border-brick/20 bg-brick/[0.045] p-5" role="alert">
        <p class="text-[13px] font-semibold text-brick">
            Revisa {{ $errors->count() }} {{ $errors->count() === 1 ? 'campo' : 'campos' }} del formulario
        </p>
        <ul class="mt-2 space-y-1 text-[12.5px] leading-relaxed text-brick/85">
            @foreach ($errors->all() as $error)
                <li class="flex gap-2"><span aria-hidden="true">—</span><span>{{ $error }}</span></li>
            @endforeach
        </ul>
    </div>
@endif
```

### 7.2 · Patrón de campo (usa `code` como modelo)

Sustituye el bloque del campo `code` por este:

```blade
<div>
    <div class="field-label">
        <label for="code">Código</label>
        <span class="font-mono text-[10px] font-normal tracking-normal text-muted/70" data-counter-for="code">0/10</span>
    </div>
    <input id="code" name="code" type="text" maxlength="10"
           value="{{ old('code') }}"
           placeholder="PRD001"
           autocomplete="off" autocapitalize="characters" spellcheck="false"
           aria-required="true"
           aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}"
           @if ($errors->has('code')) aria-describedby="code-error" @endif
           @class(['input', 'input-invalid' => $errors->has('code')])>
    @error('code')
        <p id="code-error" class="error-text">{{ $message }}</p>
    @else
        <p class="hint">Letras y números · máx. 10</p>
    @enderror
</div>
```

Fíjate en las cuatro marcas:

1. `value="{{ old('code') }}"`
2. `aria-invalid` con la expresión ternaria
3. `@class(['input', 'input-invalid' => ...])`
4. `@error ... @else ... @enderror`

### 7.3 · Repite el patrón

Aplica lo mismo a los otros tres campos. Cambia solo el `id`, el `name`
y el texto del `hint`:

| Campo | `id` / `name` | `hint` original |
| --- | --- | --- |
| Precio | `price` | `Valor numérico mayor a 0.01` |
| Nombre | `name` | `Entre 3 y 100 caracteres` |
| Imagen | `image_proof` | `Solo JPEG, PNG o WebP...` |

En el campo de imagen, además, añade la clase condicional al contenedor:

```blade
<div @class(['file-drop', 'is-invalid' => $errors->has('image_proof')]) data-dropzone>
```

**Comprobación:** envía vacío. Deben aparecer cuatro bordes en rojo, el
resumen arriba y el foco automático en el primer campo con error
(eso último ya lo hace `resources/js/app.js`, no lo escribas tú).

---

## Cómo saber que quedó bien

- [ ] Sin `@csrf` → `419`. Con `@csrf` → el formulario envía.
- [ ] Enviar vacío → 4 errores en español, sin perder lo escrito.
- [ ] Escribir en `code` y fallar en `price` → `code` conserva su valor.
- [ ] Tabular por el formulario → el lector de pantalla anuncia "no válido".
- [ ] El botón sigue funcionando sin JavaScript (la validación es del servidor).
