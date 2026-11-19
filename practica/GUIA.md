# Guía de la práctica · de cero a formulario defensivo

Recorrido completo, en orden. Cada fase dice **qué haces**, **qué deberías
ver** y **cómo comprobar** que salió bien.

Tiempo estimado: **50–70 min**. En clase **no se usa terminal**: todo se
abre con doble clic desde la carpeta del proyecto.

---

## §0 · Antes de clase (solo facilitador, con administrador)

Se hace **una vez por equipo**, antes de que empiece la clase:

1. Copia `practica-alumnos` en el equipo.
2. Doble clic en `00-preparacion/PREPARAR-EQUIPO.bat`.
3. Acepta UAC y espera a `EQUIPO LISTO`.

El preparador deja:

- Proyecto Laravel 11 en `Documentos\LabLaravel11\lab-formularios`.
- Estilos compilados (`public/build`), sin necesidad de Vite en clase.
- Enlace `public/storage` creado.
- `StoreProductRequest.php` y las 5 pruebas, con TODO, ya en el proyecto.
- Los cinco lanzadores y un acceso directo en el escritorio.

**Comprobación:** doble clic en `VERIFICAR-ENTORNO.bat`. Todo debe estar
en `[OK]`. Si algo falla, revisa `00-preparacion/LEEME-PREPARACION.md`.

> Si el preparador no se usó, el Anexo A explica la instalación manual con
> terminal. En ese caso sí necesitarás administrador y conexión.

---

## §1 · Abrir la práctica

Doble clic en **`INICIAR-APP.bat`** (o en el acceso “Lab Laravel 11” del
escritorio).

El lanzador arranca el servidor, espera a que responda y abre el navegador
en <http://127.0.0.1:8000/products/create>.

**Deberías ver:** el formulario completo, con estilos, en tonos ivory y
verde, y el aviso de que es la versión **sin defensas**.

> Para apagar el servidor al terminar: `DETENER-APP.bat`.

---

## §2 · Los dos fallos del punto de partida

Antes de arreglar nada, compruébalos. Son la razón de ser de la práctica.

### Fallo A · No hay token CSRF → 419

Rellena algo y pulsa **Registrar producto**.

**Deberías ver:** la página `419 · El token CSRF no es válido`.

Abre F12 → **Network** y observa el POST: el servidor lo rechazó antes de
ejecutar una sola línea del controlador. Ese es el middleware
`ValidateCsrfToken` del grupo `web`.

### Fallo B · No hay validación → acepta cualquier cosa

Esto se ve **después** del Reto 6 (mientras falte `@csrf` no hay forma de
enviar nada). Vuelve aquí cuando tengas el token.

Envía el formulario vacío. **Deberías ver** el aviso rojo
`Producto registrado sin validar`: el servidor aceptó cadenas vacías.

Prueba también a subir un `.php` renombrado a `.jpg`: se guarda igual.

> Esto es una demo local y deliberada. En un servidor real, subir un
> archivo ejecutable es exactamente el fallo que vamos a cerrar.

---

## §3 · Retos 1, 2 y 3 · El Form Request

El archivo **ya está creado** en el proyecto:

```
app/Http/Requests/StoreProductRequest.php
```

Ábrelo y completa los TODO. No hace falta generarlo ni abrir terminal.

### Reto 1 · Reglas

Cuatro campos, con esta intención:

| Campo | Qué debe cumplir |
| --- | --- |
| `code` | obligatorio, texto, solo letras y números, máx. 10 |
| `name` | obligatorio, texto, entre 3 y 100 |
| `price` | obligatorio, numérico, ≥ 0.01, ≤ 999999.99 |
| `image_proof` | obligatorio, archivo, imagen real, jpeg/png/webp, ≤ 5120 KB |

Un campo es un arreglo de reglas:

```php
'name' => ['required', 'string', 'min:3', 'max:100'],
```

> Para el archivo necesitas `file` + `image` + `mimes:` + `max:`.
> Consulta <https://laravel.com/docs/11.x/validation#available-validation-rules>

### Reto 2 · Mensajes

La clave es `campo.regla`:

```php
'name.required' => 'El nombre del producto es obligatorio.',
```

Sin esto, Laravel responde en inglés.

### Reto 3 · Respuesta JSON

`failedValidation()` decide qué pasa cuando la validación falla:

- petición normal → recarga la vista con errores (`parent::failedValidation`);
- petición `Accept: application/json` → responde **422** con
  `success`, `message` y `errors`.

**Comprobación en clase:** la prueba `8.2` del Reto 8 valida esta respuesta.
Si quieres verla antes con `curl`, el Anexo B tiene el comando con token.

---

## §4 · Reto 4 · Controlador delgado

Copia el esqueleto sobre el controlador actual:

```
03-practica/app/Http/Controllers/ProductController.php
        ↓
app/Http/Controllers/ProductController.php
```

Arrástralo con el explorador y confirma el reemplazo. El archivo trae los
TODO 4.1–4.3 y, mientras no los completes, el POST lanza una excepción
clara: es intencional.

Tres ideas:

1. **`validated()`** devuelve solo los campos declarados en el Form Request.
   Es la barrera contra *mass assignment*: si alguien añade `is_admin=1`,
   ese valor no existe para el controlador.
2. **El archivo** se guarda con `->store('products', 'public')`, que le
   asigna un nombre aleatorio.
3. **PRG** (*Post → Redirect → Get*): nunca se devuelve una vista desde un
   POST; se redirige. Así, refrescar no reenvía el formulario.

**Deberías ver:** tras enviar datos válidos, el aviso verde de registro y
el archivo en `storage/app/public/products/`.

---

## §5 · Reto 5 · Throttle

En `routes/web.php`, añade el middleware al POST:

```php
Route::post('/products', [ProductController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('products.store');
```

`6,1` = 6 peticiones por minuto, por IP y por ruta.

**Comprobación:** envía 7 veces seguidas. El séptimo responde **429**.

Entre pruebas, doble clic en **`REINICIAR-DEMO.bat`** (limpia el contador).

> Trampa del laboratorio: los envíos rechazados por validación **también**
> consumen el contador, porque el throttle se evalúa antes del controlador.

---

## §6 · Retos 6 y 7 · La vista

Sigue `03-practica/VISTA-RETOS.md`. Son cuatro cambios pequeños sobre
`resources/views/products/create.blade.php`:

1. `@csrf` dentro del `<form>`.
2. Resumen de errores arriba del formulario.
3. `old('campo')` en cada `value`.
4. `@error` + `@class(['input-invalid' => ...])` + `aria-invalid`.

**Deberías ver:** enviar vacío ya no recarga en blanco: pinta cuatro bordes
rojos, un resumen con el número de campos y pone el foco en el primero.
Lo escrito en los campos correctos se conserva.

> Aquí se ve por fin el **Fallo B**: sin reglas, el servidor acepta el
> formulario vacío. Con los Retos 1-3 hechos, ya no.

---

## §7 · Reto 8 · Pruebas

El archivo **ya está en el proyecto** y arranca en rojo:

```
tests/Feature/ProductFormSecurityTest.php
```

Cada prueba tiene un `$this->fail('TODO 8.x...')`. Sustitúyelo por las
aserciones que describe el comentario de arriba.

```text
Doble clic en PROBAR.bat
```

**Deberías ver:** `5 failed` al principio y **`0 failed`** al terminar
(la prueba de humo `ExampleTest` ya pasa desde el inicio).

> El **419 no se puede probar** en PHPUnit: Laravel desactiva la
> verificación de CSRF durante las pruebas. Se demuestra en el navegador.

> En PHP 8.5 pueden aparecer líneas **`DEPR`** con
> `Constant PDO::MYSQL_ATTR_SSL_CA is deprecated`. Son avisos del
> intérprete, no fallos: mira solo que el resumen diga `0 failed`.

---

## §8 · Protocolo final en vivo

Con todo conectado, ejecuta las cuatro pruebas y anota el estado HTTP:

| # | Acción | Esperado |
| --- | --- | --- |
| 1 | Enviar vacío | 422 (vista con errores) |
| 2 | Subir un `.php` como imagen | 422 |
| 3 | Borrar `_token` con F12 y enviar | 419 |
| 4 | Enviar 7 veces en un minuto | 429 |

Cierra con la matriz:

| Concepto | Componente | Respuesta |
| --- | --- | --- |
| Anti-CSRF | `@csrf` · `_token` | 419 |
| Validación estricta | `StoreProductRequest` | 422 |
| Mass assignment | `->validated()` | filtra |
| Rate limiting | `throttle:6,1` | 429 |
| Archivos seguros | `mimes` + `max:5120` | 422 |

---

## Anexo A · Instalación manual (solo sin preparador)

```bash
composer create-project laravel/laravel:^11.0 lab-formularios
cd lab-formularios
# copiar 01-base y 02-arranque-inseguro sobre la raíz
npm install
npm run build
php artisan storage:link
php artisan serve
```

> **Composer 2.9+** bloquea Laravel 11 por avisos de seguridad. Define
> `COMPOSER_NO_SECURITY_BLOCKING=1` antes del comando (PowerShell:
> `$env:COMPOSER_NO_SECURITY_BLOCKING = "1"`).

> Laravel 11 no trae `app/Http/Requests/`: créala con
> `php artisan make:request StoreProductRequest`.

Sin el preparador, copia también a mano
`03-practica/tests/Feature/ProductFormSecurityTest.php` y
`03-practica/app/Http/Requests/StoreProductRequest.php`.

---

## Anexo B · Verificar el 422 con curl (opcional)

El POST lleva token CSRF, así que primero se obtiene de la página y se
guarda la cookie de sesión:

```bash
TOKEN=$(curl -s -c cookies.txt http://127.0.0.1:8000/products/create \
  | grep -o 'name="csrf-token" content="[^"]*"' | cut -d'"' -f4)

curl -i -X POST http://127.0.0.1:8000/products -b cookies.txt \
  -H "Accept: application/json" -H "Content-Type: application/json" \
  -d "{\"_token\":\"$TOKEN\"}"
```

En PowerShell:

```powershell
$html  = curl.exe -s -c cookies.txt http://127.0.0.1:8000/products/create
$token = [regex]::Match($html, 'name="csrf-token" content="([^"]+)"').Groups[1].Value
'{"_token":"' + $token + '"}' | Set-Content body.json -NoNewline
curl.exe -i -X POST http://127.0.0.1:8000/products -b cookies.txt `
  -H "Accept: application/json" -H "Content-Type: application/json" `
  --data-binary "@body.json"
```

**Deberías ver:** `HTTP/1.1 422 Unprocessable Content` y un JSON con
`"success": false`.

> Si responde **419**, falta el token: el middleware CSRF actúa antes que
> la validación. No es un fallo del Form Request.

---

## Problemas frecuentes

**La app no abre o el navegador muestra error.**
Doble clic en `VERIFICAR-ENTORNO.bat`. Si el servidor no está en marcha,
`INICIAR-APP.bat`. Si el puerto 8000 está ocupado, `DETENER-APP.bat` y
vuelve a intentarlo.

**El formulario se ve sin estilos.**
Falta `public/build`. Avisa al facilitador: se recompila con
`PREPARAR-EQUIPO.bat -Forzar`.

**`Vite manifest not found`.**
Mismo motivo. El layout usa `@vite(...)` y necesita el manifest compilado.

**Sigue dando 419 después de añadir `@csrf`.**
Recarga la página: el token caduca con la sesión.

**`storage:link` falla en Windows.**
Requiere administrador. Se resuelve en la preparación previa, no en clase.

**El throttle no bloquea.**
Doble clic en `REINICIAR-DEMO.bat`, o el middleware no está en la ruta
correcta.

**La imagen no aparece en el aviso de éxito.**
Falta el enlace `public/storage` (preparación previa).

**`PROBAR.bat` muestra 5 fallos desde el principio.**
Es correcto: son los TODO del Reto 8. Deben pasar a verde al escribirlas.

**`PROBAR.bat` falla en `ExampleTest`.**
No se copió `02-arranque-inseguro/tests/Feature/ExampleTest.php`.

**Composer dice `could not be resolved ... security advisories`.**
Es el bloqueo de Composer 2.9+ a paquetes con avisos. Usa
`COMPOSER_NO_SECURITY_BLOCKING=1`, como indica el Anexo A.
