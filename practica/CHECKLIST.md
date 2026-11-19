# Checklist de entrega

Marca cada punto. Si algo falla, la columna derecha dice dónde mirar.

> En clase **no se abre terminal ni se piden permisos de administrador**.
> Todo lo que necesita admin se resuelve antes de empezar (sección 0).
>
> Flujo en clase: doble clic en **`ABRIR CONSOLA.bat`** → `php artisan serve` →
> `http://127.0.0.1:8000/products/create`. El proyecto listo está en `lab-formularios\`.

---

## 0 · Preparación (antes de clase, una sola vez)

Con permisos de administrador y conexión a internet:

- [ ] `00-preparacion/PREPARAR-EQUIPO.bat` terminó en `EQUIPO LISTO`
- [ ] El proyecto existe en `Documentos\LabLaravel11\lab-formularios`
- [ ] `public/build/manifest.json` existe (assets compilados)
- [ ] `public/storage` existe (enlace creado)
- [ ] Están los 5 lanzadores: `INICIAR-APP`, `DETENER-APP`, `PROBAR`,
      `REINICIAR-DEMO`, `VERIFICAR-ENTORNO`
- [ ] `app/Http/Requests/StoreProductRequest.php` está en el proyecto con sus TODO
- [ ] `tests/Feature/ProductFormSecurityTest.php` está en el proyecto con sus TODO
- [ ] `VERIFICAR-ENTORNO.bat` muestra todo en `[OK]`

Si algo falla: `00-preparacion/LEEME-PREPARACION.md`.

---

## 1 · Arranque en clase (doble clic)

- [ ] `INICIAR-APP.bat` abre el navegador en `/products/create`
- [ ] El formulario se ve **con estilos** (no HTML pelado)
- [ ] La cabecera muestra "Formularios seguros" y la versión de Laravel
- [ ] `/` redirige a `/products/create`
- [ ] El alumno completó sus propios mensajes en `resources/views/errors/419.blade.php` y `429.blade.php` (plantilla vacía con estructura Blade lista)
- [ ] `PROBAR.bat` arranca en **5 failed** (es lo esperado, no un error)

---

## 2 · Retos

- [ ] **Reto 1** · Las 4 reglas están en `rules()`
- [ ] **Reto 2** · Los mensajes están en español
- [ ] **Reto 3** · La prueba `8.2` valida el JSON 422 (o `curl` del Anexo B)
- [ ] **Reto 4** · El controlador usa `StoreProductRequest` y `validated()`
- [ ] **Reto 5** · El POST tiene `->middleware('throttle:6,1')`
- [ ] **Reto 6** · La vista tiene `@csrf`
- [ ] **Reto 7** · La vista usa `old()`, `@error` y `aria-invalid`
- [ ] **Reto 8** · Las 5 pruebas están escritas

---

## 3 · Las cuatro defensas, en vivo

| Prueba | Cómo | Esperado | ¿OK? |
| --- | --- | --- | --- |
| Validación | Enviar vacío | 4 bordes rojos + resumen | [ ] |
| Archivo | Subir `shell.php` | mensaje de formato | [ ] |
| CSRF | Borrar `_token` en F12 | página 419 | [ ] |
| Throttle | 7 envíos en 1 min | página 429 | [ ] |

> Entre pruebas de throttle: `REINICIAR-DEMO.bat`.

---

## 4 · Código limpio

- [ ] No hay `dd()`, `var_dump()` ni `console.log()` sueltos
- [ ] No hay reglas de validación dentro del controlador
- [ ] No hay `$request->all()` en el flujo
- [ ] Los nombres de campo coinciden entre vista, reglas y controlador
- [ ] `PROBAR.bat` → **0 failed** (las 5 pruebas del reto en verde)

---

## 5 · Evidencias para entregar

- [ ] Captura de Network con el `422` (o del JSON del Anexo B)
- [ ] Captura de la página `419`
- [ ] Captura de la página `429`
- [ ] Captura del aviso verde de registro exitoso
- [ ] Captura de `PROBAR.bat` con **0 failed**

---

## 6 · Cierre

- [ ] `DETENER-APP.bat` ejecutado (el puerto 8000 queda libre)
- [ ] El trabajo quedó en la carpeta del proyecto (no en el escritorio)
