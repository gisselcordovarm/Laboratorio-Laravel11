# Práctica · Formularios críticos en Laravel 11

Kit de arranque para la práctica de clase. Trae **todo lo que no es el
objetivo de aprendizaje** ya resuelto (estilos, layout, JavaScript, páginas
de error) y deja **sin escribir las cuatro defensas** que vas a programar.

> Regla del kit: `01` y `02` se copian. `03` se escribe. `99` no se abre
> hasta haber terminado.

---

## Cómo se trabaja en clase

**No se abre PowerShell ni CMD.** El equipo se prepara antes de clase con
`00-preparacion/PREPARAR-EQUIPO.bat` (una sola vez, con administrador).
A partir de ahí, todo es doble clic:

| Acción | Archivo | Qué hace |
| --- | --- | --- |
| Abrir la práctica | `INICIAR-APP.bat` | Arranca el servidor y abre `/products/create` |
| Ejecutar pruebas | `PROBAR.bat` | Corre las 5 pruebas y deja la ventana abierta |
| Reiniciar el throttle | `REINICIAR-DEMO.bat` | Limpia la caché para repetir el 429 |
| Apagar el servidor | `DETENER-APP.bat` | Libera el puerto 8000 |
| Diagnóstico | `VERIFICAR-ENTORNO.bat` | Comprueba PHP, assets, storage y servidor |

Los cinco lanzadores se crean dentro del proyecto y **no piden contraseña**.
El detalle está en `00-preparacion/LEEME-PREPARACION.md`.

---

## Qué contiene

```
practica-alumnos/
├── 00-preparacion/          ANTES DE CLASE · una vez, con admin
│   ├── PREPARAR-EQUIPO.bat      doble clic; pide UAC y hace todo
│   ├── PREPARAR-EQUIPO.ps1      lógica del preparador
│   ├── LEEME-PREPARACION.md     qué hace y cómo resolver fallos
│   └── plantillas/              los 5 lanzadores de clase
│
├── 01-base/                 COPIAR TAL CUAL · estética y andamiaje
│   ├── package.json
│   ├── postcss.config.js
│   ├── tailwind.config.js
│   ├── vite.config.js
│   └── resources/
│       ├── css/app.css                  sistema visual (Tailwind)
│       ├── js/app.js                    contadores, drag & drop, foco en el error
│       ├── js/bootstrap.js
│       └── views/
│           ├── layouts/app.blade.php    cabecera, tipografías, @vite
│           └── errors/                  404, 419, 429, 500 y panel común
│
├── 02-arranque-inseguro/    COPIAR TAL CUAL · punto de partida
│   ├── routes/web.php                         2 rutas, sin throttle
│   ├── app/Http/Controllers/ProductController.php   guarda sin validar
│   ├── resources/views/products/create.blade.php    formulario sin defensas
│   └── tests/Feature/ExampleTest.php          humo: "/" redirige (302)
│
├── 03-practica/             ESCRIBIR TÚ · esqueletos con TODO
│   ├── app/Http/Requests/StoreProductRequest.php
│   ├── app/Http/Controllers/ProductController.php
│   ├── routes/web.php
│   ├── tests/Feature/ProductFormSecurityTest.php
│   └── VISTA-RETOS.md                 los 4 cambios de la vista, explicados
│
├── 99-solucion/             PROFESOR · versión terminada
├── GUIA.md                  recorrido completo, fase por fase
└── CHECKLIST.md             entregables
```

---

## Los 8 retos

| # | Dónde | Qué escribes | Se comprueba con |
| --- | --- | --- | --- |
| 1 | `StoreProductRequest::rules()` | 4 reglas de validación | 422 |
| 2 | `StoreProductRequest::messages()` | mensajes en español | texto del error |
| 3 | `StoreProductRequest::failedValidation()` | respuesta JSON 422 | `curl` / test |
| 4 | `ProductController::store()` | `validated()`, archivo, PRG | registro exitoso |
| 5 | `routes/web.php` | `throttle:6,1` | 429 |
| 6 | vista | `@csrf` | deja de dar 419 |
| 7 | vista | `old()`, `@error`, `aria-invalid` | errores visibles |
| 8 | test | 5 pruebas | `PROBAR.bat` |

**La idea:** al principio todo funciona mal a propósito. Cada reto cierra
una grieta concreta y la deja verificable.

---

## Antes de clase (facilitador)

1. Copia `practica-alumnos` en el equipo.
2. Doble clic en **`00-preparacion/PREPARAR-EQUIPO.bat`**.
3. Acepta UAC una vez y espera a `EQUIPO LISTO`.

El preparador crea el proyecto en
`Documentos\LabLaravel11\lab-formularios`, compila los estilos, crea el
enlace `public/storage`, deja los esqueletos con TODO y genera los cinco
lanzadores.

Opciones útiles:

```bat
PREPARAR-EQUIPO.bat -Destino "D:\Lab\lab-formularios" -Puerto 8000
```

| Opción | Para qué |
| --- | --- |
| `-Destino <ruta>` | Cambia dónde se crea el proyecto |
| `-SoloVerificar` | No instala: solo comprueba el estado |
| `-Forzar` | Recopia la base y recompila (borra cambios) |
| `-SinBuild` | No ejecuta npm |
| `-SinAcceso` | No crea el acceso directo del escritorio |

Requisitos que deben existir **antes** de la preparación: PHP 8.2+,
Composer 2.x, Node 18+ y conexión a internet. En clase no se usa ninguno.

---

## En clase

1. Doble clic en **`INICIAR-APP.bat`** (o en el acceso “Lab Laravel 11”).
2. Comprobar los dos fallos del arranque: **419** y “acepta cualquier cosa”
   (`GUIA.md` §2).
3. Resolver los retos 1-8 en orden (`GUIA.md` §3-§7).
4. Doble clic en **`PROBAR.bat`** hasta ver **0 failed**.
5. Protocolo final de las cuatro defensas (`GUIA.md` §8).

Tiempo estimado: **50–70 minutos**.

> El proyecto arranca **sin defensas a propósito**. Las 5 pruebas del Reto 8
> también arrancan en rojo: cada una tiene un `$this->fail()` que recuerda
> el TODO. Verde significa que la defensa quedó escrita.

---

## Instalación manual (solo referencia)

Si no puedes usar el preparador, el camino con terminal es:

```bash
composer create-project laravel/laravel:^11.0 lab-formularios
cd lab-formularios
# copiar 01-base y 02-arranque-inseguro sobre la raíz
npm install
npm run build
php artisan storage:link
php artisan serve
```

> **Composer 2.9 o superior** bloquea Laravel 11 por avisos de seguridad.
> Desactívalo solo para ese comando:
>
> ```powershell
> $env:COMPOSER_NO_SECURITY_BLOCKING = "1"
> composer create-project laravel/laravel:^11.0 lab-formularios
> ```
>
> ```bash
> COMPOSER_NO_SECURITY_BLOCKING=1 composer create-project laravel/laravel:^11.0 lab-formularios
> ```

> Laravel 11 **no** trae `app/Http/Requests/`. La crea
> `php artisan make:request StoreProductRequest`.

---

## Qué NO tienes que hacer

- Diseñar la interfaz ni elegir colores.
- Escribir el layout, el CSS de Tailwind ni las animaciones.
- Programar los contadores de caracteres, la vista previa de la imagen
  ni el foco automático en el primer error.
- Configurar base de datos (esta práctica no la necesita).
- Escribir los mensajes de error desde cero en blanco: las vistas de error ya traen la estructura Blade lista (`resources/views/errors/`). Tú solo completas los textos con tus propias palabras.
- Instalar nada ni abrir una terminal durante la clase.

---

## Si te atascas

1. Mira el TODO concreto en el archivo de `03-practica`.
2. Lee la sección correspondiente de `GUIA.md`.
3. Compara **solo esa función** con `99-solucion/` — nunca copies el
   archivo entero: el valor está en escribirlo.
4. Si la app no abre: doble clic en `VERIFICAR-ENTORNO.bat`.
