# Preparación del equipo · antes de clase

Este folder resuelve un problema concreto: **en clase no habrá terminal ni
permisos de administrador de forma continua**. Todo lo que necesita admin
(instalar, compilar, crear el enlace de storage) se hace aquí, una sola vez,
y la clase se trabaja con **doble clic**.

---

## Lo que hay que hacer antes de clase

1. Copia la carpeta `practica-alumnos` en el equipo (Escritorio, USB, etc.).
2. Doble clic en **`PREPARAR-EQUIPO.bat`**.
3. Acepta el aviso de UAC (una vez). El script hace todo lo demás.
4. Espera a ver:

```
  [OK]    EQUIPO LISTO. En clase no hace falta abrir terminal.
```

Al terminar tendrás:

- Un proyecto Laravel 11 funcionando en
  `Documentos\LabLaravel11\lab-formularios`.
- Los estilos **ya compilados** (no hace falta `npm run dev` en clase).
- El enlace `public/storage` creado (imágenes visibles).
- **Los esqueletos ya colocados** en el proyecto:
  - `app/Http/Requests/StoreProductRequest.php` con los TODO 1–3;
  - `tests/Feature/ProductFormSecurityTest.php` con los TODO 8.1–8.5.
- Cinco lanzadores de doble clic dentro del proyecto.
- Un acceso directo **“Lab Laravel 11”** en el escritorio.

> **Las 5 pruebas arrancan en rojo a propósito.** Cada una lleva un
> `$this->fail('TODO 8.x…')`: es el recordatorio de que falta escribirlas.
> `PROBAR.bat` debe mostrar `5 failed` el primer día y `0 failed` al final.
> No es un error de la preparación.

> Los esqueletos se copian porque **en clase no se puede ejecutar
> `php artisan make:request`**. Sin terminal, el archivo tiene que existir
> desde antes.

> Si el equipo es de un alumno, ejecútalo con la sesión de ese alumno
> elevada: así conserva los permisos de escritura sobre su proyecto.

---

## En clase: solo doble clic

| Acción | Archivo | Qué hace |
| --- | --- | --- |
| Abrir la práctica | `INICIAR-APP.bat` (o el acceso del escritorio) | Arranca el servidor y abre el navegador en `/products/create` |
| Ejecutar pruebas | `PROBAR.bat` | `php artisan test` y deja la ventana abierta |
| Reiniciar el throttle | `REINICIAR-DEMO.bat` | Limpia la caché para volver a probar el 429 |
| Apagar el servidor | `DETENER-APP.bat` | Libera el puerto 8000 |
| Diagnóstico | `VERIFICAR-ENTORNO.bat` | Comprueba PHP, assets, storage y servidor |

Ninguno pide contraseña. Ninguno obliga a escribir comandos.

---

## Opciones del preparador

Se pasan al `.bat`, por ejemplo:

```bat
PREPARAR-EQUIPO.bat -Destino "D:\Lab\lab-formularios" -Puerto 8000
```

| Opción | Para qué |
| --- | --- |
| `-Destino <ruta>` | Cambia dónde se crea el proyecto |
| `-Puerto <n>` | Cambia el puerto (por defecto 8000) |
| `-SoloVerificar` | No instala nada: solo comprueba el estado |
| `-Forzar` | Recopia la base, **sobrescribe los esqueletos** y recompila |
| `-SinBuild` | No ejecuta `npm install` / `npm run build` |
| `-SinAcceso` | No crea el acceso directo del escritorio |

**Volver a ejecutarlo es seguro**: si el proyecto ya existe, no sobrescribe
el trabajo de los alumnos. Solo `-Forzar` reemplaza archivos.

> **Cuidado con `-Forzar` en clase:** borra lo que los alumnos hayan escrito
> en la vista, en `StoreProductRequest.php` y en las pruebas. Úsalo solo
> antes de la sesión o para reiniciar un equipo entre grupos.

---

## Requisitos que deben estar instalados antes

- PHP 8.2 o superior con `fileinfo`, `mbstring`, `openssl`
- Composer 2.x
- Node.js 18+ y npm
- Conexión a internet (solo durante la preparación, para Composer y npm)

El script busca PHP en el `PATH` y en las rutas habituales
(`C:\php*`, `C:\xampp`, `C:\laragon`, `C:\wamp64`).

---

## Si algo falla

**`storage:link` no se crea.**
El preparador no se ejecutó como administrador. Cierra y vuelve a abrirlo
con “Ejecutar como administrador”.

**El navegador abre antes de que el servidor responda.**
`INICIAR-APP.bat` espera hasta 20 segundos. Si aun así falla, ejecuta
`VERIFICAR-ENTORNO.bat` y revisa que el puerto 8000 esté libre
(`DETENER-APP.bat`).

**La app se ve sin estilos.**
Falta `public/build`. Vuelve a ejecutar `PREPARAR-EQUIPO.bat` (sin `-Forzar`
no recompila; usa `-Forzar` o borra la carpeta `public/build`).

**`PROBAR.bat` muestra 5 pruebas en rojo.**
Es el estado esperado: son los TODO 8.1–8.5 que los alumnos deben escribir.
La prueba de humo (`ExampleTest`) sí debe pasar desde el inicio. Si también
falla, no se copió `02-arranque-inseguro/tests/Feature/ExampleTest.php`:
vuelve a ejecutar el preparador.

**Falta `StoreProductRequest.php` o el archivo de pruebas.**
El preparador no llegó al paso 3b. Vuelve a ejecutarlo (sin `-Forzar`
respeta lo que ya exista). Sin esos archivos la clase no puede avanzar,
porque en clase no se ejecuta `php artisan make:request`.

**Composer bloquea Laravel 11 por “security advisories”.**
El preparador ya define `COMPOSER_NO_SECURITY_BLOCKING=1`. Si lo ejecutas a
mano, defínelo tú antes del comando.

**Varias máquinas.**
Ejecuta el preparador una vez en cada equipo. No copies el proyecto ya
compilado entre máquinas: el enlace de storage es local.

---

## Qué NO hace falta en clase

- `composer install` / `composer update`
- `npm install` / `npm run build` / `npm run dev`
- `php artisan serve` escrito a mano
- `php artisan storage:link`
- `php artisan cache:clear` escrito a mano (está en `REINICIAR-DEMO.bat`)
